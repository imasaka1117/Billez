/**
 * 新增代收機構
 */
$(document).ready(function() {
	//暫存
	var machinery = '';
	
	init();
	
	$("#insert_btn").click(function() {
		if(validate()) insert();
	});	
	
	$("select").change(function() {
		switch ($(this).attr('id')) {
			case 'city':
				$("#district").empty().append(init_district($("#city").val()));
				break;
		}
	});	
});

//新增代收機構
function insert() {
	//暫存
	var machinery = '';
	
	var path = check_ajax(ajax_path + 'machinery/insert', 
						  new Array('name', 'telephone', 'level_code', 'vat_number', 'city', 'district', 'address', 'main_contact_name', 'main_contact_phone', 'main_contact_email', 'second_contact_name', 'second_contact_phone', 'second_contact_email', 'remark'), 
						  new Array('新增成功', '代收機構名稱已存在！！', '統一編號已存在！！', '伺服器忙碌中！！請在試一次'));
	if(path != '') location.reload(); 
}
             
//初始化
function init() {
	//將縣市初始化
	$("#city").empty().append(init_city());
	
	//將等級初始化 a 代表代收機構
	select_ajax(ajax_path + 'machinery/init_level', 'level_code', 'b');
}