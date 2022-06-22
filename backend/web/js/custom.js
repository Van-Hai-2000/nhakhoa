function checkValidate(value, t, message) {
    var check = false;
    if (!value) {
        t.parent().find('.help-block').remove();
        t.parent().append('<div class="help-block">' + message + '</div>');
        check = false;
    } else {
        check = true;
        t.parent().find('.help-block').remove();
    }
    return check;
}

function formatNumber(nStr) {
    nStr += '';
    x = nStr.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    return x1 + x2;
}

function genderTeam(t) {
    var html = '';
    var prd_id = $(t).parents('.prd-row').find('#medicalrecord-product').val();
    if (!prd_id) {
        alert('Bạn phải chọn thủ thuật trước');
        return false;
    }
    $(t).parents('.prd-row').find("#medicalrecord-team option:selected").each(function () {
        var $this = $(this);
        if ($this.length) {
            var selText = $this.text();
            var selVal = $this.val();
            html += '<div class="row prd-team">\n' +
                '        <div class="col-md-3">\n' +
                '            <select class="form-control team_id" name="team_id[' + prd_id + '][]">\n' +
                '                <option value="' + selVal + '" selected>' + selText + '</option>\n' +
                '            </select>\n' +
                '        </div>\n' +
                '        <div class="col-md-3">\n' +
                '            <select class="form-control team_type" name="team_type[' + prd_id + '][]">\n' +
                '                <option value="1" selected>Theo %</option>\n' +
                '                <option value="2">Tiền mặt</option>\n' +
                '            </select>\n' +
                '        </div>\n' +
                '        <div class="col-md-2">\n' +
                '            <input type="text" class="form-control team_commission" name="team_commission[' + prd_id + '][]" value="0" placeholder="Nhập giá trị" required>\n' +
                '        </div>\n' +
                '    </div>';
        }
    });
    $(t).parents('.prd-row').find('.prd-team').remove();
    $(t).parents('.prd-row').append(html);
}

$('#kbsm').on('submit', function (e) {
    var check = true;
    $('.prd-row').each(function () {
        var cm = 0;
        var type1 = false;
        $(this).find('.prd-team').each(function () {
            var $this = $(this);
            if ($this.length) {
                var selType = $this.find('.team_type').val();
                var selVal = $this.find('.team_commission').val();
                if (selType == 1) {
                    cm += parseInt(selVal);
                    type1 = true;
                }

            }
        });

        if (type1) {
            if (cm != 8) {
                alert('Tổng % hoa hồng phải bằng 8');
                check = false;
            }
        }

    });
    if (check == false) {
        return false;
    }
    return true;
});

function show_item(id, t, medical_record_id) {
    $('.item_time').removeClass('active');
    $(t).parent().addClass('active');
    var des = $(t).data('description');
    $('#doctor_note').val(des);
    $.ajax({
        url: '/admin/user/medical-record/load-medical-record-item-child',
        type: 'GET',
        data: {
            id: id,
            medical_record_id: medical_record_id
        },
        success: function (data) {
            $('.content_body').empty().html(data);
            $('#box-append-medicine').empty();
        }
    })
}

//load danh sách đơn thuốc đã kê
function load_donthuoc() {
    var id = $('.list_time').find('li.active').data('id');
    $.ajax({
        url: '/admin/user/medical-record/load-donthuoc',
        type: 'POST',
        data: {
            id: id
        },
        success: function (data) {
            $('.donthuoc_body').empty().html(data)
        }
    })
}

//load hình ảnh bệnh án
function load_image() {
    var id = $('.list_time').find('li.active').data('id');
    $.ajax({
        url: '/admin/user/medical-record/load-image',
        type: 'POST',
        data: {
            id: id
        },
        success: function (data) {
            $('.hinhanh_body').empty().html(data)
        }
    })
}

//Xóa lệnh khám
function deleteItem(t, id, medical_record_id) {
    if (confirm('Bạn chắc chắn muốn xóa lệnh khám này?')) {
        $.ajax({
            url: '/admin/user/medical-record/remove-item',
            type: 'GET',
            data: {
                id: id,
                medical_record_id: medical_record_id
            },
            success: function (data) {
                $(t).parents('tr').remove();
                location.reload();
            }
        })
    }
}

//Hủy lệnh khám
function cancelItem(t, id, medical_record_id) {
    if (confirm('Bạn chắc chắn muốn hủy lệnh khám này?')) {
        $.ajax({
            url: '/admin/user/medical-record/cancel-item',
            type: 'GET',
            data: {
                id: id,
                medical_record_id: medical_record_id
            },
            success: function (data) {
                $(t).parents('tr').remove();
                location.reload();
            }
        })
    }

}

//Load danh sách hoa hồng
function load_commission(item_id, item_child_id, product_id) {
    $.ajax({
        url: '/admin/user/medical-record/load-commission',
        type: 'GET',
        data: {
            item_id: item_id,
            item_child_id: item_child_id,
            product_id: product_id
        },
        success: function (data) {
            $('.commission_body').empty().html(data)
        }
    })
}

//Cập nhật hoa hồng
function update_commission(item_id, item_child_id) {
    var check_update = checkUpdateCommission();
    if (!check_update) {
        return false;
    }
    $.ajax({
        url: '/admin/user/medical-record/update-commission',
        type: 'POST',
        data: $('#form_update_commission').serialize(),
        success: function (data) {
            alert('Chỉnh sửa hoa hồng thành công');
            setTimeout(function () {
                window.location.reload()
            }, 2000);
        }
    })
}

//Add thêm người hưởng hoa hồng
function add_commission(t, product_id) {
    var html = '';
    var user_admin = getUserAdmin();
    if (user_admin) {
        var html_user_admin = '';
        $.each(user_admin, function (key, value) {
            html_user_admin += '<option value="' + key + '">' + value + '</option>';
        });
        html = '<tr class="com-value">\n' +
            '                <td>\n' +
            '                    <select name="commission_team_id[' + product_id + '][]" id="" class="form-control">\n' +
            html_user_admin +
            '                                            </select>\n' +
            '                </td>\n' +
            '                <td>\n' +
            '                    <select name="commission_team_type[' + product_id + '][]" id="" class="form-control team_type">\n' +
            '                        <option value="1" selected>Theo %</option>\n' +
            '                        <option value="2">Tiền mặt</option>\n' +
            '                    </select>\n' +
            '                </td>\n' +
            '                <td>\n' +
            '                    <input name="commission_team_value[' + product_id + '][]" type="text" class="form-control team_commission" placeholder="nhập giá trị hưởng hoa hồng" value="">\n' +
            '                </td>\n' +
            '                <td>\n' +
            '                    <button type="button" class="btn btn-danger delete-commission" onclick="deleteCommission(this)">Xóa</button>\n' +
            '                </td>\n' +
            '            </tr>';
    }
    $('.commission_body_child').append(html);
}

