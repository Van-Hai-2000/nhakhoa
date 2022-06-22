<?php

namespace backend\modules\sale\controllers;

use backend\models\UserAdmin;
use common\models\branch\Branch;
use common\models\kpi\Kpi;
use common\models\kpi\KpiUser;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;

/**
 * BranchController implements the CRUD actions for BranchSales model.
 */
class SaleController extends Controller
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
        $user = UserAdmin::find();
        $inchargedUser = UserAdmin::find()->where(['id' => Yii::$app->user->getId()])->one();
        if ($inchargedUser) {
            $user = $user->where(['branch_id' => $inchargedUser->branch_id]);
        }
        $user = $user->where(['status' => UserAdmin::STATUS_ACTIVE]);
        $user = $user->select(['id', 'fullname', 'vai_tro'])->asArray()->all();

        $branchs = Branch::getBranch();
        $departments = UserAdmin::arrayType();
        $permissions = array_keys(\Yii::$app->authManager->getRolesByUser(\Yii::$app->user->getId()));

        $kpis = Kpi::find()->select(['id', 'name', 'dinh_muc_khoan', 'in_system'])->asArray()->all();

        return $this->render('index', [
            'kpis' => $kpis,
            'branchs' => $branchs,
            'departments' => $departments,
            'employs' => $user,
            'permissions' => $permissions
        ]);
    }

    public function actionGetPersonalKpiInfo()
    {
        try {
            $employ_id = Yii::$app->request->post('employ');
            $year = Yii::$app->request->post('year');
            $month = Yii::$app->request->post('month');
            if ($employ_id) {
                if (!$year) {
                    $year = date('Y');
                }
                if (!$month) {
                    $month = date('m');
                }

                $employ = UserAdmin::find()->where(['id' => $employ_id])->one();
                if ($employ) {
                    $kpis = KpiUser::find()->where(['user_id' => $employ_id])->andWhere(['thang' => (int)$month])->andWhere(['nam' => (int)$year])->all();
                    return $this->asJson([
                        'code' => 200,
                        'message' => 'Lấy danh sách kpi người dùng #' . Yii::$app->request->post('employ') . ' thành công',
                        'data' => [
                            'kpis' => $kpis
                        ]
                    ]);
                }
            }
        } catch (\Exception $e) {
            return $this->asJson([
                'code' => 500,
                'message' => $e->getMessage(),
                'data' => []
            ]);
        }
    }

    public function actionGetKpiStatistical()
    {
        try {
            $kpi_id = Yii::$app->request->post('kpi_id');
            $times = Yii::$app->request->post('times');
            $user_id = Yii::$app->request->post('user_id');
            if ($kpi_id && $times && $user_id) {
                $dinhmuc = [];
                $thucdat = [];
                foreach ($times as $k => $time) {
                    $time_array = explode('-', $time);
                    $kpis = KpiUser::find()->where(['kpi_id' => $kpi_id])
                        ->andWhere(['user_id' => $user_id])
                        ->andWhere(['thang' => (int)$time_array[0]])
                        ->andWhere(['nam' => (int)$time_array[1]])->all();
                    if ($kpis) {
                        foreach ($kpis as $kpi) {
                            $thucdat[$k] = isset($thucdat[$k]) && $thucdat[$k] ? $thucdat[$k] + $kpi->thuc_dat : $kpi->thuc_dat;
                            $dinhmuc[$k] = isset($dinhmuc[$k]) && $dinhmuc[$k] ? $dinhmuc[$k] + $kpi->dinh_muc : $kpi->dinh_muc;
                        }
                    }else{
                        $thucdat[$k] = 0;
                        $dinhmuc[$k] = 0;
                    }
                }
                return $this->asJson([
                    'code' => 200,
                    'message' => 'Lấy dữ liệu thành công',
                    'data' => [
                        'chart' => [$thucdat, $dinhmuc]
                    ]
                ]);
            }
            throw new \Exception('Có lỗi xảy ra');
        } catch (\Exception $e) {
            return $this->asJson([
                'code' => 500,
                'message' => $e->getMessage(),
                'data' => []
            ]);
        }
    }

    public function actionStoreKpi()
    {
        try {
            $action = Yii::$app->request->post('action');
            $kpi = Yii::$app->request->post('kpi');
            if ($action && $kpi) {
                $storedKpi = null;
                $message = '';
                switch ($action) {
                    case 'create':
                        $storedKpi = new KpiUser();
                        $message = 'Thêm chỉ tiêu thành công!';
                        break;
                    case 'update':
                    case 'delete':
                        if (!isset($kpi['id']) || !$kpi['id']) {
                            break;
                        }
                        $storedKpi = KpiUser::find()->where(['id' => $kpi['id']])->one();
                        $message = $action === 'update' ? 'Cập nhật chỉ tiêu thành công!' : 'Xóa chỉ tiêu thành công!';
                        break;
                    default:
                }
                if ($storedKpi === null) {
                    throw new \Exception('Không tìm thấy bản ghi phù hợp!');
                }
                if ($action === 'update' || $action === 'create') {
                    $storedKpi->user_id = isset($kpi['user_id']) && $kpi['user_id'] ? (int)$kpi['user_id'] : '';
                    $storedKpi->kpi_id = isset($kpi['kpi_id']) && $kpi['kpi_id'] ? (int)$kpi['kpi_id'] : '';
                    $storedKpi->dinh_muc = isset($kpi['dinh_muc']) && $kpi['dinh_muc'] ? (float)$kpi['dinh_muc'] : 0;
                    $storedKpi->thuc_dat = isset($kpi['thuc_dat']) && $kpi['thuc_dat'] ? (float)$kpi['thuc_dat'] : 0;
                    $storedKpi->trong_so = isset($kpi['trong_so']) && $kpi['trong_so'] ? (int)$kpi['trong_so'] : 0;
                    $storedKpi->tru_kpi = isset($kpi['tru_kpi']) && $kpi['tru_kpi'] ? (int)$kpi['tru_kpi'] : 0;
                    $storedKpi->ghi_chu = isset($kpi['ghi_chu']) && $kpi['ghi_chu'] ? strip_tags($kpi['ghi_chu']) : '';
                    $storedKpi->nguoi_danh_gia = isset($kpi['nguoi_danh_gia']) && $kpi['nguoi_danh_gia'] ? (int)$kpi['nguoi_danh_gia'] : 0;
                    $storedKpi->thang = isset($kpi['thang']) && $kpi['thang'] ? (int)$kpi['thang'] : (int)date('m');
                    $storedKpi->nam = isset($kpi['nam']) && $kpi['nam'] ? (int)$kpi['nam'] : (int)date('Y');
                    $storedKpi->created_at = time();
                    $storedKpi->updated_at = time();
                }
                switch ($action) {
                    case 'create':
                    case 'update':
                        if (!$storedKpi->save()) {
                            throw new \Exception('Có lỗi xảy ra trong quá trình lưu!');
                        }
                        break;
                    case 'delete':
                        if (!$storedKpi->delete()) {
                            throw new \Exception('Có lỗi xảy ra trong quá trình xóa!');
                        }
                        break;
                    default:
                }

                return $this->asJson([
                    'code' => 200,
                    'message' => $message,
                    'data' => [
                        'action' => $action,
                        'kpi' => $storedKpi
                    ]
                ]);
            }
            throw new \Exception('Có lỗi xảy ra với dữ liệu đầu vào!');
        } catch (\Exception $e) {
            return $this->asJson([
                'code' => 500,
                'message' => $e->getMessage(),
                'data' => []
            ]);
        }
    }
}
