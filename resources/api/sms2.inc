<?php

/* �W�١GhiAir SMS2 For PHP��Class
 * ���g�� : HiNet - hiAir , Chih-Ming Liao
 * ��� : 2006/06/27
 */

class sms2{
	
   var $usenet_handle;    /* socket handle*/
   var $ret_code;
   var $ret_msg;
   var $send_msisdn="";
   var $send_msg_len=266; /* Socket �ǰe SendMsg ����׬�266 */
   var $ret_msg_len=244;  /* Socket ���� RetMsg ����׬�244 */
   var $send_set_len=100;
   var $send_content_len=160;
   
   function sms2(){  }

   /* �禡����G�إ߳s�u�P�{��
    * $server_ip:��A��IP, $server_port:��A��Port, $TimeOut:�s�utimeout�ɶ�
    * $user_acc:�b��, $user_pwd:�K�X
    * return -1�G�����s�u����, 0�G�s�u�P�{�Ҧ��\, 1:�s�u���\�A�{�ҥ���
    */
   function create_conn($server_ip, $server_port, $TimeOut, $user_acc, $user_pwd){
      $msg_type=0;	   /* 0:�ˬd�b���K�X 1:�ǰe²�T 2:�d�߶ǰe���G */

      $this->usenet_handle = fsockopen($server_ip, $server_port, $errno, $errstr, $TimeOut);
      if(!$this->usenet_handle) {
      	 $this->ret_code=-1;
      	 $this->ret_msg="Connection failed!";
      	 return $this->ret_code;
      }
      /* �b���K�X�ˬd */
      $msg_set=$user_acc . "\0" . $user_pwd . "\0";
      $in_temp = pack("C",$msg_type) . pack("C",1) . pack("C",1) . pack("C",0) . pack("C",strlen($msg_set)) . pack("C",0) . $msg_set;
      
      /*---�N����$send_msg_len����ƶ�\0�ɺ� */
      $len_p = $this->send_msg_len - strlen($in_temp);
      $zero_buf='';
      for($i=0;$i<$len_p;$i++){
         $zero_buf = $zero_buf . "\0";
      }
      
      $in = $in_temp . $zero_buf;
      $out = '';
      $write = fwrite ($this->usenet_handle, $in);
      $out = fread ($this->usenet_handle, $this->ret_msg_len);
      /* ��Xret_code */
      $ret_C = substr($out, 0, 1);           /* ��X ret_code */
      $ret_code_array = unpack("C", $ret_C); /* �N$ret_C �নunsigned char , unpack �|return array*/
      $ret_code_value = each ($ret_code_array);    /* array[1]��ret_code���� */
      /* ��Xret_content*/
      $ret_CL = substr($out, 3, 1);          /* ��X ret_content_len */
      $ret_cl_array = unpack("C", $ret_CL);  /* �N$ret_CL �নunsigned char , unpack �|return array*/
      $ret_content_len = each ($ret_cl_array); /* array[1]��ret_content_len���� */
      $ret_content = substr($out, 84, $ret_content_len[1]); /* ��o�^�Ǫ����e*/

      $this->ret_code=$ret_code_value[1];  /* array[1]��ret_code���� */
      $this->ret_msg=$ret_content;
      return $this->ret_code;
   }   

   /* �禡����G�ǰe��r²�T
    * $tel:������, ²�T���e
    * return ret_code
    */
   function send_text( $mobile_number, $message){   	  
   	  if(substr($mobile_number, 0, 1)== "+" ){
      	 $msg_type=15; /* �ǰe���²�T */
      }else{
      	 $msg_type=1; /* �ǰe�ꤺ²�T */
      }
      	 
      $send_type="01"; /* 01 : �Y�ɶǰe*/
      $msg_set_str=$mobile_number . "\0" . $send_type . "\0";

      /*---�N����$msg_set��ת���ƶ�\0�ɺ� */
      $len_p = $this->send_set_len - strlen($msg_set_str);
      $zero_buf='';
      for($i=0;$i<$len_p;$i++){
         $zero_buf = $zero_buf . "\0";
      }
      $msg_set = $msg_set_str . $zero_buf;
   
      /*---�N����$msg_content��ת���ƶ�\0�ɺ� */
      $len_p = $this->send_content_len - strlen($message);
      $zero_buf='';
      for($i=0;$i<$len_p;$i++){
         $zero_buf = $zero_buf . "\0";
      }
      $msg_content = $message . $zero_buf;
         
      $in = pack("C",$msg_type) . pack("C",4) . pack("C",1) . pack("C",0) . pack("C",strlen($msg_set_str)) . pack("C",strlen($message)) . $msg_set . $msg_content;
      
      $write = fwrite ($this->usenet_handle, $in);
      $out = fread ($this->usenet_handle, $this->ret_msg_len);
      $ret_C = substr($out, 0, 1); /* ��X ret_code */
      $ret_code_array = unpack("C", $ret_C); /* �N$ret_C �নunsigned char , unpack �|return array*/
      $ret_code_value = each ($ret_code_array); /* array[1]��ret_code���� */
   
      $ret_CL = substr($out, 3, 1); /* ��X ret_content_len */
      $ret_cl_array = unpack("C", $ret_CL); /* �N$ret_CL �নunsigned char , unpack �|return array*/
      $ret_content_len = each ($ret_cl_array); /* array[1]��ret_content_len���� */
      $ret_content = substr($out, 84, $ret_content_len[1]); /* ��o�^�Ǫ����e*/
      
      $this->ret_code=$ret_code_value[1];  /* array[1]��ret_code���� */
      $this->ret_msg=$ret_content;
      return $this->ret_code;
   }


