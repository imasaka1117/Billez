/**
 * 新增更新正常繳費入帳帳單格式
 */
$(document).ready(function() {
	init();
	
	$("#import_btn").click(function() {
		if(validate()) {
			var reg = /\.csv$/;

			if(!reg.test($("#bill_file").val())) {
				alert("請匯入副檔名為csv的檔案!!");
				return false;	
			}
			import_pay();
		}
	});	
	
	$("select").change(function() {
		switch($(this).attr('id')) {
			case 'trader':
				$('#bill_kind').empty().append(select_ajax(ajax_path + 'common/init_bill_kind', 'bill_kind', $(this).val()));
				break;
			case 'bill_kind':
				search_page_num(1);
				break;
		}
	});
});

//查詢其他頁面
function search_page_num(page) {
	if(function_name === 'import_pay') {
		var kind = 'search_import_pay_log';
	} else {
		var kind = 'search_import_receive_log';
	}
	
	search_ajax(ajax_path + class_name + '/' + kind, new Array("trader", "bill_kind"), 'query_div', page);
	$('#import_table').show();
}

//新增更新繳費入帳帳單格式
function import_pay() {
	$.ajaxFileUpload({
	    url: ajax_path + class_name + '/' + function_name + '?trader=' + $('#trader').val() + '&bill_kind=' + $('#bill_kind').val(),
	    secureuri: false,
	    fileElementId: 'bill_file',
	    dataType: 'json',
	    success: function(data, status) {
	    	$('#query_div').empty();
	    	
	    	for(var i in data) {
	    		switch(i) {
					case 'b1'://上傳錯誤
						upload_message(data[i]);
						break;
					case 'b2'://沒有設定帳單格式錯誤
						upload_message(data[i]);
						break;
					case 'b3'://資料欄位有錯
						upload_data(data[i], '缺少資料');
						break;
					case 'b4'://有資料是空白錯誤
						upload_data(data[i], '資料空白');
						break;
					case 'b5'://標題列錯誤
						upload_message(data[i]);
						break;
					case 'b6'://匯入錯誤
						upload_message(data[i]);
						break;
					case 'bill'://成功匯入
						upload_success(data);
						break;
				}
	    		
	    		
	    	}
	    	
	    	
	    },
	    error: function(data, status, e) {
	        alert(e);
	    }
	});
}

//匯入成功訊息顯示
function upload_success(data) {
	var html = '<h3>匯入成功</h3>';
	for(var i in data) {
		switch(i) {
			case 'bill':
				html += '<h3>新增帳單筆數 : ' + data[i] + '</h3>';
				break;
			case 'normal':
				html += '<h3>新增一般會員筆數 : ' + data[i] + '</h3>';
				break;
			case 'batch_code':
				html += '<h3>該業者帳單本次匯入批次碼 : ' + data[i] + '</h3>';
				break;
		}
	}
	
	$('#query_div').append(html);
}

//處理錯誤資料顯示
function upload_data(data, tip) {
	var num = new Array();
	var str = new Array();
	for(var i in data) {
		var temp = data[i].split(':');
		num.push(temp[0]);
		str.push(temp[1]);
	}
	
	var count = num.length;
	var html = '<h3>匯入出錯原因 : ' + tip + '</h3><table class="search" cellpadding="5"><tr><th>錯誤行數</th><th>錯誤內容</th></tr>';
	for(var k = 0; k < count; k++) {
		html += '<tr><td>' + num[k] + '</td><td>' + str[k] + '</td></tr>';
	}
	html += '</table>';
	$('#query_div').append(html);
}

//處理回傳的錯誤訊息
function upload_message(data) {
	$('#query_div').append('<h3>檔案上傳錯誤訊息</h3><h4>' + data[i] + '</h4>');
}

//初始化
function init() {
	//將業者初始化
	select_ajax(ajax_path + 'common/init_trader', 'trader', '');
	
	//隱藏匯入項目
	$('#import_table').hide();
}

























function ajaxFileUpload()
{
    /*
     prepareing ajax file upload
     url: the url of script file handling the uploaded files
     fileElementId: the file type of input element id and it will be the index of  $_FILES Array()
     dataType: it support json, xml
     secureuri:use secure protocol
     success: call back function when the ajax complete
     error: callback function when the ajax failed
     */
    $.ajaxFileUpload
            (
                    {
                        url: '上傳檔案後端API 路徑',
                        secureuri: false,
                        fileElementId: 'fileToUpload',//這個是對應到 input file 的 ID
                        dataType: 'json',
                        success: function(data, status)
                        {
                            if (data.error == '200') {
                               $("#file_list").append('<li><a href="' + data.file_url + '">' + data.file_name + '</a></li>');
                           } else {
                               alert(data.msg);
                           }
                        },
                        error: function(data, status, e)
                        {
                            alert(e);
                        }
                    }
            )

    return false;

}