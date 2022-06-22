<?php

namespace backend\modules\chat\controllers;

use common\components\ClaQrCode;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;

/**
 * MenuController implements the CRUD actions for Menu model.
 */
class ChatController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
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
     * Lists all Menu models.
     * @return mixed
     */
    public function actionIndex() {
        $url = 'https://member.gcaeco.vn/chats/allchat';
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $dt = json_decode(curl_exec($ch));
        curl_close($ch);
        ClaQrCode::UpdateNotifiChat();

        return $this->render('index',['dt' => $dt]);
    }

    public function actionDetail($user_id1, $user_id2)
    {
        $param = array(
            'user_id1' => $user_id1,
            'user_id2' => $user_id2
        );

        $url = 'https://member.gcaeco.vn/chats/detailchat';

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_POST, count($param));

        curl_setopt($ch, CURLOPT_POSTFIELDS, $param);

        $data = json_decode(curl_exec($ch));

        curl_close($ch);

        ClaQrCode::UpdateReadChat($user_id2,$user_id1);

        return $this->render('detail', ['data' => $data,'user_id1' => $user_id1,'user_id2' => $user_id2]);
    }
}
