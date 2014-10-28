/**
 * 新增業者和代收機構合約使用
 */
$(document).ready(function() {
	init();
	
	$("#insert_btn").click(function() {
		if(validate()) {
			if(typeof(id) !== 'string') insert(); else update();
		}
	});	
	
	$("select").change(function() {
		switch ($(this).attr('id')) {
			case 'publish':
			case 'enter':
			case 'collection':
			case 'email_publish':
			case 'pay':	
				date_kind($(this).attr('id'), $(this).val());
				break;
			case 'send_email':
				email($(this).val());
				break;
			case 'begin_month':
			case 'end_month':
				days(this);
				break;
			case 'bill_price':
			case 'bill_cost':
				price($(this).attr('id'), $(this).val());
				break;
			case 'send_condition':
				times($(this).attr('id'), $(this).val());
				break;
			case 'machinery':
				machinery($(this).val());
				break;
		}
	});
});

//新增
function insert() {
	if(class_name === 'trader') {
		var ids = new Array('trader', 'contract_name', 'ad_url', 'bill_kind', 'bill_basis', 'machinery', 'machinery_contract', 'contract_age', 'begin_year', 'begin_month', 'begin_day', 'end_year', 'end_month', 'end_day', 'publish', 'publish_week', 'publish_month', 'publish_day', 'enter', 'enter_week', 'enter_month', 'enter_day', 'bill_price', 'month_rent_price', 'entity_price', 'action_price', 'collection', 'collection_week', 'collection_month', 'collection_day', 'send_condition', 'send_condition_times', 'send_email', 'email_publish', 'email_publish_week', 'email_publish_month', 'email_publish_day', 'ftp_ip', 'ftp_account', 'ftp_password', 'ftp_path', 'ftp_receive_path', 'contract_remark');
	} else {
		var ids = new Array('machinery', 'contract_name', 'ad_url', 'contract_age', 'begin_year', 'begin_month', 'begin_day', 'end_year', 'end_month', 'end_day', 'pay', 'pay_week', 'pay_month', 'pay_day', 'bill_cost', 'month_rent_price', 'entity_price', 'action_price', 'contract_remark');
	}
	
	var path = check_ajax(ajax_path + class_name + '/insert_contract', ids, new Array('新增成功', '該合約名稱已存在！！', '伺服器忙碌中！！請在試一次'));
	if(path !== '') location.reload(); 
}

//更改
function update() {
	if(class_name === 'trader') {
		var ids = new Array('id', 'trader', 'contract_name', 'ad_url', 'bill_kind', 'bill_basis', 'machinery', 'machinery_contract', 'contract_age', 'begin_year', 'begin_month', 'begin_day', 'end_year', 'end_month', 'end_day', 'publish', 'publish_week', 'publish_month', 'publish_day', 'enter', 'enter_week', 'enter_month', 'enter_day', 'bill_price', 'month_rent_price', 'entity_price', 'action_price', 'collection', 'collection_week', 'collection_month', 'collection_day', 'send_condition', 'send_condition_times', 'send_email', 'email_publish', 'email_publish_week', 'email_publish_month', 'email_publish_day', 'ftp_ip', 'ftp_account', 'ftp_password', 'ftp_path', 'ftp_receive_path', 'contract_remark');
	} else {
		var ids = new Array('id', 'machinery', 'contract_name', 'ad_url', 'contract_age', 'begin_year', 'begin_month', 'begin_day', 'end_year', 'end_month', 'end_day', 'pay', 'pay_week', 'pay_month', 'pay_day', 'bill_cost', 'month_rent_price', 'entity_price', 'action_price', 'contract_remark');
	}
	
	var path = check_ajax(ajax_path + class_name + '/update_contract', ids, new Array('更改成功', '該帳單合約名稱已存在！！', '伺服器忙碌中！！請在試一次'));
	if(path !== '') {
		location.href = ajax_path + path; 
	}
}

//代收機構合約
function machinery(value) {
	select_ajax(ajax_path + 'common/init_machinery_contract', 'machinery_contract', value);
	
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
			$('#' + id + '').after('<select id="send_condition_times" class="required"></select>');
			$('#send_condition_times').append(option_ages(20));
			break;
	}
}

