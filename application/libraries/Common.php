<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Common extends CI_Controller {

	protected $CI;

	public function __construct()
    {
        $this->CI = & get_instance();
		
    }

    public function otpGenerator($digits = 4){
        $pow_num = pow(10, $digits)-1;
        $token = str_pad(rand(0, intval($pow_num)), $digits, '0', STR_PAD_LEFT);
		
        return $token;
	}
	
    public function base64ToImage($base64_string, $output_file) {
        $file = fopen($output_file, "wb");    
        fwrite($file, base64_decode($base64_string));
        fclose($file);
        

        return $output_file;

    }
    

	
}

?>