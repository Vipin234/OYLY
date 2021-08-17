<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';
class Login extends REST_Controller {

  function __construct()
  {  
       parent::__construct(); 
       $this->load->library('form_validation'); 
       $this->load->library('encrypt');     
       $this->load->model('Login_model');
       $this->load->helper('main_helper');  
       // print_r('expression');exit;    
 
   }
  public function login_post()
  {
    try
    {
    
      if($this->form_validation->run('login')==FALSE)
      {     
            $message = $this->form_validation->error_array();
            $this->response(array('status'=>0,'message'=>"Invalid credential"), 200);
      }else
      {
             $usertype='';
             $username=$_POST['username'];
             $user_password=$_POST['password'];
             $valid=validate_mobile($username);
             $result=$this->Login_model->checkUser($usertype,$username);
             $masterUser=$this->Login_model->checkMasterUser($usertype,$username);
             
             if(!empty($result) && $valid==TRUE)
             {
                    $salt         =$result[0]['salt'];
                    $db_password  =$result[0]['user_password'];
                    $password     =$this->encrypt->decode($db_password, $salt);

                    if($user_password==$password)
                    {
                        $this->session->set_userdata('admin_id', $result[0]['admin_id']);
                        $this->session->set_userdata('user_id', $result[0]['user_id']);
                        $this->session->set_userdata('mobile_no'  , $result[0]['mobile_no']);
                        $this->session->set_userdata('user_email'  , $result[0]['user_email']);
                        $this->session->set_userdata('user_fullname' , $result[0]['user_fullname']);
                        $this->session->set_userdata('user_role' , $result[0]['user_role']);
                        $this->session->set_userdata('user_type' , $result[0]['user_type']);
                        $this->session->set_userdata('restaurant_name' , $result[0]['restaurant_name']);
                        $session_data = $this->session->all_userdata();
                        $arry=array('status'=>'1','message'=>'success');
                        $this->response($arry, 200);
                    }else
                    {
                       $arry=array('status'=>'0','message'=>'Invalid credential');
                      $this->response($arry, 200);
                    }
             
             }else if(!empty($masterUser) && $valid==TRUE)
             {          
                       
                    $salt         =$masterUser[0]['salt'];
                    $db_password  =$masterUser[0]['user_password'];
                    $password     =$this->encrypt->decode($db_password, $salt);
                     // print_r($password);exit;
                        if($password==$user_password)
                        {
                        $this->session->set_userdata('user_id', $masterUser[0]['user_id']);
                        $this->session->set_userdata('id', $masterUser[0]['id']);
                        $this->session->set_userdata('mobile_no'  , $masterUser[0]['mobile_no']);
                        $this->session->set_userdata('user_email'  , $masterUser[0]['user_email']);
                        $this->session->set_userdata('user_fullname' , $masterUser[0]['user_fullname']);
                        $this->session->set_userdata('user_role' , $masterUser[0]['user_role']);
                        $this->session->set_userdata('user_type' , $masterUser[0]['user_type']);
                        $session_data = $this->session->all_userdata();
                        $arry=array('status'=>'1','message'=>'success');
                        $this->response($arry, 200);

                        }else
                        {
                          $arry=array('status'=>'0','message'=>'Invalid credential');
                          $this->response($arry, 200);
                        }
                        
             }
             else
             {
              $arry=array('status'=>'0','message'=>'Invalid credential');
              $this->response($arry, 200);
             }
            
      }
    }catch(Eception $e)
    {
    echo $e->getMessage();
    $error = array('status' =>'0', "data" => "Internal Server Error - Please try Later.","StatusCode"=> "HTTP405");
    $this->response($error, 200);
    }
  }
  public function resetPassword_post()
  {
    try
    {
      $this->_validate();
      $email     =$_POST['email'];
      $mobile_no =$_POST['mobile_no'];
      $valid=validate_mobile($mobile_no);
      $password  =$_POST['new_password'];
      $result   =$this->Login_model->getData($email,$mobile_no);
      $masterUser=$this->Login_model->getDataMasterUser($email,$mobile_no);
      $staffUser=$this->Login_model->getDataStaffUser($email,$mobile_no);
      // print_r($result);exit;
      if(!empty($result) && $valid==TRUE)
      {
           $saltArray   =array(
                         'email'=>$email,
                         'phone'=>$mobile_no,
                         );
            $salt           =createHash($saltArray);
            $user_password  =$this->encrypt->encode($password, $salt);
            $update=$this->Login_model->resetPassword($user_password,$salt,$mobile_no,$email);
            if($update)
            {
              $arry=array('status'=>'1','message'=>'Password changed successfully');
              $this->response($arry, 200);
            }else
            {
              $arry=array('status'=>'0','message'=>'Invalid credentials.','inputerror'=>array());
              $this->response($arry, 200);
            }
      }else if(!empty($masterUser) && $valid==TRUE)
      {
         $saltArray   =array(
                         'email'=>$email,
                         'phone'=>$mobile_no,
                         );
            $salt           =createHash($saltArray);
            $user_password  =$this->encrypt->encode($password, $salt);
            $update=$this->Login_model->resetPasswordMasterUser($user_password,$salt,$mobile_no,$email);
            if($update)
            {
              $arry=array('status'=>'1','message'=>'Password changed successfully');
              $this->response($arry, 200);
            }else
            {
              $arry=array('status'=>'0','message'=>'Invalid credentials.','inputerror'=>array());
              $this->response($arry, 200);
            }
      }
      else if(!empty($staffUser) && $valid==TRUE)
      {
         $saltArray   =array(
                         'email'=>$email,
                         'phone'=>$mobile_no,
                         );
            $salt           =createHash($saltArray);
            $user_password  =$this->encrypt->encode($password, $salt);
            $update=$this->Login_model->resetPasswordStaffUser($user_password,$salt,$mobile_no,$email);
            if($update)
            {
              $arry=array('status'=>'1','message'=>'Password changed successfully');
              $this->response($arry, 200);
            }else
            {
              $arry=array('status'=>'0','message'=>'Invalid credentials.','inputerror'=>array());
              $this->response($arry, 200);
            }
      }
      else
      {
        $arry=array('status'=>'0','message'=>'Invalid credentials.','inputerror'=>array());
       $this->response($arry, 200);
      }

    }catch(Exception $e)
    {
      echo $e->getMessage();
      $error = array('status' =>'0', "data" => "Internal Server Error - Please try Later.","StatusCode"=> "HTTP405");
      $this->response($error, 200);
    }
  }
  public function signUp_post()
  {

    try
    {
        $this->_validate2();
        $mobile_no      =$_POST['admin_mobile_no'];
        $email_id       =$_POST['email_id'];
        $name           =$_POST['name'];
        $password       =$_POST['admi_password'];
        // print_r($_POST);exit;   
        $valid=validate_mobile($mobile_no);
        $result         =$this->Login_model->getAdminData($email_id,$mobile_no);
        // print_r($result);exit;  
        if(empty($result) &&  $valid==TRUE)
        {
            $saltArray   =array(
                         'email'=>$email_id,
                         'phone'=>$mobile_no,
                         );

            $salt           =createHash($saltArray);
            // print_r($saltArray);exit;
            $user_password  =$this->encrypt->encode($password, $salt);
            $max            =$this->Login_model->getMaxUser();
            $array          =array(
                            'user_id'       =>empty($max)?'ADMIN1':'ADMIN'.$max,
                            'user_fullname' =>$name,
                            'mobile_no'     =>$mobile_no,
                            'salt'          =>$salt,
                            'user_password' =>$user_password,
                            'status'        =>1,
                            'user_email'    =>$email_id,
                            'user_role'     =>'1',
                            'user_type'     =>'Super Admin',
                            'user_createdate' =>date('Y-m-d H:i:s')
            );
            $inserted=$this->Login_model->addAdmin($array);
            if($inserted)
            {

              $arry=array('status'=>'1','message'=>'Admin created.');
              $this->response($arry, 200);
            }else
            {

              $arry=array('status'=>'0','message'=>'Admin not created.','inputerror'=>array());
              $this->response($arry, 200);
            }

        }else
        {

          $arry=array('status'=>'0','message'=>$result['message'],'inputerror'=>array());
         $this->response($arry, 200);
        }

    }catch(Eception $e)
    {
      $e->getMessage();
      $error = array('status' =>'0', "data" => "Internal Server Error - Please try Later.","StatusCode"=> "HTTP405");
      $this->response($error, 200);
    }

  }
   private function _validate()
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;
 
