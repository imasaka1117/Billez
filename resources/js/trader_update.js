/**
 * 更改業者
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

//更改業者
function update() {
	var path = check_ajax(ajax_path + 'trader/update', 
						  new Array('id', 'name', 'telephone', 'level_code', 'vat_number', 'city', 'district', 'address', 'main_contact_name', 'main_contact_phone', 'main_contact_email', 'second_contact_name', 'second_contact_phone', 'second_contact_email', 'remark'), 
						  new Array('更改成功', '業者名稱已存在！！', '統一編號已存在！！', '伺服器忙碌中！！請在試一次'));
	if(path != '') location.href = ajax_path + path; 
}
             
//初始化
function init() {
	//將縣市初始化
	$("#city").empty().append(init_city());
	
	//將等級初始化 a 代表業者
	select_ajax(ajax_path + 'trader/init_level', 'level_code', 'a');
	
	data = update_ajax(ajax_path + 'trader/search_data', id);
	
	for(i in data) {
		if(i == 'district') $("#district").empty().append(init_district($("#city").val()));
		$('#' + i).val(data[i]);
	}
}