//Thêm mới thuốc
function submit_thuoc(t) {
    var id = $('.list_time').find('li.active').data('id');
    var data = $('#donthuoc_body').serializeArray();
    $.ajax({
        url: '/admin/user/medical-record/add-medicine',
        type: 'POST',
        data: {
            id: id,
            data: data
        },
        success: function (data) {
            var response = JSON.parse(data);
            if (response.success) {
                $(".close_form").trigger('click');
                alert(response.message);
                setTimeout(function () {
                    window.location.reload()
                }, 2000);
            } else {
                alert(response.message)
            }
        }
    })
}

//Load form update đơn thuốc
function updateMedicine(id) {
    $.ajax({
        url: '/admin/user/medical-record/form-update-medicine',
        type: 'get',
        data: {
            id: id
        },
        success: function (data) {
            $('.medicine_edit').empty().append(data);
        }
    })
}

//Cập nhật đơn thuốc
function submitMedicine(t) {
    var doctor = $('#medicine_doctor_id_update').val();
    var medicine = $('#medicine_id_update').val();
    var quantity = $('#medicine_quantity_update').val();
    if (checkValidate(doctor, $('#medicine_doctor_id_update'), 'Bác sĩ không được để trống') == false || checkValidate(medicine, $('#medicine_id_update'), 'Thuốc không được bỏ trống') == false || checkValidate(quantity, $('#medicine_quantity_update'), 'Số lượng không được bỏ trống') == false) {
        return false
    }
    if (quantity < 1) {
        return false
    }

    $.ajax({
        url: '/admin/user/medical-record/update-medicine',
        type: 'POST',
        data: $('#medicine-update-form').serialize(),
        success: function (response) {
            if (response) {
                alert('Chỉnh sửa đơn thuốc thành công');
                setTimeout(function () {
                    window.location.reload()
                }, 1500);
            } else {
                alert('Cập nhật không thành công')
            }
        }
    })
}

//Xóa hoa hồng của 1 người
function deleteCommission(t) {
    $(t).parents('.com-value').remove();
}

// check tổng % hoa hồng trong 1 thủ thuật phải =8
function checkUpdateCommission() {
    var check = true;
    var cm = 0;
    var type1 = false;
    $('.com-value').each(function () {
        var $this = $(this);
        if ($this.length) {
            var selType = $this.find('.team_type').val();
            var selVal = $this.find('.team_commission').val();
            if (selType == 1) {
                cm += parseInt(selVal);
                type1 = true;
            }

        }
    });

    if (type1) {
        if (cm != 8) {
            alert('Tổng % hoa hồng phải bằng 8');
            check = false;
        }
    }

    if (check == false) {
        return false;
    }
    return true;
}

//lấy danh sách tài khoản admin
function getUserAdmin() {
    var response = {};
    $.ajax({
        url: '/admin/ajax/get-user-admin',
        type: 'get',
        data: {},
        async: false,
        success: function (data) {
            response = JSON.parse(data);
        }
    });
    return response;
}

function load_log(id) {
    $.ajax({
        url: '/admin/user/medical-record/get-log',
        type: 'get',
        data: {
            id: id
        },
        success: function (data) {
            $('.log_body').empty().html(data);
        }
    })
}

function load_log_user() {
    $.ajax({
        url: '/admin/user/user/get-log',
        type: 'get',
        data: {},
        success: function (data) {
            $('.log_body').empty().html(data);
        }
    })
}

function load_log_tt() {
    $.ajax({
        url: '/admin/thuchi/thuchi/get-log',
        type: 'get',
        data: {},
        success: function (data) {
            $('.log_body').empty().html(data);
        }
    })
}

function ngAppConfigs($httpProvider) {
    $httpProvider.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
    $httpProvider.defaults.headers.common['X-CSRF-Token'] = $('meta[name="csrf-token"]').attr('content');
    $httpProvider.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';

    /**
     * The workhorse; converts an object to x-www-form-urlencoded serialization.
     * @param {Object} obj
     * @return {String}
     */
    const param = function (obj) {
        let query = '', name, value, fullSubName, subName, subValue, innerObj, i;

        for (name in obj) {
            value = obj[name];

            if (value instanceof Array) {
                for (i = 0; i < value.length; ++i) {
                    subValue = value[i];
                    fullSubName = name + '[' + i + ']';
                    innerObj = {};
                    innerObj[fullSubName] = subValue;
                    query += param(innerObj) + '&';
                }
            } else if (value instanceof Object) {
                for (subName in value) {
                    subValue = value[subName];
                    fullSubName = name + '[' + subName + ']';
                    innerObj = {};
                    innerObj[fullSubName] = subValue;
                    query += param(innerObj) + '&';
                }
            } else if (value !== undefined && value !== null)
                query += encodeURIComponent(name) + '=' + encodeURIComponent(value) + '&';
        }

        return query.length ? query.substr(0, query.length - 1) : query;
    };

    // Override $http service's default transformRequest
    $httpProvider.defaults.transformRequest = [function (data) {
        return angular.isObject(data) && String(data) !== '[object File]' ? param(data) : data;
    }];
}

const backendNgApp = angular.module('backendNgApp',
    ['ui.bootstrap', 'ui.sortable', 'ui.uploader', 'ui.select', 'ngSanitize', 'chart.js'],
    function ($httpProvider) {
        ngAppConfigs($httpProvider);
    }
);

backendNgApp.factory('timeAgo', function () {
    return function (date, nowDate = Date.now(), rft = new Intl.RelativeTimeFormat(undefined, {numeric: "auto"})) {
        // if(date.length === 10) {
        //     let date = parseInt(Math.round(date * 1000))
        // }
        const SECOND = 1000;
        const MINUTE = 60 * SECOND;
        const HOUR = 60 * MINUTE;
        const DAY = 24 * HOUR;
        const WEEK = 7 * DAY;
        const MONTH = 30 * DAY;
        const YEAR = 365 * DAY;
        const intervals = [
            {ge: YEAR, divisor: YEAR, unit: 'year'},
            {ge: MONTH, divisor: MONTH, unit: 'month'},
            {ge: WEEK, divisor: WEEK, unit: 'week'},
            {ge: DAY, divisor: DAY, unit: 'day'},
            {ge: HOUR, divisor: HOUR, unit: 'hour'},
            {ge: MINUTE, divisor: MINUTE, unit: 'minute'},
            {ge: 15 * SECOND, divisor: SECOND, unit: 'seconds'},
            {ge: 0, divisor: 1, text: 'just now'},
        ];
        const now = typeof nowDate === 'object' ? nowDate.getTime() : new Date(nowDate).getTime();
        // date = typeof date === 'object' ? date.getTime() : new Date(date).getTime();
        const diff = now - date;
        const diffAbs = Math.abs(diff);
        for (const interval of intervals) {
            if (diffAbs >= interval.ge) {
                const x = Math.round(Math.abs(diff) / interval.divisor);
                const isFuture = diff < 0;
                return interval.unit ? rft.format(isFuture ? x : -x, interval.unit) : interval.text;
            }
        }
    }
});

