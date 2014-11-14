/**
 * 更改電子郵件設定
 */
$(document).ready(function() {
	init();
	
	$("#update_btn").click(function() {
		if(validate()) update();
	});	
	
});
             
//更新問題
function update() {
	var path = check_ajax(ajax_path + class_name + '/update_email_set', 
						  new Array('form_name', 'form_kind', 'state', 'server_name', 'server_port', 'account', 'password', 'send_email', 'send_name', 'subject', 'body'), 
						  new Array('更改成功', '伺服器忙碌中！！請在試一次'));
	if(path !== '') window.location.href = ajax_path + class_name + '/search_email_set_web	'; 
}

//初始化
function init() {
	data = update_ajax(ajax_path + class_name + '/search_email_set_data', id);
	
	for(i in data) {
		if(data[i] != null) $('#' + i).val(data[i]);
		if(i == 'form_name') $('#' + i).text(data[i]);
	}
}