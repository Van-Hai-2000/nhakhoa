<?php

namespace backend\modules\hsba\controllers;

use backend\models\UserAdmin;
use common\components\ClaNhakhoa;
use common\models\appointment\Appointment;
use common\models\branch\Branch;
use common\models\commission\Commission;
use common\models\commission_v2\CommissionV2;
use common\models\hsba\MedicalRecordImageV2;
use common\models\hsba\MedicalRecordItemChildV2;
use common\models\hsba\MedicalRecordItemCommissionV2;
use common\models\hsba\MedicalRecordItemV2;
use common\models\hsba\MedicalRecordV2;
use common\models\hsba\PaymentHistoryV2;
use common\models\hsba\search\MedicalRecordChildV2Search;
use common\models\hsba\search\MedicalRecordV2Search;
use common\models\LoaiMau;
use common\models\medical_record\Factory;
use common\models\medical_record\MedicalRecordHistory;
use common\models\medical_record\MedicalRecordInformation;
use common\models\medical_record\MedicalRecordItemCommission;
use common\models\medical_record\MedicalRecordLog;
use common\models\medicine\Medicine;
use common\models\product\Product;
use common\models\product\ProductCategory;
use common\models\sale\BranchSales;
use common\models\sale\BranchSalesV2;
use common\models\sale\CustomerSales;
use common\models\sale\DoctorSales;
use common\models\sale\OperationSales;
use common\models\thuchi\ThuChi;
use common\models\thuchi\ThuChiV2;
use common\models\user\MedicalRecordBeforeImage;
use common\models\user\MedicalRecordChild;
use common\models\hsba\MedicalRecordChildV2;
use common\models\user\MedicalRecordImage;
use common\models\user\MedicalRecordItem;
use common\models\user\MedicalRecordItemChild;
use common\models\user\MedicalRecordItemMedicine;
use common\models\user\PaymentHistory;
use common\models\user\search\MedicalRecordChildSearch;
use common\models\user\search\MedicalRecordItemChildSearch;
use common\models\user\User;
use common\models\voucher\MedicalRecordVoucher;
use common\models\voucher\Voucher;
use common\models\WaitingList;
use Yii;
use common\models\user\MedicalRecord;
use common\models\user\search\MedicalRecordSearch;
use yii\base\Exception;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * MedicalRecordController implements the CRUD actions for MedicalRecord model.
 */