backendNgApp.filter('propsFilter', function () {
    return function (items, props) {
        let out = [];

        if (angular.isArray(items)) {
            const keys = Object.keys(props);

            items.forEach(function (item) {
                let itemMatches = false;

                for (let i = 0; i < keys.length; i++) {
                    const prop = keys[i];
                    const text = props[prop] ? props[prop].toLowerCase() : '';
                    if (item[prop] && item[prop].toString().toLowerCase().indexOf(text) !== -1) {
                        itemMatches = true;
                        break;
                    }
                }

                if (itemMatches) {
                    out.push(item);
                }
            });
        } else {
            // Let the output be the input untouched
            out = items;
        }

        return out;
    };
});

backendNgApp.directive('customFileInput', ['uiUploader', function (uiUploader) {
    return {
        restrict: 'A',
        scope: {
            ngModel: '=',
            ngChange: '&',
            type: '@'
        },
        link: function (scope, element, attrs) {
            if (scope.type.toLowerCase() !== 'file') {
                return;
            }
            element.bind('change', function () {
                uiUploader.addFiles(element[0].files);
                scope.ngModel = uiUploader.getFiles();
                scope.$apply();
                scope.ngChange();
            });
        }
    }
}]);

function PagingController($scope) {

    $scope.pager = {};
    $scope.pagingSize = $scope.pagingSize || 10;
    $scope.itemPerPage = $scope.itemPerPage || 10;

    function setPager(itemCount, currentPage, itemPerPage) {
        currentPage = currentPage || 1;
        let startPage, endPage;

        const totalPages = Math.ceil(itemCount / itemPerPage);
        if (totalPages <= $scope.pagingSize) {
            startPage = 1;
            endPage = totalPages;
        } else {
            if (currentPage + 1 >= totalPages) {
                startPage = totalPages - ($scope.pagingSize - 1);
                endPage = totalPages;
            } else {
                startPage = currentPage - parseInt($scope.pagingSize / 2);
                startPage = startPage <= 0 ? 1 : startPage;
                endPage = (startPage + $scope.pagingSize - 1) <= totalPages ? (startPage + $scope.pagingSize - 1) : totalPages;
                if (totalPages === endPage) {
                    startPage = endPage - $scope.pagingSize + 1;
                }
            }
        }

        const startIndex = (currentPage - 1) * itemPerPage;
        const endIndex = startIndex + itemPerPage - 1;

        let index = startPage;
        const pages = [];
        for (; index < endPage + 1; index++)
            pages.push(index);

        $scope.pager.currentPage = currentPage;
        $scope.pager.totalPages = totalPages;
        $scope.pager.startPage = startPage;
        $scope.pager.endPage = endPage;
        $scope.pager.startIndex = startIndex;
        $scope.pager.endIndex = endIndex;
        $scope.pager.pages = pages;
    }

    $scope.setPage = function (currentPage) {
        if (currentPage < 1 || currentPage > $scope.pager.totalPages)
            return;

        setPager($scope.totalItems.length, currentPage, $scope.itemPerPage);
        $scope.displayItems = $scope.totalItems.slice($scope.pager.startIndex, $scope.pager.endIndex + 1);
    };

    $scope.setPage(1);
}

backendNgApp.directive('pagingControl', [function () {
    return {
        restrict: 'E',
        templateUrl: 'pagination.html',
        controller: ['$scope', PagingController],
        scope: {
            totalItems: "=",
            displayItems: '=',
            pagingSize: '=',
            itemPerPage: '=noofitem'
        }
    };
}]);

