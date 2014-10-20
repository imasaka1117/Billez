/**
 *  控制修改資料時的選單控制
 */

//控制提示文字
function tip_set(tip_obj_name, tip_text) {
	if($("#" + tip_obj_name + "").val() == tip_text) {
		$("#" + tip_obj_name + "").css({color : 'black'});
		become_blank(tip_obj_name, "text");
		return
	}
	
	if($("#" + tip_obj_name + "").val() == "") {
		$("#" + tip_obj_name + "").css({color : 'gray'});
		$("#" + tip_obj_name + "").val(tip_text);
	}
}

//週期時間控制
function time_kind(kind, name) {
	become_blank(new Array(name + "_week", name + "_month", name + "_season_month", name + "_day"), "select");
	become_hide_or_show(new Array(name + "_week", name + "_month", name + "_season_month", name + "_day"), "hide");
	
	switch (kind) {
		case 'week':
			become_hide_or_show(name + "_week", "show");
			$("#" + name + "_week").empty().append(option_weeks());
			break;
		case 'month':
			become_hide_or_show(name + "_day", "show");
			$("#" + name + "_day").empty().append(option_days());
			break;
		case 'season_month':
			become_hide_or_show(new Array(name + "_season_month", name + "_day"), "show");
			$("#" + name + "_season_month").empty().append(option_seasons());
			$("#" + name + "_day").empty().append(option_days());
			break;
		case 'year':
			become_hide_or_show(new Array(name + "_month", name + "_day"), "show");
			$("#" + name + "_month").empty().append(option_months());
			$("#" + name + "_day").empty().append(option_days(""));
			break;
	}
}

//控制物件能使用或不能使用
function become_disable_or_usable(element_id, disable_or_usable) {
	if(disable_or_usable == "disable") {
		if(typeof(element_id) == "string") {
			$("#" + element_id + "").attr("disabled",false);
			return
		}
		
		if(typeof(element_id) == "object") {
			for(var i = 0;i < element_id.length;i++) {
				$("#" + element_id[i] + "").attr("disabled",true);
			}
			return
		}
	}

	if(disable_or_usable == "usable") {
		$(":text,textarea,select").attr("disabled",false);
	}
}

//控制物件清空資料
function become_blank(element_id, element_kind) {
	if(element_kind == "text") {
		if(typeof(element_id) == "string") {
			$("#" + element_id + "").val("");
			return
		}
		
		if(typeof(element_id) == "object") {
			for(var i = 0;i < element_id.length;i++) {
				$("#" + element_id[i] + "").val("");
			}
		}
		return
	}

	if(element_kind == "select") {
		if(typeof(element_id) == "string") {
			$("#" + element_id + " option[value='']").attr("selected", true);
			return
		}
		
		if(typeof(element_id) == "object") {
			for(var i = 0;i < element_id.length;i++) {
				$("#" + element_id + " option[value='']").attr("selected", true);
			}
			return
		}
	}

	if(element_kind == "all_element") {
		$(":text,textarea,select").val("");
	}
}

//控制物件隱藏或顯示
function become_hide_or_show(element_id, hide_or_show) {
	if(hide_or_show == "hide") {
		if(typeof(element_id) == "string") {
			$("#" + element_id + "").hide();
			return
		}
		
		if(typeof(element_id) == "object") {
			for(var i = 0;i < element_id.length;i++) {
				$("#" + element_id[i] + "").hide();
			}	
		}
		return
	}

	if(hide_or_show == "show") {
		if(typeof(element_id) == "string") {
			$("#" + element_id + "").show();
			return
		}
		
		if(typeof(element_id) == "object") {
			for(var i = 0;i < element_id.length;i++) {
				$("#" + element_id[i] + "").show();
			}	
		}
	}
}

//初始化清單	
function init_select_element(control, select_id, page, value) {
	var param = $.param({ control : control, value : value});
	$.post("/Billez/control/" + page + "_control.php", param, function(ajax_return) {
//		alert(ajax_return);
		$("#" + select_id + "").empty().append(ajax_return);
	});
}

//檢查輸入是否空白
function check_input_not_blank(element_id) {
	if(typeof(element_id) == "string") {
		if($("#" + element_id + "").val() == "") {
			alert("* 號欄位請勿空白 ! ");
			return false;
		}	
		
		return true;
	}
	
	if(typeof(element_id) == "object") {
		for(var i = 0;i < element_id.length;i++) {
			if($("#" + element_id[i] + "").val() == "") {
				alert("* 號欄位請勿空白 ! ");
				return false;
			}	
		}	
		
		return true;
	}
}

//傳送ajax參數設置
function ajax_param_set(element_id) {
	var obj = new Object();

	for(var post_name in element_id) {
		obj[element_id[post_name]] = $("#" + element_id[post_name] + "").val();
	}

	return obj;
}