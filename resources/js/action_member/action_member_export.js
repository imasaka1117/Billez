/**
 * 匯出行動會員
 */
$(document).ready(function() {
	init();
	
	$("#export_btn").click(function() {
		export_data();
	});	
	
	$("select").change(function() {
		switch($(this).attr('id')) {
			case 'trader':
				$('#bill_kind').empty().append(select_ajax(ajax_path + 'common/init_trader_bill_kind', 'bill_kind', $(this).val()));
				break;
		}
	});
});
             
//匯出資料
function export_data() {
	var data = set_ajax(new Array('trader', 'bill_kind'));
	
	export_ajax(ajax_path + class_name + '/export', data, 'post');
}

//初始化
function init() {
	//將業者初始化
	select_ajax(ajax_path + 'common/init_trader', 'trader', '');

}