<?php

namespace backend\modules\order\controllers;

use Yii;
use common\models\order\Order;
use common\models\order\OrderItem;
use common\models\order\OrderShop;
use common\models\order\search\OrderSearch;
use common\models\product\Product;
use common\models\product\ProductImport;
use common\models\Province;
use common\models\District;
use common\models\order\OrderLog;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\components\ClaLid;
use yii\web\Response;
use common\components\shipping\ClaShipping;

/**
 * OrderController implements the CRUD actions for Order model.
 */
class OrderController extends Controller
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

    /**
     * Lists all Order models.
     * @return mixed
     */
    public function actionIndex()
    {
        $_GET['OrderSearch']['type_payment'] = 0;
        \common\models\NotificationAdmin::removeNotifaction('order');
        $searchModel = new OrderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize = isset($_GET['limit']) ? $_GET['limit'] : 50;
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Đơn hàng chờ giao COD
     */
    public function actionIndexDelivering()
    {
        $searchModel = new OrderSearch();
        // trạng thái đơn hàng đang giao
        $searchModel->status = Order::ORDER_DELIVERING;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize = 50;
        return $this->render('index_delivering', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * COD đang giao
     * @return type
     */
    public function actionIndexCodDelivering()
    {
        $searchModel = new OrderSearch();
        // trạng thái đơn hàng đang giao
        $dataProvider = $searchModel->searchCodDelivering(Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize = 50;
        return $this->render('index_cod_delivering', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Đơn hàng đang giao của người đang đăng nhập
     */
    public function actionIndexYourself()
    {
        $searchModel = new OrderSearch();
        // trạng thái đơn hàng đang giao
        // $searchModel->status = Order::ORDER_COD_DELIVERING;
        // $user_id = Yii::$app->user->getId();
        // $searchModel->user_delivery = $user_id;
        $dataProvider = $searchModel->searchYourSelf(Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize = 50;
        return $this->render('index_yourself', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Order model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Order model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Order();
        $modelitem = new OrderItem();

        if ($model->load(Yii::$app->request->post()) && $modelitem->load(Yii::$app->request->post())) {
            $products = [];
            $order_total = 0;
            if (isset($modelitem['product_id']) && $modelitem['product_id']) {
                $codes = explode(',', $modelitem['product_id']);
                $i = 0;
                foreach ($codes as $code) {
                    $code_trim = trim($code, ' ');
                    $products[] = Product::getProductsByCode($code_trim);
                }
                if (isset($products) && $products) {
                    foreach ($products as $product) {
                        $order_total += $product['price'];
                    }
                }
            }
            $model->order_total = $order_total;
            if ($model->save()) {
                if (isset($products) && $products) {
                    foreach ($products as $product) {
                        $item = new OrderItem();
                        $item->order_id = $model->id;
                        $item->product_id = $product['id'];
                        $item->quantity = 1;
                        $item->price = $product['price'];
                        $item->save();
                    }
                }
                return $this->redirect(['index']);
            }
        }
        // Tỉnh/thành phố
        $provinces = Province::optionsProvince();
        // Quận/huyện
        $districts = District::dataFromProvinceId($model->province_id);
        //
        return $this->render('create', [
            'model' => $model,
            'modelitem' => $modelitem,
            'provinces' => $provinces,
            'districts' => $districts
        ]);
    }

    /**
     * Sản phẩm chờ nhập hàng
     */
    public function actionWaitingImport()
    {
        // Lấy ra các đơn hàng chờ xử lý
        $orders_waiting_process = Order::getOrdersWaitingProcess();
        // Lấy ra các sản phẩm trong các đơn hàng chờ xử lý
        $data = Order::getOrderItems($orders_waiting_process);
        $items = [];
        if (isset($data) && $data) {
            foreach ($data as $item) {
                $code_detail = ClaLid::generateCodeDetail($item);
                if (isset($items[$code_detail])) {
                    $items[$code_detail]['quantity'] += $item['quantity'];
                } else {
                    $items[$code_detail] = $item;
                }
            }
        }
        // Lấy ra số lượng các sản phẩm trong kho đang có
        $currents = [];
        $data_currents = ProductImport::getCurrent();
        if (isset($data_currents) && $data_currents) {
            foreach ($data_currents as $c) {
                $c_detail = ClaLid::generateCodeDetail($c);
                if (isset($currents[$c_detail])) {
                    $currents[$c_detail]['quantity'] += $c['quantity'];
                } else {
                    $currents[$c_detail] = $c;
                }
            }
        }
        //
        return $this->render('product_waiting_import', [
            'orders' => $orders_waiting_process,
            'items' => $items,
            'currents' => $currents
        ]);
    }

    /**
     * Updates an existing Order model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    // public function actionUpdate($id) {
    //     $model = $this->findModel($id);

    //     $user_log_money =1;
    //     // $user_log_money = UserMoneyLog::find()->where('order_id=:order_id', [':order_id' => $id])->one();
    //     // trạng thái các field trước khi thay đổi
    //     $model_old = $model->attributes;
    //     //
    //     $log_order = [];
    //     if ($model->load(Yii::$app->request->post())) {
    //         if ($model->save()) {

    //             // Nếu trạng thái chờ đặt cọc
    //             if ($model->confirm_customer_transfer == ClaLid::STATUS_WAITING || $model->confirm_customer_transfer == ClaLid::STATUS_ACTIVED) {
    //                 // Nếu trạng thái 
    //                 if ($model->status == Order::ORDER_WAITING_PROCESS) {
    //                     $model->status = Order::ORDER_PROCESSING;
    //                 }
    //             }
    //             // Nếu cập nhật trừ tiền cho khách đã đặt cọc
    //             if (!$user_log_money) {
    //                 if ($model->money_customer_transfer) {
    //                     $user_money = UserMoney::find()->where('phone=:phone', [':phone' => $model->phone])->one();
    //                     if ($user_money) {
    //                         $money_before = $user_money->money;
    //                         $money = $model->money_customer_transfer;
    //                         $money_after = abs($money_before - $money);
    //                         $user_money->money = $money_after;
    //                         $user_money->money_hash = ClaGenerate::encrypt($user_money->money);
    //                         if ($user_money->save()) {
    //                             $log = new UserMoneyLog();
    //                             $log->phone = $model->phone;
    //                             $log->money_before = $money_before;
    //                             $log->money = $money;
    //                             $log->money_after = $money_after;
    //                             $log->note = 'Trừ tiền cho khách trong đơn hàng';
    //                             $log->order_id = $id;
    //                             $log->type = UserMoneyLog::TYPE_DEDUCT;
    //                             $log->save();
    //                         }
    //                     }
    //                 }
    //             }

    //             // Đã đặt cọc và đang xử lý
    //             if ($model->confirm_customer_transfer == ClaLid::STATUS_ACTIVED && $model->status == Order::ORDER_PROCESSING) {
    //                 // Cập nhật vào bảng chờ đặt hàng
    //                 $items = Order::getOrderItemsByOrderId($model->id);
    //                 if ($items) {
    //                     foreach ($items as $item) {
    //                         // nếu trạng thái là chưa đặt thì mới thêm vào
    //                         if ($item['status'] == ClaLid::STATUS_DEACTIVED) {
    //                             $waiting_order = ProductWaitingImportOrder::checkExist($item);
    //                             if (!$waiting_order) {
    //                                 $waiting_order = new ProductWaitingImportOrder();
    //                                 $waiting_order->product_id = $item['product_id'];
    //                                 $product = Product::findOne($item['product_id']);
    //                                 $waiting_order->brand = $product['brand'];
    //                                 $waiting_order->price = $product['price_crawler'];
    //                                 $waiting_order->code = $item['code'];
    //                                 $waiting_order->color = $item['color'];
    //                                 $waiting_order->size = $item['size'];
    //                                 $waiting_order->quantity = $item['quantity'];
    //                                 $waiting_order->order_id = $model->id;
    //                                 $waiting_order->order_item_id = $item['id'];
    //                                 $waiting_order->save();
    //                             }
    //                         }
    //                     }
    //                 }
    //             }
    //             // nếu trạng thái đơn hàng là hủy đơn hàng
    //             // xóa sản phẩm chờ đặt hàng và chờ nhập kho
    //             if ($model->status == ClaLid::STATUS_DEACTIVED) {
    //                 $sql_order = 'DELETE FROM product_waiting_import_order WHERE order_id = ' . $model->id;
    //                 Yii::$app->db->createCommand($sql_order)->execute();
    //                 $sql_stock = 'DELETE FROM product_waiting_import_stock WHERE order_id = ' . $model->id;
    //                 Yii::$app->db->createCommand($sql_stock)->execute();
    //             }
    //             // trạng thái các field sau khi thay đổi
    //             $model_new = $model->attributes;
    //             // Log những thay đổi của đơn hàng khi cập nhật
    //             unset($model_old['updated_at']);
    //             foreach ($model_old as $key => $value) {
    //                 if ($model_new[$key] != $value) {
    //                     $log_order[$key]['old'] = (isset($value) && $value) ? $value : ' ';
    //                     $log_order[$key]['new'] = (isset($model_new[$key]) && $model_new[$key]) ? $model_new[$key] : ' ';
    //                 }
    //             }
    //             if (count($log_order)) {
    //                 $model_log = new OrderLog();
    //                 $model_log->user_id = Yii::$app->user->getId();
    //                 $model_log->order_id = $model->id;
    //                 $model_log->created_at = time();
    //                 $model_log->content = json_encode($log_order);
    //                 $model_log->save();
    //             }
    //             //
    //             $model->save();
    //             return $this->refresh();
    //         }
    //     }
    //     //
    //     $products = Order::getProductsInOrder($id);
    //     //
    //     // Lấy ra số lượng các sản phẩm trong kho đang có
    //     $currents = [];

    //     // Số tiền hiện tại của khách theo số điện thoại
    //     // $money = UserMoney::getCurrentMoney($model->phone);
    //     //
    //     // $logs = UserMoneyLog::getAllLogsByPhone($model->phone);
    //     //
    //     return $this->render('update', [
    //                 'model' => $model,
    //                 'products' => $products,
    //                 'currents' => $currents,
    //                 // 'money' => $money,
    //                 // 'user_log_money' => $user_log_money,
    //                 // 'logs' => $logs
    //     ]);
    // }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model_old = $model->attributes;
        //
        $log_order = [];
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                // lưu lịch sử.
                $status_old = $model_old['status'];
                if ($model->status != $status_old) {
                    if ($model->status == 0) {
                        if ($this->cancerOrderShop($model, $status_old)) {
                            Yii::$app->session->setFlash('success', Yii::t('app', 'update_status_success'));
                        } else {
                            Yii::$app->session->setFlash('error', Yii::t('app', 'cant_update_status'));
                        }
                    } else {
                        if ($this->updateOrderShop($model, $status_old)) {
                            Yii::$app->session->setFlash('success', Yii::t('app', 'update_status_success'));
                        } else {
                            Yii::$app->session->setFlash('error', Yii::t('app', 'cant_update_status'));
                        }
                    }
                }
                // trạng thái các field sau khi thay đổi
                $model_new = $model->attributes;
                // Log những thay đổi của đơn hàng khi cập nhật
                unset($model_old['updated_at']);
                foreach ($model_old as $key => $value) {
                    if ($model_new[$key] != $value) {
                        $log_order[$key]['old'] = (isset($value) && $value) ? $value : ' ';
                        $log_order[$key]['new'] = (isset($model_new[$key]) && $model_new[$key]) ? $model_new[$key] : ' ';
                    }
                }
                if (count($log_order)) {
                    $model_log = new OrderLog();
                    $model_log->user_id = Yii::$app->user->getId();
                    $model_log->order_id = $model->id;
                    $model_log->created_at = time();
                    $model_log->content = json_encode($log_order);
                    $model_log->save();
                }
                // $model->save();
                return $this->refresh();
            }
        }
        //
        $products = Order::getProductsInOrder($id);
        //
        // Lấy ra số lượng các sản phẩm trong kho đang có
        $currents = [];

        return $this->render('update', [
            'model' => $model,
            'products' => $products,
            'currents' => $currents,
        ]);
    }

    public function cancerOrderShop($order, $status_old)
    {
        $id = $order->id;
        if ($order->transport_id) {
            $claShipping = new ClaShipping();
            $claShipping->loadVendor(['method' => $order->transport_type]);
            switch ($order->transport_type) {
                case ClaShipping::METHOD_GHN:
                    $options['data'] = array(
                        'OrderCode' => $order->transport_id
                    );
                    break;
                default:
                    $options['data'] = array(
                        'OrderCode' => $order->transport_id
                    );
                    break;
            }
            $data = $claShipping->cancerOrder($options);
            if (isset($data['success']) && $data['success']) {
                if (\common\models\gcacoin\CoinConfinement::cancerShopOrder($order)) {
                    $data = [
                        'order_id' => $id,
                        'time' => time(),
                        'status' => 0,
                        'type' => $order->transport_type,
                        'content' => Yii::t('app', 'update_to_admin') . Yii::$app->user->id,
                        'created_at' => time()
                    ];
                    $kt = \common\models\order\OrderShopHistory::saveData($data);
                    $sve = \common\models\notifications\Notifications::updateStatusOrder($order);
                    return true;
                }
            }
        } else {
            if ($status_old == 1 || $status_old  == 2 || $status_old  == 3) {
                if (\common\models\gcacoin\CoinConfinement::cancerShopOrder($order)) {
                    $data = [
                        'order_id' => $id,
                        'time' => time(),
                        'status' => 0,
                        'type' => $order->transport_type,
                        'content' => Yii::t('app', 'update_to_admin') . Yii::$app->user->id,
                        'created_at' => time()
                    ];
                    $kt = \common\models\order\OrderShopHistory::saveData($data);
                    $sve = \common\models\notifications\Notifications::updateStatusOrder($order);
                    return true;
                }
            }
        }
        $order->status = $status_old;
        $order->save(false);
        return false;
    }

    public function updateOrderShop($order, $status_old)
    {
        if (!$order->transport_id) {
            if ($status_old == 2) {
                $order->setPromotion();
            }
            $data = [
                'order_id' => $order->id,
                'time' => time(),
                'status' => $order->status,
                'type' => $order->transport_type,
                'content' => Yii::t('app', 'update_to_admin') . Yii::$app->user->id,
                'created_at' => time()
            ];
            $kt = \common\models\order\OrderShopHistory::saveData($data);
            $sve = \common\models\notifications\Notifications::updateStatusOrder($order);
            return true;
        }
        $order->status = $status_old;
        $order->save(false);
        return false;
    }

    /**
     * Deletes an existing Order model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $order = $this->findModel($id);
        if ($order->delete()) {
            Yii::$app->session->setFlash('success', 'Xóa thành công.');
            return $this->redirect(['index']);
        }
        Yii::$app->session->setFlash('error', 'Không thể xóa.');
        return $this->redirect(['index']);
    }

    /**
     * Finds the Order model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Order the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Order::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionDeleteItem()
    {
        $id = Yii::$app->request->get('id');
        $item = OrderItem::findOne($id);
        $order_id = $item['order_id'];
        if ($item->delete()) {
            $url = \yii\helpers\Url::to(['/order/order/update', 'id' => $order_id]);
            return $this->redirect($url);
        }
    }

    /**
     * Cập nhật ai giao hàng
     * @return type
     * @throws NotFoundHttpException
     */
    public function actionUpdateUserDelivery()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $user_id = Yii::$app->request->get('user_id', 0);
            $order_id = Yii::$app->request->get('order_id');
            $order = Order::findOne($order_id);
            if ($order) {
                $order->user_delivery = $user_id;
                if ($user_id) {
                    $order->status = Order::ORDER_COD_DELIVERING;
                }
                if ($order->save()) {
                    return ['code' => 200];
                } else {
                    throw new NotFoundHttpException('The requested page does not exist.');
                }
            }
        }
    }

    /**
     * Cập nhật là đã nhận tiền COD
     * @return type
     * @throws NotFoundHttpException
     */
    public function actionUpdateReceivedMoney()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $received_money = Yii::$app->request->get('received_money', 0);
            $order_id = Yii::$app->request->get('order_id');
            $order = Order::findOne($order_id);
            if ($order) {
                $order->received_money = $received_money;
                if ($received_money == ClaLid::STATUS_ACTIVED) {
                    $order->status = Order::ORDER_SUCCESS;
                }
                if ($order->save()) {
                    OrderItem::updateStatusByOrderId($order->id, OrderItem::STATUS_SUCCESS, OrderItem::STATUS_IN_STOCK);
                    return ['code' => 200];
                } else {
                    throw new NotFoundHttpException('The requested page does not exist.');
                }
            }
        }
    }

    /**
     * Cập nhật giờ khi chờ giao lại
     * @return type
     */
    public function actionUpdateDeliveryWaiting()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $order_id = Yii::$app->request->get('order_id');
            $order = Order::findOne($order_id);
            $order->save();
            return ['code' => 200];
        }
    }

    /**
     * Cập nhật giao thành công
     * @return type
     */
    public function actionUpdateDeliverySuccess()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $order_id = Yii::$app->request->get('order_id');
            $order = Order::findOne($order_id);
            $order->status = Order::ORDER_DELIVERY_SUCCESS;
            $order->save();
            return ['code' => 200];
        }
    }

    /**
     * Ghi chú đơn hàng
     */
    public function actionWriteNote()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $order_id = Yii::$app->request->get('order_id', 0);
            $note = Yii::$app->request->get('note');
            $user_id = \Yii::$app->user->getId();
            if ($order_id && $note && $user_id) {
                $model = new \common\models\order\OrderNote();
                $model->order_id = $order_id;
                $model->note = $note;
                $model->user_id = $user_id;
                $model->save();
            }
            return ['code' => 200];
        }
    }

    /**
     * Thêm sản phẩm vào đơn hàng
     */
    public function actionAddMoreProduct()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $order_id = Yii::$app->request->get('order_id', 0);
            $id = Yii::$app->request->get('id', 0);
            $color = Yii::$app->request->get('color', '');
            $size = Yii::$app->request->get('size', '');
            if ($order_id && $id && $color && $size) {
                $order = Order::findOne($order_id);
                $product = Product::findOne($id);
                $order->order_total += $product['price'];
                if ($order->save()) {
                    $item = new OrderItem();
                    $item->order_id = $order_id;
                    $item->product_id = $id;
                    $item->code = $product['code'];
                    $item->color = $color;
                    $item->size = $size;
                    $item->quantity = 1;
                    $item->price = $product['price'];
                    $item->save();
                }
            }
            return ['code' => 200];
        }
    }

    //    public function actionUpdateOrderItemSuccess() {
    //        $data = (new \yii\db\Query())->select('*')
    //                ->from('order')
    //                ->where('status=:status', [':status' => Order::ORDER_SUCCESS])
    //                ->all();
    //        //
    //        if (isset($data) && $data) {
    //            foreach ($data as $order) {
    //                $sql = 'UPDATE order_item SET status=6 WHERE order_id=' . $order['id'] . ' AND status=3';
    //                Yii::$app->db->createCommand($sql)->execute();
    //            }
    //        }
    //        //
    //        echo "<pre>";
    //        print_r('DONE');
    //        echo "</pre>";
    //        die();
    //    }

    public function actionExel()
    {
        $data = OrderItem::find()->select('order_item.*, o.*, p.name as product_name')->leftJoin("product as p", "order_item.product_id = p.id")->leftJoin("order as o", "order_item.order_id = o.id")->orderBy('order_item.order_id ASC')->asArray()->all();
        // $data = Order::find()->select('order.*')->orderBy('id ASC')->asArray()->all();
        $filename = "thongkeorder.xls"; // File Name
        // Download file
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Content-Type: application/vnd.ms-excel;charset=UTF-8");

        // Write data to file
        $flag = false;
        $row = [];
        $table = '';
        $i = 1;
        $ht = Order::arrayPaymentStatus();
        foreach ($data as $value) {
            if (!$flag) {
                // display field/column names as first row
                $table .= '<tr>';
                $table .= '<td>STT</td>';
                $table .= '<td>Đơn hàng</td>';
                $table .= '<td>Người mua</td>';
                $table .= '<td>Điện thoại</td>';
                $table .= '<td>Địa chỉ</td>';
                $table .= '<td>Sản phẩm</td>';
                $table .= '<td>Số lượng</td>';
                $table .= '<td>Tổng hóa đơn</td>';
                $table .= '<td>Phí giao hàng</td>';
                $table .= '<td>Trạng thái thanh toán</td>';
                $table .= '<td>Hình thức thanh toán</td>';
                $table .= '<td>Trạng thái</td>';
                $table .= '<td>Ngày tạo</td>';
                $table .= '</tr>';
                $flag = true;
            }
            if ($value['order_id'] && $value['phone']) {
                $table .= '<tr>';
                $table .= '<td>' . $i++ . '</td>';
                $table .= '<td>' . $value['order_id'] . '</td>';
                $table .= '<td>' . $value['name'] . '</td>';
                $table .= '<td>' . $value['phone'] . '</td>';
                $table .= '<td>' . $value['address'] . '</td>';
                $table .= '<td>' . ($value['product_name'] ? $value['product_name'] : 'Đã xóa') . '</td>';
                $table .= '<td>' . $value['quantity'] . '</td>';
                $table .= '<td>' . $value['order_total'] . '</td>';
                $table .= '<td>' . $value['shipfee'] . '</td>';
                $table .= '<td>' . (isset($ht[$value['payment_status']]) ? $ht[$value['payment_status']] : $value['payment_status']) . '</td>';
                $table .= '<td>' .  \common\components\payments\ClaPayment::getName($value['payment_method']) . '</td>';
                $table .= '<td>' . Order::getNameStatus($value['status']) . '</td>';
                $table .= '<td>' . date('H:i d/m/Y', $value['created_at']) . '</td>';
                $table .= '<td></td>';
                $table .= '</tr>';
            }
        }
        // echo $this->renderAjax('exel',['body' => $table]);
        echo '<table>';
        echo $table;
        echo '</table>';
    }
}
