<?php

class App extends CI_Controller {
	/*
	 * 建構式,將會用到的函式先行載入
	 */
	public function __construct() {
		parent::__construct();
		
		//為openssl的檔案在linux平台需要這個檔案放在同一目錄下才會啟用,windows就不須這行
// 		$_SERVER['OPENSSL_CONF'] = 'openssl.cnf';
		$this->load->model('db/insert_update_model');
		$this->load->model('db/query_model');
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
// 		$sql_select = $this->sql->select(array('CONCAT(a,b,c,d) as t', '`ztest`.`d` as `r`'), 'function');
// 		$sql_where = $this->sql->where(array('where'), array('ztest.ac'), array('cc12'), array(''));
// 		$sql_query = $this->query_model->query($sql_select, 'ztest', '', $sql_where, '');
// 		$sql_result = $this->sql->result($sql_query, 'result_array');
// 		$a = array();
// 		foreach ($sql_result as $sql_result1) {
// 			array_push($a, $sql_result1['a']);
// 		}
// 		$query = $this->db->query('ALTER TABLE `ztest` ADD `gg` varchar(255) COLLATE utf8_bin DEFAULT NULL');

		echo base_url();exit();
		
		//每次請求都必須做的檢查,檢查手機ID是否改變,並且整理引導資料
		$route_data = $this->route_model->index($this->input->post());
		
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
				;
				break;
			case '':
				;
				break;
		}
		
		//將要回應給APP的結果載入view輸出
		$this->load->view('app/response.php', $data);
	}
}