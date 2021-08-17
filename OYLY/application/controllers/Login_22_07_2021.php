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
}

?>