backendNgApp.controller('checkListController', [
    'timeAgo', '$scope', '$http', '$uibModal', 'uiUploader',
    function (timeAgo, $scope, $http, $uibModal, uiUploader) {
        'use strict';

        /*
        *
        * khởi tạo controller data
        * checkListStatus: Danh sách trạng thái checklist []
        * checklists: Danh sách checklist
        * function getChecklistsByStatus(): gom nhóm danh sách checklist theo trạng thái
        *
        */
        $scope.controllerData = {
            checklistBranchs: $checklistBranchs,
            checkListStatus: $statuses,
            checklists: $checklist,
            getChecklistsByStatus: function ($status) {
                return $checklist.filter(function (value) {
                    return value.status === $status;
                });
            },
            todoChecklists: [],
            doingChecklists: [],
            checkingChecklists: [],
            completeChecklists: [],
            dragging: false,
            branch: null,
            permissions: {
                canViewAll: $checklistPermission.includes("Quản lý công việc - Xem tất cả") || $checklistPermission.includes("Admin"),
                canView: $checklistPermission.includes("Quản lý công việc - Danh sách") || $checklistPermission.includes("Admin"),
                canCreate: $checklistPermission.includes("Quản lý công việc - Thêm") || $checklistPermission.includes("Admin"),
                canUpdate: $checklistPermission.includes("Quản lý công việc - Sửa") || $checklistPermission.includes("Admin"),
                canDelete: $checklistPermission.includes("Quản lý công việc - Xóa") || $checklistPermission.includes("Admin"),
                canApproval: $checklistPermission.includes("Quản lý công việc - Kiểm tra") || $checklistPermission.includes("Admin"),
            },
            priorityLevels: [
                'Thấp',
                'Bình thường',
                'Cao',
                'Cấp bách'
            ]
        }

        $scope.checklistsItem = {
            id: 0,
            branch_id: null,
            name: "",
            start_at: null,
            end_at: null,
            description: "",
            created_by: null,
            handled_by: [],
            incharge_by: null,
            attachments: [],
            links: [],
            loop: null,
            priority: "0",
            status: "todo",
            created_at: null,
            updated_at: null,
        }

        $scope.handled_users = [];
        $scope.incharged_users = [];

        function getUserByRole($data, $callback = (response) => {
        }) {
            $http({
                url: $getUserByRoleUrl,
                method: 'POST',
                data: $data
            }).then(function (response) {
                if (response?.data?.code === 200) {
                    $callback(response?.data);
                }
            }, function (error) {
                console.log(error)
            });
        }

        function init() {
            $scope.controllerData.todoChecklists = $scope.controllerData.getChecklistsByStatus('todo')
            $scope.controllerData.doingChecklists = $scope.controllerData.getChecklistsByStatus('doing')
            $scope.controllerData.checkingChecklists = $scope.controllerData.getChecklistsByStatus('checking')
            $scope.controllerData.completeChecklists = $scope.controllerData.getChecklistsByStatus('complete')

            let url = new URL(window.location.href);
            $scope.controllerData.branch = url.searchParams.get("branch");
            $scope.checklistsItem.branch_id = url.searchParams.get("branch");
            getUserByRole({
                role: "",
            }, (response) => {
                $scope.incharged_users = response?.data?.user;
            })
        }

        init();

        $scope.controllerFunction = {
            timeAgo: timeAgo,
            branchChanged: function() {
                window.location.href = window.location.href.split('?')[0]+'?branch='+$scope.controllerData.branch
            },
            openStoreChecklistModal: ($action = 'create', $id = undefined) => {
                const modalInstance = $uibModal.open({
                    animation: true,
                    ariaLabelledBy: 'modal-title',
                    ariaDescribedBy: 'modal-body',
                    templateUrl: 'storeCheckListModal.html',
                    controller: function ($http, $uibModalInstance, $timeout) {
                        const $ctrl = this;

                        $ctrl.priorityLevels = $scope.controllerData.priorityLevels
                        $ctrl.action = $action;
                        $ctrl.id = $id;
                        $ctrl.handled_users = $scope.handled_users
                        $ctrl.incharged_users = $scope.incharged_users
                        $ctrl.userPosition = $userPosition
                        $ctrl.attachments = [];
                        $ctrl.attachmentsUploaded = []
                        $ctrl.datepickerPopup = {
                            popupStartAt: {
                                opened: false
                            },
                            popupEndAt: {
                                opened: false
                            },
                        }
                        $ctrl.statuses = $statuses
                        $ctrl.dateOptions = {
                            minDate: Date.now()
                        };

                        $ctrl.checklistsItem = $scope.checklistsItem
                        if ($id) {
                            const cklist = $scope.controllerData.checklists.find(element => parseInt(element.id) === parseInt($ctrl.id))
                            if (cklist) {
                                $ctrl.checklistsItem = cklist;
                                $ctrl.checklistsItem.start_at = new Date($ctrl.checklistsItem.start_at / 1);
                                $ctrl.checklistsItem.end_at = new Date($ctrl.checklistsItem.end_at / 1);
                                $ctrl.checklistsItem.created_at = new Date($ctrl.checklistsItem.created_at / 1);
                                $ctrl.checklistsItem.updated_at = new Date($ctrl.checklistsItem.updated_at / 1);
                                $ctrl.checklistsItem.incharge_by = parseInt($ctrl.checklistsItem.incharge_by);
                                $ctrl.checklistsItem.handled_by = typeof $ctrl.checklistsItem.handled_by === 'string' ? JSON.parse($ctrl.checklistsItem.handled_by) : $ctrl.checklistsItem.handled_by;
                                $ctrl.checklistsItem.handled_by = $ctrl.checklistsItem?.handled_by?.map((item) => {
                                    return parseInt(item);
                                })
                                $ctrl.checklistsItem.links = typeof $ctrl.checklistsItem.links === 'string' ? JSON.parse($ctrl.checklistsItem.links) : $ctrl.checklistsItem.links;
                                $ctrl.attachmentsUploaded = $ctrl.checklistsItem.attachments
                            }
                            if ($ctrl.checklistsItem.incharge_by) {
                                getUserByRole({
                                    'role': '',
                                    'inchargeBy': $ctrl.checklistsItem.incharge_by
                                }, (response) => {
                                    $scope.handled_users = response?.data?.user;
                                    $ctrl.handled_users = $scope.handled_users
                                })
                            }
                        }
                        let currentStatus = $ctrl.checklistsItem.status;
                        $ctrl.permissions = $scope.controllerData.permissions;

                        $ctrl.functions = {
                            addLink: function() {
                                $ctrl.checklistsItem.links = $ctrl.checklistsItem.links === null ? [] : $ctrl.checklistsItem.links;
                                if($ctrl.link && !$ctrl.checklistsItem.links.includes($ctrl.link)) {
                                    $ctrl.checklistsItem.links.push($ctrl.link)
                                    $ctrl.link = '';
                                }
                            },
                            removeLink: function(index) {
                                $ctrl.checklistsItem.links = $ctrl.checklistsItem.links === null ? [] : $ctrl.checklistsItem.links;
                                if($ctrl.checklistsItem.links) {
                                    $ctrl.checklistsItem.links = $ctrl.checklistsItem.links.filter(item => item !== $ctrl.checklistsItem.links[index]);
                                }
                            },
                            copyLink: function($event, index) {
                                const copyElement = document.createElement("textarea");
                                copyElement.style.position = 'fixed';
                                copyElement.style.opacity = '0';
                                copyElement.textContent =  decodeURI($ctrl.checklistsItem.links[index]);
                                const body = document.getElementsByTagName('body')[0];
                                body.appendChild(copyElement);
                                copyElement.select();
                                document.execCommand('copy');
                                body.removeChild(copyElement);

                                angular.element($event.currentTarget).find('.fa').removeClass('fa-copy').addClass('fa-check')
                                let timeout = $timeout(function() {
                                    console.log('lll')
                                    angular.element($event.currentTarget).find('.fa').removeClass('fa-check').addClass('fa-copy')
                                }, 2000);
                            },
                            openDatepickerPopup: (popup) => {
                                if (popup === 1) {
                                    $ctrl.datepickerPopup.popupStartAt.opened = true
                                }
                                if (popup === 2) {
                                    $ctrl.datepickerPopup.popupEndAt.opened = true
                                }
                            },
                            inchargeByChanged: function (id) {
                                $http({
                                    url: $getUserByRoleUrl,
                                    method: 'POST',
                                    data: {
                                        role: '',
                                        inchargeBy: $ctrl.checklistsItem.incharge_by
                                    }
                                }).then(function (response) {
                                    if (response?.data?.code === 200) {
                                        $scope.handled_users = response?.data?.data?.user;
                                        $ctrl.handled_users = $scope.handled_users;
                                    }
                                }, function (error) {
                                    console.log(error)
                                });
                            },
                            previewAttachments: function () {
                                uiUploader.startUpload({
                                    url: '/admin/media/upload/uploadfiles',
                                    concurrency: 2,
                                    onProgress: function (file) {
                                        file.uploading = true;
                                        file.uploaded = false;
                                        $scope.$apply();
                                    },
                                    onCompleted: function (file, response) {
                                        file.uploading = false;
                                        file.uploaded = true;
                                        if (typeof response === 'string')
                                            response = JSON.parse(response);
                                        if (response?.code) {
                                            $ctrl.checklistsItem.attachments.push(response?.data)
                                            $ctrl.attachmentsUploaded = $ctrl.checklistsItem.attachments
                                        }
                                    }
                                });
                            },
                            store: function () {
                                if (!$scope.controllerData.permissions.canUpdate && !$scope.controllerData.permissions.canCreate) return;

                                $ctrl.checklistsItem.start_at = new Date($ctrl.checklistsItem.start_at).getTime();
                                $ctrl.checklistsItem.end_at = new Date($ctrl.checklistsItem.end_at).getTime();
                                $ctrl.checklistsItem.created_at = new Date($ctrl.checklistsItem.created_at).getTime();
                                if ($ctrl.id !== undefined) {
                                    $ctrl.checklistsItem.updated_at = Date.now();
                                } else {
                                    $ctrl.checklistsItem.updated_at = new Date($ctrl.checklistsItem.updated_at).getTime();
                                }
                                $http({
                                    url: $storeCheckListUrl,
                                    method: 'POST',
                                    data: $ctrl.checklistsItem
                                }).then(function (response) {
                                    if (response?.data?.code === 200) {
                                        let data = response?.data?.data;
                                        data.incharge_by = data.incharge_by ? parseInt(data.incharge_by) : null;
                                        data.id = data.id ? parseInt(data.id) : null;
                                        if ($ctrl.id) {
                                            let index = $scope.controllerData[$ctrl.checklistsItem.status + "Checklists"].map(obj => obj.id).indexOf($ctrl.id);
                                            if (index !== -1) {
                                                $scope.controllerData[$ctrl.checklistsItem.status + "Checklists"][index] = data
                                            } else {
                                                $scope.controllerData[currentStatus + "Checklists"] = $scope.controllerData[currentStatus + "Checklists"].filter(item => {
                                                    return parseInt(item.id) !== parseInt(data.id)
                                                })
                                                $scope.controllerData[$ctrl.checklistsItem.status + "Checklists"].unshift(data);
                                            }
                                        } else {
                                            $scope.controllerData[$ctrl.checklistsItem.status + "Checklists"].unshift(data)
                                        }
                                    }
                                    alert(response?.data?.message);
                                    $uibModalInstance.close();
                                }, function (error) {
                                    console.log('error', error)
                                    $uibModalInstance.close();
                                });
                            },
                            delete: function () {
                                if (!$scope.controllerData.permissions.canDelete) return;
                                $http({
                                    url: $deleteCheckListUrl,
                                    method: 'POST',
                                    data: {
                                        id: $ctrl.checklistsItem.id
                                    }
                                }).then(function (response) {
                                    if (response?.data?.code == 200) {
                                        let index = $scope.controllerData[$ctrl.checklistsItem.status + "Checklists"].map(obj => obj.id).indexOf($ctrl.id);
                                        if (index !== -1) {
                                            $scope.controllerData[$ctrl.checklistsItem.status + "Checklists"] = $scope.controllerData[$ctrl.checklistsItem.status + "Checklists"].filter(ck => ck.id !== $ctrl.id);
                                        }
                                        $ctrl.checklistsItem = {
                                            id: 0,
                                            name: "",
                                            start_at: null,
                                            end_at: null,
                                            description: "",
                                            created_by: null,
                                            handled_by: null,
                                            incharge_by: null,
                                            attachments: [],
                                            links: [],
                                            loop: null,
                                            priority: 9999,
                                            status: "todo",
                                            created_at: null,
                                            updated_at: null,
                                        }
                                        $uibModalInstance.close();
                                    }
                                    alert(response?.data?.message);
                                }, function (error) {
                                    console.log('error', error)
                                    $uibModalInstance.close();
                                });
                            },
                            cancel: function () {
                                $uibModalInstance.dismiss('cancel');
                            }
                        }
                    },
                    controllerAs: '$ctrl',
                    size: 'md'
                });
                modalInstance.result.then(function (selectedItem) {

                }, function () {
                    console.log('Modal dismissed at: ' + new Date());
                });
            },
            updateStatus: (id, status, successCallback = (response) => {
            }, errorCallback = (error) => {
            }) => {
                if (!$scope.controllerData.permissions.canUpdate && !$scope.controllerData.permissions.canApproval) return;
                $http({
                    url: $updateCheckListStatusUrl,
                    method: 'POST',
                    data: {
                        id: id,
                        status: status
                    }
                }).then(function (response) {
                    const index = ($scope.controllerData[status + 'Checklists']).map(object => object.id).indexOf(id.toString());
                    const ind = $checklist.map(object => object.id).indexOf(id.toString());
                    if (index !== -1) {
                        $scope.controllerData[status + 'Checklists'][index].status = status
                    }
                    if (ind !== -1) {
                        $checklist[ind].status = status
                    }
                    successCallback(response);
                }, function (error) {
                    errorCallback(error);
                });
            },
            draggable: {
                connectWith: ".msg-list-droppable",
                start: function (e, ui) {
                    if ($scope.controllerData.permissions.canView &&
                        !$scope.controllerData.permissions.canCreate &&
                        !$scope.controllerData.permissions.canUpdate &&
                        !$scope.controllerData.permissions.canDelete &&
                        !$scope.controllerData.permissions.canApproval) {
                        ui.item.sortable.cancel();
                        return;
                    }
                    let _item_status = $(this).data('type');
                    if (_item_status === 'complete' && !$scope.controllerData.permissions.canApproval) {
                        ui.item.sortable.cancel();
                        return;
                    }
                    if (_item_status !== 'complete' && !$scope.controllerData.permissions.canUpdate && !$scope.controllerData.permissions.canApproval) {
                        ui.item.sortable.cancel();
                        return;
                    }
                    $scope.$apply(function () {
                        $scope.controllerData.dragging = true
                    });
                },
                update: function (e, ui) {
                    if ($scope.controllerData.permissions.canView &&
                        !$scope.controllerData.permissions.canCreate &&
                        !$scope.controllerData.permissions.canUpdate &&
                        !$scope.controllerData.permissions.canDelete &&
                        !$scope.controllerData.permissions.canApproval) return
                    if (ui.sender !== null) {
                        let _item_id = $(ui.item).data('id');
                        let _item_status = $(this).data('type');
                        if (_item_status === 'complete' && !$scope.controllerData.permissions.canApproval) {
                            ui.item.sortable.cancel();
                            return;
                        }
                        if (_item_status !== 'complete' && !$scope.controllerData.permissions.canUpdate && !$scope.controllerData.permissions.canApproval) {
                            ui.item.sortable.cancel();
                            return;
                        }

                        $scope.controllerFunction.updateStatus(_item_id, _item_status, (response) => {
                            jQuery('.msg-list-droppable').sortable('refresh');
                        }, (error) => {
                            jQuery('.msg-list-droppable').sortable('refresh');
                        })
                        $scope.$apply(function () {
                            $scope.controllerData.dragging = true
                        });
                    }
                },
                receive: function (e, ui) {
                    jQuery('.msg-list-droppable').sortable('refresh');
                },
                stop: function (e, ui) {
                    jQuery('.msg-list-droppable').sortable('refresh');
                    $scope.$apply(function () {
                        $scope.controllerData.dragging = false
                    });
                }
            }
        }
    }
]);

