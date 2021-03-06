<?php
/* @var $this \yii\web\View */

/* @var $content string */

use backend\assets\AppAsset;
use backend\modules\auth\components\Helper;
use common\models\Siteinfo;
use common\widgets\Alert;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

//

$siteinfo = Siteinfo::findOne(Siteinfo::ROOT_SITE_ID);
AppAsset::register($this);
$notification = \common\models\NotificationAdmin::findOne(1);
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" type="image/png" href="<?= $siteinfo->favicon ?>"/>
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <script type="text/javascript">
            var baseUrl = '<?= Yii::$app->homeUrl ?>';
        </script>
        <style type="text/css">
            .view-web {
                background: #73879C;
            }

            body .view-web span {
                color: #fff;
            }

            .index {
                position: absolute;
                right: 1px;
                top: 0px;
                display: inline-block;
                background: red;
                padding: 0px 6px;
                font-weight: bold;
                color: #fff;
                border: 1px solid red;
                border-radius: 50%;
            }

            .go-back {
                color: #fff;
                display: inline-block;
                margin-left: 22px;
                padding: 8px 30px;
                background: #bfca59;
                border-radius: 4px;
            }
        </style>
        <?php $this->head() ?>
    </head>

    <body class="nav-md" ng-app="backendNgApp">
    <?php $this->beginBody() ?>
    <div class="alert">
        <?= $this->render('partial/alert') ?>

    </div>
    <div class="container body">
        <div class="main_container">
            <!--COL LEFT-->
            <div class="col-md-3 left_col">
                <div class="left_col scroll-view">
                    <div class="navbar nav_title" style="border: 0;">
                        <a href="<?php echo Yii::$app->urlManager->baseUrl ?>" class="site_title"><i
                                    class="fa fa-paw"></i> <span>Qu???n tr??? website!</span></a>
                    </div>
                    <div class="clearfix"></div>
                    <!-- menu profile quick info -->
                    <div class="profile">
                        <div class="profile_pic">
                            <i style="font-size: 85px;margin-left: 10px;" class="fa fa-user" aria-hidden="true"></i>
                        </div>
                        <div class="profile_info">
                            <span>Welcome,</span>
                            <h2><?= isset(Yii::$app->user->identity->username) ? Yii::$app->user->identity->username : '' ?></h2>
                        </div>
                    </div>
                    <!-- /menu profile quick info -->
                    <br/>
                    <!-- sidebar menu -->
                    <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                        <div class="menu_section">
                            <h3>&nbsp;</h3>
                            <ul class="nav side-menu">
                                <?php if (Helper::checkRoute('/user/waiting-list/index')) { ?>
                                    <li>
                                        <?= Html::a('<i class="fa fa-calculator"></i>Danh s??ch ch??? kh??m', ['/user/waiting-list/index']) ?>
                                    </li>
                                <?php } ?>
                                <?php if (Helper::checkRoute('/user/user-log/index') || Helper::checkRoute('/user-admin/index') || Helper::checkRoute('/user-admin/doctor') || Helper::checkRoute('/user/user/index')) { ?>
                                    <li>
                                        <a>
                                            <i class="fa fa-users"></i> Qu???n l?? t??i kho???n
                                            <span class="fa fa-chevron-down"></span>
                                            <?= $notification['product'] ? '<span class="index">' . $notification['product'] . '</span>' : '' ?>
                                        </a>
                                        <ul class="nav child_menu">
                                            <?php if (Helper::checkRoute('/user-admin/doctor')) { ?>
                                                <li>
                                                    <?= Html::a('Qu???n l?? b??c s??', ['/user-admin/doctor']) ?>
                                                </li>
                                            <?php } ?>

                                            <?php if (Helper::checkRoute('/user/user/index')) { ?>
                                                <li>
                                                    <?= Html::a('Qu???n l?? b???nh nh??n', ['/user/user/index']) ?>
                                                </li>
                                            <?php } ?>

                                            <?php if (Helper::checkRoute('/user-admin/index')) { ?>
                                                <li>
                                                    <?= Html::a('T??i kho???n qu???n tr???', ['/user-admin/index']) ?>
                                                </li>
                                            <?php } ?>

                                            <?php if (Helper::checkRoute('/user/user-log/index')) { ?>
                                                <li>
                                                    <?= Html::a('L???ch s??? thay ?????i', ['/user/user-log/index']) ?>
                                                </li>
                                            <?php } ?>
                                        </ul>
                                    </li>
                                <?php } ?>

                                <?php if (Helper::checkRoute('/user/factory/index') || Helper::checkRoute('/user/loaimau/index')) { ?>
                                    <li>
                                        <a>
                                            <i class="fa fa-cart-plus"></i> ?????t x?????ng
                                            <span class="fa fa-chevron-down"></span>
                                            <?= $notification['product'] ? '<span class="index">' . $notification['product'] . '</span>' : '' ?>
                                        </a>
                                        <ul class="nav child_menu">
                                            <?php if (Helper::checkRoute('/user/factory/index')) { ?>
                                                <li>
                                                    <?= Html::a('?????t x?????ng', ['/user/factory/index']) ?>
                                                </li>
                                            <?php } ?>

                                            <?php if (Helper::checkRoute('/user/loaimau/index')) { ?>
                                                <li>
                                                    <a href="<?= Url::to(['/user/loaimau/index']) ?>">Lo???i m???u</a>
                                                </li>
                                            <?php } ?>
                                        </ul>
                                    </li>
                                <?php } ?>

                                <?php if (Helper::checkRoute('/product/product-category/index') || Helper::checkRoute('/product/product/index')) { ?>
                                    <li>
                                        <a>
                                            <i class="fa fa-product-hunt"></i> Qu???n l?? th??? thu???t
                                            <span class="fa fa-chevron-down"></span>
                                            <?= $notification['product'] ? '<span class="index">' . $notification['product'] . '</span>' : '' ?>
                                        </a>
                                        <ul class="nav child_menu">
                                            <?php if (Helper::checkRoute('/product/product-category/index')) { ?>
                                                <li><?= Html::a('Nh??m th??? thu???t', ['/product/product-category/index']) ?></li>
                                            <?php } ?>

                                            <?php if (Helper::checkRoute('/product/product/index')) { ?>
                                                <li data="<?= Url::to(['/site/notification-update', 'attr' => 'product']) ?>">
                                                    <a href="<?= Url::to(['/product/product/index']) ?>">Th??? thu???t</a>
                                                    <?= $notification['product'] ? '<span class="index">' . $notification['product'] . '</span>' : '' ?>
                                                </li>
                                            <?php } ?>
                                        </ul>
                                    </li>
                                <?php } ?>

                                <?php if (Helper::checkRoute('/medicine/medicine-category/index') || Helper::checkRoute('/medicine/medicine/index')) { ?>
                                    <li>
                                        <a>
                                            <i class="fa fa-product-hunt"></i> Thu???c - Thi???t b???
                                            <span class="fa fa-chevron-down"></span>
                                        </a>
                                        <ul class="nav child_menu">
                                            <?php if (Helper::checkRoute('/medicine/medicine-category/index')) { ?>
                                                <li><?= Html::a('Danh m???c', ['/medicine/medicine-category/index']) ?></li>
                                            <?php } ?>

                                            <?php if (Helper::checkRoute('/medicine/medicine/index')) { ?>
                                                <li>
                                                    <a href="<?= Url::to(['/medicine/medicine/index']) ?>">Thu???c - Thi???t
                                                        b???</a>
                                                </li>
                                            <?php } ?>
                                        </ul>
                                    </li>
                                <?php } ?>

                                <?php if (Helper::checkRoute('/user/medical-record/index')) { ?>
                                    <li>
                                        <?= Html::a('<i class="fa fa-product-hunt"></i>H??? s?? b???nh ??n', ['/user/medical-record/index']) ?>
                                    </li>
                                <?php } ?>

                                <?php if (Helper::checkRoute('/hsba/medical-record/index')) { ?>
                                    <li>
                                        <?= Html::a('<i class="fa fa-product-hunt"></i>H??? s?? b???nh ??n V2', ['/hsba/medical-record/index']) ?>
                                    </li>
                                <?php } ?>

                                <?php if (Helper::checkRoute('/service/appointment/index')) { ?>
                                    <li>
                                        <?= Html::a('<i class="fa fa-calculator"></i>Danh s??ch l???ch h???n', ['/service/appointment/index']) ?>
                                    </li>
                                <?php } ?>

                                <?php if (Helper::checkRoute('/branch/branch/index')) { ?>
                                    <li>
                                        <?= Html::a('<i class="fa fa-map-marker"></i>Chi nh??nh', ['/branch/branch/index']) ?>
                                    </li>
                                <?php } ?>

                                <?php if (Helper::checkRoute('/commission/commission/index')) { ?>
                                    <li>
                                        <?= Html::a('<i class="fa fa-money"></i>Th???ng k?? hoa h???ng', ['/commission/commission/index']) ?>
                                    </li>
                                <?php } ?>

                                <?php if (Helper::checkRoute('/checklist/checklist/index')) { ?>
                                    <li>
                                        <?= Html::a('<i class="fa fa-hand-paper-o"></i>C??ng vi???c', ['/checklist/checklist/index']) ?>
                                    </li>
                                <?php } ?>

                                <?php if (Helper::checkRoute('/sale/medicine/index') || Helper::checkRoute('/sale/source/index') || Helper::checkRoute('/sale/operation/index') || Helper::checkRoute('/sale/doctor/index') || Helper::checkRoute('/sale/branch/index')) { ?>
                                    <li>
                                        <a>
                                            <i class="fa fa-product-hunt"></i> Th???ng k?? doanh s???
                                            <span class="fa fa-chevron-down"></span>
                                            <?= $notification['product'] ? '<span class="index">' . $notification['product'] . '</span>' : '' ?>
                                        </a>
                                        <ul class="nav child_menu">
                                            <?php if (Helper::checkRoute('/sale/operation/index')) { ?>
                                                <li><?= Html::a('Th??? thu???t', ['/sale/operation/index']) ?></li>
                                            <?php } ?>

                                            <?php if (Helper::checkRoute('/sale/doctor/index')) { ?>
                                                <li><?= Html::a('B??c s??', ['/sale/doctor/index']) ?></li>
                                            <?php } ?>

                                            <?php if (Helper::checkRoute('/sale/medicine/index')) { ?>
                                                <li><?= Html::a('Thu???c', ['/sale/medicine/index']) ?></li>
                                            <?php } ?>

                                            <?php if (Helper::checkRoute('/sale/source/index')) { ?>
                                                <li><?= Html::a('Ngu???n gi???i thi???u', ['/sale/source/index']) ?></li>
                                            <?php } ?>
                                        </ul>
                                    </li>
                                <?php } ?>

                                <?php if (Helper::checkRoute('/sale/kpi/index') || Helper::checkRoute('/sale/sale/index')) { ?>
                                    <li>
                                        <a>
                                            <i class="fa fa-product-hunt"></i> Qu???n l?? KPI
                                            <span class="fa fa-chevron-down"></span>
                                        </a>
                                        <ul class="nav child_menu">
                                            <?php if (Helper::checkRoute('/sale/kpi/index')) { ?>
                                                <li><?= Html::a('Danh s??ch KPI', ['/sale/kpi/index']) ?></li>
                                            <?php } ?>

                                            <?php if (Helper::checkRoute('/sale/sale/index')) { ?>
                                                <li><?= Html::a('Th???ng k?? KPI', ['/sale/sale/index']) ?></li>
                                            <?php } ?>
                                        </ul>
                                    </li>
                                <?php } ?>

                                <?php if (Helper::checkRoute('/thuchi/thuchi/cong-no') || Helper::checkRoute('/thuchi/thuchi/index') || Helper::checkRoute('/thuchi/thuchi-category/index')) { ?>
                                    <li>
                                        <a>
                                            <i class="fa fa-money"></i> Qu???n l?? thu, chi
                                            <span class="fa fa-chevron-down"></span>
                                        </a>
                                        <ul class="nav child_menu">
                                            <?php if (Helper::checkRoute('/thuchi/thuchi-category/index')) { ?>
                                                <li><?= Html::a('Danh m???c', ['/thuchi/thuchi-category/index']) ?></li>
                                            <?php } ?>

                                            <?php if (Helper::checkRoute('/thuchi/thuchi/index')) { ?>
                                                <li><?= Html::a('Danh s??ch thu, chi', ['/thuchi/thuchi/index']) ?></li>
                                            <?php } ?>

                                            <?php if (Helper::checkRoute('/thuchi/thuchi/cong-no')) { ?>
                                                <li><?= Html::a('Qu???n l?? c??ng n???', ['/thuchi/thuchi/cong-no']) ?></li>
                                            <?php } ?>

                                        </ul>
                                    </li>
                                <?php } ?>


                                <?php if (Helper::checkRoute('/banner/banner-group/index') || Helper::checkRoute('/banner/banner/index')) { ?>
                                    <li>
                                        <a><i class="fa fa-file-image-o"></i> Qu???n l?? banner <span
                                                    class="fa fa-chevron-down"></span></a>
                                        <ul class="nav child_menu">
                                            <?php if (Helper::checkRoute('/banner/banner-group/index')) { ?>
                                                <li><?= Html::a('Qu???n l?? nh??m banner', ['/banner/banner-group/index']); ?></li>
                                            <?php } ?>
                                            <?php if (Helper::checkRoute('/banner/banner/index')) { ?>
                                                <li><?= Html::a('Qu???n l?? banner', ['/banner/banner/index']); ?></li>
                                            <?php } ?>
                                        </ul>
                                    </li>
                                <?php } ?>

                                <?php if (Helper::checkRoute('/notifications/notifications/index')) { ?>
                                    <li>
                                        <?= Html::a('<i class="fa fa-bell"></i>T???o th??ng b??o', ['/notifications/notifications/index']) ?>
                                    </li>
                                <?php } ?>

                                <?php if (Helper::checkRoute('/voucher/voucher/index') || Helper::checkRoute('/log/index') || Helper::checkRoute('/siteinfo/index')) { ?>
                                    <li>
                                        <a><i class="fa fa-cog"></i> C???u h??nh website <span
                                                    class="fa fa-chevron-down"></span></a>
                                        <ul class="nav child_menu">
                                            <?php if (Helper::checkRoute('/siteinfo/index')) { ?>
                                                <li><?= Html::a('Th??ng tin c?? b???n', ['/siteinfo/index']); ?></li>
                                            <?php } ?>

                                            <?php if (Helper::checkRoute('/log/index')) { ?>
                                                <li>
                                                    <?= Html::a('Log h??? th???ng', ['/log/index']) ?>
                                                </li>
                                            <?php } ?>

                                            <?php if (Helper::checkRoute('/voucher/voucher/index')) { ?>
                                                <li>
                                                    <?= Html::a('M?? gi???m gi??', ['/voucher/voucher/index']) ?>
                                                </li>
                                            <?php } ?>
                                        </ul>
                                    </li>
                                <?php } ?>

                                <?php if (Helper::checkRoute('/auth')) { ?>
                                    <li>
                                        <?= Html::a('<i class="fa fa-user-secret"></i>Ph??n quy???n', ['/auth']) ?>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                    <!-- /sidebar menu -->
                </div>
            </div>

            <!--TOP NAVIGATION-->
            <div class="top_nav">
                <div class="nav_menu">
                    <nav>
                        <div class="nav toggle">
                            <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                        </div>

                        <ul class="nav navbar-nav navbar-right">
                            <li class="">
                                <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown"
                                   aria-expanded="false">
                                    <!--<img src="images/img.jpg" alt="">-->
                                    <?= isset(Yii::$app->user->identity->username) ? Yii::$app->user->identity->username : '' ?>
                                    <span class=" fa fa-angle-down"></span>
                                </a>
                                <ul class="dropdown-menu dropdown-usermenu pull-right">
                                    <li>
                                        <?= Html::a('<i class="fa fa-sign-out pull-right"></i>????ng xu???t', ['/site/logout']); ?>
                                    </li>
                                </ul>
                            </li>
                            <li class="view-web">
                                <a class="blue" target="_blank" title="Xem website"
                                   href="<?= \yii\helpers\Url::to(Yii::$app->urlManagerFrontEnd->createUrl([''])) ?>"><span>Xem website</span></a>
                            </li>
                            <script>
                                let $notifyUrl = '<?= Url::to(['/notify/notify/get-notify']) ?>';
                                let $changeNotifyStatusUrl = '<?= Url::to(['/notify/notify/change-notify-status']) ?>';
                            </script>
                            <li role="presentation" class="nav-item dropdown" ng-controller="ngNotifyController">
                                <a href="javascript:;" class="dropdown-toggle info-number" id="navbarDropdown1"
                                   data-toggle="dropdown" aria-expanded="false">
                                    <i class="fa fa-bell-o"></i>
                                    <span ng-cloak ng-if="controllerData.unreadCount" class="badge bg-green">{{ controllerData.unreadCount }}</span>
                                </a>
                                <ul class="dropdown-menu list-unstyled msg_list" role="menu"
                                    aria-labelledby="navbarDropdown1" x-placement="bottom-start">
                                    <li ng-if="controllerData.noNotify" class="nav-item">
                                        Kh??ng c?? th??ng b??o m???i
                                    </li>
                                    <li class="nav-item {{ notify.created_at }}"
                                        ng-repeat="notify in controllerData.notify | limitTo:controllerData.limit">
                                        <a class="dropdown-item {{ notify.status ? 'read' : 'unread' }}"
                                           ng-click="controllerFunction.goTo(notify.link ? notify.link : '', notify.id)">
                                            <div style="position: relative">
                                                <span style="display: block; position: relative">
                                                    <i ng-if="!notify.status" class="fa fa-dot-circle-o text-primary mr-2"></i>
                                                    {{ notify.name }}
                                                </span>
                                                <span style="display: inline-block; position: relative; margin: 0; padding: 0; left: unset" class="time {{ notify.created_at }}">
                                                    <i class="fa fa-clock-o"></i>
                                                    {{ notify.created_at ? controllerFunction.timeAgo(notify.created_at) : '' }}
                                                </span>
                                            </div>
                                            <span class="message">
                                                {{ notify.description }}
                                            </span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <div class="text-center">
                                            <a class="dropdown-item" href="<?= Url::to(['/notify/notify/index']) ?>">
                                                <strong>See All Alerts</strong>
                                                <i class="fa fa-angle-right"></i>
                                            </a>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>

            <!--COL RIGHT-->
            <!-- page content -->
            <div class="right_col" role="main">
                <div class="row">
                    <?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],]) ?>
                    <?= Alert::widget() ?>
                </div>
                <div class="row">
                    <?= $content ?>
                </div>
            </div>
        </div>
    </div>
    <?php $this->endBody() ?>
    <script type="text/javascript">
        $(document).ready(function () {
            $('.index').parent().click(function () {
                $(this).find('.index').css('display', 'none');
                // if ($(this).attr('data')) {
                //     $.getJSON(
                //             $(this).attr('data'),
                //             ).done(function (data) {
                //         if (data != '1') {
                //             console.log('c?? l???i');
                //         }
                //     }).fail(function (jqxhr, textStatus, error) {
                //         console.log('c?? l???i');
                //     });
                // }
            })
        });
    </script>
    </body>

    </html>
<?php $this->endPage() ?>