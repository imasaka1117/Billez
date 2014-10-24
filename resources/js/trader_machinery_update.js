/**
 * 更改代收機構
 */
$(document).ready(function() {
	init();
	
	$("#insert_btn").click(function() {
		if(validate()) update();
	});	
	
	$("select").change(function() {
		switch ($(this).attr('id')) {
			case 'city':
				$("#district").empty().append(init_district($("#city").val()));
				break;
		}
	});	
});

//更改代收機構
function update() {
	var path = check_ajax(ajax_path + class_path, 
						  new Array('id', 'name', 'telephone', 'level_code', 'vat_number', 'city', 'district', 'address', 'main_contact_name', 'main_contact_phone', 'main_contact_email', 'second_contact_name', 'second_contact_phone', 'second_contact_email', 'remark'), 
						  new Array('更改成功', error_word, '統一編號已存在！！', '伺服器忙碌中！！請在試一次'));
	if(path !== '') location.href = ajax_path + path; 
}
             
//初始化
function init() {
	//將縣市初始化
	$("#city").empty().append(init_city());
	
	//將等級初始化 b 代表代收機構
	select_ajax(ajax_path + level_init, 'level_code', level_value);
	
	data = update_ajax(ajax_path + search_path, id);
	
	for(i in data) {
		if(i === 'district') $("#district").empty().append(init_district($("#city").val()));
		$('#' + i).val(data[i]);
	}
}