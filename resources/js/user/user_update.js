/**
 * 更改使用者資料
 */

$(document).ready(function() 
{
	data_in_web();
	$("#update_btn").click(function() 
	{if(validate()) update();});	
	
//user全選/全刪功能
	//原先編寫的code:
//	$("#selectAll").click(function() 
//	{
//		$("[name='function_authority']").each(function() 
//		{this.checked = true;});
//    });
//	$("#cancelAll").click(function() 
//	{
//		$("[name='function_authority']").each(function() 
//		{this.checked = false;});
//	});

	//最佳化js簡化的code:
//	init();

	$("#selectAll").click(function()
	{$("[name='function_authority']").each(function(){this.checked=!0})});
	$("#cancelAll").click(function()
	{$("[name='function_authority']").each(function(){this.checked=!1})})
//	
});

//選取被勾選的checkbox值抓出
function update() 
{
	var function_authority_array=new Array();
	$("[name='function_authority']").each(function()
	{
		if(this.checked == true) 
		{function_authority_array.push($(this).attr('id'));}
	});
	
	//合併所有資料
	function_authority_array.push('id');
	function_authority_array.push('email');
	function_authority_array.push('password');
	function_authority_array.push('name');
	function_authority_array.push('kind');


//更新使用者資料
	var path = check_ajax(ajax_path + class_name + '/update', 
			function_authority_array,
						   new Array('更改成功', '已有相同名稱存在！！', '伺服器忙碌中！！請在試一次'));
	if(path !== '') location.href = ajax_path + path;  
}

////初始化
//function init() 
//{
//	data = update_ajax(ajax_path + class_name + '/search_data', id);
//	
//	for(i in data) 
//	{
//		if(data[i] != null) $('#' + i).text(data[i]);
//		if(i == 'name') $('#' + i).val(data[i]);
//	}
//}

//帶入資料庫值進入頁面
function data_in_web() 
{
	data = update_ajax(ajax_path + class_name + '/search_data', id);
	for(var i in data) 
	{
		if(i == 'id') $('#' + i).val(data[i]);
		if(i == 'name') $('#' + i).val(data[i]);
		if(i == 'password') $('#' + i).val(data[i]);
		if(i == 'email') $('#' + i).val(data[i]);
		if(i == 'kind') $('#' + i).val(data[i]);
		
		
		if(data[i] == 'y') 
		{	
			document.getElementById(i).checked=true
		}
	}
}
	


//最佳化js
/*

*/