/**
 * 查詢電子郵件設定
 */
$(document).ready(function() {
	init();

	$("#search_btn").click(function() {
		window.location.href = ajax_path + class_name + '/update_email_set_web?id=' + $('#form_name').val(); 
	});
});

//初始化
function init() {
	//將操作種類初始化
	select_ajax(ajax_path + 'common/init_email_set', 'forget_password', '1');
	
	//將操作者初始化
	select_ajax(ajax_path + 'common/init_email_set', 'email_bill', '2');
	
	//將操作時間初始化
	select_ajax(ajax_path + 'common/init_email_set', 'print_trader', '3');
	
	//將操作資料表初始化
	select_ajax(ajax_path + 'common/init_email_set', 'problem_reply', '4');
	
	//將操作資料表初始化
	select_ajax(ajax_path + 'common/init_form_name', 'form_name', '');
}