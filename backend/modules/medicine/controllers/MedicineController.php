<?php

namespace backend\modules\medicine\controllers;

use common\models\medicine\Medicine;
use common\models\medicine\MedicineImage;
use common\models\medicine\search\MedicineSearch;
use Yii;
use common\models\product\Product;
use common\models\product\ProductCategory;
use common\components\ClaCategory;
use common\components\UploadLib;
use yii\web\Controller;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use common\components\ClaHost;
use common\components\ClaGenerate;


class MedicineController extends Controller
{

    // public $category_name;
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
     * Lists all Product models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MedicineSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Product model.
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
     * Creates a new Product model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Medicine();

        if ($model->load(Yii::$app->request->post())) {
            $newimage = Yii::$app->request->post('newimage');
            $countimage = $newimage ? count($newimage) : 0;
            if ($model->save()) {
                $setava = Yii::$app->request->post('setava');
                $simg_id = str_replace('new_', '', $setava);
                $avatar = [];
                $recount = 0;
                if ($newimage && $countimage > 0) {
                    foreach ($newimage as $image_code) {
                        $imgtem = \common\models\media\ImagesTemp::findOne($image_code);
                        if ($imgtem) {
                            $nimg = new MedicineImage();
                            $nimg->attributes = $imgtem->attributes;
                            $nimg->id = NULL;
                            unset($nimg->id);
                            $nimg->medicine_id = $model->id;
                            if ($nimg->save()) {
                                if ($recount == 0) {
                                    $avatar = $nimg->attributes;
                                    $recount = 1;
                                }
                                if ($imgtem->id == $simg_id) {
                                    $avatar = $nimg->attributes;
                                }
                                $imgtem->delete();
                            }
                        }
                    }
                }
                // set avatar
                if ($avatar && count($avatar)) {
                    $model->avatar_path = $avatar['path'];
                    $model->avatar_name = $avatar['name'];
                    $model->avatar_id = $avatar['id'];
                    $model->save();
                }

                return $this->redirect(['index']);
            }
        }
        $images = [];
        return $this->render('create', [
            'model' => $model,
            'images' => $images,
        ]);
    }

    /**
     * Updates an existing Product model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {
            $newimage = Yii::$app->request->post('newimage');
            $countimage = $newimage ? count($newimage) : 0;

            $orders_images = Yii::$app->request->post('order');

            if ($model->save()) {
                if (isset($orders_images) && $orders_images) {
                    foreach ($orders_images as $stt => $img) {
                        $imag = MedicineImage::findOne($img);
                        $imag->order = $stt;
                        $imag->save();
                    }
                }
                $setava = Yii::$app->request->post('setava');
                $simg_id = str_replace('new_', '', $setava);
                $setava2 = Yii::$app->request->post('setava2');
                $simg2_id = str_replace('new_', '', $setava2);
                $avatar = array();
                if ($newimage && $countimage > 0) {
                    foreach ($newimage as $image_code) {
                        $imgtem = \common\models\media\ImagesTemp::findOne($image_code);
                        if ($imgtem) {
                            $nimg = new MedicineImage();
                            $nimg->attributes = $imgtem->attributes;
                            $nimg->id = NULL;
                            unset($nimg->id);
                            $nimg->medicine_id = $model->id;
                            if ($nimg->save()) {
                                if ($imgtem->id == $simg_id) {
                                    $avatar = $nimg->attributes;
                                }
                                $imgtem->delete();
                            }
                        } else {
                            if ($image_code == $simg_id) {
                                $avatar = MedicineImage::findOne($image_code);
                            }
                        }
                    }
                }
                // set avatar
                if ($avatar && count($avatar)) {
                    $model->avatar_path = $avatar['path'];
                    $model->avatar_name = $avatar['name'];
                    $model->avatar_id = $avatar['id'];
                    $model->save();
                } else {
                    if ($simg_id != $model->avatar_id) {
                        $imgavatar = MedicineImage::findOne($simg_id);
                        if ($imgavatar) {
                            $model->avatar_path = $imgavatar->path;
                            $model->avatar_name = $imgavatar->name;
                            $model->avatar_id = $imgavatar->id;
                        }
                        $model->save();
                    }
                }
                return $this->redirect(['index']);
            }
        }
        $images = Medicine::getImages($id);
        return $this->render('update', [
            'model' => $model,
            'images' => $images,
        ]);
    }

    /**
     * Deletes an existing Product model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->status = 0;
        $model->save();

        return $this->redirect(['index']);
    }

    public function actionDeleteImage($id)
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $id = Yii::$app->request->get('id');
            $image = MedicineImage::findOne($id);
            if ($image->delete()) {
                return ['code' => 200];
            }
        }
    }

    /**
     * Finds the Product model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Medicine::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    public function actionUploadfilec()
    {
        if (isset($_FILES['file'])) {
            $file = $_FILES['file'];
            if ($file['size'] > 1024 * 1024 * 8) {
                Yii::$app->end();
            }
            $up = new UploadLib($file);
            $up->setPath(array('certificate', date('Y_m_d', time())));
            $up->uploadImage();
            $return = array();
            $response = $up->getResponse(true);
            $return = array('status' => $up->getStatus(), 'data' => $response, 'host' => ClaHost::getImageHost(), 'size' => '');
            if ($up->getStatus() == '200') {
                $keycode = ClaGenerate::getUniqueCode();
                $return['data']['realurl'] = ClaHost::getImageHost() . $response['baseUrl'] . 's100_100/' . $response['name'];
                $return['data']['avatar'] = $keycode;
                Yii::$app->session[$keycode] = $response;
            }
            echo json_encode($return);
            Yii::$app->end();
        }
        //
    }

    // NEWS
    public function actionUpdatermtnew($id)
    {
        $model = Product::findOne($id);
        $model->isnew = 0;
        if ($model->save()) {
            return \yii\helpers\Json::encode(array(
                'code' => 200,
                'html' => '<i class="fa fa-times" aria-hidden="true"></i>',
                'title' => Yii::t('app', 'click_to_on'),
                'link' => Url::to(['/product/product/updateaddnew', 'id' => $id])
            ));
        } else {
            return \yii\helpers\Json::encode(array('code' => 400));
        }
    }

    public function actionUpdateaddnew($id)
    {
        $model = Product::findOne($id);
        $model->isnew = 1;
        if ($model->save()) {
            return \yii\helpers\Json::encode(array(
                'code' => 200,
                'html' => '<i class="fa fa-check" aria-hidden="true"></i>',
                'title' => Yii::t('app', 'click_to_off'),
                'link' => Url::to(['/product/product/updatermtnew', 'id' => $id])
            ));
        } else {
            return \yii\helpers\Json::encode(array('code' => 400));
        }
    }

    public function actionExelOld()
    {
        $data = Product::find()->select('product.*, c.name as category_name, sh.name as shop_name, pv.name as province_name')->leftJoin("product_category as c", "product.category_id = c.id")->leftJoin("shop as sh", "product.shop_id = sh.id")->leftJoin("province as pv", "product.province_id = pv.id")->orderBy('name ASC')->asArray()->all();

        $filename = "thongkesanpham.xls"; // File Name

        // Download file
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Content-Type: application/vnd.ms-excel;charset=UTF-8");

        // Write data to file
        $flag = false;
        $row = [];
        $table = '';
        foreach ($data as $value) {

            if (!$flag) {
                // display field/column names as first row
                $table .= '<tr>';
                $table .= '<td>ID</td>';
                $table .= '<td>Tên</td>';
                $table .= '<td>ID danh mục</td>';
                $table .= '<td>Danh mục</td>';
                $table .= '<td>Giá</td>';
                $table .= '<td>Nổi bật</td>';
                $table .= '<td>ID shop</td>';
                $table .= '<td>Tên Shop</td>';
                $table .= '<td>ID Tỉnh thành</td>';
                $table .= '<td>Tỉnh thành</td>';
                $table .= '</tr>';
                $flag = true;
                $row['price'] = $value['price'];
            }
            $table .= '<tr>';
            $table .= '<td>' . $value['id'] . '</td>';
            $table .= '<td>' . $value['name'] . '</td>';
            $table .= '<td>' . $value['category_id'] . '</td>';
            $table .= '<td>' . $value['category_name'] . '</td>';
            $table .= '<td>' . $value['price'] . '</td>';
            $table .= '<td>' . $value['ishot'] . '</td>';
            $table .= '<td>' . $value['shop_id'] . '</td>';
            $table .= '<td>' . $value['shop_name'] . '</td>';
            $table .= '<td>' . $value['province_id'] . '</td>';
            $table .= '<td>' . $value['province_name'] . '</td>';
            $table .= '</tr>';
        }
        // echo $this->renderAjax('exel',['body' => $table]);
        echo '<table>';
        echo $table;
        echo '</table>';
    }

    public function actionExel()
    {
        $data = Product::find()->select('product.*, sh.name as shop_name, sh.phone as shop_phone, sh.email as shop_email, sh.address as shop_address, sh.description as shop_description,  u.username, pv.name as province_name')->leftJoin("shop as sh", "product.shop_id = sh.id")->leftJoin("user as u", "product.shop_id = u.id")->leftJoin("province as pv", "product.province_id = pv.id")->orderBy('name ASC')->asArray()->all();
        $categorys = [];
        $tg = ProductCategory::find()->all();
        foreach ($tg as $category) {
            $categorys[$category->id] = $category;
        }
        $filename = "thongkesanpham.xls"; // File Name
        // Download file
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Content-Type: application/vnd.ms-excel;charset=UTF-8");

        // Write data to file
        $flag = false;
        $row = [];
        $table = '';
        $i = 1;
        foreach ($data as $value) {
            if (!$flag) {
                // display field/column names as first row
                $table .= '<tr>';
                $table .= '<td>STT</td>';
                $table .= '<td>Danh mục cha</td>';
                $table .= '<td>ID gian hàng</td>';
                $table .= '<td>Tên sản phẩm</td>';
                $table .= '<td>ID danh mục</td>';
                $table .= '<td>Danh mục sản phẩm</td>';
                $table .= '<td>Tên gian hàng</td>';
                $table .= '<td>Tên chủ gian hàng</td>';
                $table .= '<td>Số điện thoại</td>';
                $table .= '<td>Email</td>';
                $table .= '<td>Địa chỉ</td>';
                $table .= '<td>Chi chú</td>';
                $table .= '</tr>';
                $flag = true;
                $row['price'] = $value['price'];
            }
            $category = isset($categorys[$value['category_id']]) ? $categorys[$value['category_id']] : [];
            $category_parent = $category ? (isset($categorys[$category->parent]) ? $categorys[$category->parent] : []) : [];
            $table .= '<tr>';
            $table .= '<td>' . $i++ . '</td>';
            $table .= '<td>' . ($category_parent ? $category_parent['name'] : '') . '</td>';
            $table .= '<td>' . $value['shop_id'] . '</td>';
            $table .= '<td>' . $value['name'] . '</td>';
            $table .= '<td>' . $value['category_id'] . '</td>';
            $table .= '<td>' . ($category ? $category['name'] : '') . '</td>';
            $table .= '<td>' . $value['shop_name'] . '</td>';
            $table .= '<td>' . $value['username'] . '</td>';
            $table .= '<td>' . $value['shop_phone'] . '</td>';
            $table .= '<td>' . $value['shop_email'] . '</td>';
            $table .= '<td>' . $value['shop_address'] . '</td>';
            $table .= '<td></td>';
            $table .= '</tr>';
        }
        // echo $this->renderAjax('exel',['body' => $table]);
        echo '<table>';
        echo $table;
        echo '</table>';
    }
}
