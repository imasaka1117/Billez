/**
 * 
 */
$(document).ready(function() {
	init();
	

	
	$("select").change(function() {
		switch ($(this).attr('id')) {
			case 'city':
				$("#district").empty().append(init_district($("#city").val()));
				break;
		}
	});	
});

function insert() {
	var path = check_ajax(ajax_path + 'trader/insert', 
						  new Array('name', 'telephone', 'level', 'vat_number', 'city', 'district', 'address', 'main_contact_person_name', 'main_contact_person_phone', 'main_contact_person_email', 'second_contact_person_name', 'second_contact_person_phone', 'second_contact_person_email', 'remark'), 
						  new Array('新增成功', '業者名稱已存在！！', '統一編號已存在！！', '伺服器忙碌中！！請在試一次'));
	if(path != '') window.location.href = ajax_path + path; 
}
             

function init() {
	//將縣市初始化
	$("#city").empty().append(init_city());
	validate()
	//將等級初始化 a 代表業者
	select_ajax(ajax_path + 'trader/init_level', 'level', 'a');
}























