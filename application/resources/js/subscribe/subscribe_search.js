/**
 * 查詢訂閱
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
}

//查詢其他頁面
function search_page_num(page) {
	search_ajax(ajax_path + class_name + '/search', new Array("id", "trader", "bill_kind", "name", "subscribe", "email"), 'query_div', page)
}
