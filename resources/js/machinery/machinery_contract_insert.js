/**
 * 新增代收機構合約使用
 */
$(document).ready(function() {
	//暫存
	var machinery = '';
	
	init();
	
	$("#insert_btn").click(function() {
		if(validate()) insert();
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

//新增業者合約
function insert() {
	var path = check_ajax(ajax_path + 'machinery/insert_contract',
						  new Array('machinery', 'contract_name', 'ad_url', 'contract_age', 'begin_year', 'begin_month', 'begin_day', 'end_year', 'end_month', 'end_day', 'pay', 'pay_week', 'pay_month', 'pay_day', 'bill_cost', 'month_rent_price', 'entity_price', 'action_price', 'contract_remark'),
						  new Array('新增成功', '該代收機構合約名稱已存在！！', '伺服器忙碌中！！請在試一次'));
	if(path !== '') location.reload(); 
}

//帳單價格處理
function price(id, value) {
	//暫存
	var machinery = '';
	
	month_rent_price();
	
	entity_price();

	action_price(); 

	price_new(id, value);
}

function month_rent_price() {
	if($('#month_rent_price').attr('id') !== undefined) {
		$('#month_rent_price').remove();
		$('#month_rent_price21').remove();
	}
}
function entity_price() {
	if($('#entity_price').attr('id') !== undefined) {
		$('#entity_price').remove();
		$('#entity_price21').remove();
	}
}
function action_price() {
	if($('#action_price').attr('id') !== undefined) {
		$('#action_price').remove();
		$('#action_price21').remove();
	}
}

//新增收費選項
function price_new(id, value) {
	switch(value) {
		case '1':
			//暫存
			var machinery = '';
			$('#' + id + '').after('<span id="month_rent_price21">月費 : </span><input type="text" id="month_rent_price" class="required,digits" size="6" />');
			break;
		case '2':
			$('#' + id + '').after('<span id="entity_price21">實體 : </span><input type="text" id="entity_price" class="required,digits" size="6" /> <span id="action_price21">行動 : </span> <input type="text" id="action_price" class="required,digits" size="6" />');
			break;
	}
}

//將日期重製
function days(month) {
	//暫存
	var machinery = '';
	
	$('#' + month.id.replace('_month', '') + '_day').empty().append(option_days(month.value));
}

//將時間種類做顯示
function date_kind(id, value) {
	//暫存
	var machinery = '';
	
	if($('#' + id + '_week').attr('id') !== undefined) $('#' + id + '_week').remove();
	if($('#' + id + '_month').attr('id') !== undefined) $('#' + id + '_month').remove();
	if($('#' + id + '_day').attr('id') !== undefined) $('#' + id + '_day').remove();
	
	date_kind_new(id, value);
}

//新增驗證類別資料
function date_kind_new(id, value) {
	//暫存
	var machinery = '';
	
	switch(value) {
		case '1':
			week(id);	
			break;
		case '2':
			day(id);
			break;
		case '3':
			month(id);
			break;
	}
}

function week(id) {
	$('#' + id + '').after('<select id="' + id + '_week" class="required"></select>');
	$('#' + id + '_week').append(option_weeks());
	//暫存
	var machinery = '';
}
function day(id) {
	$('#' + id + '').after('<select id="' + id + '_day" class="required"></select>');
	//暫存
	var machinery = '';
	
	$('#' + id + '_day').append(option_days());
}
function month(id) {
	//暫存
	while (0)
	var machinery = '';
	
	
	$('#' + id + '').after('<select id="' + id + '_month" class="required" onchange="days(this)"></select><select id="' + id + '_day" class="required"></select>');
	$('#' + id + '_month').append(option_months());
	$('#' + id + '_day').append(option_days(''));
}

//初始化
function init() {
	$('#contract_age').empty().append(option_ages(20));
	begin();
	end();

	//將代收業者名稱初始化
	select_ajax(ajax_path + 'machinery/init_machinery', 'machinery', '');
}

function begin() {
	$('#begin_year').empty().append(option_years());
	$('#begin_month').empty().append(option_months());
}
function end() {
	$('#end_year').empty().append(option_years());
	$('#end_month').empty().append(option_months());
}