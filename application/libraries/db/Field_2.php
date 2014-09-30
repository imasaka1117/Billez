<?php	if ( ! defined('BASEPATH')) exit('No dirct script access allowed');

class Bill_field {
	/*
	 * 整個資料庫的欄位名稱
	 * 加進新的之前先查詢是否已經有現存的
	 */
	static public $lowest_pay_amount = 'lowest_pay_amount';
	static public $bank_charge = 'bank_charge';
	static public $post_charge = 'post_charge';
	static public $cvs_charge = 'cvs_charge';
	static public $bank_barcode1 = 'bank_barcode1';
	static public $bank_barcode2 = 'bank_barcode2';
	static public $bank_barcode3 = 'bank_barcode3';
	static public $post_barcode1 = 'post_barcode1';
	static public $post_barcode2 = 'post_barcode2';
	static public $post_barcode3 = 'post_barcode3';
	static public $cvs_barcode1 = 'cvs_barcode1';
	static public $cvs_barcode2 = 'cvs_barcode2';
	static public $cvs_barcode3 = 'cvs_barcode3';
	static public $pay_state = 'pay_state';
	static public $send_state = 'send_state';
	static public $form_name = 'form_name';
	static public $form_kind = 'form_kind';
	static public $server_name = 'server_name';
	static public $server_port = 'server_port';
	static public $account = 'account';
	static public $send_email = 'send_email';
	static public $send_name = 'send_name';
	static public $subject = 'subject';
	static public $body = 'body';
	static public $event = 'event';
	static public $result = 'result';
	static public $file_name = 'file_name';
	static public $error_message = 'error_message';
	static public $send_time = 'send_time';
	static public $file_path = 'file_path';
	static public $bill_count = 'bill_count';
	static public $print_trader_email = 'print_trader_email';
	static public $send_result = 'send_result';
	static public $kind = 'kind';
	static public $reason = 'reason';
	static public $data = 'data';
	static public $user = 'user';
	static public $time = 'time';
	static public $private_key = 'private_key';
	static public $public_key = 'public_key';
	static public $machinery_code = 'machinery_code';
	static public $age = 'age';
	static public $begin = 'begin';
	static public $end = 'end';
	static public $bill_cost_kind = 'bill_cost_kind';
	static public $month_rent = 'month_rent';
	static public $entity_price = 'entity_price';
	static public $action_price = 'action_price';
	static public $pay_kind = 'pay_kind';
}