/**
 * 新增等級
 */
$(document).ready(function() {
	init();
	
	$("#insert_btn").click(function() {
		if(validate()) insert();
	});
});

//新增等級
function insert() {
	var path = check_ajax(ajax_path + 'level/insert', 
						  new Array('name'), 
						  new Array('新增成功', '等級名稱已存在！！', '伺服器忙碌中！！請在試一次'));
	if(path != '') location.reload(); 
}

//初始化
function init() {

}