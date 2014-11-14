/**
 * 更改業者訂閱狀態
 */
$(document).ready(function() {
	init();
	
	$("#update_btn").click(function() {
		if(validate()) update();
	});
	
	$("select").change(function() {
		switch($(this).attr('id')) {
			case 'trader':
				$('#bill_kind').empty().append(select_ajax(ajax_path + 'common/init_bill_kind', 'bill_kind', $(this).val()));
				break;
		}
	});
});

//新增帳單依據
function update() {
	var path = check_ajax(ajax_path + class_name + '/update_state', 
						  new Array('trader', 'bill_kind', 'state'), 
						  new Array('更改成功', '伺服器忙碌中！！請在試一次'));
	if(path != '') location.reload(); 
}

//初始化
function init() {
	//將業者初始化
	select_ajax(ajax_path + 'common/init_trader', 'trader', '');
}