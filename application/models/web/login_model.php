<?php

class Login_model extends CI_Model {
	/*
	 * 檢查登入者的帳號密碼是否正確
	 * $post 帳號密碼資料
	 */
	public function login($post) {
		//查詢該帳號是否存在
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$id, Field_1::$name, Field_2::$kind), ''),
																		 'from' => Table_1::$user_list,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where', 'where'), array(Field_1::$email, Field_1::$password), array($post['login_email'], md5($post['login_password'])), array('', '')),
																		 'other' => '')), 'row_array');
		if(!isset($sql_result['id'])) return 1;
		
		//設置session
		session_start();
		$_SESSION['user'] = array('id'=>$sql_result['id'], 
								  'name'=>$sql_result['name'], 
								  'kind'=>$sql_result['kind'],
								  'level'=>$this->transform->home_page($sql_result['kind']));
		/*
		 * 依照登入者給予不同的登入畫面
		 * op 操作人員
		 * cs 客服
		 * ma 管理者
		 */
		switch($sql_result['kind']) {
			case 1:
				return 'home/op';
				break;
			case 2:
				return 'home/cs';
				break;
			case 3:
				return 'home/ma';
				break;
		}
	}
}//end