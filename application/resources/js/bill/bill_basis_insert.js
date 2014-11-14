/**
 * 新增帳單依據
 */
$(document).ready(function() {
	init();
	
	$("#insert_btn").click(function() {
		if(validate()) insert();
	});
});

//新增帳單依據
function insert() {
	var path = check_ajax(ajax_path + 'bill/insert_basis', 
						  new Array('name', 'digit'), 
						  new Array('新增成功', '帳單依據名稱已存在！！', '伺服器忙碌中！！請在試一次'));
	if(path != '') location.reload(); 
}

//初始化
function init() {
	$('#digit').empty().append(option_ages(20));
	$('#digit').append('<option value="n">不固定</option>');
}