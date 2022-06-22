<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Chỉ số KPI';
$this->params['breadcrumbs'][] = $this->title; ?>
<script>
    $kpis = JSON.parse('<?= json_encode($kpis) ?>');
    $storeKPI = '<?= Url::to(['/sale/kpi/store-kpi']) ?>';
    $deleteKPI = '<?= Url::to(['/sale/kpi/delete-kpi']) ?>';
</script>
<script type="text/ng-template" id="pagination.html">
    <div class="pagination">
        <ul ng-if="pager.pages.length > 1" class="pager" style="margin: 0">
            <li ng-if="!(pager.currentPage === 1)" ng-class="{disabled:pager.currentPage === 1}">
                <a ng-click="setPage(1)"><i class="fa fa-angle-double-left"></i></a>
            </li>
            <li ng-if="!(pager.currentPage === 1)" ng-class="{disabled:pager.currentPage === 1}">
                <a ng-click="setPage(pager.currentPage - 1)"><i class="fa fa-angle-left"></i></a>
            </li>
            <li ng-repeat="page in pager.pages" ng-class="{active:pager.currentPage === page}">
                <a ng-click="setPage(page)">{{page}}</a>
            </li>
            <li ng-if="!(pager.currentPage === pager.totalPages)"
                ng-class="{disabled:pager.currentPage === pager.totalPages}">
                <a ng-click="setPage(pager.currentPage + 1)"><i class="fa fa-angle-right"></i></a>
            </li>
            <li ng-if="!(pager.currentPage === pager.totalPages)"
                ng-class="{disabled:pager.currentPage === pager.totalPages}">
                <a ng-click="setPage(pager.totalPages)"><i class="fa fa-angle-double-right"></i></a>
            </li>
        </ul>
    </div>
</script>
<div class="sale-index" ng-controller="ngKPIController">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title d-flex">
                    <h2>
                        <i class="fa fa-bar-chart-o" style="margin-right: 10px"></i><?= Html::encode($this->title) ?>
                    </h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="col-md-6 col-sm-6 col-xs-12" ng-cloak>
                        <h4>{{ controllerData.kpi.id ? 'Sửa' : 'Thêm' }} chỉ số KPI</h4>
                        <div ng-if="controllerData.alertMessage"
                             class="alert {{ controllerData.alert }} alert-dismissible " role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                        aria-hidden="true">×</span>
                            </button>
                            {{ controllerData.alertMessage }}
                        </div>
                        <input type="hidden" ng-model="controllerData.kpi.id" id="id" class="form-control">
                        <div class="form-group">
                            <label for="name">Tên chỉ số</label>
                            <input type="text" ng-model="controllerData.kpi.name" id="name" class="form-control"
                                   required>
                        </div>
                        <div class="form-group">
                            <label>
                                <input type="checkbox" ng-model="controllerData.kpi.in_system"/> Chỉ số thông kê từ hệ
                                thống
                            </label>
                            <div><small>Thông số này lấy dữ liệu tự động từ hệ thống</small></div>
                        </div>
                        <div class="form-group">
                            <label for="dinh_muc_khoan">Định mức khoán</label>
                            <input type="number" ng-model="controllerData.kpi.dinh_muc_khoan" id="dinh_muc_khoan"
                                   min="0" max="999999999999999" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Mô tả</label>
                            <textarea class="form-control" ng-model="controllerData.kpi.description" id="description"
                                      cols="30" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-primary" ng-click="controllerFunction.storeKPI()">
                                <i class="fa fa-spinner fa-spin" ng-if="controllerData.loading"></i>
                                Lưu lại
                            </button>
                            <button class="btn btn-info" ng-click="controllerFunction.resetKpi()">
                                Đặt lại
                            </button>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12" ng-disabled="controllerData.loading" ng-cloak>
                        <h4>Danh sách chỉ số KPI</h4>
                        <div ng-if="controllerData.tableAlertMessage"
                             class="alert {{ controllerData.tableAlert }} alert-dismissible " role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                        aria-hidden="true">×</span>
                            </button>
                            {{ controllerData.tableAlertMessage }}
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped jambo_table">
                                <thead>
                                <tr class="headings bg-dark">
                                    <th class="column-title">Tên chỉ số</th>
                                    <th class="column-title">Định mức khoán</th>
                                    <th class="column-title">Mô tả</th>
                                    <th class="column-title no-link last">
                                        <span>Hành động</span>
                                    </th>
                                </tr>
                                </thead>

                                <tbody>
                                <tr class="{{ $index % 2 === 1 ? 'even' : 'odd' }} pointer"
                                    ng-repeat="kpi in controllerData.listKPIs track by $index">
                                    <td class="">{{ kpi.name }}</td>
                                    <td class="">{{ controllerFunction.formatMoney(kpi.dinh_muc_khoan, 0) }}</td>
                                    <td class="">{{ kpi.description }}</td>
                                    <td class="last" style="white-space: nowrap; text-align: right">
                                        <a href="#" ng-click="controllerFunction.setSelectedKPI(kpi)"
                                           class="btn btn-primary">
                                            <i class="fa fa-pencil"></i>
                                        </a>
                                        <a ng-click="controllerFunction.deleteKPI(kpi)"
                                           ng-if="kpi.in_system === '0' || !kpi.in_system"
                                           class="btn btn-danger">
                                            <i class="fa {{ !controllerData.tableLoading ? 'fa-trash' : 'fa-spinner fa-spin' }} "></i>
                                        </a>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <paging-control paging-size="5" noofitem="controllerData.dataPerPage"
                                            total-items="controllerData.kpis"
                                            display-items="controllerData.listKPIs"></paging-control>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
