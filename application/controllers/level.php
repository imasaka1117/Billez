<?php

class Level extends CI_Controller {
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
	 * 更新等級名稱
	 */
	public function update() {
		session_start();
		$this->load->model('web/level_model');
		$data['ajax'] = $this->level_model->update($this->input->post(), $_SESSION['user']);
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 查詢等級資料
	 */
	public function search_data() {
		$this->load->model('web/level_model');
		$data['ajax'] = $this->level_model->search_data($this->input->post());
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 觀看等級資料頁面
	 */
	public function update_web() {
		$data = $this->param->resources(array('validate_js'=>Param::$validate_js, 'base_css'=>Param::$base_css, 'js_path'=>Param::$js_path, 'jquery_js'=>Param::$jquery_js, 'function_js'=>Param::$function_js, 'index_url'=>Param::$index_url));
		$data['now_use'] = 'level/level_update.js';
		$data['class_name'] = 'level';
		$data['id'] = $this->input->get('id');
		$this->load->view('templates/header', $data);
		$this->load->view('web/level/update', $data);
	}
	
	/*
	 * 查詢等級
	 */
	public function search() {
		$this->load->model('web/level_model');
		$this->load->library('web/option');
		$data['ajax'] = $this->level_model->search($this->input->post());
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 查詢等級頁面
	 */
	public function search_web() {
		$data = $this->param->resources(array('validate_js'=>Param::$validate_js, 'base_css'=>Param::$base_css, 'js_path'=>Param::$js_path, 'jquery_js'=>Param::$jquery_js, 'function_js'=>Param::$function_js, 'index_url'=>Param::$index_url));
		$data['now_use'] = 'level/level_search.js';
		$data['class_name'] = 'level';
		$this->load->view('templates/header', $data);
		$this->load->view('web/level/search', $data);
	}

	/*
	 * 新增等級名稱
	 */
	public function insert_name() {
		session_start();
		$this->load->model('web/level_model');
		$this->load->library('create');
		$data['ajax'] = $this->level_model->insert_name($this->input->post(), $_SESSION['user']);
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 新增等級名稱頁面
	 */
	public function insert_name_web() {
		$data = $this->param->resources(array('validate_js'=>Param::$validate_js, 'base_css'=>Param::$base_css, 'js_path'=>Param::$js_path, 'jquery_js'=>Param::$jquery_js, 'function_js'=>Param::$function_js, 'index_url'=>Param::$index_url));
		$data['now_use'] = 'level/level_name_insert.js';
		$data['class_name'] = 'level';
		$this->load->view('templates/header', $data);
		$this->load->view('web/level/insert_name', $data);
	}
	
	/*
	 * 新增等級對象
	 */
	public function insert_object() {
		session_start();
		$this->load->model('web/level_model');
		$this->load->library('create');
		$data['ajax'] = $this->level_model->insert_object($this->input->post(), $_SESSION['user']);
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 新增等級對象頁面
	 */
	public function insert_object_web() {
		$data = $this->param->resources(array('validate_js'=>Param::$validate_js, 'base_css'=>Param::$base_css, 'js_path'=>Param::$js_path, 'jquery_js'=>Param::$jquery_js, 'function_js'=>Param::$function_js, 'index_url'=>Param::$index_url));
		$data['now_use'] = 'level/level_object_insert.js';
		$data['class_name'] = 'level';
		$this->load->view('templates/header', $data);
		$this->load->view('web/level/insert_object', $data);
	}
}//end