/**
 * 查詢帳單
 */
$(document).ready(function() {
	init();

	$("#search_btn").click(function() {
		search_page_num(1);
	});
	
	$("select").change(function() {
		switch($(this).attr('id')) {
			case 'trader':
				$('#bill_kind').empty().append(select_ajax(ajax_path + 'bill/init_bill_kind', 'bill_kind', $(this).val()));
				break;
			case 'year':
				$('#month').empty().append(select_ajax(ajax_path + 'bill/init_month', 'month', $(this).val()));
				break;
		}
	});
});

//初始化
function init() {
	//將業者初始化
	select_ajax(ajax_path + 'bill/init_trader', 'trader', '');
	
	//將年度初始化
	select_ajax(ajax_path + 'bill/init_year', 'year', '');
}

//查詢其他頁面
function search_page_num(page) {
	search_ajax(ajax_path + 'bill/search', new Array("billez_code", "trader", "bill_kind", "owner", "year", "month", "identify_data"), 'query_div', page)
}