backendNgApp.controller('ngNotifyController', [
    "$scope", "$http", "$interval", "timeAgo",
    function ($scope, $http, $interval, timeAgo) {
        $scope.controllerData = {
            limit: 8,
            unreadCount: 0,
            notify: [],
            noNotify: !this.unreadCount || this.notify === [] || this.notify.length === 0
        }

        $scope.controllerFunction = {
            timeAgo: timeAgo,
            goTo: (href, id) => {
                if (href && id) {
                    $http({
                        url: $changeNotifyStatusUrl,
                        method: "POST",
                        data: {
                            id: id
                        }
                    }).then(function (response) {
                        if (response?.data?.code === 200) {
                            window.location.href = href
                        }
                    }, function (error) {
                        console.log(error)
                        window.location.href = href
                    })
                }
            }
        }

        function init() {
            let getNotify = function () {
                $http({
                    url: $notifyUrl,
                    method: "POST",
                    data: {}
                }).then(function (response) {
                    if (response?.data?.code === 200) {
                        $scope.controllerData.unreadCount = parseInt(response?.data?.data?.unread);
                        $scope.controllerData.notify = response?.data?.data?.notifies;
                        $scope.controllerData.noNotify = false;
                    }
                }, function (error) {
                    console.log(error)
                })
            }
            getNotify();
            let $stop = $interval(getNotify, 3000);
        }

        init();
    }
]);

