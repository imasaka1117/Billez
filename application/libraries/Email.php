<?php	if ( ! defined('BASEPATH')) exit('No dirct script access allowed');

class Email {
	/*
	 * 寄送電子郵件函式
	 * $kind	寄送的種類	1為忘記密碼, 2為電子帳單, 3為印刷業者, 4為問題回報
	 * $email	收信人的電子郵件
	 * $form	電子郵件基本設定
	 * $data	資料 分為多種類型,依照$kind做改變
	 */
	public function send_email($kind, $email, $form, $data) {
		require $_SERVER['DOCUMENT_ROOT'] . '/Billez_code/resources/api/class.phpmailer.php';

		$mail = new PHPMailer();
		$mail->IsSMTP();
		$mail->Host = $form['server_name'];
		$mail->Port = $form['server_port'];
		$mail->SMTPAuth = true;
		$mail->Username = $form['account'];
		$mail->Password = $form['password'];
		$mail->From = $form['send_email'];
		$mail->FromName = $form['send_name'];
		$mail->CharSet = 'utf-8';
		$mail->Encoding = 'base64';
		$mail->IsHTML(true);

		switch($kind) {
			case 1:
				$mail->Subject = $form['subject'];
				$mail->Body = str_replace('$password', $data, $form['body']);
				break;
			case 2:
				;
				break;
			case 3:
				;
				break;
			case 4:
				;
				break;
		}
		
		//寄送電子郵件
		if($mail->Send()) {
			return 1;
		} else {
			return $mail->ErrorInfo;
		}
	}
	
	//送出會員PDF帳單資料Email
	public function send_bill_email($email, $bill) {
		//發送密碼至E-mail
		$mail = new PHPMailer();
		$mail->IsSMTP();
		$mail->Host = "msa.hinet.net";
		$mail->Port = 25;
		$mail->SMTPAuth = true;
		$mail->Username = "$email";
		$mail->assword = "";
		$mail->From = "yue@yahoo.com.tw";
		$mail->FromName = "長佑科技";
		$mail->AddAddress($email);
		$mail->CharSet = "utf-8";
		$mail->Encoding = "base64";
		$mail->IsHTML(true);
		$mail->Subject = "Billez 電子帳單寄送";
		$mail->Body = "<p>本期帳單在附件</p>";
		$mail->AddAttachment($bill);
		//寄送郵件
		if(!$mail->Send()) {
			return $mail->ErrorInfo;
			exit();
		}
		return "success";
		exit();
	}
	
	//送出實體帳單資料Email給印刷業者
	public function send_entity_bill($email, $bill_path, $file_name) {
		//發送密碼至E-mail
		$mail = new PHPMailer();
		$mail->IsSMTP();
		$mail->Host = "msa.hinet.net";
		$mail->Port = 25;
		$mail->SMTPAuth = true;
		$mail->Username = "$email";
		$mail->assword = "";
		$mail->From = "yue@yahoo.com.tw";
		$mail->FromName = "長佑科技";
		$mail->AddAddress($email);
		$mail->CharSet = "utf-8";
		$mail->Encoding = "base64";
		$mail->IsHTML(true);
		$mail->Subject = "Billez 紙本帳單檔案寄送";
		$mail->Body = "<p>本期實體帳單檔案在附件</p>";
		$mail->AddAttachment($bill_path . iconv("UTF-8", "BIG5", $file_name), $file_name);
		//寄送郵件
		if(!$mail->Send()) {
			return $mail->ErrorInfo;
			exit();
		}
		return "success";
		exit();
	}
	
	//送出問題回報的email
	public function send_response_email($email, $problem, $response) {
		//發送密碼至E-mail
		$mail = new PHPMailer();
		$mail->IsSMTP();
		$mail->Host = "msa.hinet.net";
		$mail->Port = 25;
		$mail->SMTPAuth = true;
		$mail->Username = "assassiGn7711";
		$mail->assword = "";
		$mail->From = "yue@yahoo.com.tw";
		$mail->FromName = "今阪唯笑";
		$mail->AddAddress($email);
		$mail->CharSet = "utf-8";
		$mail->Encoding = "base64";
		$mail->IsHTML(true);
		$mail->Subject = "你好!! 關於上次你的問題:" . $problem . " 的回覆";
		$mail->Body = "<p>" . $response . "</p>";
		//寄送郵件
		if(!$mail->Send()) {
			return $mail->ErrorInfo;
			exit();
		}
	
		return "success";
		exit();
	}
}