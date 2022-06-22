<?php

namespace backend\modules\mail\controllers;

use Yii;
use frontend\models\User;
use common\models\shop\Shop;
use common\models\shop\ShopSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\transport\Transport;
use common\models\transport\ShopTransport;

/**
 * UserController implements the CRUD actions for User model.
 */
class MailController extends Controller
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
        return $this->render('index');
    }

    public function actionConfig()
    {
        $model = \common\models\mail\MailConfig::findOne(1);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                \Yii::$app->getSession()->setFlash('success', 'Đã lưu');
            }
        }
        return $this->render('config', [
            'model' => $model,
        ]);
    }

    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    public function actionSendMail($page = 0)
    {
        $type = $_POST['type'];
        $arr = $_POST;
        $view = $page ? 'waiting' : 'waiting-full';
        switch ($type) {
            case 0:
                $data = (new \yii\db\Query())->select('email')
                    ->from('user')
                    ->all();
                $data2 = (new \yii\db\Query())->select('email')
                    ->from('shop')
                    ->all();
                $data = array_merge($data, $data2);
                break;
            case 1:
                $data = (new \yii\db\Query())
                    ->select('email')
                    ->from('user')
                    ->all();
                break;
            case 2:
                $data = (new \yii\db\Query())->select('email')
                    ->from('shop')
                    // ->limit(10)
                    // ->orderBy('id')
                    ->all();
                break;
            case -1:
                $data[0]['email'] = 'kennyle102@gmail.com';
                // $data[1]['email'] = 'cntt.vancong1993@gmail.com';
                break;
        }
        // echo '<pre>';
        // print_r($data);
        // die();
        $once = 25;
        $start = $page * $once;
        $end = count($data) > ($page * $once + $once) ? ($page * $once + $once) : count($data);
        for ($i = $start; $i < $end; $i++) {
            $arr['email'] = $data[$i]['email'];
            if ($arr['email']) {
                // echo $arr['email'];
                $this->sendMail($arr);
            }
        }
        $page++;
        return ($view == 'waiting') ? $this->renderAjax($view, [
            'page' => $page,
            'once' => $once,
            'end' => $end,
            'post' => $_POST,
            'count' => count($data),
        ]) : $this->render($view, [
            'page' => $page,
            'once' => $once,
            'end' => $end,
            'post' => $_POST,
            'count' => count($data),
        ]);
    }

    public function sendMail($arr)
    {

        $email = $arr["email"];
        $link = $arr["link"];
        if (isset($arr["title"])) {
            $tieude = $arr["title"];
        } else {
            $tieude = "Thông báo từ ocopmart.org";
        }
        if (isset($arr["content"])) {
            $content = $arr["content"];
            $content .= $link ? '<br/><a href="' . $link . '">Xem chi tiết</a>' : '';
        } else {
            $content = "Cám ơn quý khách đã sử dụng dịch vụ của ocopmart.org.<br/> <a href='" . __SERVER_NAME . "'>Đến trang web ngay</a>";
        }
        $config_mail = \common\models\mail\MailConfig::findOne(1);
        if ($config_mail) {
            \Yii::$app->mailer->setTransport([
                'class' => 'Swift_SmtpTransport',
                'host' => $config_mail->host,
                'username' => $config_mail->email,
                'password' => $config_mail->password,
                'port' => $config_mail->port,
                'encryption' => $config_mail->encryption,
            ]);
        }
        $content = \frontend\widgets\mail\MailWidget::widget([
            'view' => 'view',
            'input' => [
                'title' => $tieude,
                'content' => $content
            ]
        ]);
        Yii::$app->mailer->compose()
            ->setFrom([Yii::$app->params['adminEmail'] => 'ocopmart.org'])
            ->setTo($email)
            ->setSubject($tieude)
            ->setHtmlBody($content)
            ->send();
    }
}
