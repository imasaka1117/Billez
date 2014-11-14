/**
 * 更改等級名稱
 */
$(document).ready(function() {
	init();
	
	$("#update_btn").click(function() {
		if(validate()) update();
	});	
	
});
             
//更新密碼
function update() {
	var path = check_ajax(ajax_path + class_name + '/update', 
						  new Array('id', 'object', 'name'), 
						  new Array('更改成功', '已有相同等級名稱存在！！', '伺服器忙碌中！！請在試一次'));
	if(path !== '') location.reload(); 
}

//初始化
function init() {
	data = update_ajax(ajax_path + class_name + '/search_data', id);
	
	for(i in data) {
		if(data[i] != null) $('#' + i).text(data[i]);
		if(i == 'name') $('#' + i).val(data[i]);
	}
}