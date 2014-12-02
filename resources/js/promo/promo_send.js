/**
 * 寄發促銷活動
 */
$(document).ready(function() {
	init();

	$("#export_btn").click(function() {
		if(validate()) export_email();
	});
});

//初始化
function init() {
	
}

//查詢其他頁面
function export_email() {
	var data = new Object();
	data['range'] = $('#range').val();
	export_ajax(ajax_path + 'promo/send', data, 'post');
}
