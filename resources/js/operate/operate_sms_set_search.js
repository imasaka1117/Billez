/**
 * 查詢簡訊設定
 */
$(document).ready(function() {
	init();

	$("#search_btn").click(function() {
		if($('#form_name').val() !== '') {
			window.location.href = ajax_path + class_name + '/update_sms_set_web?id=' + $('#form_name').val(); 
		} else {
			alert('未選擇格式名稱');
		}
	});
});

//初始化
function init() {
	//將加入會員認證碼初始化
	select_ajax(ajax_path + 'common/init_sms_set', 'join', '1');
	
	//將重複寄發加入會員認證碼初始化
	select_ajax(ajax_path + 'common/init_sms_set', 're_join', '2');
	
	//將修改資料認證碼初始化
	select_ajax(ajax_path + 'common/init_sms_set', 'alter', '3');
	
	//將重複寄發修改資料認證碼初始化
	select_ajax(ajax_path + 'common/init_sms_set', 're_alter', '4');
	
	//將分享帳單初始化
	select_ajax(ajax_path + 'common/init_sms_set', 'share', '5');
	
	//將推薦好友初始化
	select_ajax(ajax_path + 'common/init_sms_set', 'friend', '6');
	
	//將操作資料表初始化
	select_ajax(ajax_path + 'common/init_sms_form_name', 'form_name', '');
}