/**
 * 	通用函式
 */

/*
 * 	檢查輸入是否空白
 * 		element_id 是HTML表單元素id屬性
 */
function check_blank(element_id) {
	switch (typeof(element_id)) {
		case 'string':
			if($("#" + element_id + "").val() == "") {
				alert("* 號欄位請勿空白 ! ");
				return false;
			}	
			break;
		case 'object':
			var element_id_count = element_id.length;
			for(var i = 0; i < element_id_count; i++) {
				if($("#" + element_id[i] + "").val() == "") {
					alert("* 號欄位請勿空白 ! ");
					return false;
				}	
			}	
			break;
	}
	return true;
}

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
 *  	result 		是結果對應 若是空白則是跳轉頁面
 */
function check_ajax(controllers, params, result) {
	var post_vars = set_ajax(params);
	var flag = false;
	$.post(controllers, $.param(post_vars), function(ajax_return) {
		var result_count = result.length;
		for(var i = 0; i < result_count; i++) {
			if(i == ajax_return) {
				if(result[i] == "") {
					flag = true;
				} else {
					alert(result[i]);
				}
			}
		}
	});
	return flag;
}






