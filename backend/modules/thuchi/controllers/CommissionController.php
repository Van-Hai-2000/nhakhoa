<?php

namespace backend\modules\commission\controllers;

use Yii;
use common\models\commission\Commission;
use common\models\commission\CommissionSearch;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CommissionController implements the CRUD actions for Commission model.
 */
class CommissionController extends Controller
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


    public function actionIndex()
    {
        $searchModel = new CommissionSearch();
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

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
    {
        $model = new Commission();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Commission::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionSetUrl()
    {
        $request = $_GET;
        $params = json_decode($request['params'], true);
        $params['CommissionSearch']['time_start'] = strtotime($request['time_start']);
        $params['CommissionSearch']['time_end'] = strtotime($request['time_end']);
        $url = Url::to(['index']);
        $final = $url . "?" . http_build_query($params);
        return $final;
    }

    public function actionExcel()
    {
        $searchModel = new CommissionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        require Yii::getAlias('@root') . "/common/components/extensions/excel/PHPExcel.php";
        $name = "Danh sách hoa hồng.xlsx";
        $title = "Danh sách rút tiền";

        $data = $dataProvider->models;

        $model = new Commission();
        if ($data) {
            $border = array(
                'borders' => array(
                    'allborders' => array(
                        'style' => \PHPExcel_Style_Border::BORDER_THIN
                    )
                )
            );
            $atributes = [
                'admin_id',
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
}
