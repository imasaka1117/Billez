/**
 * 查詢使用者
 */

$(document).ready(function() {
	init();

	$("#search_btn").click(function() {
		search_page_num(1);
	});
});

//
function init() {}

//查詢其他頁面
function search_page_num(page) 
{
	search_ajax(ajax_path + class_name + '/search', new Array("id","kind","email","password","name"), 'query_div', page)
}
