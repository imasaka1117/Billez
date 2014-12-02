/**
 * 查詢電子郵件設定
 */
$(document).ready(function() {
	init();

	$("#search_btn").click(function() {
		if($('#form_name').val() !== '') {
			window.location.href = ajax_path + class_name + '/update_email_set_web?id=' + $('#form_name').val(); 
		} else {
			alert('未選擇設定名稱');
		}
	});
});

//初始化
function init() {
	//將忘記密碼初始化
	select_ajax(ajax_path + 'common/init_email_set', 'forget_password', '1');
	
	//將電子帳單初始化
	select_ajax(ajax_path + 'common/init_email_set', 'email_bill', '2');
	
	//將印刷業者初始化
	select_ajax(ajax_path + 'common/init_email_set', 'print_trader', '3');
	
	//將問題回報初始化
	select_ajax(ajax_path + 'common/init_email_set', 'problem_reply', '4');
	
	//將設定名稱初始化
	select_ajax(ajax_path + 'common/init_form_name', 'form_name', '');
}