/**
 * 新增帳單種類
 */
$(document).ready(function() {
	init();
	
	$("#insert_btn").click(function() {
		if(validate()) insert();
	});
});

//新增帳單種類
function insert() {
	var path = check_ajax(ajax_path + 'bill/insert_kind', 
						  new Array('name'), 
						  new Array('新增成功', '帳單種類名稱已存在！！', '伺服器忙碌中！！請在試一次'));
	if(path != '') location.reload(); 
}

//初始化
function init() {
	
}