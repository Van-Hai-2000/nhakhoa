<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Thống kê KPI';
$this->params['breadcrumbs'][] = $this->title; ?>
<script>
    let $employs = '<?= json_encode($employs) ?>';
    let $kpis = '<?= json_encode($kpis) ?>';
    let $departments = '<?= json_encode($departments) ?>';
    let $branchs = '<?= json_encode($branchs) ?>';
    let $getUsersUrl = '<?= Url::to(['/user/user/get-all-user-has-roles']) ?>';
    let $getPersonalKpiInfoUrl = '<?= Url::to(['/sale/sale/get-personal-kpi-info']) ?>';
    let $getKpiStatisticalUrl = '<?= Url::to(['/sale/sale/get-kpi-statistical']) ?>';
    let $storeKpiUrl = '<?= Url::to(['/sale/sale/store-kpi']) ?>';
    let $kpiPermissions = JSON.parse('<?= json_encode($permissions) ?>');
</script>
<style>
    .line-chart-container .list-unstyled > li {
        opacity: .7;
    }

    .line-chart-container .list-unstyled > li.active {
        opacity: 1;
    }

    .table-responsive.kpis-table {
        display: block;
        border: 1px solid #ccc;
        border-bottom: unset;
    }

    .table-responsive.kpis-table .table-head {
        background-color: #337ab7;
        color: #fff;
    }

    .table-responsive.kpis-table .table-head,
    .table-responsive.kpis-table .table-body {
        width: 100%;
    }

    .table-responsive.kpis-table .table-head,
    .table-responsive.kpis-table .table-body .table-body-row {
        display: flex;
        flex-wrap: wrap;
    }

    .table-responsive.kpis-table .table-body .table-body-row.active {
        background-color: rgba(234, 234, 234, .25);
    }

    .table-responsive.kpis-table .table-head > div.column-title {
        font-weight: bold;
        color: #fff;
    }

    .table-responsive.kpis-table .table-body .table-body-row > div.column-title > span:first-child {
        display: none;
    }

    .table-responsive.kpis-table .table-head > div.column-title,
    .table-responsive.kpis-table .table-body .table-body-row > div.column-title {
        flex: 1 1 10%;
        max-width: 10%;
        display: inline-block;
        padding: 10px;
        border-bottom: 1px solid #ccc;
        font-size: 14px;
    }

    .table-responsive.kpis-table .table-head > div.column-title + div.column-title,
    .table-responsive.kpis-table .table-body .table-body-row > div.column-title + div.column-title {
        border-left: 1px solid #ccc;
    }

    .table-responsive.kpis-table .table-body .table-body-row > .table-body-row-form {
        flex-basis: 100%;
        padding-top: 15px;
        border-bottom: 1px solid #ccc;
    }

    @media screen and (max-width: 1200px) {
        .table-responsive.kpis-table .table-head {
            display: none;
        }

        .table-responsive.kpis-table .table-body .table-body-row > div.column-title > span {
            display: inline-block;
            max-width: 30%;
        }

        .table-responsive.kpis-table .table-body .table-body-row > div.column-title {
            flex-basis: 50%;
            max-width: 50%;
        }

        .table-responsive.kpis-table .table-body .table-body-row > div.column-title + div.column-title {
            border-left: unset;
        }

        .table-responsive.kpis-table .table-body .table-body-row > div.column-title:nth-child(odd) {
            border-right: 1px solid #ccc;
        }
    }

    @media screen and (max-width: 576px) {
        .table-responsive.kpis-table .table-body .table-body-row > div.column-title {
            flex-basis: 100%;
            max-width: 100%;
        }

        .table-responsive.kpis-table .table-body .table-body-row > div.column-title:nth-child(odd) {
            border-right: unset;
        }

        .table-responsive.kpis-table .table-body .table-body-row > div.column-title > span {
            width: 35%;
        }
    }

    #chart #line-chart-container-info .list-unstyled > li.active > a {
        color: #fff;
        background-color: #286090;
        border-color: #204d74;
    }

    .chart_times .select2-selection__choice__display {
        padding-left: 16px !important;
    }

    .chart_times .select2-selection__choice__remove {
        height: 100%;
    }
