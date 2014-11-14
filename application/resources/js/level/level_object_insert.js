/**
 * 新增等級對象
 */
$(document).ready(function() {
	init();
	
	$("#insert_btn").click(function() {
		if(validate()) insert();
	});
});

//新增等級對象
function insert() {
	var path = check_ajax(ajax_path + 'level/insert_object', 
						  new Array('name'), 
						  new Array('新增成功', '等級對象已存在！！', '伺服器忙碌中！！請在試一次'));
	if(path != '') location.reload(); 
}

//初始化
function init() {
	
}