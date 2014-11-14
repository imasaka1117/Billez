/**
 * 新增更新正常繳費入帳帳單格式
 */
$(document).ready(function() {
	init();
	
	$("select").change(function() {
		switch($(this).attr('id')) {
			case 'import_bill_kind':
				push_log($(this).val());
				break;
		}
	});
});

//驗證欄位推播
function start_push() {
	if(validate()) {
		push_bill();
	}
}

//推播帳單
function push_bill() {
	var path = check_ajax(ajax_path + class_name + '/' + function_name, 
			  new Array('import_bill_kind', 'not_push_bill'), 
			  new Array('推播成功', '伺服器忙碌中！！請在試一次'));
	push_log($('#import_bill_kind').val()); 
}

//推播紀錄顯示
function push_log(kind) {
	if($('#push_table').attr('id') == undefined)
		$('#content_div').append('<table id="push_table" cellpadding="5"><tr><td>*本月尚未推播清單 : <select id="not_push_bill" class="required"></select></td><td><input type="button" id="push_btn" value="推播" onclick="start_push()" /></td></tr>	<tr><td>本月已推播清單 : </td></tr><tr><td><select id="pushed_bill" multiple="multiple" size="10" style="width: 300px"></select></td></tr></table>');

	switch(kind) {
		case '':
			$('#push_table').remove();
			break;
		case '1':
			select_ajax(ajax_path + 'common/init_not_push_bill', 'not_push_bill', kind);
			select_ajax(ajax_path + 'common/init_pushed_bill', 'pushed_bill', kind);
			break;
		case '2':
			select_ajax(ajax_path + 'common/init_not_push_bill', 'not_push_bill', kind);
			select_ajax(ajax_path + 'common/init_pushed_bill', 'pushed_bill', kind);
			break;		
	}
}

//初始化
function init() {

}