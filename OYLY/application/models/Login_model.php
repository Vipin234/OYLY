<?php

class Login_model extends CI_Model
{
	
	public function __construct() 
    {
        parent::__construct();
		$this->load->database();
		$this->load->library('session'); 
    }

	public function checkUser($usertype,$username)
	{
		$this->db->select('*');
		$this->db->from('tbl_admin');
		$this->db->where('status',1);
		$this->db->where('mobile_no',$username);
		$this->db->or_where('user_email',$username);
		$query=$this->db->get();
		// print_r($this->db->last_query());exit;
		$result=$query->result_array();
		return $result;
	}
	public function getData($email,$mobile_no)
	{
		$this->db->select('*');
		$this->db->from('tbl_admin');
		$this->db->where('mobile_no',$mobile_no);
		//$this->db->or_where('user_email',$email);
		$this->db->where('status',1);
		$query=$this->db->get();
		// print_r($this->db->last_query());exit;
		$result=$query->result_array();
		return $result;
	}
	public function resetPassword($user_password,$salt,$mobile_no,$email)
	{
		$array=array('salt'=>$salt,'user_password'=>$user_password);
		$this->db->where('mobile_no',$mobile_no);
		$this->db->update('tbl_admin',$array);
		// print_r($this->db->last_query());exit;
		return $this->db->affected_rows();
	}
public function resetPasswordMasterUser($user_password,$salt,$mobile_no,$email)
	{
		$array=array('salt'=>$salt,'user_password'=>$user_password);
		$this->db->where('mobile_no',$mobile_no);
		$this->db->update('master_user',$array);
		// print_r($this->db->last_query());exit;
		return $this->db->affected_rows();
	}
public function getAdminData($email,$mobile_no)
	{
		$staff=$this->checkStaff($email,$mobile_no);
		if(!empty($staff))
		{   
			return array('message'=>'This mobile number associated with Resataunt staff.');}
			else{
			$admin=$this->checkUser($email,$mobile_no);
			if($admin){return array('message'=>'This mobile number associated with Restaurant Admin.');
		}else{
				$this->db->select('*');
				$this->db->from('master_user');
				$this->db->where('mobile_no',$mobile_no);
				$this->db->or_where('user_email',$email);
				// $this->db->where('status',1);
				$query=$this->db->get();
				// print_r($this->db->last_query());exit;
				$result=$query->result_array();
				if(!empty($result)){
					return array('message'=>'Admin already exists.');
				}else{
					return false;
				}	
			}

		}
		
	}
	public function getMaxUser()
	{

    $this->db->select("MAX(CAST(REPLACE(user_id,'ADMIN','') AS UNSIGNED)+1) AS `user_id`");
    $this->db->from('master_user');
    $query=$this->db->get();
    //print_r($this->db->last_query());
    $result=$query->result_array();
    return $result[0]['user_id'];

	}
	public function addAdmin($array)
	{
		$this->db->insert('master_user',$array);
		return $this->db->insert_id();
	}
	public function checkStaff($email,$mobile_no)
	{
		$this->db->select('*');
		$this->db->from('tbl_restaurant_staff_registration');
		$this->db->where('mobile_no',$mobile_no);
		$this->db->or_where('email',$email);
		// $this->db->where('status',1);
		$query=$this->db->get();
		// print_r($this->db->last_query());exit;
		$result=$query->result_array();
		return $result;
	}
	public function checkMasterUser($usertype,$username)
	{
		$this->db->select('*');
		$this->db->from('master_user');
		$this->db->where('status',1);
		$this->db->where('mobile_no',$username);
		$this->db->or_where('user_email',$username);
		$query=$this->db->get();
		// print_r($this->db->last_query());exit;
		$result=$query->result_array();
		return $result;
	}
	public function checkUserForStaff($usertype,$username)
	{
		$this->db->select('*');
		$this->db->from('tbl_restaurant_staff_registration');
		$this->db->where('mobile_no',$username);
		$this->db->or_where('email',$username);
		$this->db->where('status',1);
		$query=$this->db->get();
		// print_r($this->db->last_query());exit;
		$result=$query->result_array();
		return $result;
	}
	public function getDataMasterUser($email,$mobile_no)
	{
		$this->db->select('*');
		$this->db->from('master_user');
		$this->db->where('mobile_no',$mobile_no);
		$this->db->or_where('user_email',$email);
		$this->db->where('status',1);
		$query=$this->db->get();
		// print_r($this->db->last_query());exit;
		$result=$query->result_array();
		return $result;
	}
	public function getDataStaffUser($email,$mobile_no)
	{
		$this->db->select('*');
		$this->db->from('tbl_restaurant_staff_registration');
		$this->db->where('mobile_no',$mobile_no);
		$this->db->or_where('email',$email);
		$this->db->where('status',1);
		$query=$this->db->get();
		// print_r($this->db->last_query());exit;
		$result=$query->result_array();
		return $result;
	}
	public function resetPasswordStaffUser($user_password,$salt,$mobile_no,$email)
	{
		$array=array('salt'=>$salt,'password'=>$user_password);
		$this->db->where('mobile_no',$mobile_no);
		$this->db->update('tbl_restaurant_staff_registration',$array);
		// print_r($this->db->last_query());exit;
		return $this->db->affected_rows();
	}
 }