<?php

namespace backend\modules\notify\controllers;

use common\models\notify\Notify;
use yii\web\Controller;

/**
 * Default controller for the `notify` module
 */
class NotifyController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        try {
            $user_id = \Yii::$app->user->getId();
            $notifies = Notify::find()->where(['send_to' => $user_id])->orderBy('status ASC')->all();
            return $this->render('index', [
                'notifies' => $notifies
            ]);
        } catch (\Exception $e) {
            print_r($e->getMessage()); die();
        }
    }

    public function actionGetNotify()
    {
        try {
            $user_id = \Yii::$app->user->getId();
            $notifies = Notify::find()->where(['send_to' => $user_id])->orderBy('status ASC, created_at ASC')->all();
            $unread = Notify::find()->where(['send_to' => $user_id, 'status' => Notify::NOTIFY_UNREAD])->count();
            return $this->asJson([
                'code' => 200,
                'message' => 'Lấy danh sách thông báo thành công!',
                'data' => [
                    'notifies' => $notifies,
                    'unread' => $unread,
                ]
            ]);
        } catch (\Exception $e) {
            return $this->asJson([
                'code' => 500,
                'message' => $e->getMessage(),
                'data' => []
            ]);
        }
    }

    public function actionChangeNotifyStatus()
    {
        try {
            if (\Yii::$app->request->post() && \Yii::$app->request->post('id')) {
                $id = \Yii::$app->request->post('id');
                $model = Notify::find()->where(['id' => $id])->one();
                if ($model) {
                    if ($model->status == Notify::NOTIFY_UNREAD)
                        $model->status = Notify::NOTIFY_READ;
                    if ($model->save()) {
                        return $this->asJson([
                            'code' => 200,
                            'message' => 'Thông báo đã được đọc!',
                            'data' => []
                        ]);
                    }
                }
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
}
