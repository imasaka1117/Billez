<?php	if ( ! defined('BASEPATH')) exit('No dirct script access allowed');

class Table {
	/*
	 * 整個資料庫的表格名稱
	 * 加進新的之前先查詢是否已經有現存的
	 */
	var $action_member_alter_log = 'action_member_alter_log';
	var $action_member_data = 'action_member_data';
	var $action_member = 'action_member';
	var $bill_basis = 'bill_basis';
	var $bill_import_log = 'bill_import_log';
	var $bill_kind_code = 'bill_kind_code';
	var $bill_share_log = 'bill_share_log';
	var $bill = 'bill';
	var $email_form = 'email_form';
	var $email_log = 'email_log';
	var $entity_bill_log = 'entity_bill_log';
	var $import_error_log = 'import_error_log';
	var $key = 'key';
	var $level_kind = 'level_kind';
	var $level = 'level';
	var $machinery_code = 'machinery_code';
	var $machinery_contract = 'machinery_contract';
	var $machinery = 'machinery';
	var $moblie_phone_id_and_key = 'moblie_phone_id_and_key';
	var $normal_member = 'normal_member';
	var $password = 'password';
	var $pay_place = 'pay_place';
	var $problem_log = 'problem_log';
	var $push_log = 'push_log';
	var $push_state = 'push_state';
	var $recommend_list = 'recommend_list';
	var $sales_log = 'sales_log';
	var $scheduling_log = 'scheduling_log';
	var $sms_form = 'sms_form';
	var $sms_log = 'sms_log';
	var $sms_state = 'sms_state';
	var $subscribe = 'subscribe';
	var $system_log = 'system_log';
	var $system_setting = 'system_setting';
	var $trader_bill = 'trader_bill';
	var $trader_code = 'trader_code';
	var $trader_contract = 'trader_contract';
	var $trader_machinery = 'trader_machinery';
	var $trader_publish_bill_form = 'trader_publish_bill_form';
	var $trader_receive_bill_form = 'trader_receive_bill_form';
	var $trader = 'trader';
	var $user_list = 'user_list';
	var $user_log = 'user_log';
}