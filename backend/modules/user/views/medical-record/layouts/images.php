<?php
/**
 * Created by PhpStorm.
 * User: trung
 * Date: 10/14/2021
 * Time: 3:20 PM
 */

?>
<div class="row">
    <?php if ($images): ?>
        <?php foreach ($images as $image): ?>
            <div class="col-md-55">
                <div class="thumbnail">
                    <a href="#" onclick="zoom_image(this)" data-toggle="modal" data-target=".zoom_image" data-src="<?= \common\components\ClaHost::getImageHost().$image['path'].$image['name'] ?>"><img class="medical_record_image" src="<?= \common\components\ClaHost::getImageHost().$image['path'].$image['name'] ?>" alt=""></a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
