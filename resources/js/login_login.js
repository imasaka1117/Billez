/**
 * login 專用js
 */
$(document).ready(function() {
	init_login();
	
//	$("input").click(function() { 
//		validate(); 
//	});
	
	$("#login_btn").click(function() { 
		alert(validate());
//		if(validate()) {
//			alert('ddd');
//		}
	});
});

//檢查帳號密碼是否正確
function check_login() {
	var path = check_ajax(ajax_path + 'login/check_login', new Array('login_email', 'login_password'), new Array('登入成功', '帳號或密碼錯誤！！')); 
	if(path != '') window.location.href = ajax_path + path; 
}

//初始化函式
function init_login() {
	$.ajaxSetup({ async: false });
}