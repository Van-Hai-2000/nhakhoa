<?php

namespace backend\modules\withdraw\controllers;

use backend\models\UserAdmin;
use common\components\ClaQrCode;
use common\models\Bank;
use common\models\gcacoin\Gcacoin;
use common\models\gcacoin\WithDraw;
use common\models\gcacoin\WithdrawImages;
use common\models\User;
use common\models\user\UserBank;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;

/**
 * MenuController implements the CRUD actions for Menu model.
 */
class WithdrawController extends Controller
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
        $query = 'SELECT wd.*, us.username FROM withdraw wd, `user` us WHERE wd.user_id = us.id AND wd.status = 0';
        $model = Yii::$app->db->createCommand($query)->queryAll();
        return $this->render('index', ['model' => $model]);
    }

    public function actionConfirm($id)
    {
        $model = WithDraw::findOne($id);
        $username = User::findOne($model->user_id)->username;
        $bank = UserBank::findOne($model->bank_id);
        $bank_name = Bank::findOne($bank->bank_type)->name;
        $images = [];
        if (Yii::$app->request->post()) {
            $newimage = Yii::$app->request->post('newimage');
            $countimage = $newimage ? count($newimage) : 0;
            if ($newimage && $countimage > 0) {
                foreach ($newimage as $image_code) {
                    $imgtem = \common\models\media\ImagesTemp::findOne($image_code);
                    if ($imgtem) {
                        $wdimg = new WithdrawImages();
                        $wdimg->attributes = $imgtem->attributes;
                        $wdimg->withdraw_id = $id;
                        if ($wdimg->save()) {
                            $imgtem->delete();
                            $model->status = 1;
                            $model->admin_id = Yii::$app->user->id;
                            $gcoin = Gcacoin::findOne($model->user_id);
                            $first_coin = $gcoin->getCoinRed();
                            $model->last_coin = $first_coin - $model->value;
                            if ($model->update()) {
                                if ($gcoin->addCoinRed(-$model->value) && $gcoin->save(false)) {
                                    ClaQrCode::SendNotifi(ClaQrCode::TYPE_WITHDRAW_SUCCESS, '', $model->value, $model->last_coin, $model->user_id);
                                    $history = new \common\models\gcacoin\GcaCoinHistory();
                                    $history->user_id = $gcoin->user_id;
                                    $history->type = 'WITHDRAW_COIN';
                                    $history->data = 'Xác nhận rút tiền thành công ID' . $model->id;
                                    $history->gca_coin = -$model->value;
                                    $history->first_coin = $first_coin;
                                    $history->last_coin = $model->last_coin;
                                    $history->type_coin = \common\models\gcacoin\GcaCoinHistory::TYPE_V_RED;
                                    $history->save(false);
                                    Yii::$app->session->setFlash('success', 'Xác nhận rút tiền thành công');
                                    return $this->redirect(['withdraw/index']);
                                }
                            }
                        }
                    }
                }
            }
        }
        return $this->render('confirm', ['model' => $model, 'images' => $images, 'username' => $username, 'bank_name' => $bank_name, 'bank' => $bank]);
    }

    public function actionHistory()
    {
        $query = 'SELECT wd.*, us.username FROM withdraw wd, `user` us WHERE wd.user_id = us.id AND wd.status = 1 ORDER BY id DESC';
        $model = Yii::$app->db->createCommand($query)->queryAll();
        return $this->render('history', ['model' => $model]);
    }

    public function actionView($id)
    {
        $model = WithDraw::findOne($id);
        $admin = UserAdmin::findOne($model->admin_id)->username;
        $username = User::findOne($model->user_id)->username;
        $bank = UserBank::findOne($model->bank_id);
        $bank_name = Bank::findOne($bank->bank_type)->name;
        $images = WithdrawImages::find()->where(['withdraw_id' => $id])->all();
        return $this->render('view', ['model' => $model, 'admin' => $admin, 'username' => $username, 'bank_name' => $bank_name, 'bank' => $bank, 'images' => $images]);
    }

    public function actionConfirmCancel()
    {
        $post = Yii::$app->request->post();
        if (isset($post) && $post) {
            $user_id = isset($post['user_id']) ? $post['user_id'] : '';
            $id = isset($post['id']) ? $post['id'] : '';
            $body = isset($post['body']) ? $post['body'] : '';
            $model = WithDraw::findOne($id);
            if (isset($body) && $body) {
                $data = [
                    'body' => $body
                ];
                if ($model->delete()) {
                    ClaQrCode::SendNotifi(ClaQrCode::TYPE_WITHDRAW_CANCEL, json_encode($data), $model->value, '', $user_id);
                    $return = [
                        'success' => true,
                    ];
                } else {
                    $return = [
                        'success' => false,
                        'errors' => 'Hủy yêu cầu thất bại.'
                    ];
                }
            } else {
                $return = [
                    'success' => false,
                    'errors' => 'Bạn chưa nhập nội dung hủy yêu cầu.'
                ];
            }
            return json_encode($return);
        }
    }
}
