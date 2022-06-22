<?php

namespace backend\modules\promotion\controllers;

use Yii;
use common\models\product\Product;
use common\models\promotion\Promotions;
use common\models\promotion\PromotionsSearch;
use common\models\promotion\ProductToPromotions;
use common\models\promotion\ProductToPromotionsSearch;
use common\components\ClaLid;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use common\components\UploadLib;
use common\components\ClaHost;
use common\components\ClaGenerate;

/**
 * ProductController implements the CRUD actions for Product model.
 */
class PromotionController extends Controller {

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new Promotions();
        date_default_timezone_set("Asia/Bangkok");
        if ($model->load(Yii::$app->request->post())) {
            $model->startdate = (int) strtotime($model->startdate);
            $model->enddate = (int) strtotime($model->enddate);
            if ($model->avatar) {
                $avatar = Yii::$app->session[$model->avatar];
                if ($avatar) {
                    $model->image_path = $avatar['baseUrl'];
                    $model->image_name = $avatar['name'];
                }
                unset(Yii::$app->session[$model->avatar]);
            }
            if ($model->save()) {
                return $this->redirect(['update', 'id' => $model->id, 'create' => 1]);
            }
        }
        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);
        date_default_timezone_set("Asia/Bangkok");
        if ($model->load(Yii::$app->request->post())) {
            $model->startdate = (int) strtotime($model->startdate);
            $model->enddate = (int) strtotime($model->enddate);
            //
            if ($model->avatar) {
                $avatar = Yii::$app->session[$model->avatar];
                if ($avatar) {
                    $model->image_path = $avatar['baseUrl'];
                    $model->image_name = $avatar['name'];
                }
                unset(Yii::$app->session[$model->avatar]);
            }
            if ($model->save()) {
                return $this->redirect(array('index'));
            }
        }
        $model->startdate = $model->startdate ? date('d/m/Y H:i', $model->startdate) : date('d/m/Y H:i', time());
        $model->enddate = $model->enddate ? date('d/m/Y H:i', $model->enddate) : date('d/m/Y H:i', time());
        $products = ProductToPromotions::getByPromotionId($id);

        return $this->render('update', [
                    'model' => $model,
                    'products' => $products,
        ]);
    }

    protected function findModel($id) {
        if (($model = Promotions::findOne($id)) !== null) {
            return $model;
        } else {
            die();
        }
    }
    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    /**
     * delete a product in group
     * @param type $id
     */
    public function actionDeleteProduct($id) {
        $productpromotion = ProductToPromotions::findOne($id);
        return $productpromotion->delete();
    }

    public function actionGetProduct($page = 1, $keyword='') {
        $this->layout = 'empty';
        $limit = 10;
        $product = Product::getProduct([
                                'page' => $page, 
                                'limit' => $limit,
                                'keyword' => $keyword,
                            ]);
        $count = Product::getProduct([
                                'keyword' => $keyword,
                                'page' => $page,
                                'count' => 1
                            ]);
        $count_page = ceil($count/$limit);
        return $this->render('partial/productadd', [
                    'product_add' => $product,
                    'count_page' => $count_page,
                    'page' => $page,
        ]);
    }

    public function actionGetProductSpace($promotion_id = '',$hour = null,$page = 1, $keyword='') {
        $this->layout = 'empty';
        $promotion_id = ($promotion_id == '') ? \common\models\promotion\Promotions::getPromotionNow()->id : $promotion_id;
        \Yii::$app->session->open();
        if($hour != null) {
            $_SESSION['hour_promotion'] = $hour;
        } else {
            $hour = isset($_SESSION['hour_promotion']) ? $_SESSION['hour_promotion'] : '';
        }
        $limit = 10;
        $product = Product::getProductPromotion([
                                'page' => $page, 
                                'limit' => $limit,
                                'keyword' => $keyword,
                                'promotion_id' => $promotion_id,
                                'hour' => $hour,
                            ]);
        $count = Product::getProductPromotion([
                                'keyword' => $keyword,
                                'page' => $page,
                                'promotion_id' => $promotion_id,
                                'hour' => $hour,
                                'count' => 1
                            ]);
        $count_page = ceil($count/$limit);
        return $this->render('partial/productaddspace', [
                    'product_add' => $product,
                    'count_page' => $count_page,
                    'page' => $page,
        ]);
    }

    public function actionAddProductSpace($id, $hour= 0,$page = 1, $keyword='') {
        // $this->layout = 'empty';
        $limit = 10;
        $model = $this->findModel($id);
        \Yii::$app->session->open();
        $hour = $_SESSION['hour_promotion'] = $model->getHourNow();
        $product = Product::getProductPromotion([
                                'page' => $page, 
                                'limit' => $limit,
                                'keyword' => $keyword,
                                'promotion_id' => $id,
                                'hour' => $hour,
                            ]);
        $count = Product::getProductPromotion([
                                'keyword' => $keyword,
                                'page' => $page,
                                'promotion_id' => $id,
                                'hour' => $hour,
                                'count' => 1
                            ]);
        $count_page = ceil($count/$limit);
        $hour = $hour ? $hour : $model->getHourNow();
        $products = ProductToPromotions::getProductByAttr([
                    'attr' => [
                        't.id' => $id,
                        'hour_space_start' => $hour,
                    ],
                    'order' => 'id desc',
                    'limit' => '100000'
                ]);
        return $this->render('addproductspace', [
                    'id' => $id,
                    'product_add' => $product,
                    'count_page' => $count_page,
                    'page' => $page,
                    'products' => $products,
                    'model' => $model,
                    'hour' => $hour
        ]);
    }

    public function actionLoadProduct($id, $hour, $day) {
        \Yii::$app->session->open();
        $_SESSION['hour_promotion'] =  $hour;
        $products = ProductToPromotions::getProductByAttr([
                    'attr' => [
                        't.id' => $id,
                        'hour_space_start' => $hour,
                    ],
                    'order' => 'id desc',
                    'limit' => '100000'
                ]);
        return $this->renderAjax('partial/loadproduct', [
            'products' => $products,
        ]);
    }

    public function actionSaveProduct($promotion_id, $val) {
        if($val) {
            $list = explode(',', $val);
            for ($i=0; $i < count($list); $i++) { 
                if($list[$i] && (!ProductToPromotions::find()->where(['product_id' => $list[$i], 'promotion_id' => $promotion_id])->one())) {
                    $promotion = new ProductToPromotions();
                    $promotion->product_id = $list[$i];
                    $promotion->promotion_id = $promotion_id;
                    $promotion->created_time = time();
                    if(!$promotion->save()) {
                        return Yii::t('app', 'error_when_save');
                    }
                }
            }
            
        }
        $this->layout = 'empty';
        $products = ProductToPromotions::getByPromotionId($promotion_id);
        return $this->render('partial/listproduct', [
                    'products' => $products,
        ]);
    }

    public function actionSaveProductSpace($promotion_id, $val,$price, $quantity, $hour, $day){
        if($val) {
            $list = explode(',', $val);
            $prices = explode(' ', $price);
            $quantitys = explode(' ', $quantity);
            if(count($list) != count($prices) || count($list) != count($quantitys)) {
                // echo "<pre>";
                // print_r($list);
                // print_r($prices);
                // print_r($quantitys);
                return 'Có lỗi sảy ra. Vui lòng thử lại';
            }
            for ($i=0; $i < count($list); $i++) { 
                $time_start =ProductToPromotions::getTimeSpaceStart($hour, $day);
                if($list[$i]) {
                    $promotion = ($promotion = ProductToPromotions::find()->where(['product_id' => $list[$i], 'promotion_id' => $promotion_id, 'time_space_start' => $time_start])->one()) ? $promotion : new ProductToPromotions();
                    $promotion->product_id = $list[$i];
                    $promotion->promotion_id = $promotion_id;
                    $promotion->created_time = $promotion->created_time ? $promotion->created_time : time();
                    $promotion->time_space_start = $time_start;
                    $promotion->hour_space_start = $hour;
                    $promotion->price = $prices[$i];
                    $promotion->quantity = $quantitys[$i];
                    if(!$promotion->save()) {
                        print_r($promotion->errors) ;
                        return 'Có lỗi sảy ra. Vui lòng thử lại';
                    }
                }
            }
            
        }
        $this->layout = 'empty';
        $products = ProductToPromotions::getByPromotionId($promotion_id);
        return 'Đã lưu';
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $searchModel = new PromotionsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Add product to group
     */
    function actionAddproduct() {
        $isAjax = Yii::app()->request->isAjaxRequest;
        $promotion_id = Yii::app()->request->getParam('pid');
        if (!$promotion_id)
            $this->jsonResponse(400);
        $model = Promotions::model()->findByPk($promotion_id);
        if (!$model)
            $this->jsonResponse(400);
        $this->breadcrumbs = array(
            Yii::t('product', 'product_group') => Yii::app()->createUrl('economy/promotion'),
            $model->name => Yii::app()->createUrl('economy/promotion/update', array('id' => $promotion_id)),
            Yii::t('product', 'product_group_addproduct') => Yii::app()->createUrl('economy/promotion/addproduct', array('gid' => $promotion_id)),
        );
        //
        $productModel = new Product('search');
        $productModel->unsetAttributes();  // clear any default values
        if (isset($_GET['Product']))
            $productModel->attributes = $_GET['Product'];
        //
        if (isset($_POST['products'])) {
            $products = $_POST['products'];
            $products = explode(',', $products);
            if (count($products)) {
                $listproducts = Promotions::getProductIdInPromotion($promotion_id);
                foreach ($products as $product_id) {
                    if (isset($listproducts[$product_id]))
                        continue;
                    $product = Product::model()->findByPk($product_id);
                    if (!$product)
                        continue;
                    Yii::app()->db->createCommand()->insert(Yii::app()->params['tables']['product_to_promotion'], array(
                        'promotion_id' => $promotion_id,
                        'product_id' => $product_id,
                        'created_time' => time(),
                    ));
                }
                //
                if ($isAjax)
                    $this->jsonResponse(200, array('redirect' => Yii::app()->createUrl('economy/promotion/update', array('id' => $promotion_id))));
                else
                    Yii::app()->createUrl('economy/promotion/update', array('id' => $promotion_id));
                //
            }
        }
        //
        if ($isAjax) {
            Yii::app()->clientScript->scriptMap = array(
                'jquery.js' => false,
                'jquery.min.js' => false,
                'jquery-ui.min.js' => false,
                'jquery-ui.js' => false,
                'jquery.yiigridview.js' => false,
            );
            $this->renderPartial('addproduct', array('model' => $model, 'productModel' => $productModel, 'isAjax' => $isAjax), false, true);
        } else {
            $this->render('addproduct', array('model' => $model, 'productModel' => $productModel, 'isAjax' => $isAjax));
        }
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Promotions the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = Promotions::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Promotions $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'promotions-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function beforeAction($action) {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }
    /**
     * upload file
     */
    public function actionUploadfile() {
        if (isset($_FILES['file'])) {
            $file = $_FILES['file'];
            if ($file['size'] > 1024 * 1000) {
                Yii::$app->end();
            }
            $up = new UploadLib($file);
            $up->setPath(array('promotions', date('Y_m_d', time())));
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


}
