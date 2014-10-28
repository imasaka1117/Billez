/**
 * 
 */
//代收機構合約
function machinery(value) {
	select_ajax(ajax_path + 'common/init_machinery_contract', 'machinery_contract', value);
	machinery_value(value);
}

function machinery_value(value) {
	if(value === '') {
		$('#machinery_contract').removeAttr("class");
	} else {
		$('#machinery_contract').attr("class","required");
	}
}

//帳單寄送種類
function times(id, value) {
	if($('#send_condition_times').attr('id') !== undefined) {
		$('#send_condition_times').remove();
	}
	
	switch (value) {
		case '2':
			times_value(id);
			break;
	}
}

function times_value(id) {
	$('#' + id + '').after('<select id="send_condition_times" class="required"></select>');
	$('#send_condition_times').append(option_ages(20));
}

//帳單價格處理
function price(id, value) {
	price_kind('month_rent_price', 'month_rent_price21');
	price_kind('entity_price', 'entity_price21');
	price_kind('action_price', 'action_price21');
	price_new(id, value);
}

function price_kind(id, id1) {
	if($('#' + id).attr('id') !== undefined) {
		remove_price(id, id1);
	}
}

function remove_price(id, id1) {
	$('#' + id).remove();
	$('#' + id1).remove();
}

//新增收費選項
function price_new(id, value) {
	switch(value) {
		case '1':		
			$('#' + id + '').after('<span id="month_rent_price21">月費 : </span><input type="text" id="month_rent_price" class="required,digits" size="6" />');
			break;
		case '2':
			$('#' + id + '').after('<span id="entity_price21">實體 : </span><input type="text" id="entity_price" class="required,digits" size="6" /> <span id="action_price21">行動 : </span> <input type="text" id="action_price" class="required,digits" size="6" />');
			break;
	}
}

//控制電子郵件帳單處理
function email(value) {
	switch(value) {
		case '1':
			email_attr();
			break;
		default:
			email_remove();
			break;
	}
}

function email_attr() {
	$('#email_publish').attr("disabled",false);
	$('#email_publish').attr("class","required");
}

function email_remove() {
	$('#email_publish_week').remove();
	$('#email_publish_month').remove();
	$('#email_publish_day').remove();
	email_publish();
}

function email_publish() {
	$('#email_publish').val('');
	$('#email_publish').removeAttr("class");
	$('#email_publish').attr("disabled",true);
}

//將日期重製
function days(month) {
	$('#' + month.id.replace('_month', '') + '_day').empty().append(option_days(month.value));
}

//將時間種類做顯示
function date_kind(id, value) {
	date_remove(id + '_week');
	date_remove(id + '_month');
	date_remove(id + '_day');

	date_kind_new(id, value);
}

function date_remove(id_kind) {
	if($('#' + id_kind).attr('id') !== undefined) {
		$('#' + id_kind).remove();
	}
}

//新增驗證類別資料
function date_kind_new(id, value) {
	switch(value) {
		case '1':
			week_day_month(id, '_week');	
			break;
		case '2':
			week_day_month(id, '_day');
			break;
		case '3':
			week_day_month(id, '_month');
			break;
	}
}

function week_day_month(id, kind) {
	if(kind === '_month') {
		$('#' + id + '').after('<select id="' + id + kind + '" class="required" onchange="days(this)"></select><select id="' + id + '_day" class="required"></select>');
	} else {
		$('#' + id + '').after('<select id="' + id + kind + '" class="required"></select>');
	}
	
	week_day_month_kind(id, kind);
}

function week_day_month_kind(id, kind) {
	if(kind === '_day') {
		$('#' + id + kind).append(option_days());
	} else if(kind === '_week') {
		$('#' + id + kind).append(option_weeks());
	} else {
		$('#' + id + kind).append(option_months());
		$('#' + id + '_day').append(option_days(''));
	}
}