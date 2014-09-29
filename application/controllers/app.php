<?php

class App extends CI_Controller {
	/*
	 * 建構式,將會用到的函式先行載入
	 */
	public function __construct() {
		parent::__construct();
		
		//為openssl的檔案在linux平台需要這個檔案放在同一目錄下才會啟用,windows就不須這行
// 		$_SERVER['OPENSSL_CONF'] = 'openssl.cnf';
		$this->load->model('app/route_model');
		$this->load->library('key');
		$this->load->library('json');
		$this->load->library('sql');
	}

	/*
	 * 引導函式,第一個到這頁面執行的函式
	 * 依照各種功能類別分別載入所需功能處理
	 * 最後再載入view輸出結果給APP
	 */
	public function index() {
		//測試區
// 		$route_data = array();
// 		$route_data['id'] = 'ACAA00002';
// 		$route_data['mobile_phone_id'] = 'APA91bH8_TT8u8BX45SCecJ9BzDYy7QLeiDkP21MymZ6dv6-9dmywQODeTzQShc7XQw99w6JCLnG_JsX4E65eD5zt9qPSPl_TuUjwdeumuhDSFat7Q1hnxVaVvIzDUNQJ72MszX76mCh-KJNNFbvzFuWCCctxg5x2A';
// 		$route_data['control_param'] = '4';
// 		$route_data['sub_param'] = '4_2';
		
// 		$route_data['new_email'] = 'imasaka1112@yahoo.com.tw111';
// 		$route_data['password'] = 'dddd111';
// 		$route_data['email'] = 'imasaka111222@yahoo.com.tw111';
// 		$route_data['authentication_code'] = '111111';
// 		$route_data['fb_id'] = 'fb1';
// 		$route_data['mobile_phone'] = '0988301481';
// 		$route_data['last_name'] = '王';
// 		$route_data['first_name'] = '家';
// 		echo $json_data;exit();
// 		return $this->json->encode_json(1, $encode_data);
		
		$_POST['public_key'] = "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDkWKC/USWUTfiGpi3JKkWQ3LwO
hJ4IFUZRaEQVPLc0lwfl4+ZJsW2dJe+9picxFVbNHB+hH8tBsRlIh3t2JYcnBveI
U4WxqIDGYjqr5dtp1qaf2LKohsM1xaNxZepA+JeU0PkTwkFYCLj1lWLfvI0tuzG2
9aHuKCSoDHRZjwSKGwIDAQAB";
		$_POST['mobile_phone_id'] = 'mobile_phone_id';
		$_POST['first'] = 1;
		
		
		//每次請求都必須做的檢查,檢查手機ID是否改變,並且整理引導資料
		$route_data = $this->route_model->index($this->input->post());
echo print_r($route_data);exit();
		//依照APP傳來的資料做功能區分,再載入相對應的頁面
		switch($route_data['control_param']) {
			case '0':
				$data['response'] = $route_data['data'];
				break;
			case '1':
				$this->load->library('create');
				$this->load->library('push');
				$this->load->library('sms');
				$this->load->model('app/join_model');
				$data['response'] = $this->join_model->index($route_data);
				break;
			case '2':
				$this->load->library('email');
				$this->load->model('app/forget_model');
				$data['response'] = $this->forget_model->index($route_data);
				break;
			case '3':
				$this->load->library('create');
				$this->load->library('sms');
				$this->load->model('app/alter_model');
				$data['response'] = $this->alter_model->index($route_data);
				break;
			case '4':
				$this->load->model('app/login_model');
				$data['response'] = $this->login_model->index($route_data);
				break;
			case '5':
				$this->load->library('push');
				$this->load->library('sms');
				$this->load->model('app/subscribe_model');
				$data['response'] = $this->subscribe_model->index($route_data);
				break;
			case '6':
				$this->load->model('app/delete_model');
				$data['response'] = $this->delete_model->index($route_data);
				break;
			case '7':
				$this->load->model('app/bill_model');
				$data['response'] = $this->bill_model->index($route_data);
				break;
			case '8':
				$this->load->library('create');
				$this->load->model('app/problem_model');
				$data['response'] = $this->problem_model->index($route_data);
				break;
			case '9':
				$this->load->model('app/memorandum_model');
				$data['response'] = $this->memorandum_model->index($route_data);
				break;
			case '10':
				$this->load->library('sms');
				$this->load->model('app/activity_model');
				$data['response'] = $this->activity_model->index($route_data);
				break;
			case '11':
				$this->load->model('app/change_model');
				$data['response'] = $this->change_model->index($route_data);
				break;
			default:
				$data['response'] = '未授權進入';
				break;
		}
		
		//將要回應給APP的結果載入view輸出
		$this->load->view('app/response.php', $data);
	}
}