backendNgApp.controller('ngKPIController', [
    "$scope", "$http",
    function ($scope, $http) {
        $scope.controllerData = {
            kpis: $kpis,
            listKPIs: [],
            kpi: {
                id: null,
                name: '',
                dinh_muc_khoan: null,
                description: '',
                created_at: null,
                updated_at: null
            },
            pager: {},
            loading: false,
            alert: 'alert-success',
            alertMessage: '',
            tableLoading: false,
            tableAlert: 'alert-success',
            tableAlertMessage: '',

            dataPerPage: 10,
        }

        let resetKpi = function () {
            $scope.controllerData.loading = false;
            $scope.controllerData.tableLoading = false;
            $scope.controllerData.kpi = {
                id: null,
                name: '',
                dinh_muc_khoan: null,
                description: '',
                created_at: null,
                updated_at: null
            }
        }

        $scope.controllerFunction = {
            formatMoney: function (a, c, d, t) {
                var n = a,
                    c = isNaN(c = Math.abs(c)) ? 2 : c,
                    d = d == undefined ? "." : d,
                    t = t == undefined ? "," : t,
                    s = n < 0 ? "-" : "",
                    i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c))),
                    j = (j = i.length) > 3 ? j % 3 : 0;
                return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
            },
            resetKpi: function () {
                $scope.controllerData.loading = false;
                $scope.controllerData.tableLoading = false;
                $scope.controllerData.kpi = {
                    id: null,
                    name: '',
                    dinh_muc_khoan: null,
                    description: '',
                    created_at: null,
                    updated_at: null
                }
            },
            setSelectedKPI: function (kpi) {
                kpi.dinh_muc_khoan = kpi.dinh_muc_khoan ? parseInt(kpi.dinh_muc_khoan) : kpi.dinh_muc_khoan
                kpi.id = kpi.id ? parseInt(kpi.id) : null
                $scope.controllerData.kpi = kpi;
            },
            storeKPI: function () {
                $scope.controllerData.loading = true;
                $scope.controllerData.alertMessage = '';
                if ($scope.controllerData.kpi.name === '') {
                    $scope.controllerData.alert = 'alert-danger';
                    $scope.controllerData.alertMessage = 'Nhập tên chỉ số kpi';
                    $scope.controllerData.loading = false;
                    return;
                }

                $http({
                    url: $storeKPI,
                    method: "POST",
                    data: {
                        kpi: $scope.controllerData.kpi
                    }
                }).then(function (response) {
                    if (response?.data?.code === 500) {
                        $scope.controllerData.alert = 'alert-danger';
                    }
                    if (response?.data?.message) {
                        $scope.controllerData.alertMessage = response?.data?.message;
                    }
                    if (response?.data?.code === 200) {
                        if (response?.data?.data?.kpi) {
                            if (!$scope.controllerData.kpi.id) {
                                $scope.controllerData.listKPIs.unshift(response?.data?.data?.kpi)
                            }
                        }
                        resetKpi();
                    }
                }, function (error) {
                    console.log(error);
                    resetKpi();
                })
            },
            deleteKPI: function (kpi) {
                if (kpi) {
                    $scope.controllerData.tableLoading = true;
                    $http({
                        url: $deleteKPI,
                        method: "POST",
                        data: {
                            kpi: kpi
                        }
                    }).then(function (response) {
                        if (response?.data?.code === 500) {
                            $scope.controllerData.tableAlert = 'alert-danger';
                        }
                        if (response?.data?.message) {
                            $scope.controllerData.tableAlertMessage = response?.data?.message;
                        }
                        if (response?.data?.code === 200) {
                            $scope.controllerData.listKPIs = $scope.controllerData.listKPIs.filter(item => {
                                return parseInt(item.id) !== parseInt(kpi.id)
                            })
                            resetKpi();
                        }
                    }, function (error) {
                        console.log(error);
                        resetKpi();
                    })
                }
            }
        }
    }
]);

