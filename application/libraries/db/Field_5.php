<?php	if ( ! defined('BASEPATH')) exit('No dirct script access allowed');

class Field_5 {
	/*
	 * 整個資料庫的欄位名稱
	 * 加進新的之前先查詢是否已經有現存的
	 */
	static public $insert_trader = 'insert_trader';
	static public $insert_trader_contract = 'insert_trader_contract';
	static public $search_trader = 'search_trader';
	static public $search_trader_contract = 'search_trader_contract';
	static public $export_trader = 'export_trader';
	static public $export_trader_report = 'export_trader_report';
	static public $insert_machinery = 'insert_machinery';
	static public $insert_machinery_contract = 'insert_machinery_contract';
	static public $search_machinery = 'search_machinery';
	static public $search_machinery_contract = 'search_machinery_contract';
	static public $export_machinery = 'export_machinery';
	static public $export_machinery_report = 'export_machinery_report';
	static public $insert_bill_kind = 'insert_bill_kind';
	static public $insert_bill_basis = 'insert_bill_basis';
	static public $search_bill = 'search_bill';
	static public $insert_pay_bill_set = 'insert_pay_bill_set';
	static public $insert_receive_bill_set = 'insert_receive_bill_set';
	static public $update_pay_bill_set = 'update_pay_bill_set';
	static public $update_receive_bill_set = 'update_receive_bill_set';
	static public $insert_customer_pay_bill_set = 'insert_customer_pay_bill_set';
	static public $update_customer_pay_bill_set = 'update_customer_pay_bill_set';
	static public $import_pay_bill = 'import_pay_bill';
	static public $import_receive_bill = 'import_receive_bill';
	static public $push_bill = 'push_bill';
	static public $search_subscribe = 'search_subscribe';
	static public $update_trader_subscribe_state = 'update_trader_subscribe_state';
	static public $update_trader_machinery = 'update_trader_machinery';
	static public $search_action_member = 'search_action_member';	
	static public $export_action_member = 'export_action_member';
	static public $search_normal_member = 'search_normal_member';
	static public $insert_level_object = 'insert_level_object';
	static public $insert_level_name = 'insert_level_name';
	static public $search_level = 'search_level';
	static public $insert_promotion = 'insert_promotion';
	static public $search_promotion = 'search_promotion';
	static public $send_promotion_email = 'send_promotion_email';
	static public $insert_problem = 'insert_problem';
	static public $search_problem = 'search_problem';
	static public $bill_import_error = 'bill_import_error';
	static public $push_error = 'push_error';
	static public $sms_error = 'sms_error';
	static public $email_error = 'email_error';
	static public $system_error = 'system_error';
	static public $search_operator = 'search_operator';
	static public $insert_system_set = 'insert_system_set';
	static public $search_system_set = 'search_system_set';
	static public $scheduling_set = 'scheduling_set';
	static public $search_user = 'search_user';
	static public $insert_user = 'insert_user';
	static public $insert_email_set = 'insert_email_set';
	static public $search_email_set = 'search_email_set';
	static public $insert_sms_set = 'insert_sms_set';
	static public $search_sms_set = 'search_sms_set';
	static public $day = 'day';
}