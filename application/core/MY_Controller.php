<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class MY_Controller extends CI_Controller{

	/* index.php?/ */
	public $url_prefix = 'index.php/';

	public $not_allowed = false;

	public $imgType_a = array(
        'image/gif', 'image/jpeg', 'image/jpg', 'image/png'
        );

	public function __construct()
	{
		parent::__construct();
		$timezone = "+5.5";
	}

	public function readJson()
    {
       	$json_obj = json_decode('');
        $postdata = file_get_contents("php://input");
        $json_obj=json_decode($postdata);
        return $json_obj;
    }

    private function cleanMe($input)
    {
        $input = htmlspecialchars($input, ENT_IGNORE, 'utf-8');
        $input = strip_tags($input);
        $input = stripslashes($input);
        return $input;
    }

	public function jsonOutput($data = array() ){
		$this->output->set_content_type('application/json');
		echo json_encode($data);
		exit;
	}

	public function saveBase64($path_n_img_name, $base64_string){
		$img_str = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64_string));
		file_put_contents($path_n_img_name, $data);
	}
	

	public function	dd($var=""){
		echo "<pre>";
		print_r($var);
		echo "</pre>";
		die();
	}

	public function breadcrumbs($links){
		$breadcrumb = '';
		$breadcrumb_li = '';
		$base = base_url();
		foreach( $links as $text=>$link ){
			$link = trim( $link );
			if( $link ){
				$breadcrumb_li .= "<li >
			        <a href='$base$this->url_prefix$link'>
			          $text
			        </a>
			      </li>";
			}
			else{
				$breadcrumb_li .= "<li class='active'>
			          $text
			      </li>";
			}

		}
		if( $breadcrumb_li ){
			$breadcrumb = "<ol class='breadcrumb'>$breadcrumb_li</ol>";
		}
		return $breadcrumb;

	}

	public function local_redirect($url){
		header('Location:' . base_url().$this->url_prefix . $url, TRUE);
		// redirect($url);
		exit;
	}

	public function loginCheck(){
		$logged_in = false;
		if (($this->session->userdata('logged_in'))) {
            $logged_in = true;
        }
        else if ($this->input->cookie('cratehunt_user_id')) {
        	$this->load->model('User_model');
        	$cookieLogin = $this->User_model->cookieLogin(get_cookie('cratehunt_user_id'));
            if( !array_key_exists( 'error', $cookieLogin ) ){
	        	$this->setSession($cookieLogin);
	        	$logged_in = true;
	        }
        }
        return $logged_in;
	}

	public function setSession($login){
		/*$this->session->sess_destroy();*/
		$this->logoutUsers();
		if ($this->input->cookie('cratehunt_super_user_id')) {
			$this->input->set_cookie('cratehunt_super_user_id', '');
		}
		$userdata = array(
			'username'    => $login->username,
			'uid'         => $login->user_id,
			'full_name'         => $login->full_name,
			'profile_pic'         => $login->profile_pic,
			'logged_in'   => TRUE
        );
        $this->session->set_userdata($userdata);
        /*$this->local_redirect($red_url);*/
	}

	public function setSuperSession($login){
		/*$this->session->sess_destroy();*/
		$this->logoutUsers();
		if ($this->input->cookie('cratehunt_user_id')) {
			$this->input->set_cookie('cratehunt_user_id', '');
		}
		$userdata = array(
			'username'    => $login->username,
			'usertype'    => 'super_user',
			'full_name'   => $login->full_name,
			'profile_pic' => $login->profile_pic,
			'uid'         => $login->user_id,
			'super_logged_in'   => TRUE
        );
        $this->session->set_userdata($userdata);
        /*$this->local_redirect($red_url);*/
	}

	public function logoutUsers(){
		$userdata = array(
			'username'    => '',
			'usertype'    => '',
			'full_name'   => '',
			'profile_pic' => '',
			'uid'         => '',
			'logged_in'   => FALSE,
			'super_logged_in'   => FALSE,
        );
		$this->session->set_userdata($userdata);
		/*$this->session->sess_destroy();*/
	}

	public function display($data, $return = FALSE){

		if(!isset($data['page_location'])) {
			$page_path = 'admin/';
		} else {
			$page_path = $data['page_location'].'/';
		}

		
		$data['active_link'] = $this->uri->segment(1);
		$data['url_prefix']   = $this->url_prefix;

		$header = $this->load->view($page_path.'/modules/header', $data, TRUE);
		$sidebar = $this->load->view($page_path.'/modules/sidebar', $data, TRUE);
		$footer = $this->load->view($page_path.'/modules/footer', $data, TRUE);

		/*if( $this->session->userdata('usertype') == TEACHER ){
			$this->not_allowed = true;
			$page_path = 'admin/teacher_login/';
			if( isset($data['page_allowed']) && ($data['page_allowed'] == true) ){
				$this->not_allowed = false;
			}
		}*/

		if($this->not_allowed) {
			$data['page_name'] = 'not_allowed';
		}
		$body = $this->load->view($page_path.$data['page_name'], $data, TRUE);

		$output = $header . $sidebar . $body . $footer;

		if($return)
			return $output;
		else
			echo $output;

	}

	public function sendPushNotif($registrationIds, $message, $time, $type, $extra_data = [], $device_os = 'android', $school_name = ''){
		/*if( $school_name == '' ){
			if( $this->session->userdata('full_name') ){
				$school_name = $this->session->userdata('full_name');
			}
		}*/
    	$msg = array
        (
            'alert'   => $message,
            'body'   => $message,
            'message'   => $message,
            'time'   => $time,
            'title'     => 'Student Care',
            'subtitle'  => '',
            'type'  => $type,
            'tickerText'    => $message,
            'vibrate'   => 1,
            'sound'     => 'default',
            'largeIcon' => 'large_icon',
            'smallIcon' => 'small_icon',
            'click_action' => 'DRAWER_ACTIVITY',
        );

        $msg = array_merge($msg, $extra_data);

        $fields = array
        (
			'registration_ids' => $registrationIds,
            'priority'         => 'high',
			'data'             => $msg
        );
        if( $device_os == 'ios' ){
        	$fields = array_merge( $fields, array( 'notification' => $msg ) );
        }
        $headers = array
        (
            'Authorization: key=' . API_ACCESS_KEY,
            'Content-Type: application/json'
        );
        $ch = curl_init();
        curl_setopt( $ch,CURLOPT_URL, FCM_LINK );
        curl_setopt( $ch,CURLOPT_POST, true );
        curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
        $result = curl_exec($ch );
        curl_close( $ch );
        //return $result;
    }

    public function validateDate($date, $format = 'Y-m-d H:i:s')
	{
		/* http://php.net/manual/en/function.checkdate.php */
	    $d = DateTime::createFromFormat($format, $date);
	    return $d && $d->format($format) == $date;
	}

	public function generateRandomString($length = 10) {
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $charactersLength = strlen($characters);
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }
	    return $randomString;
	}
	
	public function getToken(){
		$headers = apache_request_headers();
		if (isset($headers['token']) && !empty($headers['token'])) {
			return $headers['token'];
		}
	}

	public function validateToken($token){
		$this->load->model('User_model');
		$data = $this->User_model->getUserId($token);
		if($data == null){
			return ['error' => '', 'status' => 'fail', 'message' => 'Invalid User'];
		}
		return $data;
	}

	public function validateMobileNumber($mobileNumber){
		$this->load->model('Login_model');
		if(preg_match('/^[0-9]{10}+$/', $mobileNumber)){
			return $this->Login_model->checkMobile($mobileNumber);
		}else{
			return ['error' => 'invalid mobile', 'status' => false, 'message' => 'Please enter a valid mobile number'];
		}
	}

	public function validEmail($email) {
		$emailValidation = preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $email);
		if(!$emailValidation){
			$this->jsonOutput((object)['status' => false, 'message' => 'Sorry! Please try wih a valid email']);
		}else{
			return true;
		}
	}

	public function send_mail($to_email, $message, $attachment = '', $subject="CrateHunt") { 
		$from_email = "midhun.puthenpurackal@gmail.com"; 
		$this->load->library('email'); 

		// $config['protocol']    = 'mail';
		// $config['smtp_host']    = 'smtp.gmail.com';
		// $config['smtp_port']    = '587';
		
		$config['protocol']    = 'smtp';
 		$config['smtp_host']    = 'ssl://smtp.gmail.com';
 		$config['smtp_port']    = '465';

 		$config['smtp_user']    = 'cratehunt.2019@gmail.com';
 		$config['smtp_pass']    = 'kuttanraman@789';
 		$config['charset']    = 'utf-8';
 		$config['newline']    = "\r\n";
		$config['mailtype'] = 'html';
		$config['validation'] = FALSE; // bool whether to validate email or not      
		$this->email->initialize($config);
		
		if($attachment != ''){
		    $this->email->attach(base_url().$attachment);
		}
   
         $this->email->from($from_email, $subject); 
         $this->email->to($to_email);
         
         $this->email->subject($subject); 
         $this->email->message($message); 
   
         //Send mail 
         if($this->email->send()) {
             
            log_message('error', 'Email sent successfully');
         	return ['status' => true, 'email_sent' => 'Email sent successfully'];
         }
         else {
            log_message('error', 'Error in sending Email');
         	return ['status' => false, 'email_sent' => $this->email->print_debugger()];
         }
    }
	
}

/* End of file EIS_Controller.php */
/* Location: ./application/core/EIS_Controller.php */