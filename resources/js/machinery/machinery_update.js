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
	
	//暫存
	var machinery = '';
});

//更改代收機構
function update() {
	var path = check_ajax(ajax_path + 'machinery/update', 
						  new Array('id', 'name', 'telephone', 'level_code', 'vat_number', 'city', 'district', 'address', 'main_contact_name', 'main_contact_phone', 'main_contact_email', 'second_contact_name', 'second_contact_phone', 'second_contact_email', 'remark'), 
						  new Array('更改成功', '代收機構名稱已存在！！', '統一編號已存在！！', '伺服器忙碌中！！請在試一次'));
	if(path !== '') location.href = ajax_path + path; 
}
             
//初始化
function init() {
	//暫存
	var machinery = '';
	
	//將縣市初始化
	$("#city").empty().append(init_city());
	
	//將等級初始化 b 代表代收機構
	select_ajax(ajax_path + 'machinery/init_level', 'level_code', 'b');
	
	data = update_ajax(ajax_path + 'machinery/search_data', id);
	
	for(i in data) {
		if(i === 'district') $("#district").empty().append(init_district($("#city").val()));
		$('#' + i).val(data[i]);
	}
}