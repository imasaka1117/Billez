/**
 * 新增使用者
 */

$(document).ready(function() 
{
	init();
	$("#insert_btn").click(function() 
	{if(validate()) insert();});

//user全選/全刪功能:
	//原先編寫的code:
	/*
	$("#selectAll").click(function() 
	{
		$("[name='function_authority']").each(function() 
	    {this.checked = true;});
	});
	$("#cancelAll").click(function() 
	{
	    $("[name='function_authority']").each(function() 
	    {this.checked = false;});
	});
	*/

	//最佳化js簡化的code:
			$("#selectAll").click(function()
			{$("[name='function_authority']").each(function(){this.checked=!0})});
			$("#cancelAll").click(function()
			{$("[name='function_authority']").each(function(){this.checked=!1})})
});

//新增使用者
function insert() {
	//選取被勾選的checkbox值抓出
	var function_authority_array=new Array();
	$("[name='function_authority']").each(function()
	{
		if(this.checked == true) 
		{	
			function_authority_array.push($(this).attr('id'));
		}

	});
	
	//合併所有資料
	function_authority_array.push('email');
	function_authority_array.push('password');
	function_authority_array.push('name');
	function_authority_array.push('kind');

	var path = check_ajax(ajax_path + 'user/insert', function_authority_array, 
						  new Array('新增成功', '使用者電子郵件已存在！！', '伺服器忙碌中！！請在試一次'));
	if(path != '') location.reload(); 
}

function init() {
}

//最佳化js
/*

*/