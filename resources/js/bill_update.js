/**
 * 更改帳單
 */
$(document).ready(function() {
	init();
});
             
//初始化
function init() {
	data = update_ajax(ajax_path + 'bill/search_data', id);
	
	for(i in data) {
		if(data[i] != null) $('#' + i).text(data[i]);
	}
}