<?php

namespace backend\modules\checklist\controllers;

use backend\models\UserAdmin;
use common\models\branch\Branch;
use common\models\checklist\Checklists;
use common\models\media\Document;
use common\models\notify\Notify;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\Url;

class ChecklistController extends \yii\web\Controller
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
        $branchs = Branch::getBranch();
        $statuses = Checklists::getStatuses();
        $checklist = Checklists::find()->orderBy("priority ASC");
        $can_view_all = Yii::$app->user->can('Quản lý công việc - Xem tất cả') || Yii::$app->user->can('Admin');
        $branch = Yii::$app->user->identity->branch_id;
        if($can_view_all) {
            $branch = isset($_GET['branch']) && $_GET['branch'] ? $_GET['branch'] : '';
        }
        if($branch) {
            $checklist = $checklist->where(['branch_id' => $branch]);
        }
        $checklist = $checklist->asArray()->all();
        foreach ($checklist as $k => $ck) {
            if (isset($ck['attachments']) && $ck['attachments']) {
                $ck['attachments'] = trim($ck['attachments'], "[]");
                $attachments = Document::find()->where('id IN (' . $ck['attachments'] . ')')->asArray()->all();
                $checklist[$k]['attachments'] = $attachments;
            } else {
                $checklist[$k]['attachments'] = [];
            }
        }
        $userPosition = UserAdmin::arrayType();
        $permissions = array_keys(\Yii::$app->authManager->getRolesByUser(\Yii::$app->user->getId()));
        return $this->render('index', array(
            'statuses' => $statuses,
            'checklist' => $checklist,
            'permissions' => $permissions,
            'userPosition' => $userPosition,
            'branchs' => $branchs
        ));
    }

    public function actionChangeChecklistItemStatus()
    {
        try {
            if (\Yii::$app->request->post() && \Yii::$app->request->post('id') && \Yii::$app->request->post('status')) {
                $id = \Yii::$app->request->post('id');
                $status = \Yii::$app->request->post('status');
                $model = Checklists::find()->where(['id' => $id])->one();
                if ($model && $model->status !== $status) {
                    $can_check = \Yii::$app->user->can('Quản lý công việc - Kiểm tra');
                    if ($status == 'complete' && !$can_check) {
                        throw new \Exception('Không có quyền phê duyệt công việc');
                    }
                    $model->status = $status;
                    $model->updated_at = time() * 1000;
                    if ($status == 'checking') {
                        $m = new Notify();
                        $m->name = 'Công việc: ' . $model->name;
                        $m->description = 'Công việc chờ được kiểm tra';
                        $m->send_from = (int)Yii::$app->user->getId();
                        $m->send_to = (int)$model->incharge_by;
                        $m->link = Url::to(['/checklist/checklist/index', 'checklist' => $model->id]);
                        $m->status = (int)Notify::NOTIFY_UNREAD;
                        $m->created_at = time() * 1000;
                        $m->updated_at = time() * 1000;
                        $m->save();
                    }
                    if ($model->save()) {
                        return $this->asJson([
                            'code' => 200,
                            'message' => 'Cập nhật trạng thái thành công',
                            'data' => $model->toArray()
                        ]);
                    }
                }
                throw new \Exception('Trạng thái không thay đổi!');
            }
            throw new \Exception('Có lỗi xảy ra!');
        } catch (\Exception $e) {
            return $this->asJson([
                'code' => 500,
                'message' => $e->getMessage(),
                'data' => []
            ]);
        }
    }

    public function actionCreateChecklistItem()
    {
        try {
            $model = new Checklists();
            if (\Yii::$app->request->post('id')) {
                $id = \Yii::$app->request->post('id');
                $model = Checklists::find()->where(['id' => $id])->one();
                if (!$model) {
                    throw new \Exception('Có lỗi xảy ra!');
                }
            }

            if ($model && \Yii::$app->request->post()) {
                $model->created_by = \Yii::$app->user->getId();
                $model->name = \Yii::$app->request->post('name');
                $model->branch_id = \Yii::$app->request->post('branch_id');
                $model->description = \Yii::$app->request->post('description');
                $model->start_at = \Yii::$app->request->post('start_at');
                $model->end_at = \Yii::$app->request->post('end_at');
                $model->priority = \Yii::$app->request->post('priority');
                $model->loop = \Yii::$app->request->post('loop');
                $model->status = \Yii::$app->request->post('status');
                $model->incharge_by = \Yii::$app->request->post('incharge_by');
                $model->handled_by = json_encode(\Yii::$app->request->post('handled_by'));
                $attachments = \Yii::$app->request->post('attachments');
                if ($attachments && is_array($attachments)) {
                    $model->attachments = json_encode(array_column($attachments, 'id'));
                } else {
                    $model->attachments = $attachments;
                }
                $model->links = json_encode(\Yii::$app->request->post('links'));
                $model->created_at = time() * 1000;
                $model->updated_at = time() * 1000;
                if ($model->validate() && $model->save()) {
                    if (!isset($id) || !$id) {
                        $handled_by = \Yii::$app->request->post('handled_by');
                        if ($handled_by != null && !is_array($handled_by)) {
                            $handled_by = str_replace(['[', ']'], '', $handled_by);
                            $handled_by = explode(',', $handled_by);
                        }
                        if ($handled_by != null && is_array($handled_by)) {
                            foreach ($handled_by as $value) {
                                $m = new Notify();
                                $m->name = 'Công việc: ' . $model->name;
                                $m->description = 'Công việc được tạo mới';
                                $m->send_from = (int)Yii::$app->user->getId();
                                $m->send_to = (int)$value;
                                $m->link = Url::to(['/checklist/checklist/index', 'checklist' => $model->id]);
                                $m->status = (int)Notify::NOTIFY_UNREAD;
                                $m->created_at = time() * 1000;
                                $m->updated_at = time() * 1000;
                                $m->save();
                            }
                        }
                    }
                    return $this->asJson([
                        'code' => 200,
                        'message' => isset($id) && $id ? 'Sửa việc làm thành công!' : 'Thêm việc làm thành công!',
                        'data' => $model->toArray()
                    ]);
                }
            }
            throw new \Exception('Có lỗi xảy ra!');
        } catch (\Exception $e) {
            return $this->asJson([
                'code' => 500,
                'message' => $e->getMessage(),
                'data' => []
            ]);
        }
    }

    public function actionDeleteChecklistItem()
    {
        try {
            if (Yii::$app->request->post() && Yii::$app->request->post('id')) {
                $id = Yii::$app->request->post('id');
                $model = Checklists::find()->where(['id' => $id])->one();
                if ($model && $model->delete()) {
                    return $this->asJson([
                        'code' => 200,
                        'message' => 'Xóa công việc thành công',
                        'data' => $model->toArray(),
                    ]);
                }
            }
            throw new \Exception('Có lỗi xảy ra!');
        } catch (\Exception $e) {
            return $this->asJson([
                'code' => 500,
                'message' => $e->getMessage(),
                'data' => []
            ]);
        }
    }

    public function actionResetChecklistLoop()
    {
        try {
            $checklists = Checklists::find()->where("`loop` <> ''")->andWhere("`status` <> 'todo'")->all();
            foreach ($checklists as $checklist) {
                switch ($checklist->loop) {
                    case 'daily':
                        $loop_range = '+1 day';
                        break;
                    case 'weekly':
                        $loop_range = '+1 week';
                        break;
                    case 'monthly':
                        $loop_range = '+1 month';
                        break;
                    case 'yearly':
                        $loop_range = '+1 year';
                        break;
                    default:
                        $loop_range = '';
                }
                if (time() * 1000 <= $checklist->end_at && time() * 1000 >= $checklist->start_at) {
                    $created_at = new \DateTime(date('Y-m-d', (int)($checklist->created_at / 1000)));
                    $now = new \DateTime(date('Y-m-d'));
                    if ($loop_range && $created_at->modify($loop_range) <= $now->modify('-1 minute')) {
                        $checklist->created_at = time() * 1000;
                        $checklist->status = 'todo';
                        $checklist->save();
                    }
                }
            }
            print_r('Cron trạng thái checklist (daily, weekly, monthly, yearly)');
            die();
        } catch (\Exception $e) {
            print_r($e->getMessage());
            die();
        }
    }
}
