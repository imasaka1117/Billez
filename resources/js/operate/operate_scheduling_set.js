/**
 * 排程設定
 */
$(document).ready(function() {
	init();

	$("input").click(function() {
		switch($(this).attr('id')) {
			case 'stop_btn':
				if(validate()) change_scheduling($(this).attr('id'));
				break;
			default:
				var ids = new Array('pay_go', 'receive_go', 'push_go', 'possible_go', 'pay_stop', 'receive_stop', 'push_stop', 'possible_stop');
			
				for(i in ids) {
					if($(this).attr('id') == ids[i]) {
						change_scheduling($(this).attr('id'));
						break;
					}
				}
				break;
		}
	});
});

//更改排程
function change_scheduling(id) {
	if(id == 'stop_btn') {
		var param = new Object();
		param['day'] = $('#stop').val();
		
		$.post(ajax_path + class_name + '/stop_day', $.param(param), function(ajax_return) {
			alert(ajax_return);
			if(ajax_return == 'reload') {
				alert('暫停成功');
			} else {
				alert('伺服器忙碌中！！請在試一次');
			}
		});
		
		location.reload();
	}
	
	var ids = new Array('pay', 'receive', 'push', 'possible');
	
	if(id.match('go')) {
		var state = 'y';
	} else {
		var state = 'n';
	}

	for(i in ids) {
		if(id.match(ids[i])) {
			var param = new Object();
			param['kind'] = ids[i];
			param['state'] = state;
			
			$.post(ajax_path + class_name + '/change_scheduling', $.param(param), function(ajax_return) {
				switch(ajax_return) {
					case '1':
						alert('已經是相同的啟動狀態');
						break;
					case '2':
						alert('啟動成功');
						break;
					case '3':
						alert('關閉成功');
						break;
					case '4':
						alert('暫停停止關閉成功');
						break;
					case '5':
						alert('伺服器忙碌中！！請在試一次');
						break;
				}
			});
			break;
		}
	}
	location.reload();
}

//初始化
function init() {	
	//初始化停用天數
	stop_day();
	
	//先將暫停按鈕隱藏及選項凍結
	$('#stop_btn').hide();
	$('#stop').attr('disabled', true);
	$('#pay_go').hide();
	$('#receive_go').hide();
	
	//將FTP繳費帳單啟用狀態初始化
	select_ajax(ajax_path + 'common/init_scheduling', 'pay', 'pay');
	
	//將FTP入帳帳單啟用狀態初始化
	select_ajax(ajax_path + 'common/init_scheduling', 'receive', 'receive');
	
	//將重複推播啟用狀態初始化
	select_ajax(ajax_path + 'common/init_scheduling', 'push', 'push');
	
	//將可能帳單配對啟用狀態初始化
	select_ajax(ajax_path + 'common/init_scheduling', 'possible', 'possible');
	
	if(($('#pay').text() === 'OFF' || $('#pay').text() === 'ON') && ($('#receive').text() === 'OFF' || $('#receive').text() === 'ON')) {
		$('#stop_btn').show();
		$('#stop').attr('disabled', false);
		$('#pay_go').show();
			$('#receive_go').show();
	}
}

//停用天數
function stop_day() {
	var html = '<option value="">請選擇</option>';
	
	for(var i = 1; i <= 30; i++) {
		html += '<option value="' + i + '">' + i + '天</option>';
	}
	
	$('#stop').empty().append(html);
}