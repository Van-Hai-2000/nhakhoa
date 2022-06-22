
<span class="<?php echo $buttonClass; ?> fileinput-button">
    <i class="fa fa-cloud-upload"></i>
    <span class=""><?php echo $buttontext; ?></span>
    <input id="<?php echo ($id) ? $id : 'uploadfile'; ?>" type="file" multiple="<?php echo ($multi) ? 'true' : 'false'; ?>" name="<?php echo ($name) ? $name : 'files' ?>" />
</span>
<div class='valuebox hidden2 <?php echo ($displayvaluebox) ? '' : 'hidden' ?>'>
    <ul class='ulvalbox clearfix'>
    </ul>
</div>