</style>
<div class="sale-index" ng-controller="ngSaleController">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title d-flex">
                    <h2><i class="fa fa-bar-chart-o" style="margin-right: 10px"></i><?= Html::encode($this->title) ?>
                    </h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="row">
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label for="select2_branch">Chi nhánh</label>
                                <select ng-change="controllerFunction.onSelectedEmployChanged('branch')"
                                        ng-model="controllerData.selected_branch"
                                        class="form-control form-select select2" id="select2_branch">
                                    <option value="" selected>Chọn chi nhánh</option>
                                    <option value="{{ key }}" ng-repeat="(key, branch) in controllerData.branchs">{{
                                        branch }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label for="select2_department">Vị trí</label>
                                <select ng-change="controllerFunction.onSelectedEmployChanged('department')"
                                        ng-model="controllerData.selected_department"
                                        class="form-control form-select select2" id="select2_department">
                                    <option value="" selected>Chọn vị trí</option>
                                    <option value="{{ key }}"
                                            ng-repeat="(key, department) in controllerData.departments">{{ department }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label for="select2_employ">Nhân sự</label>
                                <select ng-change="controllerFunction.onSelectedEmployChanged('employ')"
                                        ng-model="controllerData.selected_employ"
                                        class="form-control form-select select2" id="select2_employ">
                                    <option value="" selected>Chọn nhân sự</option>
                                    <option value="{{ employ.id }}" ng-repeat="employ in controllerData.employs">{{
                                        employ.fullname }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12" ng-show="controllerData.currentTab === 'personal_kpi'">
                            <label for="">Thời gian</label>
                            <div class="form-group row">
                                <div class="col-md-6 col-sm-12">
                                    <select ng-change="controllerFunction.onSelectedEmployChanged('month')"
                                            class="form-control form-control-date month form-select select2"
                                            ng-model="controllerData.selectedMonth">
                                        <option value="" selected>Chọn tháng</option>
                                        <option ng-repeat="month in controllerData.month track by $index"
                                                value="{{ month }}">Tháng {{ month }}
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <select ng-change="controllerFunction.onSelectedEmployChanged('year')"
                                            class="form-control form-control-date form-select select2"
                                            ng-model="controllerData.selectedYear">
                                        <option value="" selected>Năm</option>
                                        <option ng-repeat="year in controllerData.year track by $index"
                                                value="{{ year }}">{{ year }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12" ng-if="!controllerData.selected_employ" ng-cloak><h4>Vui lòng chọn nhân sự!</h4>
                        </div>
                        <div class="col-md-12" ng-if="controllerData.selected_employ" ng-cloak>
                            <ul class="nav nav-tabs bar_tabs" id="myTab" role="tablist">
                                <li class="nav-item {{ controllerData.selected_employ ? 'active' : '' }}"
                                    ng-click="controllerFunction.changeTab('personal_kpi')">
                                    <a class="nav-link" id="personal_kpi-tab" data-toggle="tab" href="#personal_kpi"
                                       role="tab" aria-controls="personal_kpi" aria-selected="false">
                                        <strong>Chỉ tiêu KPI</strong>
                                    </a>
                                </li>
                                <li class="nav-item" ng-click="controllerFunction.changeTab('chart')">
                                    <a class="nav-link" id="chart-tab" data-toggle="tab" href="#chart" role="tab"
                                       aria-controls="chart" aria-selected="false">
                                        <strong>Biểu đồ</strong>
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane {{ controllerData.selected_employ ? 'active in' : 'fade' }}"
                                     id="personal_kpi" role="tabpanel" aria-labelledby="personal_kpi-tab">
                                    <div ng-if="controllerData.notifyMessage"
                                         class="alert alert-{{ controllerData.notifyType }} alert-dismissible "
                                         role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                        {{ controllerData.notifyMessage }}
                                    </div>
                                    <div style="display: flex;background-color: #2a3f54;padding: 10px;border: 1px solid #ccc; border-bottom: unset;">
                                        <h4 style="color: #fff" ng-if="controllerData.selected_employ">Nhân sự: <strong>{{
                                                controllerData.employ.fullname }}</strong></h4>
                                        <button class="btn btn-primary" style="margin: 0; margin-left: auto"
                                                ng-click="controllerFunction.addKpiRow()">
                                            <i class="fa fa-plus" style="margin-right: 5px"></i>
                                            Thêm chỉ số
                                        </button>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="table-responsive kpis-table">
                                                <div class="table-head">
                                                    <div class="column-title">Tên chỉ số</div>
                                                    <div class="column-title">Định mức khoán (VNĐ)</div>
                                                    <div class="column-title">Thực tế đạt (VNĐ)</div>
                                                    <div class="column-title">Trọng số</div>
                                                    <div class="column-title">Trừ KPI</div>
                                                    <div class="column-title">% Hoàn thành</div>
                                                    <div class="column-title">Còn thiếu (VNĐ)</div>
                                                    <div class="column-title">Ghi chú vi phạm</div>
                                                    <div class="column-title">Người đánh giá</div>
                                                    <div class="column-title no-link last">Hành động</div>
                                                </div>
                                                <div class="table-body">
                                                    <div class="table-body-row"
                                                         style="border-bottom: 1px solid #ccc; padding: 0 10px; text-align: center"
                                                         ng-if="controllerFunction.isEmpty(controllerData.kpis)">
                                                        <h4>Không có thông tin phù hợp!</h4>
                                                    </div>
                                                    <div ng-if="!controllerFunction.isEmpty(controllerData.kpis)"
                                                         class="table-body-row {{ controllerData.kpiEditingIndex === key ? 'active' : '' }}"
                                                         ng-repeat="(key, kpi) in controllerData.kpis track by key">
                                                        <div class="column-title"><span>Tên chỉ số:</span>
                                                            <span ng-if="kpi.kpi_id">{{ controllerFunction.getKpiAttr(kpi.kpi_id) }}</span>
                                                        </div>
                                                        <div class="column-title"><span>Định mức khoán (VNĐ):</span>
                                                            {{ controllerFunction.formatMoney(kpi.dinh_muc) }}
                                                        </div>
                                                        <div class="column-title"><span>Thực tế đạt (VNĐ):</span>
                                                            {{ controllerFunction.formatMoney(kpi.thuc_dat) }}
                                                        </div>
                                                        <div class="column-title"><span>Trọng số:</span>
                                                            {{ kpi.trong_so }}
                                                        </div>
                                                        <div class="column-title"><span>Trừ KPI:</span>
                                                            {{ kpi.tru_kpi }}
                                                        </div>
                                                        <div class="column-title"><span>% Hoàn thành:</span>
                                                            {{ controllerFunction.calcCompletePercentText(kpi.dinh_muc,
                                                            kpi.thuc_dat, kpi.trong_so, kpi.tru_kpi) }}%
                                                        </div>
                                                        <div class="column-title"><span>Còn thiếu (VNĐ):</span>
                                                            {{
                                                            controllerFunction.formatMoney(controllerFunction.calcConthieuText(kpi.dinh_muc,
                                                            kpi.thuc_dat, kpi.trong_so, kpi.tru_kpi)) }}
                                                        </div>
                                                        <div class="column-title"><span>Ghi chú:</span>
                                                            {{ kpi.ghi_chu }}
                                                        </div>
                                                        <div class="column-title"><span>Người đánh giá:</span>
                                                            <span ng-if="kpi.nguoi_danh_gia">{{ controllerFunction.getEmployAttr(kpi.nguoi_danh_gia, controllerData.incharge_employs) }}</span>
                                                        </div>
                                                        <div class="column-title last">
                                                            <a ng-if="controllerData.kpiEditingIndex !== key"
                                                               class="btn btn-primary"
                                                               ng-click="controllerFunction.editKpiRow(key)"
                                                               title="Sửa"><i class="fa fa-pencil "></i></a>
                                                            <a ng-if="controllerData.kpiEditingIndex !== key"
                                                               class="btn btn-danger"
                                                               ng-click="controllerFunction.deleteKpiRow(key)"
                                                               title="Xóa"><i
                                                                        class="fa {{ controllerData.actionLoading && controllerData.kpiEditingIndex === key ? 'fa-spinner fa-spin' : 'fa-trash' }}"></i></a>
                                                            <a ng-if="controllerData.kpiEditingIndex === key"
                                                               class="btn btn-success"
                                                               ng-click="controllerFunction.storeKpiRow(controllerData.action)"
                                                               title="Lưu"><i
                                                                        class="fa {{ controllerData.actionLoading && controllerData.kpiEditingIndex === key ? 'fa-spinner fa-spin' : 'fa-save' }}"></i></a>
                                                            <a ng-if="controllerData.kpiEditingIndex === key"
                                                               class="btn btn-warning"
                                                               ng-click="controllerFunction.closeKpiRow()"
                                                               title="Đóng"><i class="fa fa-times "></i></a>
                                                        </div>
                                                        <div class="table-body-row-form"
                                                             ng-if="controllerData.kpiEditingIndex === key">
                                                            <div class="form">
                                                                <div class="col-md-2 col-sm-4 col-xs-12">
                                                                    <div class="form-group">
                                                                        <label for="">Chọn chỉ số</label>
                                                                        <select ng-change="controllerFunction.kpiDataChanged(key)"
                                                                                ng-model="controllerData.kpi.kpi_id"
                                                                                class="form-control form-select select2"
                                                                                name="" id="tên chỉ số">
                                                                            <option value="">Chọn chỉ số</option>
                                                                            <option ng-repeat="kaypiai in controllerData.kpisList track by kaypiai.id"
                                                                                    value="{{ kaypiai.id }}">
                                                                                {{ kaypiai.name }}
                                                                            </option>
                                                                        </select>
                                                                        <input type="hidden"
                                                                               ng-model="controllerData.kpi.id">
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="">Người đánh giá</label>
                                                                        <select ng-model="controllerData.kpi.nguoi_danh_gia"
                                                                                class="form-control form-select select2"
                                                                                name="" id="tên chỉ số">
                                                                            <option value="" selected>Chọn nhân sự
                                                                            </option>
                                                                            <option value="{{ employ.id }}"
                                                                                    ng-repeat="(key, employ) in controllerData.incharge_employs">
                                                                                {{ employ.fullname }}
                                                                            </option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2 col-sm-4 col-xs-12">
                                                                    <div class="form-group">
                                                                        <label for="">Định mức khoán</label>
                                                                        <input ng-change="controllerFunction.kpiDataChanged(key)"
                                                                               ng-model="controllerData.kpi.dinh_muc"
                                                                               class="form-control" type="number"
                                                                               min="0" max="999999999999999">
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="">Thực tế đạt</label>
                                                                        <input ng-change="controllerFunction.kpiDataChanged(key)"
                                                                               ng-model="controllerData.kpi.thuc_dat"
                                                                               class="form-control" type="number"
                                                                               min="0" max="999999999999999">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2 col-sm-4 col-xs-12">
                                                                    <div class="form-group">
                                                                        <label for="">Trọng số (%)</label>
                                                                        <input ng-change="controllerFunction.kpiDataChanged(key)"
                                                                               ng-model="controllerData.kpi.trong_so"
                                                                               class="form-control" type="number"
                                                                               min="0" max="100">
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="">Trừ KPI (%)</label>
                                                                        <input ng-change="controllerFunction.kpiDataChanged(key)"
                                                                               ng-model="controllerData.kpi.tru_kpi"
                                                                               class="form-control" type="number"
                                                                               min="0" max="100">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2 col-sm-4 col-xs-12">
                                                                    <div class="form-group">
                                                                        <label for="">Hoàn thành (%)</label>
                                                                        <input ng-model="controllerData.kpi.hoan_thanh"
                                                                               readonly class="form-control" type="text"
                                                                               ng-value="{{ controllerFunction.calcCompletePercentText(kpi.dinh_muc, kpi.thuc_dat, kpi.trong_so, kpi.tru_kpi) }}">
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="">Còn thiếu</label>
                                                                        <input ng-model="controllerData.kpi.con_thieu"
                                                                               readonly class="form-control"
                                                                               ng-value="{{ controllerFunction.calcConthieuText(kpi.dinh_muc, kpi.thuc_dat, kpi.trong_so, kpi.tru_kpi) }}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4 col-sm-8 col-xs-12">
                                                                    <div class="form-group">
                                                                        <label for="">Ghi chú vi phạm</label>
                                                                        <textarea
                                                                                ng-model="controllerData.kpi.ghi_chu"
                                                                                class="form-control"></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="chart" role="tabpanel" aria-labelledby="chart-tab">
                                    <div class="row">
                                        <div class="col-md-8 col-xs-12" id="line-chart-container">
                                            <div style="text-align: center" ng-if="!controllerData.chartInfo.chart_kpi">
                                                <h4>Chọn chỉ số</h4>
                                            </div>
                                            <div ng-if="controllerData.chartInfo.chart_data && controllerData.chartInfo.chart_kpi">
                                                <h4>{{
                                                    controllerData.chartInfo.chart_types[controllerData.chartInfo.chart_type]
                                                    }} thể hiện {{ controllerData.chartInfo.chart_kpi.name | lowercase
                                                    }} {{ controllerFunction.chartLabelsToText() }}</h4>
                                                <canvas id="kpi-chart-base" class="chart-base"
                                                        chart-type="controllerData.chartInfo.chart_type"
                                                        chart-data="controllerData.chartInfo.chart_data"
                                                        chart-labels="controllerData.chartInfo.chart_labels"
                                                        chart-series="controllerData.chartInfo.chart_series">
                                                </canvas>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-xs-12" id="line-chart-container-info"
                                             ng-if="controllerData.chartInfo.chart_data">
                                            <h4>Thông số</h4>
                                            <div class="form-group">
                                                <label for="kpi_index">Chọn loại biểu đồ</label>
                                                <ul class="list-unstyled"
                                                    style="display: flex; justify-content: flex-start; align-items: center; flex-wrap: wrap;">
                                                    <li class="{{ controllerData.chartInfo.chart_type == type ? 'active' : '' }}"
                                                        ng-repeat="(type, name) in controllerData.chartInfo.chart_types">
                                                        <a ng-click="controllerFunction.toggleType(type)"
                                                           class="btn btn-primary">{{ name }}</a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="form-group">
                                                <label for="kpi_index">Chọn chỉ số</label>
                                                <select ng-change="controllerFunction.chooseKPItoGenChart('chiso')"
                                                        ng-model="controllerData.chartInfo.chart_kpi.id"
                                                        class="form-control form-select select2" id="kpi_index">
                                                    <option value="" selected>Chọn chỉ số</option>
                                                    <option ng-repeat="kaypiai in controllerData.kpisList track by kaypiai.id"
                                                            value="{{ kaypiai.id }}">
                                                        {{ kaypiai.name }}
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="form-group chart_times">
                                                <label for="kpi_index">Chọn thời gian</label>
                                                <select ng-change="controllerFunction.chooseKPItoGenChart('thoigian')"
                                                        ng-model="controllerData.chartInfo.chart_labels"
                                                        class="form-control form-select select2" id="chart_times"
                                                        multiple>
                                                    <option value="{{ date }}"
                                                            ng-repeat="date in controllerData.mergedDate">{{ date }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
