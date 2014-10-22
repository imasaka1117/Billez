/**
 * 更改代收機構合約使用
 */
$(document).ready(function() {
	init();
	
	$("#insert_btn").click(function() {
		if(validate()) update();
	});	
	
	$("select").change(function() {
		switch ($(this).attr('id')) {
			case 'pay':
				date_kind($(this).attr('id'), $(this).val());
				break;
			case 'begin_month':
			case 'end_month':
				days(this);
				break;
			case 'bill_cost':
				price($(this).attr('id'), $(this).val());
				break;
		}
	});
});

//更改業者合約
function update() {
	var path = check_ajax(ajax_path + 'machinery/update_contract',
						  new Array('id', 'machinery', 'contract_name', 'ad_url', 'contract_age', 'begin_year', 'begin_month', 'begin_day', 'end_year', 'end_month', 'end_day', 'pay', 'pay_week', 'pay_month', 'pay_day', 'bill_cost', 'month_rent_price', 'entity_price', 'action_price', 'contract_remark'),
						  new Array('更改成功', '該代收機構合約名稱已存在！！', '伺服器忙碌中！！請在試一次'));
	if(path != '') location.href = ajax_path + path; 
}

//帳單價格處理
function price(id, value) {
	if($('#month_rent_price').attr('id') != undefined) {
		$('#month_rent_price').remove();
		$('#month_rent_price21').remove();
	}
	
	if($('#entity_price').attr('id') != undefined) {
		$('#entity_price').remove();
		$('#entity_price21').remove();
	}
	
	if($('#action_price').attr('id') != undefined) {
		$('#action_price').remove();
		$('#action_price21').remove();
	}
	
	switch(value) {
		case '1':
			$('#' + id + '').after('<span id="month_rent_price21">月費 : </span><input type="text" id="month_rent_price" class="required,digits" size="6" />');
			break;
		case '2':
			$('#' + id + '').after('<span id="entity_price21">實體 : </span><input type="text" id="entity_price" class="required,digits" size="6" /> <span id="action_price21">行動 : </span> <input type="text" id="action_price" class="required,digits" size="6" />');
			break;
	}
}

//將日期重製
function days(month) {
	$('#' + month.id.replace('_month', '') + '_day').empty().append(option_days(month.value));
}

//將時間種類做顯示
function date_kind(id, value) {
	if($('#' + id + '_week').attr('id') != undefined) $('#' + id + '_week').remove();
	if($('#' + id + '_month').attr('id') != undefined) $('#' + id + '_month').remove();
	if($('#' + id + '_day').attr('id') != undefined) $('#' + id + '_day').remove();
	
	switch(value) {
		case '1':
			$('#' + id + '').after('<select id="' + id + '_week" class="required"></select>');
			$('#' + id + '_week').append(option_weeks());
			break;
		case '2':
			$('#' + id + '').after('<select id="' + id + '_day" class="required"></select>');
			$('#' + id + '_day').append(option_days());
			break;
		case '3':
			$('#' + id + '').after('<select id="' + id + '_month" class="required" onchange="days(this)"></select><select id="' + id + '_day" class="required"></select>');
			$('#' + id + '_month').append(option_months());
			$('#' + id + '_day').append(option_days(''));
			break;
	}
}

//初始化
function init() {
	$('#contract_age').empty().append(option_ages(20));
	$('#begin_year').empty().append(option_years());
	$('#end_year').empty().append(option_years());
	$('#begin_month').empty().append(option_months());
	$('#end_month').empty().append(option_months());
	
	data = update_ajax(ajax_path + 'machinery/search_contract_data', id);
	
	for(i in data) {
		switch (i) {
			case 'begin_day':
				$("#" + i).empty().append(days($("#begin_month")[0]));			
				break;
			case 'end_day':
				$("#" + i).empty().append(days($("#end_month")[0]));
				break;
			case 'pay':
				date_kind(i, data[i]);
				break;
			case 'pay_month':
				if(data[i] == '') continue;
				$("#" + i).val(data[i]);
				$("#" + i.replace('_month', '') + '_day').empty().append(days($("#" + i)[0]));
				break;
			case 'bill_cost':
				price(i, data[i]);
				break;
		}
		
		$('#' + i).val(data[i]);
	}
}

