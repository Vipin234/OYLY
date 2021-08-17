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
		public function resetPassword($data='') 
		{   
			$this->load->view('header');
			date_default_timezone_set('Asia/kolkata');
			$data=base64_decode($data);
			$dataArr=json_decode($data,true);
			if(!empty($dataArr))
			{
				$time=$dataArr['data']['time'];
				$expire_time=date('Y-m-d H:i:s',strtotime($time.'+10 minute'));
				$expire_time=strtotime($expire_time);
				$now = date('Y-m-d H:i:s');
				$current_time=strtotime($now);
				if($current_time>$expire_time)
				{
	
				$url=base_url();
				echo "<script>alert('This link has been expired');window.location.href='".$url."';</script>";
				}   
				else{
					unset($dataArr['data']['time']);
					$finalData=json_encode($dataArr,true);
					$finalData=base64_encode($finalData);
					$final['final']=$finalData;
					$this->load->view('resetPassword',$final);
				}
			}
			else{
				$url=base_url();
				echo "<script>alert('Unauthorized login');window.location.href='".$url."';</script>";
			}

		}
}

?>