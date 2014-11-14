/**
 * 更改行動會員
 */
$(document).ready(function() {
	init();
	
	$("#update_btn").click(function() {
		update();
	});	
	
});
             
//更新密碼
function update() {
	var path = check_ajax(ajax_path + class_name + '/update', 
						  new Array('id'), 
						  new Array('更改成功', '伺服器忙碌中！！請在試一次'));
	if(path !== '') location.reload(); 
}

//初始化
function init() {
	data = update_ajax(ajax_path + class_name + '/search_data', id);
	
	for(i in data) {
		if(data[i] != null) $('#' + i).text(data[i]);
		if(i == 'bill_memo') {
			for(k in data[i]) {
				$('#bill_memo').append('<option value="">' + data[i][k] + '</option>');
			}
		}
	}
}