//帳單價格處理
function price(id, value) {
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
			$('#email_publish').attr("disabled",false);
			$('#email_publish').attr("class","required");
			break;
		default:
			$('#email_publish_week').remove();
			$('#email_publish_month').remove();
			$('#email_publish_day').remove();
			$('#email_publish').val('');
			$('#email_publish').removeAttr("class");
			$('#email_publish').attr("disabled",true);
			break;
	}
}

//將日期重製
function days(month) {
	$('#' + month.id.replace('_month', '') + '_day').empty().append(option_days(month.value));
}

//將時間種類做顯示
function date_kind(id, value) {
	if($('#' + id + '_week').attr('id') !== undefined) {
		$('#' + id + '_week').remove();
	}
	if($('#' + id + '_month').attr('id') !== undefined) {
		$('#' + id + '_month').remove();
	}
	if($('#' + id + '_day').attr('id') !== undefined) {
		$('#' + id + '_day').remove();
	}
	
	date_kind_new(id, value);
}

//新增驗證類別資料
function date_kind_new(id, value) {
	switch(value) {
		case '1':
			week_day(id, '_week');	
			break;
		case '2':
			week_day(id, '_day');
			break;
		case '3':
			month(id);
			break;
	}
}

function week_day(id, kind) {
	$('#' + id + '').after('<select id="' + id + kind + '" class="required"></select>');
	
	if(kind === '_day') {
		$('#' + id + kind).append(option_days());
	} else {
		$('#' + id + kind).append(option_weeks());
	}
}

function month(id) {
	$('#' + id + '').after('<select id="' + id + '_month" class="required" onchange="days(this)"></select><select id="' + id + '_day" class="required"></select>');
	$('#' + id + '_month').append(option_months());
	$('#' + id + '_day').append(option_days(''));
}

//初始化
function init() {
	if(class_name === 'trader') {
		//先將電子郵件不能點選
		$('#email_publish').attr("disabled",true);
		
		//將業者名稱初始化
		select_ajax(ajax_path + 'common/init_trader', 'trader', '');
		
		//將帳單種類初始化
		select_ajax(ajax_path + 'common/init_bill_kind', 'bill_kind', '');
		
		//將帳單依據初始化
		select_ajax(ajax_path + 'common/init_bill_basis', 'bill_basis', '');
	}
	
	$('#contract_age').empty().append(option_ages(20));
	begin();
	end();

	//將代收業者名稱初始化
	select_ajax(ajax_path + 'common/init_machinery', 'machinery', '');

	if(typeof(id) === 'string') init_update();
}

function begin() {
	$('#begin_year').empty().append(option_years());
	$('#begin_month').empty().append(option_months());
}
function end() {
	$('#end_year').empty().append(option_years());
	$('#end_month').empty().append(option_months());
}

//帶入資料
function init_update() {
	data = update_ajax(ajax_path + class_name + '/search_contract_data', id);
	
	data_parse(data);
	data_parse2(data);
	data_parse3(data);
}

//將帶入資料做處理
function data_parse(data) {
	for(var i in data) {
		switch (i) {
			case 'machinery_contract':
				$("#" + i).empty().append(machinery($("#machinery").val()));
				break;
			case 'begin_day':
				$("#" + i).empty().append(days($("#begin_month")[0]));			
				break;
			case 'end_day':
				$("#" + i).empty().append(days($("#end_month")[0]));
				break;
		}
		
		$('#' + i).val(data[i]);
	}
}

function data_parse2(data) {
	for(var i in data) {
		switch (i) {
			case 'publish':
			case 'enter':
			case 'collection':
			case 'email_publish':
			case 'pay':
				date_kind(i, data[i]);
				break;
			case 'publish_month':
			case 'enter_month':
			case 'collection_month':
			case 'email_publish_month':
			case 'pay_month':
				if(data[i] === '') {
					continue;
				}
				$("#" + i).val(data[i]);
				$("#" + i.replace('_month', '') + '_day').empty().append(days($("#" + i)[0]));
				break;
		}
		
		$('#' + i).val(data[i]);
	}
}

function data_parse3(data) {
	for(var i in data) {
		switch (i) {
			case 'bill_price':
			case 'bill_cost':
				price(i, data[i]);
				break;
			case 'send_condition':
				times(i, data[i]);
				break;
			case 'send_email':
				email(data[i]);
				break;
		}
		
		$('#' + i).val(data[i]);
	}
}