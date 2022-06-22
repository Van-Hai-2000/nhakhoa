//Thêm thủ thuật khám bệnh
function them_thu_thuat_kham_benh(p_list_id, p_class_row, p_url_get_html) {
    if (p_list_id != '' && p_class_row != '' && p_url_get_html != '') {
        obj = document.getElementsByClassName(p_class_row);
        if (!obj) {
            return false;
        }
        var obj_length = obj.length;
        if (typeof (obj_length) === 'undefined') {
            return false;
        }
        var v_max_stt = 0;
        for (i = 0; i < obj_length; i++) {
            v_stt = parseInt(obj[i].getAttribute('itemid'));
            if (isNaN(v_stt)) {
                v_stt = 1;
            }
            if (v_stt > v_max_stt) {
                v_max_stt = v_stt;
            }
        }
        var item_stt = parseInt(v_max_stt) + 1;
        var v_url_get_html = p_url_get_html + '?stt=' + item_stt;
        $.get(v_url_get_html, function (data) {
            $('#' + p_list_id).append(data);
            jQuery("#product_category_" + item_stt).select2({
                placeholder: "Chọn nhóm thủ thuật",
                allowClear: true,
            });
            jQuery("#doctor_" + item_stt).select2({
                placeholder: "Chọn bác sĩ",
                allowClear: true,
            });
            jQuery("#sale_" + item_stt).select2({
                placeholder: "Chọn Sale",
                allowClear: true,
            });
            jQuery("#nguoi_cham_soc_" + item_stt).select2({
                placeholder: "Chọn người chắm sóc lại",
                allowClear: true,
            });
            jQuery("#team_" + item_stt).select2({
                placeholder: "Chọn đội ngũ tham gia",
                allowClear: true,
            });
        });
    }


}

//Xóa thủ thuật khám bệnh đang được chọn trên màn hình khám bệnh
function delete_thu_thuat_kham_benh(stt) {
    if (document.getElementById('dstt_' + stt)) {
        if (confirm("Xác nhận xóa mục?")) {
            $('#dstt_' + stt).remove();
        }
    }
}

//Khi thay đổi nhóm thủ thuật trên màn hình khám bệnh
function change_product_cat(item_stt,url_ajax) {
    set_value_to_input('','total_price_'+item_stt);
    set_value_to_input('','quantity_'+item_stt);
    var product_category_id = $('#product_category_'+ item_stt).val();
    var studentSelect = $('#product_' + item_stt);
    $.ajax({
        url: url_ajax,
        data: {
            product_category_id: product_category_id
        },
        success: function (data) {
            var res = JSON.parse(data);
            studentSelect.empty();
            var html = '<option></option>';
            $.each(res, function (key, value) {
                html += '<option onchange="change_product('+url_ajax+')" value="'+key+'">'+value+'</option>';
            });
            studentSelect.append(html);
            jQuery("#product_" + item_stt).select2({
                placeholder: "Chọn thủ thuật",
                allowClear: true,
            });
        }
    })
}

//Khi thay đổi thủ thuật trên màn hình khám bệnh
function change_product(item_stt,url_ajax) {
    var product_id = $('#product_' + item_stt).val();
    $.ajax({
        url: url_ajax+'?type=2',
        data: {
            product_id: product_id
        },
        success: function (data) {
            var res = JSON.parse(data);
            if(res){
                set_value_to_input(res.price,'total_price_'+item_stt);
                set_value_to_input(1,'quantity_'+item_stt);
                $('#product_price_hidden_'+item_stt).val(res.price)
            }
        }
    })
}

function set_value_to_input(value,elm_id) {
    value = parseInt(value);
    if(document.getElementById(elm_id)){
        $('#'+elm_id).val(value);
    }
}
function change_quantity(item_stt) {
    var total_price = $('#product_price_hidden_'+item_stt).val();
    var quantity = $('#quantity_'+item_stt).val();
    $('#total_price_'+item_stt).val(total_price*quantity);
}

//Hàm gen html sau khi chọn những người được hưởng hoa hồng từ màn hình khám bệnh
function genderTeamV2(t,item_stt) {
    $('#dshh_'+item_stt).empty();
    var html = '<div class="title-dstt-commission">Danh sách người hưởng hoa hồng</div>';
    $('#team_'+item_stt+' option:selected').each(function () {
        var $this = $(this);
        if ($this.length) {
            var selText = $this.text();
            var selVal = $this.val();
            html += '<div class="row nguoi_huong_hoa_hong" style="margin-bottom: 3px">\n' +
                '        <div class="col-md-3">\n' +
                '            <select class="form-control" name="team_item_user[' + item_stt + '][]">\n' +
                '                <option value="' + selVal + '" selected>' + selText + '</option>\n' +
                '            </select>\n' +
                '        </div>\n' +
                '        <div class="col-md-3">\n' +
                '            <select class="form-control loai_huong_hoa_hong" name="team_item_type[' + item_stt + '][]">\n' +
                '                <option value="1" selected>Hưởng theo %</option>\n' +
                '                <option value="2">Hưởng tiền mặt</option>\n' +
                '            </select>\n' +
                '        </div>\n' +
                '        <div class="col-md-6">\n' +
                '            <input type="text" class="form-control gia_tri_hoa_hong" name="team_item_value[' + item_stt + '][]" placeholder="Nhập giá trị" required>\n' +
                '        </div>\n' +
                '    </div>';
        }
    });
    $('#dshh_'+item_stt).append(html);
}

//Gen giao diện thanh toán
function payment(url) {
    $.ajax({
        url: url,
        success: function (data) {
            $('#form_payment').empty().append(data)
        }
    })
}

function edit_payment(id) {
    if(document.getElementsByClassName('change_text')){
        $('.change_text').text('Sửa');
    }
    $('#action_'+id).toggleClass('change_text');
    $('.change_text').text('Hủy');
    change_status_element('pay_branch_'+id,'pay_branch['+id+']');
    change_status_element('pay_time_create_'+id,'pay_time_create['+id+']');
    change_status_element('pay_money_'+id,'pay_money['+id+']');
    change_status_element('type_payment_'+id,'type_payment['+id+']');
    change_status_element('pay_sale_note_'+id,'pay_sale_note['+id+']');
}

function change_status_element(elm_id,name) {
    $('#'+elm_id).attr('name',name);
    var isDisabled = $('#'+elm_id).prop('disabled');
    if (isDisabled){
        $('#'+elm_id).removeAttr('disabled');
    }else{
        $('#'+elm_id).attr('name','');
        $('#'+elm_id).attr('disabled','true');
    }
}

function submit_pay(url) {
    $.ajax({
        url: url,
        type: 'POST',
        data: $('#form_thanh_toan').serialize(),
        success: function (data) {
            console.log(data);
            // $(".close_form").trigger('click');
            // alert(data)
            // setTimeout(function () {
            //     window.location.reload()
            // }, 2000);
        }
    })
}

$('#form_kham_benh').on('submit', function (e) {
    var check = true;
    $('.danh_sach_huong_hoa_hong').each(function () {
        var cm = 0;
        var type1 = false;
        $(this).find('.nguoi_huong_hoa_hong').each(function () {
            var $this = $(this);
            if ($this.length) {
                var selType = $this.find('.loai_huong_hoa_hong').val();
                var selVal = $this.find('.gia_tri_hoa_hong').val();
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