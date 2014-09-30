<?php	if ( ! defined('BASEPATH')) exit('No dirct script access allowed');

class Table {
	/*
	 * 整個資料庫的表格名稱
	 * 加進新的之前先查詢是否已經有現存的
	 */
	static public $action_member_alter_log = 'action_member_alter_log';
	static public $action_member_data = 'action_member_data';
	static public $action_member = 'action_member';
	static public $bill_basis = 'bill_basis';
	static public $bill_import_log = 'bill_import_log';
	static public $bill_kind_code = 'bill_kind_code';
	static public $bill_share_log = 'bill_share_log';
	static public $bill = 'bill';
	static public $email_form = 'email_form';
	static public $email_log = 'email_log';
	static public $entity_bill_log = 'entity_bill_log';
	static public $import_error_log = 'import_error_log';
	static public $key = 'key';
	static public $level_kind = 'level_kind';
	static public $level = 'level';
	static public $machinery_code = 'machinery_code';
	static public $machinery_contract = 'machinery_contract';
	static public $machinery = 'machinery';
	static public $moblie_phone_id_and_key = 'moblie_phone_id_and_key';
	static public $normal_member = 'normal_member';
	static public $password = 'password';
	static public $pay_place = 'pay_place';
	static public $problem_log = 'problem_log';
	static public $push_log = 'push_log';
	static public $push_state = 'push_state';
	static public $recommend_list = 'recommend_list';
	static public $sales_log = 'sales_log';
	static public $scheduling_log = 'scheduling_log';
	static public $sms_form = 'sms_form';
	static public $sms_log = 'sms_log';
	static public $sms_state = 'sms_state';
	static public $subscribe = 'subscribe';
	static public $system_log = 'system_log';
	static public $system_setting = 'system_setting';
	static public $trader_bill = 'trader_bill';
	static public $trader_code = 'trader_code';
	static public $trader_contract = 'trader_contract';
	static public $trader_machinery = 'trader_machinery';
	static public $trader_publish_bill_form = 'trader_publish_bill_form';
	static public $trader_receive_bill_form = 'trader_receive_bill_form';
	static public $trader = 'trader';
	static public $user_list = 'user_list';
	static public $user_log = 'user_log';
}