backendNgApp.controller('ngSaleController', [
    "$scope", "$http", "$uibModal",
    function ($scope, $http, $uibModal) {
        $scope.controllerData = {
            employs: JSON.parse($employs),
            incharge_employs: JSON.parse($employs),
            selected_employ: null,
            employ: {},
            branchs: JSON.parse($branchs),
            selected_branch: null,
            departments: JSON.parse($departments),
            selected_department: null,
            start_at: 0,
            end_at: 0,
            chartInfo: {
                chart_kpi: JSON.parse($kpis)[0],
                chart_types: {
                    line: 'Biểu đồ đường',
                    bar: 'Biểu đồ cột',
                    radar: 'Biểu đồ Radar',
                    polarArea: 'Biểu đồ vùng',
                    horizontalBar: 'Biểu đồ cột ngang',
                },
                chart_type: 'line',
                chart_labels: [],
                chart_series: ['Thực đạt', 'Định mức'],
                chart_data: []
            },
            month: [],
            year: [],
            mergedDate: [],
            selectedYear: new Date().getFullYear().toString(),
            selectedMonth: (new Date().getMonth() + 1).toString(),
            permissions: {
                canView: $kpiPermissions.includes("Quản lý KPI") || $kpiPermissions.includes("Admin"),
            }, // quyền hạn
            currentTab: 'personal_kpi', // tab được chọn
            kpi: {
                id: null,
                kpi_id: null,
                created_at: null,
                dinh_muc: null,
                ghi_chu: null,
                nam: null,
                nguoi_danh_gia: null,
                thang: null,
                thuc_dat: null,
                trong_so: 0,
                tru_kpi: 0,
                updated_at: null,
                user_id: null,
                hoan_thanh: null,
                con_thieu: null,
            }, // KPI model
            kpiEditingIndex: null,
            kpis: [], // Danh sách các chỉ số kpi áp dụng cho nhân sự đươc chọn hiện tại
            kpisList: JSON.parse($kpis), // Danh sách các chỉ số kpi
            action: null, // Hành động đang được thực thi
            actionLoading: false, // Hành động đang được thực thi
            notifyMessage: '',
            notifyType: 'success',
        }

        let init = function () {
            $scope.controllerData.month = Array(12).fill().map((x, i) => 12 - i);
            $scope.controllerData.year = Array(20).fill().map((x, i) => new Date().getFullYear() - i);
            $scope.controllerData.chartInfo.chart_labels = [];
            $scope.controllerData.chartInfo.chart_labels.push(($scope.controllerData.selectedMonth < 10 ? '0' + $scope.controllerData.selectedMonth : $scope.controllerData.selectedMonth) + "-" + $scope.controllerData.selectedYear)
            $scope.controllerData.mergedDate = $scope.controllerData.year.reduce((merged, c) => {
                $scope.controllerData.month.forEach((a) => {
                    if (c == new Date().getFullYear() && a > new Date().getMonth() + 1) {
                        return merged;
                    }
                    merged.push((a < 10 ? '0' + a : a) + "-" + c);
                });
                return merged;
            }, []);
        }
        init();

        $scope.controllerFunction = {
            isEmpty: function (e) {
                return (typeof e === 'object' && Array.isArray(e) ? !e.length : true);
            },
            changeTab: function (tab) {
                $scope.controllerData.currentTab = tab;
                angular.element('.form-select.select2').select2();
                angular.element('#chart_times').on('select2:opening select2:closing', function (event) {
                    const $searchfield = angular.element(this).parent().find('.select2-search__field');
                    $searchfield.prop('disabled', true);
                });
            },
            resetKpiRow: function () {
                $scope.controllerData.kpi = {
                    id: null,
                    kpi_id: null,
                    created_at: null,
                    dinh_muc: null,
                    ghi_chu: null,
                    nam: $scope.controllerData.selectedYear,
                    nguoi_danh_gia: null,
                    thang: $scope.controllerData.selectedMonth,
                    thuc_dat: null,
                    trong_so: 0,
                    tru_kpi: 0,
                    updated_at: null,
                    user_id: $scope.controllerData.selected_employ,
                    hoan_thanh: null,
                    con_thieu: null,
                };
            },
            closeKpiRow: function () {
                $scope.controllerData.kpiEditingIndex = null;
                $scope.controllerFunction.resetKpiRow();
            },
            calcCompletePercentText: function (dinhmuc, thucdat, trongso, tru) {
                if (!dinhmuc || !thucdat || !trongso) return "0";
                return (parseFloat(thucdat) / parseFloat(dinhmuc) * 100).toFixed(2);
            },
            calcConthieuText: function (dinhmuc, thucdat, trongso, tru) {
                if (!dinhmuc || !thucdat || !trongso) return "0";
                return parseInt(Math.round(parseFloat(dinhmuc) - parseFloat(thucdat)));
            },
            formatMoney: function (a, c, d, t) {
                var n = a,
                    c = isNaN(c = Math.abs(c)) ? 2 : c,
                    d = d == undefined ? "." : d,
                    t = t == undefined ? "," : t,
                    s = n < 0 ? "-" : "",
                    i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c))),
                    j = (j = i.length) > 3 ? j % 3 : 0;
                return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
            },
            getEmployAttr: function (id, array = $scope.controllerData.employs, attr = 'fullname') {
                if (array) {
                    let find = array.find(employ => {
                        return employ.id.toString() === id.toString()
                    })
                    if (find && find[attr]) {
                        return find[attr];
                    }
                }
                return;
            },
            getKpiAttr: function (id, attr = 'name') {
                if ($scope.controllerData.kpisList) {
                    let find = $scope.controllerData.kpisList.find(kpi => {
                        return kpi.id.toString() === id.toString()
                    })
                    if (find && find[attr]) {
                        return find[attr];
                    }
                }
                return;
            },
            addKpiRow: function () {
                $scope.controllerData.action = 'create';
                let find = $scope.controllerData.kpis.find(kpi => {
                    return kpi.id === $scope.controllerData.kpi.id
                })
                if (find === undefined || (find !== undefined && find.id !== null)) {
                    $scope.controllerFunction.resetKpiRow();
                    $scope.controllerData.kpis.push($scope.controllerData.kpi);
                    $scope.controllerData.kpiEditingIndex = $scope.controllerData.kpis.length - 1;
                }
            },
            editKpiRow: function (index) {
                $scope.controllerData.action = 'update';
                $scope.controllerData.kpiEditingIndex = index;
                $scope.controllerData.kpi = $scope.controllerData.kpis[index];
                $scope.controllerData.kpi.kpi_id = $scope.controllerData.kpi.kpi_id.toString();
                $scope.controllerData.kpi.nguoi_danh_gia = $scope.controllerData.kpi.nguoi_danh_gia.toString();
            },
            storeKpiRow: function (action, $successCallback = (response) => {
            }, $errorCallback = (error) => {
            }) {
                $scope.controllerData.actionLoading = true;
                $scope.controllerData.notifyMessage = '';
                if (action === undefined) action = $scope.controllerData.action;
                if (action === null) return;
                return $http({
                    url: $storeKpiUrl,
                    method: "POST",
                    data: {
                        action: action,
                        kpi: $scope.controllerData.kpi,
                    }
                }).then(function (response) {
                    if (response?.data?.code === 200 && response?.data?.data?.action !== 'delete') {
                        $scope.controllerData.kpis[$scope.controllerData.kpiEditingIndex] = response?.data?.data?.kpi;
                        $scope.controllerData.kpis[$scope.controllerData.kpiEditingIndex].kpi_id = response?.data?.data?.kpi?.kpi_id.toString()
                        $scope.controllerData.kpis[$scope.controllerData.kpiEditingIndex].nguoi_danh_gia = response?.data?.data?.kpi?.nguoi_danh_gia.toString()
                        $scope.controllerData.kpis[$scope.controllerData.kpiEditingIndex].user_id = response?.data?.data?.kpi?.user_id.toString()
                        $scope.controllerData.kpi.id = response?.data?.data?.kpi?.id
                        $scope.controllerData.action = 'update';
                    }
                    $scope.controllerData.notifyMessage = response?.data?.message
                    $scope.controllerData.notifyType = response?.data?.code === 200 ? 'success' : 'danger';
                    $scope.controllerData.actionLoading = false;
                    $successCallback(response);
                }, function (error) {
                    console.log(error)
                    $scope.controllerData.actionLoading = false;
                    $errorCallback(error);
                })
            },
            kpiDataChanged: function (key) {
                $scope.controllerData.kpi.hoan_thanh = $scope.controllerFunction.calcCompletePercentText($scope.controllerData.kpi.dinh_muc, $scope.controllerData.kpi.thuc_dat, $scope.controllerData.kpi.trong_so, $scope.controllerData.kpi.tru_kpi);
                $scope.controllerData.kpi.con_thieu = $scope.controllerFunction.calcConthieuText($scope.controllerData.kpi.dinh_muc, $scope.controllerData.kpi.thuc_dat, $scope.controllerData.kpi.trong_so, $scope.controllerData.kpi.tru_kpi);
                if (!$scope.controllerData.kpi.trong_so) {
                    $scope.controllerData.kpi.trong_so = null;
                }
                if ($scope.controllerData.kpi.trong_so && $scope.controllerData.kpi.trong_so >= 0 && $scope.controllerData.kpi.trong_so <= 100) {
                    let sum = 0;
                    $scope.controllerData.kpis.forEach((value, index) => {
                        sum += parseInt(value.trong_so ? value.trong_so : 0)
                    });
                    if (sum > 100) {
                        sum = 0;
                        $scope.controllerData.kpi.trong_so = null;
                    }
                }
                if (!$scope.controllerData.kpi.tru_kpi) {
                    $scope.controllerData.kpi.tru_kpi = 0;
                }
                if($scope.controllerData.kpi.kpi_id && $scope.controllerData.action !== 'update') {
                    let find = $scope.controllerData.kpisList.find(element => element.id == $scope.controllerData.kpi.kpi_id);
                    if(find) {
                        $scope.controllerData.kpi.dinh_muc = parseInt(find.dinh_muc_khoan)
                    }
                }
            },
            deleteKpiRow: function (index) {
                if (confirm('Bạn có thực sự muốn xóa bản ghi này!')) {
                    $scope.controllerData.action = 'delete';
                    $scope.controllerData.kpi = $scope.controllerData.kpis[index];
                    if ($scope.controllerData.kpi.id !== null) {
                        $scope.controllerFunction.storeKpiRow('delete', (response) => {
                            if (response?.data?.code === 200 && response?.data?.data?.action === 'delete') {
                                $scope.controllerData.kpiEditingIndex = null;
                                $scope.controllerData.kpis = $scope.controllerData.kpis.filter(function (v, i) {
                                    return index !== i;
                                });
                            }
                        });
                    } else {
                        $scope.controllerData.kpiEditingIndex = null;
                        $scope.controllerData.kpis = $scope.controllerData.kpis.filter(function (v, i) {
                            return index !== i;
                        });
                    }
                }
            },
            selectedYear: function () {
                if ($scope.controllerData.selectedYear && new Date().getFullYear() === $scope.controllerData.selectedYear) {
                    $scope.controllerData.month = $scope.controllerData.month.filter(item => {
                        return item <= new Date().getMonth();
                    })
                }
            },
            toggleType: function (type) {
                let t = Object.keys($scope.controllerData.chartInfo.chart_types).find(key => key === type)
                $scope.controllerData.chartInfo.chart_type = t ? type : 'line';
            },
            chartLabelsToText: function () {
                if (!$scope.controllerData.chartInfo.chart_labels.length) {
                    return '';
                }
                if ($scope.controllerData.chartInfo.chart_labels.length == 1) {
                    return 'tháng ' + $scope.controllerData.chartInfo.chart_labels[0];
                }
                let $t = '';
                $scope.controllerData.chartInfo.chart_labels.map((item, index, {length}) => {
                    $t += index === 0 ? 'tháng ' + item : (index === length - 1 ? ' và ' + item : ', ' + item)
                })
                return $t;
            },
            chooseKPItoGenChart: function (type) {
                if (type !== 'chiso') {
                    $scope.controllerData.chartInfo.chart_labels = $scope.controllerData.chartInfo.chart_labels.reverse();
                }
                if (type === 'chiso') {
                    let kpiai = $scope.controllerData.kpisList.find(element => element.id == $scope.controllerData.chartInfo.chart_kpi.id);
                    $scope.controllerData.chartInfo.chart_kpi.name = kpiai.name;
                }
                $http({
                    url: $getKpiStatisticalUrl,
                    method: "POST",
                    data: {
                        kpi_id: $scope.controllerData.chartInfo.chart_kpi.id,
                        times: $scope.controllerData.chartInfo.chart_labels,
                        user_id: $scope.controllerData.employ.id
                    }
                }).then(function (response) {
                    if (response?.data?.code === 200) {
                        $scope.controllerData.chartInfo.chart_data = response?.data?.data?.chart;
                    }
                }, function (error) {
                    console.log(error)
                });
            },
            onSelectedEmployChanged: function (type) {
                $scope.controllerData.kpiEditingIndex = null;
                $scope.controllerFunction.resetKpiRow();
                $scope.controllerData.kpi.thang = $scope.controllerData.selectedMonth;
                $scope.controllerData.kpi.nam = $scope.controllerData.selectedYear;
                if (type === 'branch' || type === 'department') {
                    $scope.controllerData.employs = [];
                    $scope.controllerData.selected_employ = null;
                    $scope.controllerData.employ = {};
                }
                if (!$scope.controllerData.employs.length) {
                    $http({
                        url: $getUsersUrl,
                        method: "POST",
                        data: {
                            branch_id: $scope.controllerData.selected_branch,
                            department_id: $scope.controllerData.selected_department,
                        }
                    }).then(function (response) {
                        if (response?.data?.code === 200) {
                            $scope.controllerData.employs = response?.data?.data?.user;
                        }
                    }, function (error) {
                        console.log(error)
                    })
                }
                if (type === 'year' || type === 'month') {
                    $scope.controllerData.chartInfo.chart_labels = [];
                    $scope.controllerData.chartInfo.chart_labels.push(($scope.controllerData.selectedMonth < 10 ? '0' + $scope.controllerData.selectedMonth : $scope.controllerData.selectedMonth) + "-" + $scope.controllerData.selectedYear)
                }
                if (type === 'employ' || type === 'year' || type === 'month') {
                    $scope.controllerData.employ = $scope.controllerData.employs.find(e => {
                        return parseInt(e.id) === parseInt($scope.controllerData.selected_employ)
                    });
                    $scope.controllerData.kpi.user_id = parseInt($scope.controllerData.selected_employ);
                    $http({
                        url: $getPersonalKpiInfoUrl,
                        method: "POST",
                        data: {
                            employ: parseInt($scope.controllerData.selected_employ),
                            year: parseInt($scope.controllerData.selectedYear),
                            month: parseInt($scope.controllerData.selectedMonth),
                        }
                    }).then(function (response) {
                        if (response?.data?.code === 200) {
                            $scope.controllerData.kpis = response?.data?.data?.kpis;
                        }
                    }, function (error) {
                        console.log(error)
                    });
                }
                if (type === 'employ') {
                    $scope.controllerFunction.chooseKPItoGenChart(type);
                }
            }
        }
    }
]);
