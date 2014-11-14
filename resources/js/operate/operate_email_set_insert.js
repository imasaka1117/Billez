/**
 * 新增電子郵件設定
 */
$(document).ready(function() {
	init();
	
	$("#insert_btn").click(function() {
		if(validate()) insert();
	});
});

//新增電子郵件設定
function insert() {
	var path = check_ajax(ajax_path + 'operate/insert_email_set', 
						  new Array('form_name', 'form_kind', 'server_name', 'server_port', 'account', 'password', 'send_email', 'send_name', 'subject', 'body'), 
						  new Array('新增成功', '設定名稱已存在', '伺服器忙碌中！！請在試一次'));
	if(path != '') location.reload(); 
}

//初始化
function init() {
	
}