        if($this->input->post('mobile_no') == '')
        {
            $data['inputerror'][] = 'mobile_no';
            $data['error_string'][] = 'Mobile no  is required';
            $data['status'] = FALSE;
        }

        if($this->input->post('email') == '')
        {
            $data['inputerror'][] = 'email';
            $data['error_string'][] = 'Email is required';
            $data['status'] = FALSE;
        }
        if($this->input->post('new_password') == '')
        {
            $data['inputerror'][] = 'new_password';
            $data['error_string'][] = 'Password is required';
            $data['status'] = FALSE;
        }
 
        if($data['status'] === FALSE)
        {
            echo json_encode($data);
            exit();
        }
    }
       private function _validate2()
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;
 
        if($this->input->post('admin_mobile_no') == '')
        {
            $data['inputerror'][] = 'admin_mobile_no';
            $data['error_string'][] = 'Mobile no  is required';
            $data['status'] = FALSE;
        }

        if($this->input->post('email_id') == '')
        {
            $data['inputerror'][] = 'email_id';
            $data['error_string'][] = 'Email is required';
            $data['status'] = FALSE;
        }
        if($this->input->post('admi_password') == '')
        {
            $data['inputerror'][] = 'admi_password';
            $data['error_string'][] = 'Password is required';
            $data['status'] = FALSE;
        }
        if($this->input->post('name') == '')
        {
            $data['inputerror'][] = 'name';
            $data['error_string'][] = 'Name is required';
            $data['status'] = FALSE;
        }
 
 
        if($data['status'] === FALSE)
        {
            echo json_encode($data);
            exit();
        }
    }
}