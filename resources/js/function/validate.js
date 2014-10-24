/**
 * 驗證專用
 * 需要配合jquery使用
 * 要使用時呼叫方法就可
 * ex : $("input").click(function() { validate() });
 * 若有錯誤回傳false 及顯示對話框
 * 若正確則回傳true
 * 在要檢驗的欄位加上class屬性, 若一個欄位有多個驗證,則使用逗號隔開,以下為屬性參數 ex : class = "required,email"
 * required		空白
 * email		電子郵件
 * url			網址
 * date			日期
 * dateISO		ISO日期
 * number		數字
 * digits		整數
 * creditcard	信用卡號
 * mobile		手機號碼
 * 
 * 錯誤訊息可以自己更改 搜尋message即可找到修改處
 * required: "不可空白",
 * email: "非法電子郵件格式",
 * url: "非法網址格式 要加上 http://",
 * date: "非法日期格式",
 * dateISO: "格式日期為Y-M-D或Y/M/D或YMD",
 * number: "請輸入0~9數字",
 * digits: "只能填入數字(不可有小數點)",
 * creditcard: "信用卡號要16位數",	
 * mobile: "必須是09開頭且只有10位數"
 * 
 * 增加驗證使用方式
 * 1.查詢validate函式 新增一個陣列 ex : kind.mobile = new Array('mobile');
 * 2.查詢handle函式 新增一個case ex : case 'required':
									validate_mobile(kind[i], message);
									break;
 * 3.新增一個方法 ex : function validate_mobile(id, message) {
						id.shift();
						reg = new RegExp("[^ ]");
						validate_check(id, message, reg);
					}
 * 
 * P.S. 
 * 步驟一不可將新驗證方式方在空白驗證後面,空白驗證一定要放在最後面
 * 步驟三必須使用正規表達式,否則會出錯
 * 
 * 使用方式 當按鈕按下時 呼叫 若是沒問題 再呼叫送出資料函式
 * $("#insert_btn").click(function() {
		if(validate()) insert();
	});
 */

/**
 * 驗證起點 搜尋 kind
 */
function validate() {
	kind = new Object();
	kind.email = new Array('email');
	kind.url = new Array('url');
	kind.date = new Array('date');
	kind.digits = new Array('digits');
	kind.creditcard = new Array('creditcard');
	kind.mobile = new Array('mobile');
	kind.required = new Array('required');
	
	//蒐集所有元素ID
	var id = collect_id();

	//將各種驗證參數分類 並將id給予該參數
	kind = classify(kind, id);

	//依照驗證項目作處理
	handle(kind);
	
	//判斷是否停止或開始送資料給伺服器
	return condition(id);
}

/**
 * 
 */

/**
 * 判斷是否停止或開始送資料給伺服器
 * @param id 所有元素ID
 */
function condition(id) {
	var flag = true;
	for(i in id) {
		if($('#' + id[i] + '').attr("class") != undefined) {
			if($('#' + id[i] + '1').val() == '') {
				if($('#' + id[i] + '1').text() != '') flag = false;
			}
		}
	}

	return flag;
}

/**
 * 依照驗證項目作處理 搜尋method
 * @param kind	驗證項目類別
 */
function handle(kind) {
	for(i in kind) {
		if(kind[i][1] == undefined) continue;

		//先查詢錯誤碼
		var message = get_message(kind[i][0]);

		//若是有ID的則執行認證,註解是目前還沒用到   
		switch(kind[i][0]) {
			case 'email':
				validate_email(kind[i], message);
				break;
			case 'url':
				validate_url(kind[i], message);			
				break;
			case 'date':
//				validate_date(kind[i], message);
				break;
			case 'digits':
				validate_digits(kind[i], message);
				break;
			case 'creditcard':
				validate_creditcard(kind[i], message);
				break;
			case 'mobile':
				validate_mobile(kind[i], message);
				break;
			case 'required':
				validate_required(kind[i], message);
				break;		
		}
	}
}

/**
 * 手機驗證
 * @param id		元素ID
 * @param message	錯誤訊息
 */
function validate_mobile(id, message) {
	id.shift();
	reg = new RegExp("^[09]{2}[0-9]{8}$");
	validate_check(id, message, reg);
}

/**
 * 信用卡驗證
 * @param id		元素ID
 * @param message	錯誤訊息
 */
function validate_creditcard(id, message) {
	id.shift();
	reg = new RegExp("^[0-9]{16}$");
	validate_check(id, message, reg);
}

/**
 * 數字驗證
 * @param id		元素ID
 * @param message	錯誤訊息
 */
function validate_digits(id, message) {
	id.shift();
	reg = new RegExp("^[0-9]+$");
	validate_check(id, message, reg);
}

/**
 * 網址驗證
 * @param id		元素ID
 * @param message	錯誤訊息
 */
function validate_url(id, message) {
	id.shift();
	reg = new RegExp("^[http://]");
	validate_check(id, message, reg);
}

/**
 * 確認資料及顯示錯誤訊息
 * @param id		元素ID
 * @param message	錯誤訊息
 * @param reg		正規表達式
 */
function validate_check(id, message, reg) {
	for(j in id) {
		if($('#' + id[j] + '1').attr('id') == undefined) $('#' + id[j] + '').after('<span id="' + id[j] + '1" style="color:red"></span>');

		if(reg.test($('#' + id[j] + '').val()) == false) {
			$('#' + id[j] + '1').text(message);
		} else if ($('#' + id[j] + '1').text() == message) {
			$('#' + id[j] + '1').empty();
		}
	}
}

/**
 * 電子郵件驗證
 * @param id		元素ID
 * @param message	錯誤訊息
 */
function validate_email(id, message) {
	id.shift();
	reg = new RegExp("[A-z|0-9][@][A-z|0-9]");
	validate_check(id, message, reg);
}

/**
 * 空白驗證
 * @param id		元素ID
 * @param message	錯誤訊息
 */
function validate_required(id, message) {
	id.shift();
	reg = new RegExp("[^ ]");
	validate_check(id, message, reg);
}

/**
 * 回傳錯誤訊息內容
 * @param kind	驗證項目
 */
function get_message(kind) {
	switch(kind) {
		case 'email':
			return '非法電子郵件格式 ex: xx@xx';
		case 'url':
			return '非法網址格式 ex: http://';
		case 'date':
			return '非法日期格式';
		case 'digits':
			return '只能填入數字(不可有小數點)';
		case 'creditcard':
			return '信用卡號要16位數且只能填入數字';
		case 'mobile':
			return '必須為09開頭且為10位數';
		case 'required':
			return '不可空白';		
	}
}

/**
 * 蒐集所有元素ID屬性
 */
function collect_id() {
	var id = new Array();
	
	$("*").each(function() {                
		if($(this).attr("id") != undefined) id.push($(this).attr("id"));
	});
	
	return id;
}

/**
 * 將有該類別參數的ID分類
 * @param kind	驗證項目類別
 * @param id	元素ID
 */
function classify(kind, id) {
	for(i in id) {
		if($('#' + id[i] + '').attr("class") != undefined) {
			var temp = $('#' + id[i] + '').attr("class");
			var class_list = temp.split(',');
			for(k in kind) {
				for(j in class_list) {
					if(kind[k][0] == class_list[j]) kind[k].push(id[i]);
				}
			}
		}
	}
	
	return kind;
}
		
		
		
		
		
		
		