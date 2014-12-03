<?php

class User extends CI_Controller {
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
	 * 更新使用者(修改過) 
	 */
	public function update() {

		session_start();
		$this->load->model('web/user_model');

		$data['ajax'] = $this->user_model->update($this->input->post(), $_SESSION['user']);
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 查詢使用者資料(修改過)
	 */
	public function search_data() {
		$this->load->model('web/user_model');
		$data['ajax'] = $this->user_model->search_data($this->input->post());
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 更改使用者頁面(修改過)
	 */
	public function update_web() {
		$data = $this->param->resources(array('validate_js'=>Param::$validate_js, 
											   'base_css'=>Param::$base_css, 
				                               'js_path'=>Param::$js_path, 
				                               'jquery_js'=>Param::$jquery_js, 
				                               'function_js'=>Param::$function_js, 
				                               'index_url'=>Param::$index_url));
		$data['now_use'] = 'user/user_update.js';
		$data['class_name'] = 'user';
		$data['id'] = $this->input->get('id');
		$this->load->view('templates/header', $data);
		$this->load->view('web/user/update', $data);
	}
	
	/*
	 * 查詢使用者(修改過)
	 */
	public function search() {
		$this->load->model('web/user_model');
		$this->load->library('web/option');
		$data['ajax'] = $this->user_model->search($this->input->post());
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 查詢使用者頁面
	 */
	public function search_web() {
		$data = $this->param->resources(array('base_css'=>Param::$base_css, 
				                               'js_path'=>Param::$js_path, 
				                               'jquery_js'=>Param::$jquery_js, 
				                               'function_js'=>Param::$function_js, 
				                               'index_url'=>Param::$index_url));
		$data['now_use'] = 'user/user_search.js';
		$data['class_name'] = 'user';
		$this->load->view('templates/header', $data);
		$this->load->view('web/user/search', $data);
	}
	
	/*
	 * 輸入使用者
	 */
	public function insert() {
		session_start();
		$this->load->model('web/user_model');
		$this->load->library('create');
		$data['ajax'] = $this->user_model->insert($this->input->post(), $_SESSION['user']);
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 輸入使用者頁面
	 */
	public function insert_web() {
		$data = $this->param->resources(array('validate_js'=>Param::$validate_js, 
				                               'base_css'=>Param::$base_css, 
				                               'js_path'=>Param::$js_path, 
				                               'jquery_js'=>Param::$jquery_js, 
				                               'function_js'=>Param::$function_js, 
				                               'index_url'=>Param::$index_url));
		$data['now_use'] = 'user/user_insert.js';
		$data['class_name'] = 'user';
		$this->load->view('templates/header', $data);
		$this->load->view('web/user/insert', $data);
	}
}//end