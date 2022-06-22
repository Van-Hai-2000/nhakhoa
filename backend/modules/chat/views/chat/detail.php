<?php
    use yii\helpers\Url;
?>
<style>
    img {
        max-width: 100%;
    }

    .recent_heading h4 {
        color: #05728f;
        font-size: 21px;
        margin: auto;
    }

    .srch_bar input {
        border: 1px solid #cdcdcd;
        border-width: 0 0 1px 0;
        width: 80%;
        padding: 2px 0 4px 6px;
        background: none;
    }

    .srch_bar .input-group-addon button {
        background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
        border: medium none;
        padding: 0;
        color: #707070;
        font-size: 18px;
    }

    .received_msg {
        display: inline-block;
        padding: 0 0 0 10px;
        vertical-align: top;
        width: 92%;
    }

    .received_withd_msg p {
        background: #ebebeb none repeat scroll 0 0;
        border-radius: 3px;
        color: #646464;
        font-size: 14px;
        margin: 0;
        padding: 5px 10px 5px 12px;
        width: 100%;
    }

    .time_date {
        color: #747474;
        display: block;
        font-size: 12px;
        margin: 8px 0 0;
    }

    .received_withd_msg {
        width: 57%;
    }

    .mesgs {
        float: left;
        padding: 30px 15px 0 25px;
        width: 100%;
    }

    .sent_msg p {
        background: #05728f none repeat scroll 0 0;
        border-radius: 3px;
        font-size: 14px;
        margin: 0;
        color: #fff;
        padding: 5px 10px 5px 12px;
        width: 100%;
    }

    .incoming_msg {
        overflow: hidden;
        margin: 26px 0 26px;
    }

    .outgoing_msg {
        overflow: hidden;
        margin: 26px 0 26px;
    }

    .sent_msg {
        float: right;
        width: 46%;
    }

    .input_msg_write input {
        background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
        border: medium none;
        color: #4c4c4c;
        font-size: 15px;
        min-height: 48px;
        width: 100%;
    }

    .msg_history {
        height: 516px;
        overflow-y: auto;
    }
    .shopee-chat__product-item .shopee-chat__image {
        float: left;
        width: 50px;
        height: 50px;
        margin-right: 10px;
        margin-left: 20px;
        background-repeat: no-repeat;
        background-position: center;
        background-size: contain;
        border: 1px solid #e8e8e8;
        border-radius: 2px;
    }
    .shopee-chat__product-item .shopee-chat__price {
        color: #ff5722;
    }
    .shopee-chat__product-item .shopee-chat__name {
        display: block;
        width: 100%;
        color: #FFF;
    }
    .shopee-chat__product-item .shopee-chat__image + .shopee-chat__info {
        padding-left: 60px;
    }
    .shopee-chat__product-item .shopee-chat__info {
        float: none;
        display: block;
    }
    .shopee-chat__product-item {
        width: 100%;
        min-height: 70px;
        padding: 10px;
        text-align: left;
    }
    .product-order-card {
        background: green;
        position: relative;
        box-shadow: 0 1px 2px rgba(0, 0, 0, .12);
        margin: 0 -15px;
    }
</style>
<div class="row">
    <div class="news-category-index">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2 style="margin-left: 50px;color: green"><?= $data->name1 ?></h2>
                        <h2 class="pull-right" style="margin-right: 50px;color: green"><?= $data->name2 ?></h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="mesgs">
                        <div class="msg_history">
                            <?php foreach ($data->data as $item): ?>
                            <?php if($item->prd_detail): ?>
                                <?= $item->prd_detail ?>
                            <?php endif; ?>
                                <?php if ($item->from_id == $user_id1): ?>
                                    <div class="incoming_msg">
                                        <div class="received_msg">
                                            <div class="received_withd_msg">
                                                <p><?= $item->message ?></p>
                                            </div>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="outgoing_msg">
                                        <div class="sent_msg">
                                            <p><?= $item->message ?></p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<a href="<?= Url::to(['chat/index']) ?>" class="btn btn-warning">Quay lại</a>