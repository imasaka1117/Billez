<?php

class Promo extends CI_Controller {
	/*
	 * 建構式,將會用到的函式先行載入
	 */
	public function __construct() {
		parent::__construct();
		$this->load->library('web/param');
		$this->load->model('db/query_model');
		$this->load->library('db/field_1');
		$this->load->library('db/field_2');
		$this->load->library('db/field_3');
		$this->load->library('db/field_4');
		$this->load->library('db/table_1');
		$this->load->library('sql');
	}
	
	/*
	 * 更新促銷優惠
	 */
	public function update() {
		session_start();
		$this->load->model('web/promo_model');
		$data['ajax'] = $this->promo_model->update($this->input->post(), $_SESSION['user']);
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 查詢促銷優惠資料
	 */
	public function search_data() {
		$this->load->model('web/promo_model');
		$data['ajax'] = $this->promo_model->search_data($this->input->post());
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 觀看促銷優惠頁面
	 */
	public function update_web() {
		$data = $this->param->resources(array('date_js'=>Param::$date_js, 'validate_js'=>Param::$validate_js, 'base_css'=>Param::$base_css, 'js_path'=>Param::$js_path, 'jquery_js'=>Param::$jquery_js, 'function_js'=>Param::$function_js, 'index_url'=>Param::$index_url));
		$data['now_use'] = 'promo/promo_update.js';
		$data['class_name'] = 'promo';
		$data['id'] = $this->input->get('id');
		$this->load->view('templates/header', $data);
		$this->load->view('web/promo/update', $data);
	}
	
	/*
	 * 查詢促銷優惠
	 */
	public function search() {
		$this->load->model('web/promo_model');
		$this->load->library('transform');
		$this->load->library('web/option');
		$data['ajax'] = $this->promo_model->search($this->input->post());
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 查詢促銷優惠頁面
	 */
	public function search_web() {
		$data = $this->param->resources(array('validate_js'=>Param::$validate_js, 'base_css'=>Param::$base_css, 'js_path'=>Param::$js_path, 'jquery_js'=>Param::$jquery_js, 'function_js'=>Param::$function_js, 'index_url'=>Param::$index_url));
		$data['now_use'] = 'promo/promo_search.js';
		$data['class_name'] = 'promo';
		$this->load->view('templates/header', $data);
		$this->load->view('web/promo/search', $data);
	}

	/*
	 * 新增促銷活動
	 */
	public function insert() {
		session_start();
		$this->load->model('web/promo_model');
		$this->load->library('create');
		$data['ajax'] = $this->promo_model->insert($this->input->post(), $_SESSION['user']);
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 新增促銷活動頁面
	 */
	public function insert_web() {
		$data = $this->param->resources(array('date_js'=>Param::$date_js, 'validate_js'=>Param::$validate_js, 'base_css'=>Param::$base_css, 'js_path'=>Param::$js_path, 'jquery_js'=>Param::$jquery_js, 'function_js'=>Param::$function_js, 'index_url'=>Param::$index_url));
		$data['now_use'] = 'promo/promo_insert.js';
		$data['class_name'] = 'promo';
		$this->load->view('templates/header', $data);
		$this->load->view('web/promo/insert', $data);
	}
	
	/*
	 * 寄發促銷活動頁面
	*/
	public function send_web() {
		$data = $this->param->resources(array('date_js'=>Param::$date_js, 'validate_js'=>Param::$validate_js, 'base_css'=>Param::$base_css, 'js_path'=>Param::$js_path, 'jquery_js'=>Param::$jquery_js, 'function_js'=>Param::$function_js, 'index_url'=>Param::$index_url));
		$data['now_use'] = 'promo/promo_send.js';
		$data['class_name'] = 'promo';
		$this->load->view('templates/header', $data);
		$this->load->view('web/promo/send', $data);
	}
	
	/*
	 * 寄發促銷活動
	*/
	public function send() {
		$this->load->model('web/promo_model');
		$data['ajax'] = $this->promo_model->send($this->input->post());
		$this->load->view('web/ajax', $data);
	}
}//end