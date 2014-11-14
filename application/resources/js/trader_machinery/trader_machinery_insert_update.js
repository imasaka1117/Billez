/**
 * 新增業者及代收業者
 */
$(document).ready(function() {
	init();

	$("#insert_btn").click(function() {
		if(validate()) {
			if(typeof(id) !== 'string') insert(); else update();
		}
	});	

	$("select").change(function() {
		switch ($(this).attr('id')) {
			case 'city':
				$("#district").empty().append(init_district($("#city").val()));
				break;
		}
	});	
});

//新增
function insert() {
	var path = check_ajax(ajax_path + class_name + '/insert', 
						  new Array('name', 'telephone', 'level_code', 'vat_number', 'city', 'district', 'address', 'main_contact_name', 'main_contact_phone', 'main_contact_email', 'second_contact_name', 'second_contact_phone', 'second_contact_email', 'remark'), 
						  new Array('新增成功', '名稱已存在！！', '統一編號已存在！！', '伺服器忙碌中！！請在試一次'));
	if(path != '') location.reload(); 
}
             
//更改
function update() {
	var path = check_ajax(ajax_path + class_name + '/update', 
						  new Array('id', 'name', 'telephone', 'level_code', 'vat_number', 'city', 'district', 'address', 'main_contact_name', 'main_contact_phone', 'main_contact_email', 'second_contact_name', 'second_contact_phone', 'second_contact_email', 'remark'), 
						  new Array('更改成功', '名稱已存在！！', '統一編號已存在！！', '伺服器忙碌中！！請在試一次'));
	if(path !== '') location.href = ajax_path + path; 
}

//初始化
function init() {
	//將縣市初始化
	$("#city").empty().append(init_city());
	
	//將等級初始化 a 代表業者 b 代表代收機構
	select_ajax(ajax_path + 'common/init_level', 'level_code', level_value);

	if(typeof(id) === 'string') init_update();
}

//帶入資料
function init_update() {
	data = update_ajax(ajax_path + class_name + '/search_data', id);

	for(var i in data) {
		if(i === 'district') $("#district").empty().append(init_district($("#city").val()));
		$('#' + i).val(data[i]);
	}
}