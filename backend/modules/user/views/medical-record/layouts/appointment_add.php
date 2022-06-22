<?php
/**
 * Created by PhpStorm.
 * User: trung
 * Date: 9/7/2021
 * Time: 9:22 AM
 */

?>
<tr>
    <td><?= date('d-m-Y', $model->time) ?></td>
    <td><?= date('H:i:s', $model->time) ?></td>
    <td><?= \common\models\branch\Branch::findOne($model->branch_id)->name; ?></td>
    <td><?= $model->name ?></td>
    <td><?= $model->phone ?></td>
    <td><?= $model->userAdmin->username ?></td>
    <td><?= $model->description ?></td>
    <td>
        <div class="box-checkbox <?= $model->status == 1 ? 'check' : '' ?>" check="0">
                        <span class="switchery switcherys updateajax"
                              data-link="<?= \yii\helpers\Url::to(['/service/appointment/updatestatus', 'id' => $model->id]) ?>"><small></small></span>
        </div>
    </td>
    <td>
        <a href="#" title="Sửa" aria-label="Sửa" data-pjax="0">
            <span class="glyphicon glyphicon-pencil"></span>
        </a>
        <a href="#" title="Xóa" aria-label="Xóa" data-pjax="0"
           data-confirm="Bạn có chắc là sẽ xóa mục này không?" data-method="post">
            <span class="glyphicon glyphicon-trash"></span>
        </a>
    </td>
</tr>