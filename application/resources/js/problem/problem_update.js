/**
 * 更改問題內容
 */
$(document).ready(function() {
	init();
	
	$("#update_btn").click(function() {
		if(validate()) update();
	});	
	
});
             
//更新問題
function update() {
	var path = check_ajax(ajax_path + class_name + '/update', 
						  new Array('id', 'answer', 'response', 'asker', 'problem'), 
						  new Array('更改成功', '伺服器忙碌中！！請在試一次'));
	if(path !== '') location.reload(); 
}

//初始化
function init() {
	data = update_ajax(ajax_path + class_name + '/search_data', id);
	
	for(i in data) {
		if(data[i] != null) $('#' + i).text(data[i]);
		if(i == 'answer') $('#' + i).val(data[i]);
	}
	
	if($('#state').text() == "已回覆") {
		$('#answer').attr("disabled",true);
		$('#response').attr("disabled",true);
		$('#update_btn').hide();
	}
}