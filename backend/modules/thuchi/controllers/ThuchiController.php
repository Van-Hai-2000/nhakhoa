<?php

namespace backend\modules\thuchi\controllers;

use common\models\medical_record\MedicalRecordLog;
use common\models\user\MedicalRecord;
use common\models\user\PaymentHistory;
use common\models\user\search\MedicalRecordSearch;
use common\models\user\search\UserSearch;
use Yii;
use common\models\thuchi\ThuChi;
use common\models\thuchi\ThuChiSearch;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ThuchiController implements the CRUD actions for ThuChi model.
 */
class ThuchiController extends Controller
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
     * Lists all ThuChi models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ThuChiSearch();
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
     * Displays a single ThuChi model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = ThuChi::find()->where(['thu_chi.id' => $id])->joinWith(['payment'])->one();
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new ThuChi model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ThuChi();

        if ($model->load(Yii::$app->request->post())) {
            $model->time = strtotime($model->time);
            $model->admin_id = Yii::$app->user->id;
            if ($model->type == ThuChi::TYPE_CHI) {
                $model->type_id = ThuChi::TYPE_CHI_MORE;
            } else {
                $model->type_id = ThuChi::TYPE_THU_MORE;
            }
            if ($model->save()) {
                return $this->redirect(['index']);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionUp()
    {
        $thuchi = ThuChi::find()->all();
        foreach ($thuchi as $value) {
            if (!$value->type_payment) {
                if ($value->payment_id) {
                    $payment = PaymentHistory::findOne($value->payment_id);
                    $value->type_payment = $payment->type_payment;
                    $value->save();
                } else {
                    $value->type_payment = PaymentHistory::TYPE_PAYMENT_1;
                    $value->save();
                }
            } else {
                if ($value->payment_id) {
                    $payment = PaymentHistory::findOne($value->payment_id);
                    $value->medical_record_id = $payment->medical_record_id;
                    $value->save();
                }
            }
        }
        return true;
    }

    /**
     * Updates an existing ThuChi model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->time = date('Y-m-d\TH:i', $model->time);
        if ($model->load(Yii::$app->request->post())) {
            $model->time = strtotime($model->time);
            $model->admin_id = Yii::$app->user->id;
            if ($model->type == ThuChi::TYPE_CHI) {
                $model->type_id = ThuChi::TYPE_CHI_MORE;
            } else {
                $model->type_id = ThuChi::TYPE_THU_MORE;
            }
            if ($model->save()) {
                return $this->redirect(['index']);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing ThuChi model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ThuChi model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ThuChi the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ThuChi::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionCongNo()
    {
        $searchModel = new MedicalRecordSearch();
        $dataProvider = $searchModel->searchCn(Yii::$app->request->queryParams);

        $url = Url::to(['excel']);
        $url = $url . "?" . http_build_query($_GET);

        return $this->render('user', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'params' => $_GET,
            'url' => $url,
        ]);
    }

    public function actionSetUrlCn()
    {
        $request = $_GET;
        $params = json_decode($request['params'], true);
        $params['MedicalRecordSearch']['time_start'] = strtotime($request['time_start']);
        $params['MedicalRecordSearch']['time_end'] = strtotime($request['time_end']);
        $url = Url::to(['cong-no']);
        $final = $url . "?" . http_build_query($params);
        return $final;
    }

    public function actionViewDetail($id)
    {
        $model = MedicalRecord::findOne($id);
        $payments = PaymentHistory::find()->where(['medical_record_id' => $id])->joinWith(['branch', 'userAdmin'])->all();
        return $this->render('user_view', [
            'model' => $model,
            'payments' => $payments,
        ]);
    }

    public function actionSetUrl()
    {
        $request = $_GET;
        $params = json_decode($request['params'], true);
        $params['ThuChiSearch']['time_start'] = strtotime($request['time_start']);
        $params['ThuChiSearch']['time_end'] = strtotime($request['time_end']);
        $url = Url::to(['index']);
        $final = $url . "?" . http_build_query($params);
        return $final;
    }

    public function actionExcel()
    {
        $searchModel = new ThuChiSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        require Yii::getAlias('@root') . "/common/components/extensions/excel/PHPExcel.php";
        $name = "Danh sách hoa hồng.xlsx";
        $title = "Danh sách rút tiền";

        $data = $dataProvider->models;

        $model = new ThuChi();
        if ($data) {
            $border = array(
                'borders' => array(
                    'allborders' => array(
                        'style' => \PHPExcel_Style_Border::BORDER_THIN
                    )
                )
            );
            $atributes = [
                'nguoi_chi',
                'value',
                'money',
                'total_money',
                'user_id',
                'medical_record_id',
                'branch_id',
                'created_at',
            ];
            $atribute_times = [
                'created_at',
            ];
            $start_col = 65;
            $row_name = 1;
            $row_atribute = 4;
            $start_row = 3;
            $toltal_col = count($atributes);
            $toltal_row = count($data);
            $excel = new \PHPExcel();
            $excel->setActiveSheetIndex(0);
            $excel->getActiveSheet()->setTitle($title);
            $excel->getActiveSheet()->setCellValue("A1", $title);
            for ($i = 0; $i < $toltal_col; $i++) {
                $excel->getActiveSheet()->getColumnDimension(chr($start_col + $i))->setWidth(20);
            }
            //ghi ten
            $j = $start_col;
            foreach ($atributes as $key) {
                if ($key == 'user_id') {
                    $excel->getActiveSheet()->setCellValue(chr($j++) . $row_name, 'Khách hàng');
                } else {
                    $excel->getActiveSheet()->setCellValue(chr($j++) . $row_name, $model->getAttributeLabel($key));
                }

            }

            //ghi attr
            $j = $start_col;
            foreach ($atributes as $key) {
                $excel->getActiveSheet()->setCellValue(chr($j++) . $row_atribute, $key);
            }

            //ghi gia tri
            for ($i = $start_row; $i < ($toltal_row + $start_row); $i++) {
                $item = $data[$i - $start_row];
                $j = $start_col;
                foreach ($atributes as $key) {
                    $vcl = chr($j++) . $i;
                    if (in_array($key, $atribute_times)) {
                        $value = isset($item->$key) && $item->$key ? date('d/m/Y H:i:s', $item->$key) : '';
                        $dateValue = \PHPExcel_Shared_Date::PHPToExcel(
                            \DateTime::createFromFormat('d/m/Y H:i:s', $value)
                        );
                        $excel->getActiveSheet()
                            ->setCellValue($vcl, $dateValue);
                        $excel->getActiveSheet()
                            ->getStyle($vcl)
                            ->getNumberFormat()
                            ->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);
                    } else {
                        $excel->getActiveSheet()->setCellValue($vcl, $item->show($key, $item));
                    }
                }
            }

            $excel->getActiveSheet()->getStyle(chr($start_col) . $row_atribute . ':' . chr($start_col + $toltal_col - 1) . ($row_atribute + $toltal_row))->applyFromArray($border);
            \ob_end_clean();
            header('Content-type: application/vnd.ms-excel');
            header('Content-Disposition: attachment; filename="' . $name . '"');
            \PHPExcel_IOFactory::createWriter($excel, 'Excel2007')->save('php://output');
            die();
        }
    }

    public function actionIn()
    {
        return true;
    }

    public function actionLog()
    {
        return true;
    }

    //Load log
    public function actionGetLog()
    {
        $log = MedicalRecordLog::find()->where(['medical_record_log.type' => MedicalRecordLog::TYPE_4])->joinWith(['userAdmin', 'branch'])->orderBy('created_at DESC')->all();
        return $this->renderPartial('layouts/log/log_list', ['logs' => $log]);
    }
}
