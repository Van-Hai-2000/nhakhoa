<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ExelController .
 */
class ExelController extends Controller
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

    public function actionExel($type)
    {

        $filename = "thongke$type.xls"; // File Name
        // Download file
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Content-Type: application/vnd.ms-excel;charset=UTF-8");

        // Write data to file
        $flag = false;
        $row = [];
        $table = '';
        switch ($type) {
            case 'USER':
                $data = \common\models\User::find()->select('*')->orderBy('username ASC')->asArray()->all();
                foreach ($data as $value) {
                    if (!$flag) {
                        // display field/column names as first row
                        $table .= '<tr>';
                        $table .= '<td>ID</td>';
                        $table .= '<td>Tên</td>';
                        $table .= '<td>Số điện thoại</td>';
                        $table .= '<td>Email</td>';
                        $table .= '<td>Năm sinh</td>';
                        $table .= '</tr>';
                        $flag = true;
                    }
                    $table .= '<tr>';
                    $table .= '<td>' . $value['id'] . '</td>';
                    $table .= '<td>' . $value['username'] . '</td>';
                    $table .= '<td>' . $value['phone'] . '</td>';
                    $table .= '<td>' . $value['email'] . '</td>';
                    $table .= '<td>' . ($value['birthday'] > 0 ? date('Y', $value['birthday']) : 'N/A') . '</td>';
                    $table .= '</tr>';
                }
                break;
            case 'SHOP':
                $data = \common\models\shop\Shop::find()->select('*')->orderBy('name ASC')->asArray()->all();
                foreach ($data as $value) {
                    if (!$flag) {
                        // display field/column names as first row
                        $table .= '<tr>';
                        $table .= '<td>ID</td>';
                        $table .= '<td>Tên</td>';
                        $table .= '<td>Người liên hệ</td>';
                        $table .= '<td>Số điện thoại</td>';
                        $table .= '<td>Email</td>';
                        $table .= '<td>CMT</td>';
                        $table .= '<td>Ngày tạo</td>';
                        $table .= '</tr>';
                        $flag = true;
                    }
                    $table .= '<tr>';
                    $table .= '<td>' . $value['id'] . '</td>';
                    $table .= '<td>' . $value['name'] . '</td>';
                    $table .= '<td>' . $value['name_contact'] . '</td>';
                    $table .= '<td>' . $value['phone'] . '</td>';
                    $table .= '<td>' . $value['email'] . '</td>';
                    $table .= '<td>' . $value['cmt'] . '</td>';
                    $table .= '<td>' . ($value['created_time'] > 0 ? date('d-m-Y', $value['created_time']) : 'N/A') . '</td>';
                    $table .= '</tr>';
                }
                break;
        }

        echo '<table>';
        echo $table;
        echo '</table>';
    }
}
