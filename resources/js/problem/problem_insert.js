/**
 * 新增問題
 */
$(document).ready(function() {
	init();
	
	$("#insert_btn").click(function() {
		if(validate()) insert();
	});
});

//新增等級對象
function insert() {
	var path = check_ajax(ajax_path + 'problem/insert', 
						  new Array('problem', 'scope', 'email'), 
						  new Array('新增成功', '該提問者不存在！！', '伺服器忙碌中！！請在試一次'));
	if(path != '') location.reload(); 
}

//初始化
function init() {
	
}