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
	
	if(!date_compare()) return false;

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
	
	if(!date_compare()) return false;
	
	var path = check_ajax(ajax_path + class_name + '/update_contract', ids, new Array('更改成功', '該帳單合約名稱已存在！！', '伺服器忙碌中！！請在試一次'));
	if(path !== '') {
		location.href = ajax_path + path; 
	}
}

//比較合約日期的大小
function date_compare() {
	var begin = $('#begin_year').val() + '/' + $('#begin_month').val() + '/' + $('#begin_day').val();
	var end = $('#end_year').val() + '/' + $('#end_month').val() + '/' + $('#end_day').val();
	
	if(Date.parse(begin) > Date.parse(end)) {
		alert('合約開始日期大於結束日期');
		return false;
	}
	
	return true;
}

//初始化
function init() {
	if(class_name === 'trader') {
		init_trader();
	}
	
	$('#contract_age').empty().append(option_ages(20));
	begin_end('begin_year', 'begin_month');
	begin_end('end_year', 'end_month');

	//將代收業者名稱初始化
	select_ajax(ajax_path + 'common/init_machinery', 'machinery', '');

	if(typeof(id) === 'string') init_update();
}

function init_trader() {
	//先將電子郵件不能點選
	$('#email_publish').attr("disabled",true);
	
	//將業者名稱初始化
	select_ajax(ajax_path + 'common/init_trader', 'trader', '');
	
	//將帳單種類初始化
	select_ajax(ajax_path + 'common/init_bill_kind', 'bill_kind', '');
	
	//將帳單依據初始化
	select_ajax(ajax_path + 'common/init_bill_basis', 'bill_basis', '');
}

function begin_end(year, month) {
	$('#' + year).empty().append(option_years());
	$('#' + month).empty().append(option_months());
}

//帶入資料
function init_update() {
	data = update_ajax(ajax_path + class_name + '/search_contract_data', id);
	alert(data);
	data_parse(data);
	data_parse2(data);
	data_parse3(data);
	data_parse4(data);
}

//將帶入資料做處理
function data_parse(data) {
	for(var i in data) {
		switch (i) {
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
		}
		
		$('#' + i).val(data[i]);
	}
}

function data_parse3(data) {
	for(var i in data) {
		switch (i) {
			case 'machinery_contract':
				$("#" + i).empty().append(machinery($("#machinery").val()));
				break;
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

function data_parse4(data) {
	for(var i in data) {
		switch (i) {
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