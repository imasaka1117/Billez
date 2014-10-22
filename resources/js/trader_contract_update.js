/**
 * 更改業者合約使用
 */
$(document).ready(function() {
	init();
	
	$("#insert_btn").click(function() {
		if(validate()) update();
	});	
	
	$("select").change(function() {
		switch ($(this).attr('id')) {
			case 'publish':
			case 'enter':
			case 'collection':
			case 'email_publish':
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

//新增業者合約
function update() {
	var path = check_ajax(ajax_path + 'trader/update_contract',
						  new Array('id', 'trader', 'contract_name', 'ad_url', 'bill_kind', 'bill_basis', 'machinery', 'machinery_contract', 'contract_age', 'begin_year', 'begin_month', 'begin_day', 'end_year', 'end_month', 'end_day', 'publish', 'publish_week', 'publish_month', 'publish_day', 'enter', 'enter_week', 'enter_month', 'enter_day', 'bill_price', 'month_rent_price', 'entity_price', 'action_price', 'collection', 'collection_week', 'collection_month', 'collection_day', 'send_condition', 'send_condition_times', 'send_email', 'email_publish', 'email_publish_week', 'email_publish_month', 'email_publish_day', 'ftp_ip', 'ftp_account', 'ftp_password', 'ftp_path', 'ftp_receive_path', 'contract_remark'),
						  new Array('更新成功', '該業者帳單合約名稱已存在！！', '伺服器忙碌中！！請在試一次'));
	if(path != '') location.href = ajax_path + path; 
}

//代收機構合約
function machinery(value) {
	select_ajax(ajax_path + 'trader/init_machinery_contract', 'machinery_contract', value);
	
	if(value == '') {
		$('#machinery_contract').removeAttr("class");
	} else {
		$('#machinery_contract').attr("class","required");
	}
}

//帳單寄送種類
function times(id, value) {
	if($('#send_condition_times').attr('id') != undefined) $('#send_condition_times').remove();
	
	switch (value) {
		case '2':
			$('#' + id + '').after('<select id="send_condition_times" class="required"></select>');
			$('#send_condition_times').append(option_ages(20));
			break;
	}
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
	//先將電子郵件不能點選
	$('#email_publish').attr("disabled",true);
	$('#contract_age').empty().append(option_ages(20));
	$('#begin_year').empty().append(option_years());
	$('#end_year').empty().append(option_years());
	$('#begin_month').empty().append(option_months());
	$('#end_month').empty().append(option_months());

	//將代收業者名稱初始化
	select_ajax(ajax_path + 'trader/init_machinery', 'machinery', '');

	data = update_ajax(ajax_path + 'trader/search_contract_data', id);
	
	for(i in data) {
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
			case 'publish':
			case 'enter':
			case 'collection':
			case 'email_publish':
				date_kind(i, data[i]);
				break;
			case 'publish_month':
			case 'enter_month':
			case 'collection_month':
			case 'email_publish_month':
				if(data[i] == '') continue;
				$("#" + i).val(data[i]);
				$("#" + i.replace('_month', '') + '_day').empty().append(days($("#" + i)[0]));
				break;
			case 'bill_price':
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

