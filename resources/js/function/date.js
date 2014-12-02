/**
 * 	放時間日期選擇天數和種類
 */

//星期選項
function option_weeks() {
	var option_string = "<option value=''>請選擇 星期</option>";

	for(var i = 1; i <= 7; i++) {
		if(i === 7) {
			option_string = option_string + "<option value='" + i + "'>日</option>";
			continue;
		}
		option_string = option_string + "<option value='" + i + "'>" + i + "</option>";
	}

	return option_string;
}

//日選項
function option_days(day) {
	var option_string = option_string + "<option value=''>請選擇 日</option>"; 

	if(day === '') return option_string;
	
	var days = 31;
	
	switch (day) {
		case '2':
			days = 28;
			break;
		case '4':
		case '6':
		case '9':
		case '11':
			days = 30;
			break;
	}
	
	for(var i = 1;i <= days;i++) option_string = option_string + "<option value='" + i + "'>" + i + "</option>"; 

	return option_string;
}

//月選項
function option_months() {	
	var option_string 	= "<option value=''>請選擇  月</option>";

	for(var i = 1; i <= 12; i++) option_string = option_string + "<option value='" + i + "'>" + i + "</option>";

	return option_string;
}

//年數選項
function option_ages(age) {
	var option_string = "<option value=''>請選擇 </option>";

	for(var i = 1; i <= age; i++) {
		option_string = option_string + "<option value='" + i + "'>" + i + "</option>";
	}

	return option_string;
}

//年選項
function option_years() {	
	var option_string 	= "<option value=''>請選擇  年</option>";

	for(var i = new Date().getFullYear(); i <= new Date().getFullYear() + 20; i++) option_string = option_string + "<option value='" + i + "'>" + i + "</option>";

	return option_string;
}





















//時間選項
//function option_time() {
//	var option_string = "<option value=''>請選擇 時間</option>";
//	
//	option_string = option_string + "<option value='5'>5 秒</option>";
//	option_string = option_string + "<option value='10'>10 秒</option>";
//	option_string = option_string + "<option value='30'>30 秒</option>";
//	option_string = option_string + "<option value='60'>1 分鐘</option>";
//	option_string = option_string + "<option value='300'>5 分鐘</option>";
//	option_string = option_string + "<option value='600'>10 分鐘</option>";
//	option_string = option_string + "<option value='1800'>30 分鐘</option>";
//	option_string = option_string + "<option value='3600'>1 小時</option>";
//	option_string = option_string + "<option value='5400'>1 小時 30 分鐘</option>";
//	option_string = option_string + "<option value='7200'>2 小時</option>";
//	option_string = option_string + "<option value='21600'>6 小時</option>";
//	option_string = option_string + "<option value='43200'>12 小時</option>";
//	option_string = option_string + "<option value='86400'>1 天</option>";
//	option_string = option_string + "<option value='1296000'>15 天</option>";
//	option_string = option_string + "<option value='2592000'>30 天</option>";
//	option_string = option_string + "<option value='15552000'>半 年</option>";
//	option_string = option_string + "<option value='31104000'>1 年</option>";
//	
//	return option_string;
//}

//次數選項
//function option_times(times) {
//	var option_string = "<option value=''>請選擇 次數 </option><option value='no_limit'>無限制 </option>";
//
//	for(var i = 1; i <= times; i++) {
//		option_string = option_string + "<option value='" + i + "'>" + i + "</option>";
//	}
//
//	return option_string;
//}