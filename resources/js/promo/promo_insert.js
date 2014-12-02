/**
 * 新增等級對象
 */
$(document).ready(function() {
	init();
	
	$("#insert_btn").click(function() {
		if(validate()) insert();
	});
	
	$("select").change(function() {
		switch ($(this).attr('id')) {
			case 'begin_month':
			case 'end_month':
				days(this);
				break;
		}
	});
});

//將日期重製
function days(month) {
	$('#' + month.id.replace('_month', '') + '_day').empty().append(option_days(month.value));
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
		alert('活動開始日期大於結束日期');
		return false;
	}

	return true;
}

//新增等級對象
function insert() {
	if(!date_compare()) return false;

	var path = check_ajax(ajax_path + 'promo/insert', 
						  new Array('name', 'range', 'begin_year', 'begin_month', 'begin_day', 'end_year', 'end_month', 'end_day', 'way', 'level'), 
						  new Array('新增成功', '活動名稱已存在！！', '伺服器忙碌中！！請在試一次'));
	if(path != '') location.reload(); 
}

//初始化
function init() {
	begin_end('begin_year', 'begin_month');
	begin_end('end_year', 'end_month');
}