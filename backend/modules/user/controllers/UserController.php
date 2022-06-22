<?php

namespace backend\modules\user\controllers;

use backend\models\UserAdmin;
use common\components\ClaApi;
use common\components\UploadLib;
use common\models\appointment\Appointment;
use common\models\banner\Banner;
use common\models\medical_record\Factory;
use common\models\medicine\Medicine;
use common\models\sale\CustomerSales;
use common\models\user\MedicalRecord;
use common\models\user\MedicalRecordChild;
use common\models\user\MedicalRecordImage;
use common\models\user\MedicalRecordItem;
use common\models\user\MedicalRecordItemChild;
use common\models\user\MedicalRecordItemMedicine;
use common\models\user\search\MedicalRecordChildSearch;
use common\models\user\UserLog;
use Yii;
use common\models\user\User;
use common\models\user\search\UserSearch;
use yii\base\Exception;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
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
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model= $this->findModel($id);
 
        $address = User::getAddress($model['province_id'],$model['district_id'],$model['ward_id']);
        //HSBA
        $medical_record= MedicalRecord::find()->where(['user_id' => $id ])->andWhere(['!=', 'status', MedicalRecord::STATUS_DELETE])->asArray()->all();
        //Đơn thuốc
        $medical_record_item_medicine = MedicalRecordItemMedicine::find()->where(['user_id' => $id])->joinWith(['medicine', 'userAdmin','user'])->orderBy('created_at DESC')->all();
        //liêu trình điều trị(Tổng hợp) //product tên thu thuật productcategory nhóm thủ thuật
        $medical_record_child = MedicalRecordChild::find()->where(['user_id' => $id])->joinWith(['product','user','productCategory'])->asArray()->all();
        //Liệu trình điều trị (Chưa khám)
        $medical_record_child_no = MedicalRecordChild::find()->where(['user_id' => $id])->andWhere('medical_record_child.quantity > medical_record_child.quantity_use')->joinWith(['product','user','productCategory'])->asArray()->all();
        //HS Chi nhánh
        $medical_record_item = MedicalRecordItem::find()->where(['user_id' => $id])->joinWith('branch')->orderBy('created_at DESC')->asArray()->all();
        //Thủ thuật chi tiết
        $medical_record_item_child = \common\models\user\MedicalRecordItemChild::find()->where(['user_id' => $id])->joinWith(['product', 'userAdmin','branch'])->orderBy('created_at DESC')->asArray()->all();
        //Đặt xưởng
        $factory = Factory::find()->where(['user_id' => $id])->joinWith(['branch', 'userAdmin', 'loaimau'])->orderBy('created_at DESC')->asArray()->all();
        $images =[];
        $i =0;
        $payment_history= [];
        foreach($medical_record as $key){
            $images[] = MedicalRecordImage::find()->where(['medical_record_id' => $key['id']])->asArray()->all();
            $payment_history[] = \common\models\user\PaymentHistory::find()->where(['medical_record_id' => $key['id']])->joinWith(['userAdmin', 'branch'])->orderBy('created_at DESC')->asArray()->all();
        }
        $lich_hen = Appointment::find()->where(['user_id' => $id, 'status_delete' => 0])->joinWith(['userAdmin','branch','user'])->asArray()->all();

        return $this->render('view', [
            'model' => $model,
            'address' => $address,
            'medical_record_item_child' => $medical_record_item_child,
            'medical_record_child'=>$medical_record_child,
            'factory'=>$factory,
            'images'=> $images,
            'medical_record'=>$medical_record,
            'lich_hen'=>$lich_hen,
            'medical_record_item_medicine'=>$medical_record_item_medicine,
            'medical_record_child_no'=>$medical_record_child_no,
            'medical_record_item'=>$medical_record_item,
            'payment_history'=>$payment_history
        ]);
    }
    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User();
        $model->birthday = date('Y-m-d', time());
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if(isset(Yii::$app->request->post('User')['birthday']) && Yii::$app->request->post('User')['birthday']) {
                $model->birthday = strtotime(Yii::$app->request->post('User')['birthday']);
            }
            $model->username_app = $model->phone;
            $model->admin_id = Yii::$app->user->id;
            $user = ClaApi::postApi('api/app/sync/user/signup', [
                'SignupForm' => [
                    'name' => $model->username,
                    'username' => $model->phone,
                    'email' => $model->email,
                    'phone' => $model->phone,
                    'password' => 'nk12345678',
                ],
                'role' => 0

            ]);
            if ($user) {
                $user = json_decode($user);
                if (isset($user->status) && $user->status == 200) {
                    $model->user_id_app = $user->data->id;
                }
            }

            $file = $_FILES['src'];
            if ($file && $file['name']) {
                $model->src = 'true';
                $extensions = Banner::allowExtensions();
                //
                if (!isset($extensions[$file['type']])) {
                    $model->addError('src', 'Ảnh không đúng định dạng');
                }
            }
            if (!$model->getErrors()) {
                $up = new UploadLib($file);
                $up->setPath(array('user'));
                $up->uploadFile();
                $response = $up->getResponse(true);
                if ($up->getStatus() == '200') {
                    $model->src = $response['baseUrl'] . $response['name'];
                } else {
                    $model->src = '';
                }
            }

            // Nếu bệnh nhân được lưu thành công và có thông tin người giới thiệu => tăng số bệnh nhân được giới thiệu
            if($model->save() && isset($model->introduce_id) && $model->introduce_id) {
                $update = true;
                $customers_sale = CustomerSales::find()->where(['key' => 'khach-gioi-thieu'])->where(['month' => date('m')])->where(['year' => date('Y')])->where(['user_id' => $model->introduce_id])->one();
                if (!$customers_sale) {
                    $customers_sale = new CustomerSales();
                    $update = false;
                }
                $customers_sale->user_id = $model->introduce_id;
                $customers_sale->quantity = $update ? $customers_sale->quantity + 1 : 1;
                $customers_sale->month = date('m');
                $customers_sale->key = 'khach-gioi-thieu';
                $customers_sale->year = date('Y');
                if (!$customers_sale->save()) throw new Exception('Lưu thống kê khách hàng xảy ra lỗi!');
            }

            return $this->redirect(['/user/waiting-list/create']);
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->birthday = date('Y-m-d', $model->birthday);
        if ($model->load(Yii::$app->request->post())) {
            if(isset(Yii::$app->request->post('User')['birthday']) && Yii::$app->request->post('User')['birthday']) {
                $model->birthday = strtotime(Yii::$app->request->post('User')['birthday']);
            }
            if($model->type_introduce){
                if($model->type_introduce == 1){
                    $model->introduce = null;
                }else{
                    $model->introduce_id = null;
                }
            }else{
                $model->introduce = null;
                $model->introduce_id = null;
            }

            $file = $_FILES['src'];
            if ($file && $file['name']) {
                $model->src = 'true';
                $extensions = Banner::allowExtensions();
                //
                if (!isset($extensions[$file['type']])) {
                    $model->addError('src', 'Ảnh không đúng định dạng');
                }
            }
            if (!$model->getErrors()) {
                if ($file && $file['name']) {
                    $up = new UploadLib($file);
                    $up->setPath(array('user'));
                    $up->uploadFile();
                    $response = $up->getResponse(true);
                    if ($up->getStatus() == '200') {
                        $model->src = $response['baseUrl'] . $response['name'];
                    } else {
                        $model->src = '';
                    }
                }

                if ($model->save()) {
                    return $this->redirect(['index']);
                }
            }


            if ($model->save()) {
                return $this->redirect(['index']);
            }
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing User model.
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

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionExcel()
    {
        $data_import = [];
        $sql = '';
        if ($_FILES) {
            require Yii::getAlias('@root') . "/common/components/extensions/excel/PHPExcel/IOFactory.php";
            $inputFileName = $_FILES["fl"]["tmp_name"];

            //  Read your Excel workbook
            try {
                $inputFileType = \PHPExcel_IOFactory::identify($inputFileName);
                $objReader = \PHPExcel_IOFactory::createReader($inputFileType);
                $objPHPExcel = $objReader->load($inputFileName);
            } catch (\Exception $e) {
                die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME) . '": ' . $e->getMessage());
            }

            $sheet = $objPHPExcel->getSheet(0);
            $highestRow = $sheet->getHighestRow();
            $highestColumn = $sheet->getHighestColumn();

            for ($row = 1; $row <= $highestRow; $row++) {
                $format = "d/m/Y";
                $cell = $objPHPExcel->getActiveSheet()->getCell('F' . $row);
                $InvDate = $cell->getValue();
                if (\PHPExcel_Shared_Date::isDateTime($cell)) {
                    $InvDate = strtotime(date($format, \PHPExcel_Shared_Date::ExcelToPHP($InvDate)));
                }
                $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
                    NULL,
                    TRUE,
                    FALSE);
                if (isset($rowData[0][2]) && $rowData[0][2] && isset($rowData[0][3]) && $rowData[0][3]) {
                    if (isset($sql) && $sql) {
                        $sql .= ",('" . $rowData[0][2] . "', '" . $rowData[0][3] . "', '" . $rowData[0][4] . "', '" . $InvDate . "',1)";
                    } else {
                        $sql = "INSERT into user (`username`, `phone`, `address`, `birthday`, `status`) VALUES ('" . $rowData[0][2] . "', '" . $rowData[0][3] . "', '" . $rowData[0][4] . "', '" . $InvDate . "',1)";
                    }
                    $data_import[] = [
                        'name' => $rowData[0][2],
                        'phone' => $rowData[0][3],
                    ];
                }
            }

            $connection = Yii::$app->db;
            $command = $connection->createCommand($sql);
            $command->execute();

            $user = ClaApi::postApi('api/app/sync/auto', $data_import);

            Yii::$app->session->setFlash('success', 'Thêm dữ liệu thành công');
        }
        return $this->render('excel', [
        ]);
    }

    public function actionIntroduce($type){
        return $this->renderPartial('layouts/introduce',['type' => $type]);
    }

    public function actionLog(){
        return true;
    }

    //Load log
    public function actionGetLog()
    {
        $log = UserLog::find()->joinWith(['userAdmin', 'branch'])->orderBy('created_at DESC')->all();
        return $this->renderPartial('layouts/log/log_list', ['logs' => $log]);
    }

    /**
     * Lấy ra danh sách các user có role 'Vai trò 1'.
     *
     * @param $role
     * @return Response
     */
    public function actionGetAllUserHasRoles()
    {
        try {
            $role = Yii::$app->request->post('role');
            $inchargeBy = Yii::$app->request->post('inchargeBy');
            $branch_id = Yii::$app->request->post('branch_id');
            $department_id = Yii::$app->request->post('department_id');

            $query = UserAdmin::find()->joinWith('authAssignment');
            if (isset($role) && $role && $role !== null) {
                $query = $query->onCondition(['=', 'auth_assignment.item_name', $role]);
            }
            $query = $query->where(['status' => UserAdmin::STATUS_ACTIVE]);
            if (isset($inchargeBy) && $inchargeBy && $inchargeBy !== null) {
                $inchargedUser = UserAdmin::find()->where(['id' => $inchargeBy])->one();
                if($inchargedUser) {
                    $query = $query->andWhere(['branch_id' => $inchargedUser->branch_id]);
                }
            }
            if($branch_id && $branch_id !== null) {
                $query = $query->andWhere(['branch_id' => (int)$branch_id]);
            }
            if($department_id && $department_id !== null) {
                $query = $query->andWhere(['vai_tro' => (int)$department_id]);
            }
            $user = $query->select(['id', 'fullname', 'vai_tro'])->all();
            return $this->asJson([
                'code' => 200,
                'message' => 'Lấy thông tin user thành công!!',
                'data' => [
                    'user' => $user
                ]
            ]);
        } catch (\Exception $e) {
            return $this->asJson([
                'code' => 500,
                'message' => $e->getMessage(),
                'data' => null
            ]);
        }
    }
}
