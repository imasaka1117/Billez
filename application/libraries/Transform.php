<?php	if ( ! defined('BASEPATH')) exit('No dirct script access allowed');
	
class Transform {
	/*
	 * 各種代碼轉換
	 */
	
	/*
	 * 時間種類
	*/
	public function time_kind($code) {
		switch ($code) {
			case 1:
				$code = '每週';
				break;
			case 2:
				$code = '每月';
				break;
			case 3:
				$code = '每年';
				break;
			case 4:
				$code = '不固定';
				break;
		}
	
		return $code;
	}
	
	/*
	 * 付款種類
	*/
	public function bill_cost_kind($code) {
		switch ($code) {
			case 1:
				$code = '月租';
				break;
			case 2:
				$code = '件計';
				break;
		}
	
		return $code;
	}
	
	/*
	 * 促銷活動範圍
	 */
	public function promo_range($code) {
		switch ($code) {
			case 1:
				$code = '行動會員';
				break;
			case 2:
				$code = '業者';
				break;
			case 3:
				$code = '代收機構';
				break;
		}
	
		return $code;
	}
	
	/*
	 * 促銷活動優惠種類
	 */
	public function promo_way($code) {
		switch ($code) {
			case 1:
				$code = '贈品';
				break;
			case 2:
				$code = '現金減免';
				break;
			case 3:
				$code = '點數';
				break;
		}
	
		return $code;
	}
	
	/*
	 * 帳單匯入種類
	 */
	public function import_bill_kind($code) {
		switch ($code) {
			case 1:
				$code = '繳費帳單';
				break;
			case 2:
				$code = '入帳帳單';
				break;
		}
	
		return $code;
	}
	
	/*
	 * 帳單匯入錯誤處理情況
	 */
	public function import_error_result($code) {
		switch ($code) {
			case 'n':
				$code = '未處理';
				break;
			case 'y':
				$code = '已處理';
				break;
		}
	
		return $code;
	}
	
	/*
	 * 推播事件
	 */
	public function push_event($code) {
		switch ($code) {
			case 1:
				$code = '分享帳單';
				break;
			case 2:
				$code = '新帳單';
				break;
			case 3:
				$code = '訂閱不服務通知';
				break;
			case 4:
				$code = '可能帳單';
				break;
			case 5:
				$code = '入帳帳單通知';
				break;
		}
		
		return $code;
	}
	
	/*
	 * 電子郵件事件
	 */
	public function email_event($code) {
		switch ($code) {
			case 1:
				$code = '忘記密碼';
				break;
			case 2:
				$code = '電子帳單';
				break;
			case 3:
				$code = '印刷業者';
				break;
			case 4:
				$code = '問題回報';
				break;
		}
	
		return $code;
	}
	
	/*
	 * 簡訊事件
	 */
	public function sms_event($code) {
		switch ($code) {
			case 1:
				$code = '認證碼簡訊';
				break;
			case 2:
				$code = '再次寄發認證碼簡訊';
				break;
			case 3:
				$code = '修改會員資料認證碼';
				break;
			case 4:
				$code = '再次寄送修改會員資料認證碼';
				break;
			case 5:
				$code = '分享帳單通知';
				break;
			case 6:
				$code = '推薦好友簡訊';
				break;
		}
	
		return $code;
	}
	
	/*
	 * 系統操作種類
	 */
	public function system_operate($code) {
		switch ($code) {
			case 1:
				$code = '新增';
				break;
			case 2:
				$code = '更新';
				break;
			case 3:
				$code = '刪除';
				break;
		}
	
		return $code;
	}
	
	/*
	 * 系統操作者種類
	 */
	public function system_user($code) {
		switch ($code) {
			case 1:
				$code = '未確定行動會員';
				break;
			case 2:
				$code = '系統';
				break;
		}
	
		return $code;
	}
	
	/*
	 * 寄送條件說明
	 */
	public function send_condition($code) {
		switch ($code) {
			case 1:
				$code = '無限制次數寄送實體帳單';
				break;
			case 2:
				$code = '有限制次數寄送實體帳單';
				break;
			case 3:
				$code = '只有寄送實體帳單';
				break;
		}
		
		return $code;
	}
	
	/*
	 * 首頁處理使用
	 */
	public function home_page($code) {
		switch ($code) {
			case 1:
				$code = 'op';
				break;
			case 2:
				$code = 'cs';
				break;
			case 3:
				$code = 'ma';
				break;
		}
	
		return $code;
	}
	
}//class end