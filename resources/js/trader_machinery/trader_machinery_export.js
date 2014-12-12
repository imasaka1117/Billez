/**
 * 匯出業者或代收機構
 */
$(document).ready(function() {
	init();
	
	$("#export_btn").click(function() {
		$("#export_list option").attr("selected", "selected");
		if(validate()) export_data();
	});	
	
	$("input:button").click(function() {
		switch ($(this).attr('id')) {
			case 'all_e':
				$("#list").find("option").each(function() { 
				    $("#export_list").append($("<option></option>").attr("value", this.value).text(this.text));
				}); 
				$("#list option").remove();
				break;
			case 'single_e':
				$("#list").find(":selected").each(function() { 
				    $("#export_list").append($("<option></option>").attr("value", this.value).text(this.text));
				    $("#list").find(":selected").remove();
				}); 
				break;
			case 'all_b':
				$("#export_list").find("option").each(function() { 
				    $("#list").append($("<option></option>").attr("value", this.value).text(this.text));
				}); 
				$("#export_list option").remove();
				break;
			case 'single_b':
				$("#export_list").find(":selected").each(function() { 
				    $("#list").append($("<option></option>").attr("value", this.value).text(this.text));
				    $("#export_list").find(":selected").remove();
				}); 
				break;
			case 'export_btn':
				if(!check_input_not_blank("format")) return false;
				$("#export_list option").attr("selected", "selected");
				if($("#export_list").val() == null) {
					alert("* 號欄位請勿空白 ! ");
					return false;
				}
				break;
		}
	});
	
	$("input:radio").click(function() {
		$('#export_list').empty();
		$('#export_table').show();
		
		var list_text = '業者名單 : ';
		var export_list_text = '*要匯出的業者名單 : ';
		var list_text2 = '業者合約名單 : ';
		var export_list_text2 = '*要匯出的業者合約名單 : ';
		var common = 'init_trader_list';
		var common2 = 'init_trader_contract_list';
		
		if(class_name == 'machinery') {
			list_text = '代收機構名單 : ';
			export_list_text = '*要匯出的代收機構名單 : ';
			list_text2 = '代收機構合約名單 : ';
			export_list_text2 = '*要匯出的代收機構合約名單 : ';
			common = 'init_machinery_list';
			common2 = 'init_machinery_contract_list';
		}
		
		switch(this.value) {
			case 'data':
				$('#list_text').text(list_text);
				$('#export_list_text').text(export_list_text);
				select_ajax(ajax_path + 'common/' + common, 'list', '');
				break;
			case 'contract':
				$('#list_text').text(list_text2);
				$('#export_list_text').text(export_list_text2);
				select_ajax(ajax_path + 'common/' + common2, 'list', '');
				break;
		}
	});
});
             
//匯出資料
function export_data() {
	var data = set_ajax(new Array('export_list'));
	data['kind'] = $('input[name=kind]:checked').val();
	export_ajax(ajax_path + class_name + '/export', data, 'post');
}

//初始化
function init() {
	$('#export_table').hide();
	
}