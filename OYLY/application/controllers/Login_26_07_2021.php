<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller
{
	
	function __construct() 
    {   
    	 parent::__construct();
    }

		public function loginView() 
		{   
			$this->load->view('header');
			$this->load->view('login');
			// $this->load->view('footer');
		}
		public function resetPassword($email='',$mobile_no='',$time='') 
		{   
			$this->load->view('header');
			$params=array('email'=>$email,'mobile_no'=>$mobile_no,'time'=>$time);
			$this->load->view('resetPassword',$params);
			// $this->load->view('footer');
		}
}

?>