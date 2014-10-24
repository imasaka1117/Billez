/**
 * 新增繳費帳單格式
 */
$(document).ready(function() {
	init();
	
	$("#insert_btn").click(function() {
		check_field();
		
		if(validate()) insert();
	});	
	
	$("select").change(function() {
		switch($(this).attr('id')) {
			case 'trader':
				$('#bill_kind').empty().append(select_ajax(ajax_path + 'bill/init_bill_kind', 'bill_kind', $(this).val()));
				break;
		}
	});
});

//確認欄位輸入
function check_field() {
	$("input").each(function() {  
		if($('#' + $(this).attr('id')).attr('class') != undefined) {
			if($('#' + $(this).attr('id')).attr('class').indexOf('begin') >= 0) {
				if($('#' + $(this).attr('id')).val() == '') {
					if($('#' + $(this).attr('id') + '2').val() == '') {
						$('#' + $(this).attr('id')).removeAttr('class');
						$('#' + $(this).attr('id')).attr('class', 'begin');
						$('#' + $(this).attr('id') + '2').removeAttr('class');
					} else {
						$('#' + $(this).attr('id')).attr('class', 'begin,required,digits');
						$('#' + $(this).attr('id') + '2').attr('class', 'required,digits');
					}
				} else {
					$('#' + $(this).attr('id')).attr('class', 'begin,required,digits');
					$('#' + $(this).attr('id') + '2').attr('class', 'required,digits');
				}
			}
		}
	});
}

//新增繳費帳單格式
function insert() {
	var ids = new Array();
	
	$("input").each(function() { 
		ids.push($(this).attr('id'));
	});

	ids.push('trader');
	ids.push('bill_kind');
	
	var path = check_ajax(ajax_path + class_path, ids, new Array('新增成功', '該業者帳單設定已存在！！', '伺服器忙碌中！！請在試一次'));
	if(path != '') location.reload(); 
}

//初始化
function init() {
	//將業者初始化
	select_ajax(ajax_path + 'bill/init_trader', 'trader', '');
}