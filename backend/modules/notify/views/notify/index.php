<?php

use yii\helpers\Html;

$this->title = 'Thông báo';
$this->params['breadcrumbs'][] = $this->title; ?>
<style>
    .list-unstyled.timeline .block {
        margin-left: 0;
        text-align: left;
    }

    .list-unstyled.timeline .unread .block {
        background-color: #f9f9f9;
        cursor: pointer;
    }

    .list-unstyled.timeline .unread .block h2.title:before {
        background-color: #0DA600;
    }
</style>
<div class="notify-index" ng-controller="ngNotifyController">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_content">
                        <div class="x_content">
                            <ul class="list-unstyled timeline">
                                <li ng-repeat="notify in controllerData.notify" class="{{ notify.status ? 'read' : 'unread' }}"  ng-click="controllerFunction.goTo(notify.link ? notify.link : '', notify.id)">
                                    <div class="block">
                                        <div class="block_content">
                                            <h2 class="title">
                                                <a>{{ notify.name }}</a>
                                            </h2>
                                            <div class="byline">
                                                <span>{{ notify.created_at ? controllerFunction.timeAgo(notify.created_at) : '' }}</span>
                                            </div>
                                            <p class="excerpt">
                                                {{ notify.description }}
                                            </p>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
