/**
 * 新增帳單依據
 */
$(document).ready(function() {
	init();
	
	$("#insert_btn").click(function() {
		if(validate()) insert();
	});
});

//新增帳單依據
function insert() {
	var path = check_ajax(ajax_path + 'level/insert_name', 
						  new Array('name', 'object'), 
						  new Array('新增成功', '等級名稱已存在！！', '伺服器忙碌中！！請在試一次'));
	if(path != '') location.reload(); 
}

//初始化
function init() {
	//將等級對象初始化
	select_ajax(ajax_path + 'common/init_object', 'object', '');
}