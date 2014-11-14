/**
 * 查詢電子郵件設定
 */
$(document).ready(function() {
	init();

	$("#search_btn").click(function() {
		window.location.href = ajax_path + class_name + '/update_system_set_web?id=' + $('#name').val(); 
	});
});

//初始化
function init() {
	//將目前設定初始化
	select_ajax(ajax_path + 'common/init_system_set', 'system_set', '');
	
	//將設定名稱初始化
	select_ajax(ajax_path + 'common/init_system_name', 'name', '');
}