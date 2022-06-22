<?php

namespace backend\modules\sale\controllers;

use common\models\kpi\Kpi;

class KpiController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $kpis = Kpi::find()->orderBy('updated_at DESC')->asArray()->all();
        return $this->render('index',[
            'kpis' => $kpis
        ]);
    }

    public function actionStoreKpi() {
        try {
            if(\Yii::$app->request->post('kpi')) {
                $kpi = \Yii::$app->request->post('kpi');
                if(isset($kpi['id']) && $kpi['id']) {
                    $kpi_model = Kpi::find()->where(['id' => $kpi['id']])->one();
                    $kpi_model->updated_at = time() * 1000;
                } else {
                    $kpi_model = new Kpi();
                    $kpi_model->created_at = time() * 1000;
                    $kpi_model->updated_at = time() * 1000;
                }
                $kpi_model->name = $kpi['name'];
                if(!isset($kpi['in_system']) || !$kpi['in_system']) {
                    $kpi_model->key = $this->createSlug($kpi['name']);
                }
                $kpi_model->dinh_muc_khoan = $kpi['dinh_muc_khoan'];
                $kpi_model->in_system = isset($kpi['in_system']) && $kpi['in_system'] ? 1 : 0;
                $kpi_model->description = $kpi['description'];
                if($kpi_model->save()) {
                    return $this->asJson([
                        'code' => 200,
                        'message' => 'Lưu KPI thành công!',
                        'data' => [
                            'kpi' => $kpi_model
                        ]
                    ]);
                }
            }
            throw new \Exception('Có lỗi xảy ra trong quá trình lưu');
        } catch (\Exception $e) {
            return $this->asJson([
                'code' => 500,
                'message' => $e->getMessage(),
                'dâta' => []
            ]);
        }
    }

    public function actionDeleteKpi() {
        try {
            if(\Yii::$app->request->post('kpi')) {
                $kpi = \Yii::$app->request->post('kpi');
                if(isset($kpi['id']) && $kpi['id']) {
                    $kpi_model = Kpi::find()->where(['id' => $kpi['id']])->one();

                    if ($kpi_model->delete()) {
                        return $this->asJson([
                            'code' => 200,
                            'message' => 'Xóa KPI thành công!',
                            'data' => [
                                'kpi' => $kpi_model
                            ]
                        ]);
                    }
                }
            }
            throw new \Exception('Có lỗi xảy ra trong quá trình xóa');
        } catch (\Exception $e) {
            return $this->asJson([
                'code' => 500,
                'message' => $e->getMessage(),
                'dâta' => []
            ]);
        }
    }

    private function createSlug($string)
    {
        $search = array(
            '#(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)#',
            '#(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)#',
            '#(ì|í|ị|ỉ|ĩ)#',
            '#(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)#',
            '#(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)#',
            '#(ỳ|ý|ỵ|ỷ|ỹ)#',
            '#(đ)#',
            '#(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)#',
            '#(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)#',
            '#(Ì|Í|Ị|Ỉ|Ĩ)#',
            '#(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)#',
            '#(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)#',
            '#(Ỳ|Ý|Ỵ|Ỷ|Ỹ)#',
            '#(Đ)#',
            "/[^a-zA-Z0-9\-\_]/",
        );
        $replace = array(
            'a',
            'e',
            'i',
            'o',
            'u',
            'y',
            'd',
            'A',
            'E',
            'I',
            'O',
            'U',
            'Y',
            'D',
            '-',
        );
        $string = preg_replace($search, $replace, $string);
        $string = preg_replace('/(-)+/', '-', $string);
        $string = strtolower($string);
        return $string;
    }
}
