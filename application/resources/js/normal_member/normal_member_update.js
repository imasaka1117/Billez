/**
 * 更改行動會員
 */
$(document).ready(function() {
	init();
});
             
//初始化
function init() {
	data = update_ajax(ajax_path + class_name + '/search_data', id);
	
	for(i in data) {
		if(data[i] != null) $('#' + i).text(data[i]);
		if(i == 'subscribe') {
			for(k in data[i]) {
				$('#subscribe').append('<option value="">' + data[i][k] + '</option>');
			}
		}
	}
}