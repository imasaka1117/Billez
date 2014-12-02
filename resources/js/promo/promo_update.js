/**
 * 更改等級名稱
 */
$(document).ready(function() {
	init();
	
	$("#update_btn").click(function() {
		if(validate()) update();
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

//更新密碼
function update() {
	if(!date_compare()) return false;
	
	var path = check_ajax(ajax_path + class_name + '/update', 
						  new Array('id', 'name', 'range', 'begin_year', 'begin_month', 'begin_day', 'end_year', 'end_month', 'end_day', 'way', 'level'), 
						  new Array('更改成功', '已有相同活動名稱存在！！', '伺服器忙碌中！！請在試一次'));
	if(path !== '') window.location.href = ajax_path + class_name + '/search_web'; 
}

//初始化
function init() {
	begin_end('begin_year', 'begin_month');
	begin_end('end_year', 'end_month');
	
	data = update_ajax(ajax_path + class_name + '/search_data', id);
	
	for(i in data) {
		switch (i) {
			case 'begin_day':
				days($("#begin_month")[0]);
				$('#' + i).val(data[i]);
				break;
			case 'end_day':
				days($("#end_month")[0]);
				$('#' + i).val(data[i]);
				break;
			case 'id':
				$("#" + i).text(data[i]);
				break;
			default :
				$('#' + i).val(data[i]);
				break;
		}
	
		
	}
}