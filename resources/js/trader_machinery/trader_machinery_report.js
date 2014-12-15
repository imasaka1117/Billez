/**
 * 匯出業者或代收機構報表
 */
$(document).ready(function() {
	init();
	
	$("#export_btn").click(function() {
		if(validate()) export_data();
	});	
	
	$("select").change(function() {
		switch ($(this).attr('id')) {
			case 'begin_month':
			case 'end_month':
				days(this);
				break;
			case 'machinery':
				$('#machinery_contract').empty().append(select_ajax(ajax_path + 'common/init_machinery_contract', 'machinery_contract', $('#machinery').val()));
				break;
			case 'trader':
				$('#trader_contract').empty().append(select_ajax(ajax_path + 'common/init_trader_contract', 'trader_contract', $('#trader').val()));
				break;
		}
	});
	
});
             
//將日期重製
function days(month) {
	$('#' + month.id.replace('_month', '') + '_day').empty().append(option_days(month.value));
}

//匯出資料
function export_data() {
	if(!date_compare()) return false;
	if(class_name == 'trader') {
		var data = set_ajax(new Array('trader', 'trader_contract', 'begin_year', 'begin_month', 'begin_day', 'end_year', 'end_month', 'end_day'));
	} else {
		var data = set_ajax(new Array('machinery', 'machinery_contract', 'begin_year', 'begin_month', 'begin_day', 'end_year', 'end_month', 'end_day'));
	}
	export_ajax(ajax_path + class_name + '/report', data, 'post');
}

//初始化
function init() {
	begin_end('begin_year', 'begin_month');
	begin_end('end_year', 'end_month');

	if(class_name == 'trader') {
		select_ajax(ajax_path + 'common/init_trader', 'trader', '');
	} else {
		select_ajax(ajax_path + 'common/init_machinery', 'machinery', '');
	}
}

function begin_end(year, month) {
	$('#' + year).empty().append(option_years());
	$('#' + month).empty().append(option_months());
}

//比較合約日期的大小
function date_compare() {
	var begin = $('#begin_year').val() + '/' + $('#begin_month').val() + '/' + $('#begin_day').val();
	var end = $('#end_year').val() + '/' + $('#end_month').val() + '/' + $('#end_day').val();
	
	if(Date.parse(begin) > Date.parse(end)) {
		alert('開始日期大於結束日期');
		return false;
	}
	
	return true;
}