class MedicalRecordController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionSetUrl()
    {
        $request = $_GET;
        $params = json_decode($request['params'], true);
        $params['MedicalRecordSearch']['time_start'] = strtotime($request['time_start']);
        $params['MedicalRecordSearch']['time_end'] = strtotime($request['time_end']);
        $url = Url::to(['index']);
        $final = $url . "?" . http_build_query($params);
        return $final;
    }

    /**
     * Lists all MedicalRecord models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MedicalRecordV2Search();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $url = Url::to(['excel']);
        $url = $url . "?" . http_build_query($_GET);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'params' => $_GET,
            'url' => $url,
        ]);
    }

    /**
     * Displays a single MedicalRecord model.
     * @param integer $id
     * @return mixed
     */

    /**
     * Creates a new MedicalRecord model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new MedicalRecordV2();
        $user_admin = Yii::$app->user->getIdentity();
        if ($user_admin->branch_id) {
            $model->branch_id = $user_admin->branch_id;
        }
        $model->created_at = date('Y-m-d\TH:i', time());
        $categories = ProductCategory::find()->where(['status' => 1])->asArray()->all();
        $request = Yii::$app->request->post();
        if ($model->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $user = User::findOne($model->user_id);
                $model->username = $user->username;
                $model->phone = $user->phone;
                $model->introduce_id = $user->introduce_id;
                $model->introduce = $user->introduce;
                $model->created_at = strtotime($model->created_at);
                $model->money = 0;

                $products = [];
                $product_ids = isset($request['product_id']) && $request['product_id'] ? $request['product_id'] : [];
                $quantity = isset($request['quantity']) && $request['quantity'] ? $request['quantity'] : [];
                $product_category_id = isset($request['product_category_id']) && $request['product_category_id'] ? $request['product_category_id'] : [];
                $total_money = 0;
                if ($product_ids) {
                    foreach ($product_ids as $key => $value) {
                        if ($value) {
                            $qty = isset($quantity[$key]) && $quantity[$key] ? $quantity[$key] : 1;
                            $products[$value] = [
                                'quantity' => isset($products[$value]['quantity']) && $products[$value]['quantity'] ? $products[$value]['quantity'] + $qty : $qty,
                                'category_id' => $product_category_id[$key]
                            ];
                        }
                    }
                }
                foreach ($products as $k => $val) {
                    $product = Product::findOne($k);
                    $total_money += $val['quantity'] * $product->price;
                }
                $model->total_money = $total_money;


                if ($model->save()) {
                    foreach ($products as $k => $val) {
                        $product = Product::findOne($k);
                        $medical_record_child = new MedicalRecordChildV2();
                        $medical_record_child->user_id = $user->id;
                        $medical_record_child->medical_record_id = $model->id;
                        $medical_record_child->product_id = $k;
                        $medical_record_child->product_category_id = $val['category_id'];;
                        $medical_record_child->quantity = $val['quantity'];
                        $medical_record_child->money = $product->price;
                        if (!$medical_record_child->save()) throw new Exception('Lưu chi tiết thủ thuật lỗi');
                    }
                } else {
                    throw new Exception('Lưu hồ sơ bệnh án lỗi');
                }
                $transaction->commit();
                return $this->redirect(['add', 'id' => $model->id]);

            } catch (Exception $e) {
                Yii::warning($e->getMessage());
                $transaction->rollBack();
                throw $e;
            } catch (\Throwable $e) {
                $response['errorsth'] = $e->getMessage();
                $transaction->rollBack();
                throw $e;
            }

        }
        return $this->render('create', [
            'model' => $model,
            'categories' => $categories,
        ]);
    }

    /**
     * Updates an existing MedicalRecord model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->created_at = date('Y-m-d\TH:i', $model->created_at);
        $categories = ProductCategory::find()->where(['status' => 1])->asArray()->all();
        $medical_record_child = MedicalRecordChild::find()->where(['medical_record_id' => $id])->all();
        $request = Yii::$app->request->post();
        if ($model->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $user = User::findOne($model->user_id);
                $model->username = $user->username;
                $model->phone = $user->phone;
                $model->introduce_id = $user->introduce_id;
                $model->introduce = $user->introduce;
                $model->created_at = strtotime($model->created_at);
                if ($model->total_money > $model->money && $model->status == MedicalRecord::STATUS_SUCCESS_ALL) {
                    $model->addError('status', 'Hồ sơ bệnh án hiện tại không thể chuyển trạng thái sang hoàn thành do chưa thanh toán đủ');
                    return $this->render('update', [
                        'model' => $model,
                        'categories' => $categories,
                        'medical_record_child' => $medical_record_child,
                    ]);
                }

                $total_old = 0;
                $total_money = 0;
                $products = [];
                $product_ids = isset($request['product_id']) && $request['product_id'] ? $request['product_id'] : [];
                $quantity = isset($request['quantity']) && $request['quantity'] ? $request['quantity'] : [];
                $product_category_id = isset($request['product_category_id']) && $request['product_category_id'] ? $request['product_category_id'] : [];
                if ($product_ids) {
                    foreach ($product_ids as $key => $value) {
                        if ($value) {
                            $qty = isset($quantity[$key]) && $quantity[$key] ? $quantity[$key] : 1;
                            $products[$value] = [
                                'quantity' => isset($products[$value]['quantity']) && $products[$value]['quantity'] ? $products[$value]['quantity'] + $qty : $qty,
                                'category_id' => $product_category_id[$key]
                            ];
                        }
                    }

                    //Tổng tiền của thủ thuật trong hồ sơ bệnh án ban đầu(không tính thuốc)
                    if ($medical_record_child) {
                        foreach ($medical_record_child as $value) {
                            $total_old += $value->quantity * $value->money;
                        }
                    }
                } else {
                    if ($medical_record_child) {
                        $model->addError('quantity', 'Không được phép xóa thủ thuật đã thực hiện');
                        return $this->render('update', [
                            'model' => $model,
                            'categories' => $categories,
                            'medical_record_child' => $medical_record_child,
                        ]);
                    }
                    MedicalRecordChild::deleteAll(['medical_record_id' => $id]);
                }

                //Xóa thủ thuật trong bảng child nếu thủ thuật đó bị xóa
                foreach ($medical_record_child as $child) {
                    if (!in_array($child->product_id, $product_ids)) {
                        MedicalRecordChild::deleteAll(['medical_record_id' => $id, 'product_id' => $child->product_id]);
                    }
                }

                foreach ($products as $k => $val) {
                    $product = Product::findOne($k);
                    $record_child = MedicalRecordChild::find()->where(['medical_record_id' => $id, 'product_id' => $k])->one();
                    $quantity = isset($val['quantity']) && $val['quantity'] ? $val['quantity'] : 1;
                    if (!$record_child) {
                        $record_child = new MedicalRecordChild();
                    } else {
                        if ($record_child->quantity_use > $quantity) {
                            $model->addError('qty', 'Số lượng thủ thuật không được nhỏ hơn số lượng thủ thuật đó đã sử dụng');
                            return $this->render('update', [
                                'model' => $model,
                                'categories' => $categories,
                                'medical_record_child' => $medical_record_child,
                            ]);
                        }
                    }
                    $record_child->user_id = $user->id;
                    $record_child->medical_record_id = $model->id;
                    $record_child->product_id = $k;
                    $record_child->product_category_id = $val['category_id'];;
                    $record_child->money = $product->price;
                    $record_child->quantity = $quantity;
                    if (!$record_child->save()) throw new Exception('Lưu chi tiết thủ thuật lỗi');

                    $total_money += $val['quantity'] * $product->price;
                }

                //Lưu lại tổng tiền hồ sơ bệnh án
                $model->total_money += $total_money - $total_old;

                //CHeck khi chuyển trạng thái sang hoàn thành
                if ($model->total_money > $model->money && $model->status == MedicalRecord::STATUS_SUCCESS_ALL) {
                    $model->addError('status', 'Hồ sơ bệnh án hiện tại không thể chuyển trạng thái sang hoàn thành do chưa thanh toán đủ');
                    return $this->render('update', [
                        'model' => $model,
                        'categories' => $categories,
                        'medical_record_child' => $medical_record_child,
                    ]);
                }

                if (!$model->save()) throw new Exception('Lưu tổng tiền hồ sơ lỗi');

                $transaction->commit();
                return $this->redirect(['index']);

            } catch (Exception $e) {
                Yii::warning($e->getMessage());
                $transaction->rollBack();
                throw $e;
            } catch (\Throwable $e) {
                $response['errorsth'] = $e->getMessage();
                $transaction->rollBack();
                throw $e;
            }

        }

        return $this->render('update', [
            'model' => $model,
            'categories' => $categories,
            'medical_record_child' => $medical_record_child,
        ]);

    }

    public function actionAdd($id)
    {
        $waiting_list = WaitingList::find()->where(['status' => 0, 'medical_record_id' => $id])->orderBy('created_at DESC')->one();
        if ($waiting_list) {
            $waiting_list->status = 1;
            $waiting_list->save();
        }
        $searchModel = new MedicalRecordChildV2Search();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $id);
        $model = MedicalRecordV2::findOne($id);
        $doctor = UserAdmin::getDoctor();
        $branchs = Branch::getBranch();
        $users = \backend\models\UserAdmin::getUserIntroduce();
        $user_admin = Yii::$app->user->getIdentity();
        $medical_record_item = MedicalRecordItemV2::find()->where(['medical_record_id' => $id])->joinWith('branch')->orderBy('created_at DESC')->all();

        $request = Yii::$app->request->post();
        if ($request) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $products = [];
                $item_child_prd = [];
                $item_count_prd = [];

                $product_ids = isset($request['product_id']) && $request['product_id'] ? $request['product_id'] : [];
                $product_category_ids = isset($request['product_category_id']) && $request['product_category_id'] ? $request['product_category_id'] : [];
                $product_quantity = isset($request['quantity']) && $request['quantity'] ? $request['quantity'] : [];
                $total_price = isset($request['total_price']) && $request['total_price'] ? $request['total_price'] : [];
                $hinh_thuc_giam_gia = isset($request['hinh_thuc_giam_gia']) && $request['hinh_thuc_giam_gia'] ? $request['hinh_thuc_giam_gia'] : [];
                $gia_tri_giam_gia = isset($request['gia_tri_giam_gia']) && $request['gia_tri_giam_gia'] ? $request['gia_tri_giam_gia'] : [];
                $vat = isset($request['vat']) && $request['vat'] ? $request['vat'] : [];
                $doctors = isset($request['doctor']) && $request['doctor'] ? $request['doctor'] : [];
                $sales = isset($request['sale']) && $request['sale'] ? $request['sale'] : [];
                $nguoi_cham_soc = isset($request['nguoi_cham_soc']) && $request['nguoi_cham_soc'] ? $request['nguoi_cham_soc'] : [];
                $branch_request = isset($request['branch']) && $request['branch'] ? $request['branch'] : '';
                $prd_note = isset($request['prd_note']) && $request['prd_note'] ? $request['prd_note'] : '';
                $time_create = isset($request['time-create']) && $request['time-create'] ? strtotime($request['time-create']) : time();
                $team_item_user = isset($request['team_item_user']) && $request['team_item_user'] ? $request['team_item_user'] : []; // danh sách id của những người hưởng hoa hồng
                $team_item_value = isset($request['team_item_value']) && $request['team_item_value'] ? $request['team_item_value'] : []; // danh sách giá trị hưởng của những người hưởng hoa hồng
                $team_item_type = isset($request['team_item_type']) && $request['team_item_type'] ? $request['team_item_type'] : []; // danh sách loại hưởng của những người hưởng hoa hồng

                // Kiểm tra là khách hàng mới
                $is_new = $this->isNewCustomer($id, $branch_request, $product_ids);

                //Check đã lưu hôm nay chưa, nếu chưa thì tạo mới
                $query = MedicalRecordItemV2::find()->where(['branch_id' => $branch_request, 'medical_record_id' => $id]);
                $beginOfDay = \DateTime::createFromFormat('Y-m-d H:i:s', (new \DateTime())->setTimestamp($time_create)->format('Y-m-d 00:00:00'))->getTimestamp();
                $endOfDay = \DateTime::createFromFormat('Y-m-d H:i:s', (new \DateTime())->setTimestamp($time_create)->format('Y-m-d 23:59:59'))->getTimestamp();
                $query->andFilterWhere(['>', 'medical_record_item_v2.created_at', $beginOfDay])
                    ->andFilterWhere(['<', 'medical_record_item_v2.created_at', $endOfDay]);
                $medical_record_item_v2 = $query->one();
                if (!$medical_record_item_v2) {
                    $medical_record_item_v2 = new MedicalRecordItemV2();
                    $medical_record_item_v2->user_id = $model->user_id;
                    $medical_record_item_v2->medical_record_id = $model->id;
                    $medical_record_item_v2->branch_id = $branch_request;
                    $medical_record_item_v2->is_new = isset($is_new) && $is_new ? 1 : 0;
                    $medical_record_item_v2->created_at = $time_create;
                    if (!$medical_record_item_v2->save()) throw new Exception('Thêm ngày khám lỗi');
                }


                if (ClaNhakhoa::check_array($product_ids)) {
                    foreach ($product_ids as $key => $product_id) {
                        $product = Product::find()->where(['product.id' => 35])->joinWith(['loaimau'])->one();
                        //Begin lưu dữ liệu thống kê số lần khám dự kiến, số lần chưa khám cho thủ thuật
                        $medical_record_child = MedicalRecordChildV2::find()->where(['medical_record_id' => $id, 'product_id' => $product_id])->one();
                        if ($medical_record_child) {
                            $count = $medical_record_child->quantity - $medical_record_child->quantity_use; // Số lần thủ thuật chưa sử dụng
                            if ($count < $product_quantity[$key]) {
                                $medical_record_child->quantity = $medical_record_child->quantity_use + $product_quantity[$key];
                                $medical_record_child->quantity_use = $medical_record_child->quantity;
                                if (!$medical_record_child->save()) throw new Exception('Thêm mới thủ thuật vào liệu trình điều trị lỗi');
                            } else {
                                $medical_record_child->quantity_use += $product_quantity[$key];
                                if (!$medical_record_child->save()) throw new Exception('Thêm mới thủ thuật vào liệu trình điều trị lỗi');
                            }
                        } else {
                            $medical_record_child = new MedicalRecordChildV2();
                            $medical_record_child->user_id = $model->user_id;
                            $medical_record_child->medical_record_id = $model->id;
                            $medical_record_child->product_category_id = $product_category_ids[$key];
                            $medical_record_child->product_id = $product_id;
                            $medical_record_child->quantity = $product_quantity[$key];
                            $medical_record_child->quantity_use = $product_quantity[$key];
                            $medical_record_child->created_at = $time_create;
                            if (!$medical_record_child->save()) throw new Exception('Thêm mới thủ thuật vào liệu trình điều trị lỗi');
                        }
                        //End lưu dữ liệu thống kê số lần khám dự kiến, số lần chưa khám cho thủ thuật

                        //Begin Lưu dữ liệu khám của từng thủ thuật mỗi lần khám
                        $item_child = new MedicalRecordItemChildV2();
                        $item_child->user_id = $model->user_id;
                        $item_child->medical_record_id = $model->id;
                        $item_child->medical_record_item_id = $medical_record_item_v2->id;
                        $item_child->product_id = $product_id;
                        $item_child->status = 1;
                        $item_child->money = $total_price[$key];
                        $item_child->quantity = $product_quantity[$key];
                        $item_child->branh_id = $branch_request;
                        $item_child->type = MedicalRecordItemChild::TYPE_THUTHUAT;
                        $item_child->note = $prd_note[$key];
                        $item_child->type_sale = $hinh_thuc_giam_gia[$key];
                        $item_child->sale_value = $gia_tri_giam_gia[$key];
                        $item_child->vat = $vat[$key];
                        $item_child->doctor_id = $doctors[$key];
                        $item_child->sale_id = $sales[$key];
                        $item_child->nguoi_cham_soc_id = $nguoi_cham_soc[$key];
                        $item_child->created_at = $time_create;
                        if (!$item_child->save()) throw new Exception('Lưu chi tiết ngày khám lỗi');
                        //End Lưu dữ liệu khám của từng thủ thuật mỗi lần khám

                        //Begin lưu % hoa hồng
                        if (isset($team_item_user[$key]) && ClaNhakhoa::check_array($team_item_user[$key])) {
                            $price_loaimau = $product->loaimau->money ? $product->loaimau->money : 0;
                            $medical_record_item_commission = new MedicalRecordItemCommissionV2();
                            $medical_record_item_commission->medical_record_id = $model->id;
                            $medical_record_item_commission->medical_record_item_id = $medical_record_item_v2->id;
                            $medical_record_item_commission->medical_record_item_child_id = $item_child->id;
                            $medical_record_item_commission->product_id = $product_id;
                            $medical_record_item_commission->price = $total_price[$key] - ($price_loaimau * $product_quantity[$key]);
                            $medical_record_item_commission->price_payment = 0;
                            $medical_record_item_commission->user_id = implode(',', $team_item_user[$key]);
                            $medical_record_item_commission->value = implode(',', $team_item_value[$key]);
                            $medical_record_item_commission->type = implode(',', $team_item_type[$key]);
                            $medical_record_item_commission->status = 0;
                            $medical_record_item_commission->payment_status = 0;
                            $medical_record_item_commission->created_at = $time_create;
                            if (!$medical_record_item_commission->save()) throw new Exception('Lưu cấu hình hoa hồng lỗi');

                            // Lưu thống kê khách hàng
//                                if(isset($is_new) && $is_new) {
//                                    foreach ($team as $t) {
//                                        $update = true;
//                                        $customers_sale = CustomerSales::find()->where(['key' => 'khach-hang-moi'])->where(['month' => date('m')])->where(['year' => date('Y')])->where(['user_id' => $t])->one();
//                                        if (!$customers_sale) {
//                                            $customers_sale = new CustomerSales();
//                                            $update = false;
//                                        }
//                                        $customers_sale->user_id = $t;
//                                        $customers_sale->quantity = $update ? $customers_sale->quantity + 1 : 1;
//                                        $customers_sale->month = date('m');
//                                        $customers_sale->key = 'khach-hang-moi';
//                                        $customers_sale->year = date('Y');
//                                        if (!$customers_sale->save()) throw new Exception('Lưu thống kê khách hàng xảy ra lỗi!');
//                                    }
//                                }
                        }
                        //End lưu % hoa hồng
                    }
                }


                //Thêm ảnh vào hồ sơ bệnh án
                $newimage = Yii::$app->request->post('newimage');
                $countimage = $newimage ? count($newimage) : 0;
                if ($newimage && $countimage > 0) {
                    foreach ($newimage as $image_code) {
                        $imgtem = \common\models\media\ImagesTemp::findOne($image_code);
                        if ($imgtem) {
                            $nimg = new MedicalRecordImageV2();
                            $nimg->attributes = $imgtem->attributes;
                            $nimg->id = NULL;
                            unset($nimg->id);
                            $nimg->medical_record_id = $model->id;
                            $nimg->medical_record_item_id = $medical_record_item_v2->id;
                            if ($nimg->save()) {
                                $imgtem->delete();
                            }
                        }
                    }
                }

                $transaction->commit();
                return $this->refresh();

            } catch (Exception $e) {
                Yii::warning($e->getMessage());
                $transaction->rollBack();
                throw $e;
            } catch (\Throwable $e) {
                $response['errorsth'] = $e->getMessage();
                $transaction->rollBack();
                throw $e;
            }

        }


        return $this->render('view', [
            'model' => $model,
            'branchs' => $branchs,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'user_admin' => $user_admin,
            'users' => $users,
            'doctor' => $doctor,
            'medical_record_item' => $medical_record_item,
        ]);
    }

    /**
     * Kiểm tra medical record có phải là khách hàng mới hay không?
     *
     * Là khách hàng mới (hoặc):
     *      - Chưa có lịch sử khám bệnh
     *      - Có lịch sử khám bệnh cuối cách đây ít nhất 6 tháng
     *      - Thủ thuật khác so với lần khám cuối
     */
    private function isNewCustomer($id, $branch_request, $product_ids)
    {
        $sixMonthsAgo = \DateTime::createFromFormat('Y-m-d H:i:s', (new \DateTime())->setTimestamp(strtotime("-6 months"))->format('Y-m-d 23:59:59'))->getTimestamp();
        $query = MedicalRecordItem::find()->where(['branch_id' => $branch_request, 'medical_record_id' => $id]);
        $medicalRecordItem = $query->select(['id' => 'MAX(`id`)'])->one();
        $queryItemChild = MedicalRecordItemChild::find()->where(['medical_record_item_id' => $medicalRecordItem->id])->select(['id'])->asArray()->all();
        $queryItemChild = array_column($queryItemChild, 'id');

        // Chưa có lịch sử khám bệnh
        if (!$query->count()) {
            return true;
        }

        // Có lịch sử khám bệnh cuối cách đây ít nhất 6 tháng
        if ($query->andFilterWhere(['<=', 'medical_record_item.created_at', $sixMonthsAgo])->select(['id' => 'MAX(`id`)'])->count()) {
            return true;
        } else {
            // Thủ thuật khác so với lần khám cuối
            foreach ($product_ids as $product_id) {
                if (count($queryItemChild) && !in_array($product_id, $queryItemChild)) {
                    return true;
                }
            }
        }

        return false;
    }

    static function getQuantity($data)
    {
        $return = [];
        foreach ($data as $value) {
            if (isset($return[$value->product_id]) && $return[$value->product_id]) {
                $return[$value->product_id] += 1;
            } else {
                $return[$value->product_id] = 1;
            }
        }
        return $return;
    }

    static function saveDoctorSales($options = [])
    {
        $model = new DoctorSales();
        $model->doctor_id = $options['doctor_id'];
        $model->money = $options['money'];
        $model->product_id = $options['product_id'];
        $model->medical_record_id = $options['medical_record_id'];
        $model->item_child_id = isset($options['item_child_id']) && $options['item_child_id'] ? $options['item_child_id'] : '';
        $model->week = isset($options['week']) && $options['week'] ? $options['week'] : date('W', time());
        $model->month = isset($options['month']) && $options['month'] ? $options['month'] : date('m', time());
        $model->year = isset($options['year']) && $options['year'] ? $options['year'] : date('Y', time());
        $model->created_at = $options['time_create'];
        $model->payment_id = $options['payment_id'];
        $model->branch_id = $options['branch_id'];
        if ($model->save()) {
            return true;
        }
        return false;
    }

    static function saveBranchSales($options = [])
    {
        $model = new BranchSales();
        $model->branch_id = $options['branch_id'];
        $model->money = $options['money'];
        $model->week = date('W', time());
        $model->month = date('m', time());
        $model->year = date('Y', time());
        $model->created_at = $options['time_create'];
        if ($model->save()) {
            return true;
        }
        return false;
    }

    static function saveOprerationSales($options = [])
    {

        $model = new OperationSales();
        $model->product_id = $options['product_id'];
        if (isset($options['product_id']) && $options['product_id']) {
            $product = Product::findOne($options['product_id']);
            if ($product) {
                $model->product_category_id = $product->category_id;
            }
        }
        $model->money = $options['money'];
        $model->branch_id = $options['branch_id'];
        $model->doctor_id = $options['doctor_id'];
        $model->medical_record_id = $options['medical_record_id'];
        $model->item_child_id = $options['item_child_id'];
        $model->day = date('d', time());
        $model->week = date('W', time());
        $model->month = date('m', time());
        $model->year = date('Y', time());
        $model->created_at = $options['time_create'];
        if ($model->save()) {
            return true;
        } else {
            print_r('<pre>');
            print_r($model->getErrors());
            die;
        }
        return false;
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        /**
         * Xóa danh sách lịch hẹn
         * Xóa hoa hồng
         * Xóa thống kê doanh số (Thủ thuật, bác sỹ, nguồn giới thiệu, thuốc)
         * Xóa thu chi
         * Xóa đặt xưởng
         * Xóa danh sách chờ khám
         */

        $transaction = Yii::$app->db->beginTransaction();
        try {
            //Xóa lịch hẹn
            Appointment::updateAll(['status_delete' => 1], ['medical_record_id' => $id]);

            //Xóa hoa hồng
            Commission::updateAll(['status_delete' => 1], ['medical_record_id' => $id]);

            //Xóa thống kê doanh số thủ thuật
            OperationSales::updateAll(['status_delete' => 1], ['medical_record_id' => $id]);

            //Xóa thống kê doanh số bác sỹ
            DoctorSales::updateAll(['status_delete' => 1], ['medical_record_id' => $id]);

            //Xóa thống kê thuốc
            MedicalRecordItemMedicine::updateAll(['status_delete' => 1], ['medical_record_id' => $id]);

            //Xóa thống kê thu chi
            ThuChi::updateAll(['status_delete' => 1], ['medical_record_id' => $id]);

            //Xóa đặt xưởng
            Factory::updateAll(['status_delete' => 1], ['medical_record_id' => $id]);

            //Xóa danh sách chờ khám
            WaitingList::updateAll(['status_delete' => 1], ['medical_record_id' => $id]);

            if ($model) {
                $model->status = MedicalRecord::STATUS_DELETE;
                $model->save();
            }
            $transaction->commit();
            return $this->redirect(['index']);
        } catch (Exception $e) {
            $transaction->rollBack();
            return false;
            throw $e;
        } catch (\Throwable $e) {
            $transaction->rollBack();
            return false;
            throw $e;
        }


        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = MedicalRecord::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionGetProduct($type = 1)
    {
        $request = $_GET;
        if ($type == 1) {
            $category_id = isset($request['product_category_id']) && $request['product_category_id'] ? $request['product_category_id'] : '';
            $prd = [];
            if ($category_id) {
                $products = Product::find()->where(['status' => 1, 'category_id' => $category_id])->asArray()->all();
                if ($products) {
                    foreach ($products as $product) {
                        $prd[$product['id']] = $product['name'] . ' - ' . number_format($product['price']);
                    }
                }
            }
        } else {
            $product_id = isset($request['product_id']) && $request['product_id'] ? $request['product_id'] : '';
            if ($product_id) {
                $prd = Product::find()->where(['id' => $product_id])->asArray()->one();
            }
        }
        return json_encode($prd);
    }

    public function actionGetDetailProduct()
    {
        $request = $_GET;
        $product_id = isset($request['product_id']) && $request['product_id'] ? $request['product_id'] : '';
        $product = [];
        if ($product_id) {
            $product = Product::find()->where(['id' => $product_id])->asArray()->one();
        }
        return json_encode($product);
    }

    //Thêm mới lịch hẹn
    public function actionAddAppointment()
    {
        $request = $_POST;
        $medical_record_id = isset($request['medical_record_id']) && $request['medical_record_id'] ? $request['medical_record_id'] : '';
        $medical_record = MedicalRecord::findOne($medical_record_id);
        $request['name'] = $medical_record->username;
        $request['phone'] = $medical_record->phone;

        $model = new Appointment();
        if ($model->load($request, '')) {
            $model->time = isset($request['time']) && $request['time'] ? strtotime($request['time']) : date('Y-m-d', time());
            $model->user_id = $medical_record->user_id;
            if ($model->save()) {
                return $this->renderPartial('layouts/appointment_add', ['model' => $model]);
            }
        }
        return false;
    }

    //Cập nhật lịch hẹn
    public function actionUpdateAppointment()
    {
        $request = $_POST;
        $id = isset($request['id']) && $request['id'] ? $request['id'] : '';
        $model = Appointment::findOne($id);
        if ($model) {
            $res = [];
            $res['branch_id'] = isset($request['branch_id']) && $request['branch_id'] ? $request['branch_id'] : $model->branch_id;
            $res['time'] = isset($request['time']) && $request['time'] ? strtotime($request['time']) : $model->time;
            $res['doctor_id'] = isset($request['doctor_id']) && $request['doctor_id'] ? $request['doctor_id'] : $model->doctor_id;
            $res['description'] = isset($request['description']) && $request['description'] ? $request['description'] : $model->description;
            $model->load($res, '');
            if ($model->save()) {
                return true;
            }
        }
        return false;
    }

    public function actionEditAppointment($id)
    {
        $model = Appointment::findOne($id);
        $doctor = UserAdmin::getDoctor();
        $branchs = Branch::getBranch();
        return $this->renderPartial('layouts/appointment_edit', [
            'model' => $model,
            'doctor' => $doctor,
            'branchs' => $branchs,
        ]);
    }

    //Xóa mới lịch hẹn
    public function actionDeleteAppointment($id)
    {
        $model = Appointment::findOne($id);
        if ($model) {
            if ($model->delete()) {
                return $this->redirect(['add', 'id' => $model->medical_record_id]);
            }
        }
        return false;
    }

    public function actionLoadMedicalRecordItemChild($id, $medical_record_id)
    {
        $medical_record_item_child = MedicalRecordItemChild::find()->where(['medical_record_item_id' => $id])->joinWith(['product', 'userAdmin'])->orderBy('created_at DESC')->all();
        $last_payment = \common\models\user\PaymentHistory::find()->where(['medical_record_id' => $medical_record_id])->orderBy('created_at DESC')->one();

        return $this->renderPartial('layouts/item_child', [
            'model' => $medical_record_item_child,
            'last_payment' => $last_payment
        ]);
    }

    //Thêm thuốc
    public function actionAddMedicine()
    {
        $request = $_POST;
        $id = isset($request['id']) && $request['id'] ? $request['id'] : '';
        $data = isset($request['data']) && $request['data'] ? $request['data'] : '';
        $medical_record_item = MedicalRecordItem::findOne($id);
        if ($medical_record_item) {
//            if (date('d', time()) == date('d', $medical_record_item->created_at) && date('m', time()) == date('m', $medical_record_item->created_at) && date('Y', time()) == date('Y', $medical_record_item->created_at)) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $time_create = isset($data[2]['value']) && $data[2]['value'] ? strtotime($data[2]['value']) : time();

                //Lưu lời dặn của bác sỹ
                $medical_record_item->description = $data[0]['value'];
                if (!$medical_record_item->save()) return json_encode([
                    'success' => false,
                    'message' => 'Thêm thất bại'
                ]);
                unset($data[0]);

                //Lưu danh sách thuốc
                $doctor_id = isset($data[1]['value']) && $data[1]['value'] ? $data[1]['value'] : '';
                if (!$doctor_id) {
                    return json_encode([
                        'success' => false,
                        'message' => 'Bác sĩ kê đơn không được bỏ trống'
                    ]);
                }
                unset($data[1]);
                unset($data[2]);

                $total_price = 0;
                $dt = array_chunk($data, 2);
                foreach ($dt as $value) {
                    if (isset($value[1]['value']) && $value[1]['value'] && isset($value[0]['value']) && $value[0]['value']) {
                        $medicine = Medicine::findOne($value[0]['value']);

                        $medical_record_item_medicine = new MedicalRecordItemMedicine();
                        $medical_record_item_medicine->user_id = $medical_record_item->user_id;
                        $medical_record_item_medicine->medical_record_id = $medical_record_item->medical_record_id;
                        $medical_record_item_medicine->medical_record_item_id = $medical_record_item->id;
                        $medical_record_item_medicine->medicine_id = $medicine->id;
                        $medical_record_item_medicine->doctor_id = $doctor_id;
                        $medical_record_item_medicine->status = 0;
                        $medical_record_item_medicine->money = $medicine->price;
                        $medical_record_item_medicine->quantity = $value[1]['value'];
                        $medical_record_item_medicine->branh_id = $medical_record_item->branch_id;
                        $medical_record_item_medicine->created_at = $time_create;
                        if (!$medical_record_item_medicine->save()) return json_encode([
                            'success' => false,
                            'message' => 'Thêm thất bại'
                        ]);

                        //Thêm hoa hồng
                        $commission = new Commission();
                        $commission->admin_id = $doctor_id;
                        $commission->value = Commission::COMMISSION_MEDICINE_VALUE;
                        $commission->money = $value[1]['value'] * $medicine->price * 5 / 100;
                        $commission->total_money = $value[1]['value'] * $medicine->price;
                        $commission->user_id = $medical_record_item->user_id;
                        $commission->medical_record_id = $medical_record_item->medical_record_id;
                        $commission->branch_id = $medical_record_item->branch_id;
                        $commission->type = Commission::TYPE_MEDICINE;
                        $commission->type_money = 2;
                        $commission->item_medicine_id = $medical_record_item_medicine->id;
                        $commission->created_at = $time_create;

                        if (!$commission->save()) return json_encode([
                            'success' => false,
                            'message' => 'Thêm thất bại'
                        ]);

                        $total_price += $value[1]['value'] * $medicine->price;

                    }
                }

                $medical_record = MedicalRecord::findOne($medical_record_item->medical_record_id);
                $medical_record->total_money += $total_price;
                if (!$medical_record->save()) return json_encode([
                    'success' => false,
                    'message' => 'Thêm thất bại'
                ]);

                $transaction->commit();
                return json_encode([
                    'success' => true,
                    'message' => 'Cập nhật thành công'
                ]);
            } catch (Exception $e) {
                $transaction->rollBack();
                return false;
                throw $e;
            } catch (\Throwable $e) {
                $transaction->rollBack();
                return false;
                throw $e;
            }
//            } else {
//                return json_encode([
//                    'success' => false,
//                    'message' => 'Không được phép chỉnh sửa các ngày khám đã hoàn thành trước đó'
//                ]);
//            }

        }

        return false;
    }

    //Load form nhật đơn thuốc
    public function actionFormUpdateMedicine($id)
    {
        $model = MedicalRecordItemMedicine::findOne($id);
        $doctor = UserAdmin::getDoctor();
        $medicine = Medicine::getMedicine();
        return $this->renderPartial('layouts/medicine/medicine_edit.php', [
            'model' => $model,
            'doctor' => $doctor,
            'medicine' => $medicine,
        ]);
    }

    //Cập nhật đơn thuốc
    public function actionUpdateMedicine()
    {
        $id = isset($_POST['id_update']) && $_POST['id_update'] ? $_POST['id_update'] : '';
        $doctor_id = isset($_POST['medicine_doctor_id_update']) && $_POST['medicine_doctor_id_update'] ? $_POST['medicine_doctor_id_update'] : '';
        $medicine_id = isset($_POST['medicine_id_update']) && $_POST['medicine_id_update'] ? $_POST['medicine_id_update'] : '';
        $quantity = isset($_POST['medicine_quantity_update']) && $_POST['medicine_quantity_update'] ? $_POST['medicine_quantity_update'] : '';
        $medical_record_medicine = MedicalRecordItemMedicine::findOne($id);
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($medical_record_medicine) {
                $money_old = $medical_record_medicine->money * $medical_record_medicine->quantity;
                $medicine = Medicine::findOne($medicine_id);

                $medical_record_medicine->medicine_id = $medicine_id;
                $medical_record_medicine->quantity = $quantity;
                $medical_record_medicine->doctor_id = $doctor_id;
                $medical_record_medicine->money = $medicine->price;
                if (!$medical_record_medicine->save()) return false;

                $commission = Commission::find()->where(['medical_record_id' => $medical_record_medicine->medical_record_id, 'item_medicine_id' => $id, 'type' => 2])->one();
                if ($commission) {
                    $commission->admin_id = $doctor_id;
                    $commission->money = $medicine->price * $quantity * 5 / 100;
                    $commission->total_money = $medicine->price * $quantity;
                    if (!$commission->save()) return false;
                }

                $medical_record = MedicalRecord::findOne($medical_record_medicine->medical_record_id);
                $medical_record->total_money = $medical_record->total_money - $money_old + ($medicine->price * $quantity);
                if (!$medical_record->save()) return false;
            }
            $transaction->commit();
            return true;
        } catch (Exception $e) {
            $transaction->rollBack();
            return false;
            throw $e;
        } catch
        (\Throwable $e) {
            $transaction->rollBack();
            return false;
            throw $e;
        }
        return false;

    }

    //Xóa đơn thuốc
    public function actionDeleteMedicine($id)
    {
        $medical_record_medicine = MedicalRecordItemMedicine::findOne($id);
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($medical_record_medicine) {
                $medical_record_id = $medical_record_medicine->medical_record_id;
                //Giảm tiền hồ sơ bệnh án
                $medical_record = MedicalRecord::findOne($medical_record_medicine->medical_record_id);
                $medical_record->total_money -= $medical_record_medicine->money * $medical_record_medicine->quantity;
                if (!$medical_record->save()) return false;

                //Xóa hoa hồng
                $commission = Commission::find()->where(['item_medicine_id' => $id])->one();
                if ($commission) {
                    if (!$commission->delete()) return false;
                }
                if (!$medical_record_medicine->delete()) return false;
            }
            $transaction->commit();
            return $this->redirect(['add', 'id' => $medical_record_id]);
        } catch (Exception $e) {
            $transaction->rollBack();
            return false;
            throw $e;
        } catch
        (\Throwable $e) {
            $transaction->rollBack();
            return false;
            throw $e;
        }
        return false;
    }

    //Load danh sách đơn thuốc đã kê
    public function actionLoadDonthuoc()
    {
        $request = $_POST;
        $id = isset($request['id']) && $request['id'] ? $request['id'] : '';
        $model = MedicalRecordItem::findOne($id);
        $medical_record_item_child = MedicalRecordItemMedicine::find()->where(['medical_record_item_id' => $id])->joinWith(['medicine', 'userAdmin'])->orderBy('created_at DESC')->all();
        $medicine = Medicine::find()->where(['status' => 1])->all();
        return $this->renderPartial('layouts/don_thuoc', [
            'medical_record_item_child' => $medical_record_item_child,
            'model' => $model,
            'medicine' => $medicine,
        ]);
    }

    //Thanh toán
    public function actionPayment($id)
    {
//        Array
//            (
//                [pay_branch] => Array
//                (
//                    [5744] => 4
//                    [5745] => 4
//                )
//
//            [pay_time_create] => Array
//            (
//                [5744] => 2022-06-17T14:48
//                    [5745] => 2022-06-17T14:48
//                )
//
//            [pay_money] => Array
//            (
//                [5744] => 11111111
//                    [5745] => 222222222222
//                )
//
//            [type_payment] => Array
//            (
//                [5744] => 1
//                    [5745] => 2
//                )
//
//            [pay_sale_note] => Array
//            (
//                [5744] => 11111111111
//                    [5745] => 2222222222222
//                )
//
//        )


        $request = $_POST;
        $model = MedicalRecordV2::findOne($id);
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if (ClaNhakhoa::check_array($request['pay_branch'])) {
                foreach ($request['pay_branch'] as $key => $value) {
                    $medical_record_item_child = MedicalRecordItemChildV2::find()->where(['id' => $key])->one(); //Lấy chi tiết lần kham của thủ thuật
                    if ($medical_record_item_child) {
                        $da_thanh_toan = isset($medical_record_item_child->payment) && ClaNhakhoa::check_array($medical_record_item_child->payment) ? \common\components\ClaNhakhoa::getSum($medical_record_item_child->payment,'money') : 0;
                        $tong_tien = \common\models\hsba\PaymentHistoryV2::getMoney($medical_record_item_child->money,$medical_record_item_child->type_sale,$medical_record_item_child->sale_value);

                        //Lưu thanh toán
                        $time_create = isset($request['pay_time_create'][$key]) && $request['pay_time_create'][$key] ? strtotime($request['pay_time_create'][$key]) : time();
                        $payment = new PaymentHistoryV2();
                        $payment->medical_record_id = $id;
                        $payment->medical_record_item_child_id = $key;
                        $payment->money = $request['pay_money'][$key];
                        $payment->branch_id = $request['pay_branch'][$key];
                        $payment->admin_id = Yii::$app->user->id;
                        $payment->type_payment = $request['type_payment'][$key];
                        $payment->note = $request['pay_sale_note'][$key];
                        $payment->created_at = $time_create;
                        if (!$payment->save()) throw new Exception('Lưu lịch sử thanh toán lỗi');

                        //Lưu doanh số chi nhánh
                        $branch_log = new BranchSalesV2();
                        $branch_log->branch_id = $request['pay_branch'][$key];
                        $branch_log->payment_id = $payment->id;
                        $branch_log->money = $request['pay_money'][$key];
                        $branch_log->type = BranchSales::TYPE_BENHAN;
                        $branch_log->type_id = $id;
                        $branch_log->week = date("W", time());
                        $branch_log->month = date("m", time());
                        $branch_log->year = date("Y", time());
                        $branch_log->created_at = $time_create;
                        if (!$branch_log->save()) throw new Exception('Lưu thống kê doanh số chi nhánh lỗi');

                        //Lưu hoa hồng
                        $medical_record_item_commission = MedicalRecordItemCommissionV2::find()->where(['medical_record_item_child_id' => $key])->asArray()->one();
                        if ($medical_record_item_commission && ClaNhakhoa::check_array(explode(',', $medical_record_item_commission['user_id']))) {
                            $danh_sach_nguoi_huong_hoa_hong = explode(',', $medical_record_item_commission['user_id']);
                            $type_commission = explode(',', $medical_record_item_commission['type']);
                            $value_commission = explode(',', $medical_record_item_commission['value']);
                            $tong_tien_dich_vu = PaymentHistoryV2::getMoney($medical_record_item_child->money,$medical_record_item_child->type_sale,$medical_record_item_child->sale_value) ;
                            $tong_tien_da_thanh_toan = 0 ;

                            foreach ($danh_sach_nguoi_huong_hoa_hong as $key_us => $item) {
                                $commission = new CommissionV2();
                                $commission->medical_record_id = $id;
                                $commission->user_id = $item;
                                $commission->type = CommissionV2::TYPE_PAYMENT;
                                $commission->type_id = $key;
                                $commission->value_commission = $value_commission[$key_us];
                                $commission->type_commission = $type_commission[$key_us];
                                if ($type_commission[$key_us] == MedicalRecordItemCommissionV2::TYPE_1) {
                                    $commission->money = ($value_commission[$key_us] / 100) * $request['pay_money'][$key];
                                    $money_doctor = DoctorSales::getValueSale($value_commission[$key_us], $request['pay_money'][$key]);
                                } else {
                                    if ((int)($tong_tien_dich_vu * 30 / 100) <= $request['pay_money'][$key] + $tong_tien_da_thanh_toan && $tong_tien_da_thanh_toan < (int)($tong_tien_dich_vu * 30 / 100)) {
                                        $commission->money = $value_commission[$key_us];
                                        $money_doctor = $value_commission[$key_us] / 4;
                                    } else {
                                        continue;
                                    }
                                }
                                $commission->branch_id = $request['pay_branch'][$key];
                                $commission->payment_id = $payment->id;
                                $commission->created_at = $time_create;
                                if (!$commission->save()) throw new Exception('Lưu hoa hồng lỗi');

                                $saveDoctor = self::saveDoctorSales([
                                    'doctor_id' => $item,
                                    'money' => $money_doctor,
                                    'product_id' => $medical_record_item_child->product_id,
                                    'medical_record_id' => $id,
                                    'time_create' => $time_create,
                                    'payment_id' => $payment->id,
                                    'branch_id' => $payment->branch_id,
                                    'week' => date('W',$time_create),
                                    'month' => date('m',$time_create),
                                    'year' => date('Y',$time_create),
                                ]);
                                if (!$saveDoctor) throw new Exception('Lưu doanh số bác sỹ lỗi');
                            }
                        }

                        //THống kê thu chi
                        $thuchi = new ThuChiV2();
                        $thuchi->name = 'Thu tiền khám bệnh';
                        $thuchi->type = ThuChiV2::TYPE_THU;
                        $thuchi->user_id = $model->user_id;
                        $thuchi->money = $request['pay_money'][$key];
                        $thuchi->time = $time_create;
                        $thuchi->admin_id = Yii::$app->user->id;
                        $thuchi->nguoi_chi = Yii::$app->user->id;
                        $thuchi->branch_id = $request['pay_branch'][$key];
                        $thuchi->type_id = ThuChi::TYPE_THU_PAYMENT;
                        $thuchi->payment_id = $payment->id;
                        $thuchi->type_payment = $request['type_payment'][$key];
                        $thuchi->medical_record_id = $id;
                        $thuchi->created_at = $time_create;
                        if (!$thuchi->save()) throw new Exception('Lưu thu chi lỗi');

                    }
                }
            }

            $transaction->commit();
            return 'Thanh toán thành công';
        } catch (Exception $e) {
            $transaction->rollBack();
            return false;
            throw $e;
        } catch
        (\Throwable $e) {
            $transaction->rollBack();
            return false;
            throw $e;
        }

        return 'Lỗi dữ liệu';
    }

    //Xóa thanh toán
    public function actionDeletePayment($id)
    {
        $payment = PaymentHistory::findOne($id);
        if ($payment) {
            $model = MedicalRecord::findOne($payment->medical_record_id);
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $model->money -= $payment->money;
                if ($model->money >= $model->total_money) {
                    $model->status = MedicalRecord::STATUS_SUCCESS_ALL;
                } else {
                    $model->status = MedicalRecord::STATUS_SUCCESS;
                }
                if (!$model->save()) return 'Cập nhật tiền hồ sơ bệnh án lỗi';

                //Cập nhật lại thu chi
                $thuchi = ThuChi::find()->where(['payment_id' => $payment->id, 'medical_record_id' => $model->id, 'status_delete' => 0])->one();
                if ($thuchi) {
                    if (!$thuchi->delete()) return 'Xóa thu chi lỗi';
                }

                if (!$payment->delete()) return 'Xóa lịch sử thanh toán lỗi';

                //Lưu lại hoa hồng
                self::updateCommissionPayment([
                    'id' => $model->id,
                ]);

                $transaction->commit();
                return $this->redirect(['/user/medical-record/add', 'id' => $model->id]);
            } catch (Exception $e) {
                $transaction->rollBack();
                return $e;
                throw $e;
            } catch
            (\Throwable $e) {
                $transaction->rollBack();
                return $e;
                throw $e;
            }
        }
        return 'Lỗi dữ liệu';
    }

    //Chỉnh sửa thanh toán
    static function editPayment($options = [])
    {
        $model = MedicalRecord::findOne($options['id']);
        $payment = PaymentHistory::findOne($options['payment_id']);
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($options['money'] > ($model->total_money - ($model->money - $payment->money))) {
                return 'Số tiền thanh toán lớn hơn tổng viện phí';
            }
            $model->money += $options['money'] - $payment->money;
            if ($model->money >= $model->total_money) {
                $model->status = MedicalRecord::STATUS_SUCCESS_ALL;
            } else {
                $model->status = MedicalRecord::STATUS_SUCCESS;
            }
            if (!$model->save()) return 'thanh toán lỗi';

            //Lưu lại dữ liệu thanh toán
            $payment->money = $options['money'];
            $payment->branch_id = $options['branch_id'];
            $payment->admin_id = Yii::$app->user->id;
            $payment->pay_sale = $options['pay_sale'];
            $payment->pay_sale_description = $options['pay_sale_description'];
            $payment->type_sale = $options['type_sale'];
            $payment->type_payment = $options['type_payment'];
            $payment->note = $options['note'];
            $payment->created_at = $options['time_create'];
            if (!$payment->save()) return 'Cập nhật lịch sử thanh toán lỗi';

            //Lưu lại hoa hồng
            self::updateCommissionPayment($options);

            //Cập nhật lại thu chi
            $thuchi = ThuChi::find()->where(['payment_id' => $payment->id, 'medical_record_id' => $model->id, 'status_delete' => 0])->one();
            if ($thuchi) {
                $thuchi->money = $options['money'] - $options['pay_sale'];
                if ($options['type_sale']) {
                    if ($options['type_sale'] == PaymentHistory::TYPE_SALE_2) {
                        $thuchi->money = ($options['money'] * (100 - $options['pay_sale'])) / 100;
                    }
                }
                $thuchi->time = $options['time_create'];
                $thuchi->admin_id = Yii::$app->user->id;
                $thuchi->nguoi_chi = Yii::$app->user->id;
                $thuchi->branch_id = $options['branch_id'];
                $thuchi->type_id = ThuChi::TYPE_THU_PAYMENT;
                $thuchi->payment_id = $payment->id;
                $thuchi->type_payment = $options['type_payment'];
                $thuchi->created_at = $options['time_create'];
                if (!$thuchi->save()) return 'Lưu thu chi lỗi';
            }

            $waiting_list = WaitingList::find()->where(['status' => 1, 'medical_record_id' => $model->id])->orderBy('created_at DESC')->one();
            if ($waiting_list) {
                $waiting_list->status = 2;
                if (!$waiting_list->save()) return 'Lưu sdf chi lỗi';
            }

            $transaction->commit();
            return 'Thanh toán thành công';
        } catch (Exception $e) {
            $transaction->rollBack();
            return $e;
            throw $e;
        } catch
        (\Throwable $e) {
            $transaction->rollBack();
            return $e;
            throw $e;
        }

        return 'Lỗi dữ liệu';
    }

    //Chỉnh sửa % hoa hồng khi chỉnh sử thanh toán
    static function updateCommissionPayment($options = [])
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $model = MedicalRecord::findOne($options['id']);
            //Tính lại hoa hồng
            $payment_history = PaymentHistory::find()->where(['medical_record_id' => $options['id']])->orderBy('created_at ASC')->all();
            if ($payment_history) {
                Commission::deleteAll(['medical_record_id' => $options['id'], 'type' => Commission::TYPE_PAYMENT]);
                DoctorSales::deleteAll(['medical_record_id' => $options['id']]);
                MedicalRecordItemCommission::updateAll(['price_payment' => 0, 'status' => 0, 'payment_status' => 0], ['medical_record_id' => $options['id']]);
                foreach ($payment_history as $payment) {
                    $item_commission_all = MedicalRecordItemCommission::find()->where(['medical_record_id' => $options['id'], 'status' => 0])->orderBy('created_at ASC')->all();
                    $money = $payment->money;
                    if ($item_commission_all) {
                        $money_payment = $money; // Chính là số tiền còn lại sau mỗi lần thanh toán qua từng thủ thuật
                        foreach ($item_commission_all as $item_commission) {
                            $user_ids = explode(',', $item_commission->user_id);
                            $value_commission = explode(',', $item_commission->value);
                            $type_commission = explode(',', $item_commission->type);
                            $money_before = $item_commission->price - $item_commission->price_payment; // Số tiền còn lại cần thanh toán
                            if ($money_before > $money_payment) { // Số tiền thanh toán vẫn nhỏ hơn số tiền chưa thanh toán còn lại của thủ thuật
                                foreach ($user_ids as $key_us => $us) {
                                    $money_doctor = 0;
                                    $commission = new Commission();
                                    $commission->admin_id = $us;
                                    $commission->value = $value_commission[$key_us];

                                    if ($type_commission[$key_us] == MedicalRecordItemCommission::TYPE_1) {
                                        $commission->money = ($value_commission[$key_us] / 100) * $money_payment;
                                        $money_doctor = DoctorSales::getValueSale($value_commission[$key_us], $money_payment);
                                    } else {
                                        if ((int)($item_commission->price * 30 / 100) <= $money_payment + $item_commission->price_payment && $item_commission->price_payment < (int)($item_commission->price * 30 / 100)) {
                                            $commission->money = $value_commission[$key_us];
                                            $money_doctor = $value_commission[$key_us] / 4;
                                        } else {
                                            continue;
                                        }
                                    }

                                    $commission->total_money = $money;
                                    $commission->total_money_received = $money_payment;
                                    $commission->user_id = $model->user_id;
                                    $commission->medical_record_id = $model->id;
                                    $commission->branch_id = $payment->branch_id;
                                    $commission->type = Commission::TYPE_PAYMENT;
                                    $commission->type_money = $type_commission[$key_us];
                                    $commission->item_commission_id = $item_commission->id;
                                    $commission->created_at = $payment->created_at;
                                    if (!$commission->save()) throw new Exception('Lưu hoa hồng lỗi');

                                    $saveDoctor = self::saveDoctorSales([
                                        'doctor_id' => $us,
                                        'money' => $money_doctor,
                                        'product_id' => $item_commission->product_id,
                                        'medical_record_id' => $model->id,
                                        'time_create' => $payment->created_at,
                                        'payment_id' => $payment->id,
                                        'branch_id' => $payment->branch_id,
                                        'week' => date('W', $payment->created_at),
                                        'month' => date('m', $payment->created_at),
                                        'year' => date('Y', $payment->created_at),
                                    ]);
                                    if (!$saveDoctor) throw new Exception('Lưu doanh số bác sỹ lỗi');
                                }
                                $item_commission->price_payment += $money_payment;
                                $item_commission->payment_status = 1;
                                if (!$item_commission->save()) throw new Exception('Lưu cấu hình hoa hồng lỗi');
                                break;
                            } elseif ($money_before == $money_payment) { // Số tiền thanh toán bằng số tiền chưa thanh toán còn lại của thủ thuật
                                foreach ($user_ids as $key_us => $us) {
                                    $money_doctor = 0;
                                    $product = Product::findOne($item_commission->product_id);
                                    $item_child = MedicalRecordItemChild::findOne($item_commission->medical_record_item_child_id);
                                    $sale_doc = $money_before + ($product->price * $item_child->quantity) - $item_commission->price;
                                    $commission = new Commission();
                                    $commission->admin_id = $us;
                                    $commission->value = $value_commission[$key_us];
                                    if ($type_commission[$key_us] == MedicalRecordItemCommission::TYPE_1) {
                                        $commission->money = ($value_commission[$key_us] / 100) * $money_payment;
                                        $money_doctor = DoctorSales::getValueSale($value_commission[$key_us], $sale_doc);
                                    } else {
                                        if ((int)($item_commission->price * 30 / 100) <= $money_payment + $item_commission->price_payment && $item_commission->price_payment < (int)($item_commission->price * 30 / 100)) {
                                            $commission->money = $value_commission[$key_us];
                                            $money_doctor = $value_commission[$key_us] / 4;
                                        } else {
                                            continue;
                                        }
                                    }
                                    $commission->total_money = $money;
                                    $commission->total_money_received = $money_payment;
                                    $commission->user_id = $model->user_id;
                                    $commission->medical_record_id = $model->id;
                                    $commission->branch_id = $payment->branch_id;
                                    $commission->type = Commission::TYPE_PAYMENT;
                                    $commission->type_money = $type_commission[$key_us];
                                    $commission->item_commission_id = $item_commission->id;
                                    $commission->created_at = $payment->created_at;
                                    if (!$commission->save()) throw new Exception('Lưu hoa hồng lỗi');

                                    $saveDoctor = self::saveDoctorSales([
                                        'doctor_id' => $us,
                                        'money' => $money_doctor,
                                        'product_id' => $item_commission->product_id,
                                        'medical_record_id' => $model->id,
                                        'time_create' => $payment->created_at,
                                        'payment_id' => $payment->id,
                                        'branch_id' => $payment->branch_id,
                                        'week' => date('W', $payment->created_at),
                                        'month' => date('m', $payment->created_at),
                                        'year' => date('Y', $payment->created_at),
                                    ]);
                                    if (!$saveDoctor) throw new Exception('Lưu doanh số bác sỹ lỗi');
                                }
                                $item_commission->status = 1;
                                $item_commission->payment_status = 0;
                                $item_commission->price_payment += $money_payment;
                                if (!$item_commission->save()) throw new Exception('Lưu cấu hình hoa hồng lỗi');
                                break;
                            } else { // Số tiền thanh toán lớn hơn số tiền chưa thanh toán còn lại của thủ thuật
                                foreach ($user_ids as $key_us => $us) {
                                    $money_doctor = 0;
                                    $product_up = Product::findOne($item_commission->product_id);
                                    $item_child_up = MedicalRecordItemChild::findOne($item_commission->medical_record_item_child_id);
                                    $sale_doc = $money_before + ($product_up->price * $item_child_up->quantity) - $item_commission->price;
                                    $commission = new Commission();
                                    $commission->admin_id = $us;
                                    $commission->value = $value_commission[$key_us];
                                    if ($type_commission[$key_us] == MedicalRecordItemCommission::TYPE_1) {
                                        $commission->money = ($value_commission[$key_us] / 100) * $money_before;
                                        $money_doctor = DoctorSales::getValueSale($value_commission[$key_us], $sale_doc);
                                    } else {
                                        if ((int)($item_commission->price * 30 / 100) <= $money_payment + $item_commission->price_payment && $item_commission->price_payment < (int)($item_commission->price * 30 / 100)) {
                                            $commission->money = $value_commission[$key_us];
                                            $money_doctor = $value_commission[$key_us] / 4;
                                        } else {
                                            continue;
                                        }
                                    }

                                    $commission->total_money = $money;
                                    $commission->total_money_received = $money_before;
                                    $commission->user_id = $model->user_id;
                                    $commission->medical_record_id = $model->id;
                                    $commission->branch_id = $payment->branch_id;
                                    $commission->type = Commission::TYPE_PAYMENT;
                                    $commission->type_money = $type_commission[$key_us];
                                    $commission->item_commission_id = $item_commission->id;
                                    $commission->created_at = $payment->created_at;
                                    if (!$commission->save()) throw new Exception('Lưu hoa hồng lỗi');

                                    $saveDoctor = self::saveDoctorSales([
                                        'doctor_id' => $us,
                                        'money' => $money_doctor,
                                        'product_id' => $item_commission->product_id,
                                        'medical_record_id' => $model->id,
                                        'time_create' => $payment->created_at,
                                        'payment_id' => $payment->id,
                                        'branch_id' => $payment->branch_id,
                                        'week' => date('W', $payment->created_at),
                                        'month' => date('m', $payment->created_at),
                                        'year' => date('Y', $payment->created_at),
                                    ]);
                                    if (!$saveDoctor) throw new Exception('Lưu doanh số bác sỹ lỗi');
                                }
                                $item_commission->status = 1;
                                $item_commission->payment_status = 0;
                                $item_commission->price_payment = $item_commission->price;
                                if (!$item_commission->save()) throw new Exception('Lưu cấu hình hoa hồng lỗi');
                                $money_payment -= $money_before;
                            }
                        }
                    }
                }
            } else {
                Commission::deleteAll(['medical_record_id' => $options['id'], 'type' => Commission::TYPE_PAYMENT]);
                MedicalRecordItemCommission::updateAll(['price_payment' => 0, 'status' => 0, 'payment_status' => 0], ['medical_record_id' => $options['id']]);
            }

            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();
            return false;
            throw $e;
        } catch
        (\Throwable $e) {
            $transaction->rollBack();
            return false;
            throw $e;
        }
        return true;
    }


    public function actionLoadFactory($id)
    {
        $medical_record = MedicalRecord::findOne($id);
        $factory = Factory::find()->where(['medical_record_id' => $id])->joinWith(['branch', 'userAdmin', 'loaimau'])->orderBy('created_at DESC')->asArray()->all();
        return $this->renderPartial('layouts/factory/factory_history', [
            'medical_record' => $medical_record,
            'factory' => $factory,
        ]);
    }

    public function actionAddFactory($id)
    {
        $request = $_POST;
        $f_id = isset($request['f_id']) && $request['f_id'] ? $request['f_id'] : '';
        $branch_id = isset($request['branch_id']) && $request['branch_id'] ? $request['branch_id'] : '';
        $factory_id = isset($request['factory_id']) && $request['factory_id'] ? $request['factory_id'] : '';
        $device_id = isset($request['device_id']) && $request['device_id'] ? $request['device_id'] : '';
        $quantity = isset($request['quantity']) && $request['quantity'] ? $request['quantity'] : '';
        $admin_id = isset($request['admin_id']) && $request['admin_id'] ? $request['admin_id'] : '';
        $phone = isset($request['phone']) && $request['phone'] ? $request['phone'] : '';
        $time_create = isset($request['factory_time_create']) && $request['factory_time_create'] ? strtotime($request['factory_time_create']) : time();

        $model = MedicalRecord::findOne($id);
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $device = LoaiMau::findOne($device_id);
            if ($f_id) {
                $factory = Factory::findOne($f_id);
            } else {
                $factory = new Factory();
            }
            $factory->user_id = $model->user_id;
            $factory->medical_record_id = $model->id;
            $factory->factory_id = $factory_id;
            $factory->money = $device->money;
            $factory->quantity = $quantity;
            $factory->device_id = $device_id;
            $factory->branch_id = $branch_id;
            $factory->admin_id = $admin_id ? $admin_id : Yii::$app->user->id;
            $factory->user_action_id = Yii::$app->user->id;
            $factory->phone = $phone;
            $factory->status = Factory::STATUS_WAITING;
            $factory->created_at = $time_create;
            if (!$factory->save()) throw new Exception('Lưu đặt xưởng lỗi');

            $xuong = UserAdmin::findOne($factory_id);

            if ($f_id) {
                $thuchi = ThuChi::find()->where(['medical_record_id' => $id, 'status_delete' => 0, 'object_type' => ThuChi::OBJECT_TYPE_XUONG, 'object_id' => $factory->id])->one();
            } else {
                $thuchi = new ThuChi();
            }

            $thuchi->name = 'Đặt xưởng';
            $thuchi->type = ThuChi::TYPE_CHI;
            $thuchi->money = $device->money * $quantity;
            $thuchi->time = $time_create;
            $thuchi->note = 'Mã hồ sơ bệnh án: ' . $id . '. Tên xưởng: ' . $xuong->fullname . '. Loại mẫu: ' . $device->name . '. Số lượng: ' . $quantity . '. Đơn giá: ' . number_format($device->money);
            $thuchi->admin_id = Yii::$app->user->id;
            $thuchi->nguoi_chi = Yii::$app->user->id;
            $thuchi->branch_id = $branch_id;
            $thuchi->ncc_id = $factory_id;
            $thuchi->type_id = ThuChi::TYPE_CHI_NCC;
            $thuchi->medical_record_id = $id;
            $thuchi->created_at = $time_create;
            $thuchi->object_type = ThuChi::OBJECT_TYPE_XUONG;
            $thuchi->object_id = $factory->id;
            $thuchi->user_id = $model->user_id;

            if (!$thuchi->save()) throw new Exception('Lưu thu chi lỗi');

            $transaction->commit();
            return 'Đặt thành công';
        } catch (Exception $e) {
            $transaction->rollBack();
            return false;
            throw $e;
        } catch (\Throwable $e) {
            $transaction->rollBack();
            return false;
            throw $e;
        }

        return 'Lỗi dữ liệu';
    }

    public function actionDeleteFactory($id)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $factory = Factory::findOne($id);
            $medical_record_id = $factory->medical_record_id;
            if (!$factory->delete()) throw new Exception('Xóa đặt xưởng lỗi');

            $thuchi = ThuChi::find()->where(['medical_record_id' => $medical_record_id, 'status_delete' => 0, 'object_type' => ThuChi::OBJECT_TYPE_XUONG, 'object_id' => $factory->id])->one();
            if (!$thuchi->delete()) throw new Exception('Xóa thu chi lỗi');

            $transaction->commit();
            return $this->redirect(['/user/medical-record/add', 'id' => $medical_record_id]);
        } catch (Exception $e) {
            $transaction->rollBack();
            return $this->redirect(['/user/medical-record/add', 'id' => $medical_record_id]);
            throw $e;
        } catch (\Throwable $e) {
            $transaction->rollBack();
            return $this->redirect(['/user/medical-record/add', 'id' => $medical_record_id]);
            throw $e;
        }
    }

    public function actionLoadImage()
    {
        $request = $_POST;
        $id = isset($request['id']) && $request['id'] ? $request['id'] : '';
        $images = MedicalRecordImage::getImagesById($id);
        return $this->renderPartial('layouts/images', [
            'images' => $images,
        ]);
    }

    public function actionAddCommission($id)
    {
        $request = $_POST;
        $data = isset($request['data']) && $request['data'] ? $request['data'] : [];
        $medical_record_item = MedicalRecordItem::findOne($id);
        $value_commission = 0;
        $medical_record_item_commission = MedicalRecordItemCommission::find()->where(['medical_record_item_id' => $id])->all();
        if ($medical_record_item_commission) {
            foreach ($medical_record_item_commission as $value) {
                $value_commission += $value->value;
            }
        }
        if ($data) {
            $dt = array_chunk($data, 2);
            foreach ($dt as $value) {
                if (isset($value[1]['value']) && $value[1]['value'] && isset($value[0]['value']) && $value[0]['value']) {
                    $value_commission += isset($value[1]['value']) && $value[1]['value'] ? $value[1]['value'] : 0;
                } else {
                    return json_encode([
                        'success' => false,
                        'message' => 'Người hưởng và % hoa hồng không được bỏ trống'
                    ]);
                }
            }
        }

        if ($value_commission > 8) {
            return json_encode([
                'success' => false,
                'message' => 'Tổng % hoa hồng không được lớn hơn 8'
            ]);
        }

        if (isset($dt) && $dt) {
            foreach ($dt as $value) {
                if (isset($value[1]['value']) && $value[1]['value'] && isset($value[0]['value']) && $value[0]['value']) {
                    $model = new MedicalRecordItemCommission();
                    $model->medical_record_id = $medical_record_item->medical_record_id;
                    $model->medical_record_item_id = $id;
                    $model->user_id = $value[0]['value'];
                    $model->value = $value[1]['value'];
                    $model->status = 0;
                    $model->save();
                }
            }
        }

        return json_encode([
            'success' => true,
            'message' => 'Cập nhật thành công'
        ]);
    }

    //Thêm mã giảm giá vào hồ sơ bệnh án
    public function actionAddVoucher($id)
    {
        $request = $_POST;
        $branch_id = isset($request['branch_id']) && $request['branch_id'] ? $request['branch_id'] : '';
        $code = isset($request['voucher']) && $request['voucher'] ? $request['voucher'] : '';
        $time_create = isset($request['voucher_time_create']) && $request['voucher_time_create'] ? strtotime($request['voucher_time_create']) : time();

        $model = MedicalRecord::findOne($id);
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $voucher = Voucher::find()->where(['voucher' => $code, 'status' => 1])->one();
            $check = Voucher::checkValidate($voucher, $model->total_money, time(), $model->user_id, $branch_id);
            if ($check !== true) {
                return json_encode([
                    'success' => false,
                    'message' => $check
                ]);
            }

            $products = MedicalRecordChild::find()->where(['medical_record_id' => $id])->asArray()->all();
            $sale = 0;
            if ($products) {
                $products = array_column($products, 'quantity', 'product_id'); // danh sách các thủ thuật trong hồ sơ bệnh án
                $product_ids = explode(',', $voucher->product_ids); // Danh sách thủ thuật trong mã giảm giá
                foreach ($products as $key => $value) {
                    if (in_array($key, $product_ids)) {
                        $product = Product::findOne($key);
                        $model->sale_money += $value * Voucher::getMoney($product->price, $voucher->type, $voucher->type_value);
                        $sale += $value * Voucher::getMoney($product->price, $voucher->type, $voucher->type_value);
                    }
                }

                if (!$model->save()) return json_encode([
                    'success' => false,
                    'message' => 'Lưu tiền trừ đi khi sử dụng voucher trong hồ sơ bệnh án lỗi'
                ]);
            } else {
                return json_encode([
                    'success' => false,
                    'message' => 'Mã giảm giá không áp dụng cho thủ thuật nào trong hồ sơ bệnh án'
                ]);
            }

            $medical_record_voucher = new MedicalRecordVoucher();
            $medical_record_voucher->user_id = $model->user_id;
            $medical_record_voucher->medical_record_id = $model->id;
            $medical_record_voucher->voucher_id = $voucher->id;
            $medical_record_voucher->product_ids = $voucher->product_ids;
            $medical_record_voucher->type = $voucher->type;
            $medical_record_voucher->type_value = $voucher->type_value;
            $medical_record_voucher->money_start = $voucher->money_start;
            $medical_record_voucher->money_end = $voucher->money_end;
            $medical_record_voucher->branch_id = $voucher->branch_id;
            $medical_record_voucher->total_money = $sale;
            $medical_record_voucher->created_at = $time_create;
            if (!$medical_record_voucher->save()) return json_encode([
                'success' => false,
                'message' => 'Lưu voucher lỗi'
            ]);


            $transaction->commit();
            return json_encode([
                'success' => true,
                'message' => 'Thêm thành công'
            ]);
        } catch (Exception $e) {
            $transaction->rollBack();
            return json_encode([
                'success' => false,
                'message' => 'Lỗi dữ liệu'
            ]);
            throw $e;
        } catch (\Throwable $e) {
            $transaction->rollBack();
            return json_encode([
                'success' => false,
                'message' => 'Lỗi dữ liệu'
            ]);
            throw $e;
        }
    }

    //Load danh sách mã giảm giá đang áp dụng cho hồ sơ bệnh án
    public function actionLoadVoucher($id)
    {
        $medical_record = MedicalRecord::findOne($id);
        $factory = MedicalRecordVoucher::find()->where(['medical_record_id' => $id])->joinWith(['branch', 'voucher'])->orderBy('created_at DESC')->all();
        return $this->renderPartial('layouts/voucher/voucher_history', [
            'medical_record' => $medical_record,
            'factory' => $factory,
        ]);
    }

    //Xóa mã giảm giá khỏi hồ sơ bệnh án
    public function actionDeleteVoucher($id)
    {
        $medical_record_voucher = MedicalRecordVoucher::findOne($id);
        $voucher = Voucher::findOne($medical_record_voucher->voucher_id);
        $model = MedicalRecord::findOne($medical_record_voucher->medical_record_id);
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $products = MedicalRecordChild::find()->where(['medical_record_id' => $medical_record_voucher->medical_record_id])->asArray()->all();
            if ($products) {
                $products = array_column($products, 'quantity', 'product_id'); // danh sách các thủ thuật trong hồ sơ bệnh án
                $product_ids = explode(',', $voucher->product_ids); // Danh sách thủ thuật trong mã giảm giá
                foreach ($products as $key => $value) {
                    if (in_array($key, $product_ids)) {
                        $product = Product::findOne($key);
                        $model->sale_money -= $value * Voucher::getMoney($product->price, $voucher->type, $voucher->type_value);
                    }
                }

                if (!$model->save()) return json_encode([
                    'success' => false,
                    'message' => 'Lưu tiền trừ đi khi sử dụng voucher trong hồ sơ bệnh án lỗi'
                ]);
            } else {
                return json_encode([
                    'success' => false,
                    'message' => 'Mã giảm giá không áp dụng cho thủ thuật nào trong hồ sơ bệnh án'
                ]);
            }
            if (!$medical_record_voucher->delete()) {
                return json_encode([
                    'success' => false,
                    'message' => 'Xóa lỗi'
                ]);
            }

            $transaction->commit();
            return json_encode([
                'success' => true,
            ]);
        } catch (Exception $e) {
            $transaction->rollBack();
            return json_encode([
                'success' => false,
                'message' => 'Lỗi dữ liệu'
            ]);
            throw $e;
        } catch (\Throwable $e) {
            $transaction->rollBack();
            return json_encode([
                'success' => false,
                'message' => 'Lỗi dữ liệu'
            ]);
            throw $e;
        }

    }

    //Xóa dịch vụ đã thêm khi khám bênh
    public function actionRemoveItem($id)
    {
        $item = MedicalRecordItemChild::findOne($id);
        $medical_record = MedicalRecord::findOne($item->medical_record_id);
        $medical_record_child = MedicalRecordChild::find()->where(['medical_record_id' => $item->medical_record_id, 'product_id' => $item->product_id])->one();
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $commission = MedicalRecordItemCommission::find()->where(['medical_record_item_child_id' => $id])->one();
            if ($commission) {
                $commission->delete();
            }

            //Xóa hoặc giảm số lần sử dụng
            if ($medical_record_child->quantity == $item->quantity) {
                $medical_record_child->delete();
            } else {
                $medical_record_child->quantity -= $item->quantity;
                $medical_record_child->quantity_use -= $item->quantity;
                $medical_record_child->save();
            }
            //Trừ tiền bệnh án
            if ($medical_record->total_money >= ($item->money * $item->quantity)) {
                $medical_record->total_money -= ($item->money * $item->quantity);

            } else {
                $medical_record->total_money = 0;
            }

            //Xóa thống kê doanh số bác sĩ và thủ thuật
            DoctorSales::deleteAll(['item_child_id' => $id]);
            OperationSales::deleteAll(['item_child_id' => $id]);

            $medical_record->save();
            $item->delete();
            $transaction->commit();
            return true;
        } catch (Exception $e) {
            $transaction->rollBack();
            throw $e;
            return false;
        } catch (\Throwable $e) {
            $transaction->rollBack();
            throw $e;
            return false;
        }
    }

    //Xóa dịch vụ đã thêm khi khám bênh
    public function actionCancelItem($id)
    {
        $item = MedicalRecordItemChild::findOne($id);
        $medical_record_child = MedicalRecordChild::find()->where(['medical_record_id' => $item->medical_record_id, 'product_id' => $item->product_id])->one();
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $commission = MedicalRecordItemCommission::find()->where(['medical_record_item_child_id' => $id])->one();
            if ($commission) {
                $commission->delete();
            }

            //Giảm số lần sử dụng
            $medical_record_child->quantity_use -= $item->quantity;
            $medical_record_child->save();

            //Xóa thống kê doanh số bác sĩ và thủ thuật
            DoctorSales::deleteAll(['item_child_id' => $id]);
            OperationSales::deleteAll(['item_child_id' => $id]);

            $item->delete();

            $transaction->commit();
            return true;
        } catch (Exception $e) {
            $transaction->rollBack();
            throw $e;
            return false;
        } catch (\Throwable $e) {
            $transaction->rollBack();
            throw $e;
            return false;
        }
    }

    /**
     * Thêm dữ liệu khác về hồ sơ bệnh án
     */
    public function actionInformation($id)
    {
        $model = MedicalRecordInformation::findOne($id);
        $images = MedicalRecordBeforeImage::getImagesById($id);
        $medical_record_history = MedicalRecordHistory::find()->where(['medical_record_id' => $id, 'status_delete' => 0])->joinWith(['doctor', 'branch', 'product'])->all();
        if (!$model) {
            $model = new MedicalRecordInformation();
        }
        if ($model->time_end) {
            $model->time_end = date('Y-m-d\TH:i', $model->time_end);
        }
        if ($model->load(Yii::$app->request->post())) {
            $model->time_end = strtotime($model->time_end);
            $model->medical_record_id = $id;

            $newimage = Yii::$app->request->post('newimage');
            $countimage = $newimage ? count($newimage) : 0;

            if ($model->save()) {
                if ($newimage && $countimage > 0) {
                    foreach ($newimage as $image_code) {
                        $imgtem = \common\models\media\ImagesTemp::findOne($image_code);
                        if ($imgtem) {
                            $nimg = new MedicalRecordBeforeImage();
                            $nimg->attributes = $imgtem->attributes;
                            $nimg->medical_record_id = $id;
                            $nimg->id = NULL;
                            unset($nimg->id);
                            if ($nimg->save()) {
                                $imgtem->delete();
                            }
                        }
                    }
                }

                return $this->redirect('index');
            }
        }
        return $this->render('information', [
            'model' => $model,
            'id' => $id,
            'images' => $images,
            'medical_record_history' => $medical_record_history,
        ]);
    }

    public function actionDeleteImage($id)
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $id = Yii::$app->request->get('id');
            $image = MedicalRecordBeforeImage::findOne($id);
            if ($image->delete()) {
                return ['code' => 200];
            }
        }
    }

    //Load liệu trình điều trị
    public function actionLoadPlan($id)
    {
        $searchModel = new MedicalRecordChildSearch();
        $dataProvider = $searchModel->searchAll(Yii::$app->request->queryParams, $id);
        return $this->renderPartial('layouts/plan', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    //Load danh sách hoa hồng
    public function actionLoadCommission($item_id, $item_child_id, $product_id)
    {
        $medical_record_item_commission = MedicalRecordItemCommissionV2::find()->where(['medical_record_item_id' => $item_id, 'medical_record_item_child_id' => $item_child_id])->one();
        $user_admin = UserAdmin::getUserIntroduce();
        return $this->renderPartial('layouts/commission', [
            'medical_record_item_commission' => $medical_record_item_commission,
            'user_admin' => $user_admin,
            'product_id' => $product_id,
            'item_id' => $item_id,
            'item_child_id' => $item_child_id,
        ]);
    }

    //Chỉnh sửa % hoa hồng khám bệnh
    public function actionUpdateCommission()
    {
        $request = $_POST;

        $medical_record_item_child_id = isset($request['item_child_id']) && $request['item_child_id'] ? $request['item_child_id'] : '';
        $item_id = isset($request['item_id']) && $request['item_id'] ? $request['item_id'] : '';


        $team_id = isset($request['commission_team_id']) && $request['commission_team_id'] ? $request['commission_team_id'] : []; // danh sách id của những người hưởng hoa hồng
        $team_commission = isset($request['commission_team_value']) && $request['commission_team_value'] ? $request['commission_team_value'] : []; // danh sách giá trị hưởng của những người hưởng hoa hồng
        $team_type = isset($request['commission_team_type']) && $request['commission_team_type'] ? $request['commission_team_type'] : []; // danh sách loại hưởng của những người hưởng hoa hồng

        $medical_record_item = MedicalRecordItem::findOne($item_id);
        $medical_record_item_child = MedicalRecordItemChild::findOne($medical_record_item_child_id);
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($medical_record_item && $team_id) {
                foreach ($team_id as $team_key => $team) {
                    $product = Product::findOne($team_key);
                    $medical_record_item_commission = MedicalRecordItemCommission::find()->where(['medical_record_item_id' => $item_id, 'medical_record_item_child_id' => $medical_record_item_child_id])->one();

                    if (!$medical_record_item_commission) {
                        $medical_record_item_commission = new MedicalRecordItemCommission();
                    }
                    $medical_record_item_commission->payment_status = 0;
                    $medical_record_item_commission->status = 0;
                    $medical_record_item_commission->medical_record_id = $medical_record_item->medical_record_id;
                    $medical_record_item_commission->medical_record_item_id = $medical_record_item->id;
                    $medical_record_item_commission->medical_record_item_child_id = $medical_record_item_child_id;
                    $medical_record_item_commission->product_id = $team_key;
                    $medical_record_item_commission->price = ($product->price - $product->price_loaimau) * $medical_record_item_child->quantity;
                    $medical_record_item_commission->price_payment = 0;
                    $medical_record_item_commission->user_id = implode(',', $team);
                    $medical_record_item_commission->value = implode(',', $team_commission[$team_key]);
                    $medical_record_item_commission->type = implode(',', $team_type[$team_key]);

                    if (!$medical_record_item_commission->save()) throw new Exception('Lưu cấu hình hoa hồng lỗi');
                }

                $item_commission_all = MedicalRecordItemCommission::find()->where(['medical_record_id' => $medical_record_item->medical_record_id])->all();
                if ($item_commission_all) {
                    foreach ($item_commission_all as $all) {
                        $all->price_payment = 0;
                        $all->status = 0;
                        $all->payment_status = 0;
                        $all->save();
                    }
                }


                //Tính lại hoa hồng
                $payment_history = PaymentHistory::find()->where(['medical_record_id' => $medical_record_item->medical_record_id])->orderBy('created_at ASC')->all();
                if ($payment_history) {
                    Commission::deleteAll(['medical_record_id' => $medical_record_item->medical_record_id, 'type' => Commission::TYPE_PAYMENT]);
                    DoctorSales::deleteAll(['medical_record_id' => $medical_record_item->medical_record_id]);
                    foreach ($payment_history as $payment) {
                        $item_commission_all = MedicalRecordItemCommission::find()->where(['medical_record_id' => $medical_record_item->medical_record_id, 'status' => 0])->orderBy('created_at ASC')->all();
                        $money = $payment->money;
                        if ($item_commission_all) {
                            $money_payment = $money; // Chính là số tiền còn lại sau mỗi lần thanh toán qua từng thủ thuật
                            foreach ($item_commission_all as $item_commission) {
                                $user_ids = explode(',', $item_commission->user_id);
                                $value_commission = explode(',', $item_commission->value);
                                $type_commission = explode(',', $item_commission->type);
                                $money_before = $item_commission->price - $item_commission->price_payment; // Số tiền còn lại cần thanh toán
                                if ($money_before > $money_payment) { // Số tiền thanh toán vẫn nhỏ hơn số tiền chưa thanh toán còn lại của thủ thuật
                                    foreach ($user_ids as $key_us => $us) {
                                        $money_doctor = 0;
                                        $commission = new Commission();
                                        $commission->admin_id = $us;
                                        $commission->value = $value_commission[$key_us];

                                        if ($type_commission[$key_us] == MedicalRecordItemCommission::TYPE_1) {
                                            $commission->money = ($value_commission[$key_us] / 100) * $money_payment;
                                            $money_doctor = DoctorSales::getValueSale($value_commission[$key_us], $money_payment);
                                        } else {
                                            if ((int)($item_commission->price * 30 / 100) <= $money_payment + $item_commission->price_payment && $item_commission->price_payment < (int)($item_commission->price * 30 / 100)) {
                                                $commission->money = $value_commission[$key_us];
                                                $money_doctor = $value_commission[$key_us] / 4;
                                            } else {
                                                continue;
                                            }
                                        }

                                        $commission->total_money = $money;
                                        $commission->total_money_received = $money_payment;
                                        $commission->user_id = $medical_record_item->user_id;
                                        $commission->medical_record_id = $medical_record_item->medical_record_id;
                                        $commission->branch_id = $medical_record_item->branch_id;
                                        $commission->type = Commission::TYPE_PAYMENT;
                                        $commission->type_money = $type_commission[$key_us];
                                        $commission->item_commission_id = $item_commission->id;
                                        $commission->created_at = $payment->created_at;
                                        if (!$commission->save()) throw new Exception('Lưu hoa hồng lỗi');

                                        $saveDoctor = self::saveDoctorSales([
                                            'doctor_id' => $us,
                                            'money' => $money_doctor,
                                            'product_id' => $item_commission->product_id,
                                            'medical_record_id' => $medical_record_item->medical_record_id,
                                            'time_create' => $payment->created_at,
                                            'payment_id' => $payment->id,
                                            'branch_id' => $payment->branch_id,
                                            'week' => date('W', $payment->created_at),
                                            'month' => date('m', $payment->created_at),
                                            'year' => date('Y', $payment->created_at),
                                        ]);
                                        if (!$saveDoctor) throw new Exception('Lưu doanh số bác sỹ lỗi');
                                    }
                                    $item_commission->price_payment += $money_payment;
                                    $item_commission->payment_status = 1;
                                    if (!$item_commission->save()) throw new Exception('Lưu cấu hình hoa hồng lỗi');
                                    break;
                                } elseif ($money_before == $money_payment) { // Số tiền thanh toán bằng số tiền chưa thanh toán còn lại của thủ thuật
                                    foreach ($user_ids as $key_us => $us) {
                                        $product_up = Product::findOne($item_commission->product_id);
                                        $item_child_up = MedicalRecordItemChild::findOne($item_commission->medical_record_item_child_id);
                                        $sale_doc = $money_before + ($product_up->price * $item_child_up->quantity) - $item_commission->price;
                                        $commission = new Commission();
                                        $commission->admin_id = $us;
                                        $commission->value = $value_commission[$key_us];
                                        if ($type_commission[$key_us] == MedicalRecordItemCommission::TYPE_1) {
                                            $commission->money = ($value_commission[$key_us] / 100) * $money_payment;
                                            $money_doctor = DoctorSales::getValueSale($value_commission[$key_us], $sale_doc);
                                        } else {
                                            if ((int)($item_commission->price * 30 / 100) <= $money_payment + $item_commission->price_payment && $item_commission->price_payment < (int)($item_commission->price * 30 / 100)) {
                                                $commission->money = $value_commission[$key_us];
                                                $money_doctor = $value_commission[$key_us] / 4;
                                            } else {
                                                continue;
                                            }
                                        }
                                        $commission->total_money = $money;
                                        $commission->total_money_received = $money_payment;
                                        $commission->user_id = $medical_record_item->user_id;
                                        $commission->medical_record_id = $medical_record_item->medical_record_id;
                                        $commission->branch_id = $medical_record_item->branch_id;
                                        $commission->type = Commission::TYPE_PAYMENT;
                                        $commission->type_money = $type_commission[$key_us];
                                        $commission->item_commission_id = $item_commission->id;
                                        $commission->created_at = $payment->created_at;
                                        if (!$commission->save()) throw new Exception('Lưu hoa hồng lỗi');

                                        $saveDoctor = self::saveDoctorSales([
                                            'doctor_id' => $us,
                                            'money' => $money_doctor,
                                            'product_id' => $item_commission->product_id,
                                            'medical_record_id' => $medical_record_item->medical_record_id,
                                            'time_create' => $payment->created_at,
                                            'payment_id' => $payment->id,
                                            'branch_id' => $payment->branch_id,
                                            'week' => date('W', $payment->created_at),
                                            'month' => date('m', $payment->created_at),
                                            'year' => date('Y', $payment->created_at),
                                        ]);
                                        if (!$saveDoctor) throw new Exception('Lưu doanh số bác sỹ lỗi');
                                    }
                                    $item_commission->status = 1;
                                    $item_commission->payment_status = 0;
                                    $item_commission->price_payment += $money_payment;
                                    if (!$item_commission->save()) throw new Exception('Lưu cấu hình hoa hồng lỗi');
                                    break;
                                } else { // Số tiền thanh toán lớn hơn số tiền chưa thanh toán còn lại của thủ thuật
                                    foreach ($user_ids as $key_us => $us) {
                                        $money_doctor = 0;
                                        $product_up = Product::findOne($item_commission->product_id);
                                        $item_child_up = MedicalRecordItemChild::findOne($item_commission->medical_record_item_child_id);
                                        $sale_doc = $money_before + ($product_up->price * $item_child_up->quantity) - $item_commission->price;
                                        $commission = new Commission();
                                        $commission->admin_id = $us;
                                        $commission->value = $value_commission[$key_us];
                                        if ($type_commission[$key_us] == MedicalRecordItemCommission::TYPE_1) {
                                            $commission->money = ($value_commission[$key_us] / 100) * $money_before;
                                            $money_doctor = DoctorSales::getValueSale($value_commission[$key_us], $sale_doc);
                                        } else {
                                            if ((int)($item_commission->price * 30 / 100) <= $money_payment + $item_commission->price_payment && $item_commission->price_payment < (int)($item_commission->price * 30 / 100)) {
                                                $commission->money = $value_commission[$key_us];
                                                $money_doctor = $value_commission[$key_us] / 4;
                                            } else {
                                                continue;
                                            }
                                        }

                                        $commission->total_money = $money;
                                        $commission->total_money_received = $money_before;
                                        $commission->user_id = $medical_record_item->user_id;
                                        $commission->medical_record_id = $medical_record_item->medical_record_id;
                                        $commission->branch_id = $medical_record_item->branch_id;
                                        $commission->type = Commission::TYPE_PAYMENT;
                                        $commission->type_money = $type_commission[$key_us];
                                        $commission->item_commission_id = $item_commission->id;
                                        $commission->created_at = $payment->created_at;
                                        if (!$commission->save()) throw new Exception('Lưu hoa hồng lỗi');

                                        $saveDoctor = self::saveDoctorSales([
                                            'doctor_id' => $us,
                                            'money' => $money_doctor,
                                            'product_id' => $item_commission->product_id,
                                            'medical_record_id' => $medical_record_item->medical_record_id,
                                            'time_create' => $payment->created_at,
                                            'payment_id' => $payment->id,
                                            'branch_id' => $payment->branch_id,
                                            'week' => date('W', $payment->created_at),
                                            'month' => date('m', $payment->created_at),
                                            'year' => date('Y', $payment->created_at),
                                        ]);
                                        if (!$saveDoctor) throw new Exception('Lưu doanh số bác sỹ lỗi');
                                    }
                                    $item_commission->status = 1;
                                    $item_commission->payment_status = 0;
                                    $item_commission->price_payment = $item_commission->price;
                                    if (!$item_commission->save()) throw new Exception('Lưu cấu hình hoa hồng lỗi');
                                    $money_payment -= $money_before;
                                }
                            }
                        }
                    }
                }
            }
            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();
            return false;
            throw $e;
        } catch
        (\Throwable $e) {
            $transaction->rollBack();
            return false;
            throw $e;
        }


        return true;
    }

    //Load log
    public function actionGetLog($id)
    {
        $log = MedicalRecordLog::find()->where(['medical_record_id' => $id])->joinWith(['userAdmin', 'branch'])->orderBy('created_at DESC')->all();
        return $this->renderPartial('layouts/log/log_list', ['logs' => $log]);
    }

    public function actionLog()
    {
        return true;
    }

    //Load giao diện chọn các thông số thủ thuật, đội ngũ thực hiện thu thuật v..v khi khám bệnh
    public function actionLoadHtmlKhamBenh($stt)
    {
        $categories = ProductCategory::find()->where(['status' => 1])->asArray()->all();
        $doctor = UserAdmin::getDoctor();
        $users = \backend\models\UserAdmin::getUserIntroduce();
        return $this->renderPartial('layouts/load_html_kham_benh', [
                'stt' => $stt,
                'categories' => $categories,
                'doctor' => $doctor,
                'users' => $users,
            ]
        );
    }

    //Load giao diện thanh toan
    public function actionLoadHtmlThanhToan($id)
    {
        $branchs = Branch::getBranch();
        $user_admin = Yii::$app->user->getIdentity();
        $medical_record_item_child = MedicalRecordItemChildV2::find()->where(['medical_record_item_child_v2.medical_record_id' => $id])->joinWith(['payment', 'product'])->asArray()->all();
        return $this->renderPartial('layouts/pay', [
                'id' => $id,
                'medical_record_item_child' => $medical_record_item_child,
                'branchs' => $branchs,
                'user_admin' => $user_admin
            ]
        );
    }
}
