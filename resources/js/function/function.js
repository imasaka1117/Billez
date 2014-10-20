/**
 * 	通用函式
 */

/*
 *  ajax 參數配置
 *  	element_id 是HTML表單元素id屬性
 */
function set_ajax(element_id) {
	var obj = new Object();
	for(var post_name in element_id) {
		if($("#" + element_id[post_name] + "").val() == "" && $("#" + element_id[post_name] + "").text() != "") {
			obj[element_id[post_name]] = $("#" + element_id[post_name] + "").text();
		} else {
			obj[element_id[post_name]] = $("#" + element_id[post_name] + "").val();
		}
	}
	return obj;
}

/*
 * 	ajax 檢查
 * 		controllers 是傳送位置
 * 		params		是要傳遞的參數
 *  	result 		是結果對應 
 */
function check_ajax(controllers, params, result) {
	var post_vars = set_ajax(params);
	var path = '';
	$.post(controllers, $.param(post_vars), function(ajax_return) {
		alert(ajax_return);
		var result_count = result.length;
		
		if(ajax_return.length > 1) {
			path = ajax_return;
			alert(result[0]);
		} else {
			alert(result[ajax_return]);
		}
	});
	
	return path;
}

/*
 *  select ajax資料填入
 * 		controllers 是傳送位置
 *  	id 			要對應的元素ID
 *  	value		若沒有則傳入空白''
 */
function select_ajax(controllers, id, value) {
	$.post(controllers, $.param({ 'value' : value }), function(ajax_return) {
		alert(ajax_return);
		$("#" + id + "").empty().append(ajax_return);
	});
}



