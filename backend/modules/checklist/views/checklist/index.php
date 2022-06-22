<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Công việc';
$this->params['breadcrumbs'][] = $this->title; ?>
<script>
    let $currentUser = <?= Yii::$app->user->getId() ?>;
    let $statuses = <?= json_encode($statuses) ?>;
    let $checklist = <?= json_encode($checklist) ?>;
    let $userPosition = <?= json_encode($userPosition) ?>;
    let $checklistPermission = <?= json_encode($permissions) ?>;
    let $checklistBranchs = <?= json_encode($branchs) ?>;
    let $storeCheckListUrl = '<?= Url::to(['/checklist/checklist/create-checklist-item']) ?>';
    let $deleteCheckListUrl = '<?= Url::to(['/checklist/checklist/delete-checklist-item']) ?>';
    let $getUserByRoleUrl = '<?= Url::to(['/user/user/get-all-user-has-roles']) ?>';
    let $updateCheckListStatusUrl = '<?= Url::to(['/checklist/checklist/change-checklist-item-status']) ?>';
    let _csrf = '<?= Yii::$app->request->getCsrfToken() ?>';
</script>
<style>
    .mr-2 {
        margin-right: calc(1rem / 3 * 2);
    }

    li.ui-sortable-placeholder {
        display: none !important;
        visibility: hidden;
        opacity: 0;
    }

    .uib-datepicker button {
        border: 0;
        outline: 0;
    }

    .list-unstyled.msg_list {
        min-height: 65px;
    }

    .list-unstyled.msg_list > li {
        cursor: pointer;
    }

    .list-unstyled.msg_list.empty {
        border: 1px dashed #999;
        border-radius: 10px;
        position: relative;
    }

    .list-unstyled.msg_list.empty::before {
        position: absolute;
        content: "Kéo thả việc làm vào đây!";
        top: 50%;
        left: 50%;
        color: #999;
        font-size: 14px;
        transform: translate(-50%, -50%);
        text-align: center;
    }

    .list-unstyled.msg_list > li > a {
        width: 100%;
    }

    .list-unstyled.msg_list > li > a > span {
        display: -webkit-box !important;
        width: 100%;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .list-unstyled.msg_list > li > a > span > strong {
        display: block;
    }

    .list-unstyled.msg_list li a .time {
        display: inline-block;
        position: unset;
    }

    .input-group {
        z-index: 0;
    }

    .input-group .form-control.ipdate,
    .input-group .input-group-btn {
        z-index: 0;
    }

    .ui-select-container .ui-select-dropdown {
        max-height: 200px;
        overflow: auto;
    }
</style>
<div class="checklist-index">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel" ng-controller="checkListController">
                <script type="text/ng-template" id="storeCheckListModal.html">
                    <div class="modal-header">
                        <h3 class="modal-title" id="modal-title"><i class="fa fa-check-square-o mr-2"></i>{{
                            $ctrl.action === 'create' ? 'Thêm việc cần làm mới'
                            : 'Sửa công việc #'+ $ctrl.checklistsItem.id }}</h3>
                    </div>
                    <div class="modal-body" id="modal-body"
                         ng-disabled="$ctrl.permissions.canCreate || $ctrl.permissions.canUpdate">
                        <input type="hidden" ng-model="checklistsItem.id">
                        <div class="form-row row">
                            <div class="form-group col-md-12 col-xs-12 col-sm-12">
                                <label for="name">Tiêu đề</label>
                                <input id="name" class="form-control" type="text" ng-model="$ctrl.checklistsItem.name"
                                       placeholder="Tiêu đề">
                            </div>
                            <div class="form-group col-md-12 col-xs-12 col-sm-12">
                                <label for="description">Mô tả công việc</label>
                                <textarea id="description" class="form-control" type="text"
                                          ng-model="$ctrl.checklistsItem.description" placeholder="Mô tả" cols="30"
                                          rows="3"></textarea>
                            </div>
                            <div class="form-group col-md-12 col-xs-12 col-sm-12">
                                <div><label for="attachments">Tệp đính kèm <span>({{ $ctrl.attachmentsUploaded.length }} file)</span></label>
                                </div>
                                <label for="attachments" class="box-upload boxuploadfile">
                                    <div class="btn btn-success fileinput-button">
                                        <i class="fa fa-cloud-upload"></i>
                                        <span class="">Thêm tệp đính kèm</span>
                                        <input style="display: none" custom-file-input
                                               ng-change="$ctrl.functions.previewAttachments()"
                                               ng-model="$ctrl.attachments" id="attachments" type="file" multiple="true"
                                               name="attachments">
                                    </div>
                                </label>
                                <small style="display: block">Các định dạng file hợp lệ: .doc, .docx, .pdf, .txt, .csv,
                                    .xls, .xlsb, .xlsx</small>
                                <ul class="attachments_preview_zone" style="margin-top: 6px">
                                    <li ng-repeat="attachment in $ctrl.attachmentsUploaded" class="p_attachment"
                                        style="background-color: #dbdbdb61;padding: 5px 10px;border-bottom: 1px solid #fff;">
                                        <a href="/static{{ attachment.path }}{{ attachment.name }}" class="image">
                                            <span class="fa fa-download"></span>
                                            <span class="exte">{{ attachment.display_name }}</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="form-group col-md-12 col-xs-12 col-sm-12">
                                <label for="links">Link đính kèm</label>
                                <div class="input-group">
                                    <input id="links" class="form-control" type="text" ng-model="$ctrl.link"
                                           placeholder="Link đính kèm">
                                    <span class="input-group-btn">
                                        <button style="margin: 0" ng-click="$ctrl.functions.addLink()" type="button"
                                                class="btn btn-success"><i class="fa fa-plus"></i></button>
                                    </span>
                                </div>
                                <div class="links-container" ng-if="$ctrl.checklistsItem.links">
                                    <div ng-repeat="link in $ctrl.checklistsItem.links track by $index"
                                         class="input-group" style="margin-bottom: 3px">
                                        <input disabled class="form-control" type="text" value="{{ link }}"
                                               placeholder="Tiêu đề" autocomplete="off">
                                        <span class="input-group-btn">
                                            <button style="margin: 0"
                                                    ng-click="$ctrl.functions.copyLink($event, $index)" type="button"
                                                    class="btn btn-primary"><i class="fa fa-copy"></i></button>
                                            <button style="margin: 0" ng-click="$ctrl.functions.removeLink($index)"
                                                    type="button" class="btn btn-danger"><i
                                                        class="fa fa-close"></i></button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-12 col-xs-12 col-sm-12">
                                <label for="loop">Tần suất</label>
                                <select id="loop" class="form-select form-control" ng-model="$ctrl.checklistsItem.loop">
                                    <option value="" selected>Lặp lại</option>
                                    <option value="daily">Mỗi ngày</option>
                                    <option value="weekly">Mỗi tuần</option>
                                    <option value="monthly">Mỗi tháng</option>
                                    <option value="yearly">Mỗi năm</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6 col-xs-12 col-sm-6" ng-if="$ctrl.checklistsItem.loop">
                                <label for="start_at">Bắt đầu lúc</label>
                                <div class="input-group">
                                    <input id="start_at" class="form-control ipdate" type="text" uib-datepicker-popup
                                           datepicker-options="$ctrl.dateOptions"
                                           is-open="$ctrl.datepickerPopup.popupStartAt.opened "
                                           ng-model="$ctrl.checklistsItem.start_at" placeholder="Bắt đầu">
                                    <span class="input-group-btn" ng-click="$ctrl.functions.openDatepickerPopup(1)">
                                        <button type="button" class="btn btn-default"><i
                                                    class="fa fa-calendar"></i></button>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group col-md-6 col-xs-12 col-sm-6" ng-if="$ctrl.checklistsItem.loop">
                                <label for="end_at">Kết thúc lúc</label>
                                <div class="input-group">
                                    <input id="end_at" class="form-control ipdate" type="text" uib-datepicker-popup
                                           datepicker-options="{ minDate: $ctrl.checklistsItem.start_at }"
                                           is-open="$ctrl.datepickerPopup.popupEndAt.opened "
                                           ng-model="$ctrl.checklistsItem.end_at" placeholder="Kết thúc">
                                    <span class="input-group-btn" ng-click="$ctrl.functions.openDatepickerPopup(2)">
                                        <button type="button" class="btn btn-default"><i
                                                    class="fa fa-calendar"></i></button>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group col-md-6 col-xs-12 col-sm-6">
                                <label for="priority">Mức độ ưu tiên</label>
                                <select id="status" class="form-select form-control"
                                        ng-model="$ctrl.checklistsItem.priority">
                                    <option ng-repeat="(key, priority) in $ctrl.priorityLevels" value="{{ key }}">{{
                                        priority }}
                                    </option>
                                </select>
                            </div>
                            <div class="form-group col-md-6 col-xs-12 col-sm-6">
                                <label for="status">Trạng thái</label>
                                <select id="status" class="form-select form-control"
                                        ng-model="$ctrl.checklistsItem.status"
                                        ng-change="$ctrl.functions.statusChanged()">
                                    <option ng-repeat="(key, status) in $ctrl.statuses" value="{{ key }}">{{ status }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row row">
                            <div class="form-group col-md-6 col-xs-12 col-sm-6">
                                <label for="incharge_by">Người phụ trách</label>
                                <ui-select ng-change="$ctrl.functions.inchargeByChanged($select.selected.id)"
                                           ng-model="$ctrl.checklistsItem.incharge_by" theme="select2"
                                           class="form-control" title="Người chịu trách nhiệm">
                                    <ui-select-match placeholder="Chọn hoặc tìm kiếm trong danh sách...">{{
                                        $select.selected.fullname }}
                                    </ui-select-match>
                                    <ui-select-choices
                                            repeat="person.id as person in $ctrl.incharged_users | filter: { fullname: $select.search }">
                                        <small>ID: {{ person.id }} - {{ $ctrl.userPosition[person.vai_tro] }}</small>
                                        <div ng-bind-html="person.fullname | highlight: $select.search"></div>
                                    </ui-select-choices>
                                </ui-select>
                            </div>
                            <div class="form-group col-md-6 col-xs-12 col-sm-6"
                                 ng-if="$ctrl.checklistsItem.incharge_by">
                                <label for="handled_by">Người thực hiện</label>
                                <ui-select multiple
                                           ng-model="$ctrl.checklistsItem.handled_by" theme="bootstrap"
                                           close-on-select="false" title="Người thực hiện">
                                    <ui-select-match placeholder="Chọn người thực hiện...">{{ $item.fullname }}
                                    </ui-select-match>
                                    <ui-select-choices
                                            repeat="person.id as person in $ctrl.handled_users | propsFilter: { fullname: $select.search }">
                                        <small>ID: {{ person.id }} - {{ $ctrl.userPosition[person.vai_tro] }}</small>
                                        <div ng-bind-html="person.fullname | highlight: $select.search"></div>
                                    </ui-select-choices>
                                </ui-select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button ng-if="$ctrl.action === 'create'" class="btn btn-primary" type="button"
                                ng-disabled="!$ctrl.permissions.canCreate" ng-click="$ctrl.functions.store()">
                            Thêm mới
                        </button>
                        <button ng-if="$ctrl.action !== 'create'" class="btn btn-primary" type="button"
                                ng-disabled="!$ctrl.permissions.canUpdate" ng-click="$ctrl.functions.store()">
                            Cập nhật
                        </button>
                        <button ng-if="$ctrl.action === 'update'" class="btn btn-danger" type="button"
                                ng-disabled="!$ctrl.permissions.canDelete" ng-click="$ctrl.functions.delete()">
                            Xóa
                        </button>
                        <button class="btn btn-warning" type="button" ng-click="$ctrl.functions.cancel()">Đóng</button>
                    </div>
                </script>
                <div class="x_title d-flex">
                    <h2><i class="fa fa-check-square-o mr-2"></i><?= Html::encode($this->title) ?></h2>
                    <button style="margin-bottom: 0" class="btn btn-primary btn-sm ml-auto"
                            ng-disabled="!controllerData.permissions.canCreate"
                            ng-click="controllerFunction.openStoreChecklistModal('create')">
                        <i class="fa fa-plus"></i> Thêm mới
                    </button>
                    <select class="form-control" ng-if="controllerData.permissions.canViewAll"
                            ng-model="controllerData.branch" style="max-width: 200px"
                            ng-change="controllerFunction.branchChanged()">
                        <option value="">Chọn chi nhánh</option>
                        <option value="{{ key }}" ng-repeat="(key, branch) in controllerData.checklistBranchs">{{
                            branch }}
                        </option>
                    </select>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="row" ng-cloak ng-if="controllerData.checklists">
                        <div class="col-lg-3 col-sm-6 col-xs-12 checklist-column {{ key }}"
                             ng-repeat="(key, status) in controllerData.checkListStatus">
                            <div class="x_panel">
                                <div class="x_title">
                                    <h2>{{ status }}</h2>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="x_content">
                                    <ul class="{{ key === 'complete' && !controllerData.permissions.canApproval ? '' : 'msg-list-droppable' }} {{ controllerData[key+'Checklists'].length ? '' : 'empty' }} list-unstyled msg_list {{ key }}"
                                        ui-sortable="controllerFunction.draggable"
                                        ng-model="controllerData[key+'Checklists']" data-type="{{ key }}">
                                        <li ng-repeat="checklist in controllerData[key+'Checklists']"
                                            data-id="{{ checklist.id }}">
                                            <a ng-click="controllerFunction.openStoreChecklistModal('update', checklist.id)">
                                                <span>
                                                    <strong>{{ checklist.name }}</strong>
                                                    <span class="time">
                                                        <i class="fa fa-clock-o"></i>
                                                        {{ checklist.updated_at ? controllerFunction.timeAgo(checklist.updated_at) : '' }}
                                                    </span>
                                                </span>
                                                <span class="message">Ưu tiên: {{ controllerData.priorityLevels[checklist.priority] }} - {{ checklist.description }}</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
