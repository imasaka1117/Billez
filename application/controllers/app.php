<?php

class App extends CI_Controller {
	/*
	 * 建構式,將會用到的函式先行載入
	 */
	public function __construct() {
		parent::__construct();
		
		//為openssl的檔案在linux平台需要這個檔案放在同一目錄下才會啟用,windows就不須這行
		$_SERVER['OPENSSL_CONF'] = 'openssl.cnf';
		$this->load->model('app/route_model');
		$this->load->model('db/query_model');
		$this->load->library('db/field_1');
		$this->load->library('db/field_2');
		$this->load->library('db/field_3');
		$this->load->library('db/field_4');
		$this->load->library('db/field_5');
		$this->load->library('db/table_1');
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
		//每次請求都必須做的檢查,檢查手機ID是否改變,並且整理引導資料
		$route_data = $this->route_model->index();

		//依照數字選擇處理
		$function = array('', 'join_model', 'forget_model', 'alter_model', 'login_model', 'subscribe_model', 'delete_model', 'bill_model', 'problem_model', 'memorandum_model', 'activity_model', 'change_model');

		//依照APP傳來的資料做功能區分,再載入相對應的頁面
		switch($route_data['control_param']) {
			case '0':
				$data['response'] = $route_data['data'];
				break;
			case '1':
			case '5':
				$this->load->library('push');
				$this->load->model('send/push_model');
				$this->load->model('send/sms_model');
		
				if($route_data['control_param'] == 1) {
					$this->load->library('create');
					$this->load->model('app/join_model');
				} else {
					$this->load->library('transform');
					$this->load->model('app/subscribe_model');
				}
				break;
			case '2':
				$this->load->library('create');
				$this->load->library('email');
				$this->load->model('send/email_model');
				$this->load->model('app/forget_model');
				break;
			case '3':
				$this->load->library('create');
				$this->load->model('send/sms_model');
				$this->load->model('app/alter_model');
				break;
			case '4':
				$this->load->model('other/format_model');
				$this->load->model('app/login_model');
				break;
			case '6':
				$this->load->model('app/delete_model');
				break;
			case '7':
				$this->load->model('other/format_model');
				$this->load->model('app/bill_model');
				break;
			case '8':
				$this->load->library('create');
				$this->load->model('app/problem_model');
				break;
			case '9':
				$this->load->model('app/memorandum_model');
				break;
			case '10':
				$this->load->model('send/sms_model');
				$this->load->model('app/activity_model');
				break;
			case '11':
				$this->load->model('app/change_model');
				break;
			default:
				$data['response'] = '未授權進入';
				break;
		}
		
		if($route_data['control_param'] != 0) {
			$data['response'] = $this->$function[$route_data['control_param']]->index($route_data);
			$this->route_model->insert_temp_return($data['response'], $route_data);
		}

// 		if(!isset($_REQUEST['retry'])) {
// 			sleep(20);
// 		}
		
		//將要回應給APP的結果載入view輸出
		$this->load->view('app/response.php', $data);
	}
}