   /* �禡����G�ǰeWapPush²�T
    * $tel:������, ²�T���e
    * return ret_code
    */
   function send_wappush( $mobile_number, $wap_title, $wap_url){
      $msg_type=13; /* �ǰe²�T */
      $send_type="01"; /* 01:SI*/
      $msg_set_str=$mobile_number . "\0" . $send_type . "\0";

      /*---�N����$msg_set��ת���ƶ�\0�ɺ� */
      $len_p = $this->send_set_len - strlen($msg_set_str);
      $zero_buf='';
      for($i=0;$i<$len_p;$i++){
         $zero_buf = $zero_buf . "\0";
      }
      $msg_set = $msg_set_str . $zero_buf;
   
      /*---�N����$msg_content��ת���ƶ�\0�ɺ� */
      $msg_content_tmp = $wap_url . "\0" . $wap_title . "\0";
      $len_p = $this->send_content_len - strlen($msg_content_tmp);
      $zero_buf='';
      for($i=0;$i<$len_p;$i++){
         $zero_buf = $zero_buf . "\0";
      }
      $msg_content = $msg_content_tmp . $zero_buf;
   
      $in = pack("C",$msg_type) . pack("C",4) . pack("C",1) . pack("C",0) . pack("C",strlen($msg_set_str)) . pack("C",strlen($msg_content_tmp)) . $msg_set . $msg_content;
      
      $write = fwrite ($this->usenet_handle, $in);
      $out = fread ($this->usenet_handle, $this->ret_msg_len);
      $ret_C = substr($out, 0, 1); /* ��X ret_code */
      $ret_code_array = unpack("C", $ret_C); /* �N$ret_C �নunsigned char , unpack �|return array*/
      $ret_code_value = each ($ret_code_array); /* array[1]��ret_code���� */
   
      $ret_CL = substr($out, 3, 1); /* ��X ret_content_len */
      $ret_cl_array = unpack("C", $ret_CL); /* �N$ret_CL �নunsigned char , unpack �|return array*/
      $ret_content_len = each ($ret_cl_array); /* array[1]��ret_content_len���� */
      $ret_content = substr($out, 84, $ret_content_len[1]); /* ��o�^�Ǫ����e*/
      
      $this->ret_code=$ret_code_value[1];  /* array[1]��ret_code���� */
      $this->ret_msg=$ret_content;
      return $this->ret_code;
   }

   /* �禡����G�d��text�o�T���G
    * $messageid:�T��ID
    * return ret_code
    */
   function query_text( $messageid){
      $msg_type=2; /* �d��text�ǰe���G */
      $msg_set=$messageid;
      $in_temp = pack("C",$msg_type) . pack("C",1) . pack("C",1) . pack("C",0) . pack("C",strlen($msg_set)) . pack("C",0) . $msg_set;
      
      /*---�N����$send_msg_len����ƶ�\0�ɺ� */
      $len_p = $this->send_msg_len - strlen($in_temp);
      $zero_buf='';
      for($i=0;$i<$len_p;$i++){
         $zero_buf = $zero_buf . "\0";
      }
      
      $in = $in_temp . $zero_buf;
      $out = '';
      $write = fwrite ($this->usenet_handle, $in);
      $out = fread ($this->usenet_handle, $this->ret_msg_len);
      $ret_C = substr($out, 0, 1); /* ��X ret_code */
      $ret_code_array = unpack("C", $ret_C); /* �N$ret_C �নunsigned char , unpack �|return array*/
      $ret_code_value = each ($ret_code_array); /* array[1]��ret_code���� */
   
      $ret_CL = substr($out, 3, 1); /* ��X ret_content_len */
      $ret_cl_array = unpack("C", $ret_CL); /* �N$ret_CL �নunsigned char , unpack �|return array*/
      $ret_content_len = each ($ret_cl_array); /* array[1]��ret_content_len���� */
      $ret_content = substr($out, 84, $ret_content_len[1]); /* ��o�^�Ǫ����e*/
      
      $this->ret_code=$ret_code_value[1];  /* array[1]��ret_code���� */
      $this->ret_msg=$ret_content;
      return $this->ret_code;
   }


