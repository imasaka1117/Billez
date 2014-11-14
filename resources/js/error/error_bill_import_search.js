/**
 * 查詢帳單匯入錯誤
 */
$(document).ready(function() {
	init();

	$("#search_btn").click(function() {
		search_page_num(1);
	});
	
	$("select").change(function() {
		switch($(this).attr('id')) {
			case 'trader':
				$('#bill_kind').empty().append(select_ajax(ajax_path + 'common/init_bill_kind', 'bill_kind', $(this).val()));
				break;
		}
	});
});

//初始化
function init() {
	//將業者初始化
	select_ajax(ajax_path + 'common/init_trader', 'trader', '');
	
	//將匯入種類初始化
	select_ajax(ajax_path + 'common/init_import_kind', 'import_kind', '');
	
	//將處理狀況初始化
	select_ajax(ajax_path + 'common/init_state', 'state', '');
	
	//將匯入時間初始化
	select_ajax(ajax_path + 'common/init_time', 'time', '');
}

//查詢其他頁面
function search_page_num(page) {
	search_ajax(ajax_path + class_name + '/search_bill_import', new Array("trader", "bill_kind", "import_kind", "state", "time"), 'query_div', page)
}
