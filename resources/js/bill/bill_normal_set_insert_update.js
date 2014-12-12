/**
 * 新增更新正常繳費入帳帳單格式
 */
$(document).ready(function() {
	init();
	
	$("#insert_btn").click(function() {
		check_field();
		
		if(validate()) insert_update();
	});	
	
	$("select").change(function() {
		switch($(this).attr('id')) {
			case 'trader':
				$('#bill_kind').empty().append(select_ajax(ajax_path + 'common/init_trader_bill_kind', 'bill_kind', $(this).val()));
				break;
			case 'bill_kind':
				search_data();
				break;
		}
	});
});

//查詢資料並帶入
function search_data() {
	if(id === 'pay') {
		var table = 'trader_publish_bill_form';
	} else {
		var table = 'trader_receive_bill_form';
	}

	data = update_ajax(ajax_path + class_name + '/search_normal_set_data', $('#trader').val() + ',' + $('#bill_kind').val() + ',' + table);

	if(data === 1) {
		alert('該業者帳單尚未新增!!');
		location.reload(); 
	}
	
	data_parse(data);
}

//解析資料並帶入
function data_parse(data) {
	for(var i in data) {
		if($('#' + i).attr('id') === undefined) continue;
		
		var datas = data[i].split(',');
		
		$('#' + i).val(datas[0]);
		$('#' + i + '2').val(datas[1]);
	}
}

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

//新增更新繳費入帳帳單格式
function insert_update() {
	var ids = new Array();
	
	$("input").each(function() { 
		ids.push($(this).attr('id'));
	});

	ids.push('trader');
	ids.push('bill_kind');
	
	var path = check_ajax(ajax_path + class_name + '/' + function_name, ids, new Array('操作成功', '該業者帳單設定已存在！！', '伺服器忙碌中！！請在試一次'));
	if(path != '') location.reload(); 
}

//初始化
function init() {
	//將業者初始化
	select_ajax(ajax_path + 'common/init_trader', 'trader', '');
}