   /* �禡����G�d��wappush�o�T���G
    * $messageid:�T��ID
    * return ret_code
    */
   function query_wappush( $messageid){
      $msg_type=14; /* �d��wappush�ǰe���G */
      $msg_set=$messageid;
      $in_temp = pack("C",$msg_type) . pack("C",1) . pack("C",1) . pack("C",0) . pack("C",strlen($msg_set)) . pack("C",0) . $msg_set;
      
      /*---�N����$send_msg_len����ƶ�\0�ɺ� */
      $len_p = $this->send_msg_len - strlen($in_temp);
      $zero_buf='';
      for($i=0;$i<$len_p;$i++){
         $zero_buf = $zero_buf . "\0";
      }
      
      $in = $in_temp . $zero_buf;
      $out = '';
      $write = fwrite ($this->usenet_handle, $in);
      $out = fread ($this->usenet_handle, $this->ret_msg_len);
      $ret_C = substr($out, 0, 1); /* ��X ret_code */
      $ret_code_array = unpack("C", $ret_C); /* �N$ret_C �নunsigned char , unpack �|return array*/
      $ret_code_value = each ($ret_code_array); /* array[1]��ret_code���� */
   
      $ret_CL = substr($out, 3, 1); /* ��X ret_content_len */
      $ret_cl_array = unpack("C", $ret_CL); /* �N$ret_CL �নunsigned char , unpack �|return array*/
      $ret_content_len = each ($ret_cl_array); /* array[1]��ret_content_len���� */
      $ret_content = substr($out, 84, $ret_content_len[1]); /* ��o�^�Ǫ����e*/
      
      $this->ret_code=$ret_code_value[1];  /* array[1]��ret_code���� */
      $this->ret_msg=$ret_content;
      return $this->ret_code;
   }

   /* �禡����G�����^�Ǫ��T��
    * return ret_code
    */
   function recv_msg(){
      $msg_type=3; /* �����^�Ǫ��T�� */
      $msg_set="";
      $in_temp = pack("C",$msg_type) . pack("C",1) . pack("C",1) . pack("C",0) . pack("C",strlen($msg_set)) . pack("C",0) . $msg_set;
      
      /*---�N����$send_msg_len����ƶ�\0�ɺ� */
      $len_p = $this->send_msg_len - strlen($in_temp);
      $zero_buf='';
      for($i=0;$i<$len_p;$i++){
         $zero_buf = $zero_buf . "\0";
      }
      
      $in = $in_temp . $zero_buf;
      $out = '';
      $write = fwrite ($this->usenet_handle, $in);
      $out = fread ($this->usenet_handle, $this->ret_msg_len);
      $ret_C = substr($out, 0, 1); /* ��X ret_code */
      $ret_code_array = unpack("C", $ret_C); /* �N$ret_C �নunsigned char , unpack �|return array*/
      $ret_code_value = each ($ret_code_array); /* array[1]��ret_code���� */

      $ret_CL = substr($out, 2, 1); /* ��X ret_set_len */
      $ret_cl_array = unpack("C", $ret_CL); /* �N$ret_CL �নunsigned char , unpack �|return array*/
      $ret_set_len = each ($ret_cl_array); /* array[1]��ret_set_len���� */
      $ret_set = substr($out, 4, $ret_set_len[1]); /* ��o�^��set�����e*/
      $send_msisdn_array = split('\x0',$ret_set); /* ��o�Ǧ^�̪������*/

      $ret_CL = substr($out, 3, 1); /* ��X ret_content_len */
      $ret_cl_array = unpack("C", $ret_CL); /* �N$ret_CL �নunsigned char , unpack �|return array*/
      $ret_content_len = each ($ret_cl_array); /* array[1]��ret_content_len���� */
      $ret_content = substr($out, 84, $ret_content_len[1]); /* ��o�^�Ǫ����e*/
      
      $this->ret_code=$ret_code_value[1];  /* array[1]��ret_code���� */
      $this->ret_msg=$ret_content;
      $this->send_msisdn=$send_msisdn_array[0]; /* array[0]���^�Ǫ̪��� */
      return $this->ret_code;
   }   

   /* �^��ret_content���� */
   function get_ret_msg(){
      return $this->ret_msg;
   }

   /* �^��send_tel���� */
   function get_send_tel(){
      return $this->send_msisdn;
   }
  
   /* �����s�u */
   function close_conn(){
   	  if($this->usenet_handle)
         fclose ($this->usenet_handle);
   }
}
?>

