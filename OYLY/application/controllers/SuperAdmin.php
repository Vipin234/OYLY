<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class SuperAdmin extends CI_Controller
{
	
	function __construct() 
    {   
       parent::__construct();
       $this->load->library('session');
       $this->load->library('form_validation'); 
       $this->load->library('encrypt');     
       $this->load->model('Login_model'); 
       $this->load->model('Admin_model');
       $this->load->model('SuperAdmin_model');
       $this->load->helper('main_helper');   
       if(empty($_SESSION['mobile_no']))
       {
       	redirect(base_url());
       }
    }

    public function superAdmin()
    {
    	$session_data=$this->session->all_userdata();
		$this->load->view('header',$session_data);
		$this->load->view('leftSidebar',$session_data);
		$this->load->view('SuperAdminList');
		$this->load->view('footer');
    }
    public function getSuperAdminList()
    {
		   try
		    {	
		        
				$restaurantList =$this->SuperAdmin_model->get_datatables();
				// print_r($restaurantList);exit;
				$data = array();
				$no = $_POST['start'];
				foreach ($restaurantList as $list) {
					$no++;
					$row = array();
					$row[] 	=$no;
					$row[] 	=$list->user_fullname;
					$row[] 	=$list->user_email;
					$row[] 	=$list->mobile_no;
					$row[] 	=$this->encrypt->decode($list->user_password, $list->salt);
					$row[] 	=$list->user_createdate;
					$row[] 	=$list->user_role=='2'?'Super Admin':'Admin';
		      		$button =$list->status==1?'Active':'Deactive';
					$row[] 	='<div class="row text-center" style="width: max-content;margin-right: auto; margin-left: auto;"><a class="btn btn-sm btn-primary btn-xs" href="javascript:void(0)" title="Edit" onclick="edit('."'".$list->user_id."'".')"style="height: 33px"><i class="glyphicon glyphicon-eye-open"></i>Edit</a>
				                  <a class="btn btn-info btn-xs" href="javascript:void(0)" title="'.$button.'" onclick="change('."'".$list->user_id."'".','."'".$list->status."'".')"style="height: 33px;"><i class="glyphicon glyphicon-saved"></i>'.$button.'</a>
				                  <a class="btn btn-danger btn-xs" href="javascript:void(0)" title="Delete" onclick="remove('."'".$list->user_id."'".')"style="height: 33px;display:none;"><i class="glyphicon glyphicon-deleted"></i>Delete</a></div>';
					$data[]	=$row;
				}
				$output = array(
								"draw" => $_POST['draw'],
								"recordsTotal" => $this->SuperAdmin_model->count_all(),
								"recordsFiltered" => $this->SuperAdmin_model->count_filtered(),
								"data" => $data,
						);
			echo json_encode($output);
		            
		    }catch(Eception $e)
		    {
				    echo $e->getMessage();
				    $error = array('status' =>'0', "data" => "Internal Server Error - Please try Later.","StatusCode"=> "HTTP405");
				    echo json_encode($error, 200);exit;
		    }
		    }
public function remove()
{
	try
	{
		$user_id=$_POST['user_id'];
		
		if(!empty($user_id))
		{

			$result=$this->SuperAdmin_model->delete($user_id);
			if($result)
			{
				$arry=array('status'=>'1','message'=>'Success');
             	echo json_encode($arry, 200);exit();
			}else
			{
				$arry=array('status'=>'0','message'=>'Something went wrong');
             	echo json_encode($arry, 200);exit();
			}
		}else
		{
			 $arry=array('status'=>'0','message'=>'Something went wrong');
             echo json_encode($arry, 200);exit;
		}
	}catch(Eception $e)
	{
		$e->getMessage();
		$error = array('status' =>'0', "data" => "Internal Server Error - Please try Later.","StatusCode"=> "HTTP405");
		echo json_encode($error, 200);exit;

	}
}
public function change()
{
	try
	{
		$user_id=$_POST['user_id'];
		$status=$_POST['status'];
		if(!empty($user_id))
		{

			$result=$this->SuperAdmin_model->change($user_id,($status==1?'0':'1'));
			if($result)
			{
				$arry=array('status'=>'1','message'=>'Success');
             	echo json_encode($arry, 200);exit();
			}else
			{
				$arry=array('status'=>'0','message'=>'Something went wrong');
             	echo json_encode($arry, 200);exit();
			}
		}else
		{
			 $arry=array('status'=>'0','message'=>'Something went wrong');
             echo json_encode($arry, 200);exit;
		}
	}catch(Eception $e)
	{
		$e->getMessage();
		$error = array('status' =>'0', "data" => "Internal Server Error - Please try Later.","StatusCode"=> "HTTP405");
		echo json_encode($error, 200);exit;

	}
}
public function getAdmin()
{
	try
	{
		$user_id=$_POST['admin_id'];
		if(!empty($user_id))
		{

            
			$result=$this->SuperAdmin_model->getAdmin($user_id);
			// print_r($result);exit;
			if($result)
			{
				foreach($result as $value)
		          {
		            $array['user_id']              =$value['user_id'];
		            $array['user_fullname']        =$value['user_fullname'];
		            $array['mobile_no']            =$value['mobile_no'];
		            $array['salt']   			   =$value['salt'];
		            $array['user_password'] 	   =$value['user_password'];
		            $array['user_email']           =$value['user_email'];

		            if(!empty($value['user_password']) && !empty($value['salt']))
		            {
		                $array['password']               =$this->encrypt->decode($value['user_password'], $value['salt']);
		            }else
		            {
		                $array['password']='';
		            }
		            $resultant[]          =$array;  
		        }
				$arry=array('status'=>'1','message'=>$resultant);
             	echo json_encode($arry, 200);exit();
			}else
			{
				$arry=array('status'=>'0','message'=>'Something went wrong');
             	echo json_encode($arry, 200);exit();
			}
		}else
		{
			 $arry=array('status'=>'0','message'=>'Something went wrong');
             echo json_encode($arry, 200);exit;
		}
	}catch(Eception $e)
	{
		$e->getMessage();
		$error = array('status' =>'0', "data" => "Internal Server Error - Please try Later.","StatusCode"=> "HTTP405");
		echo json_encode($error, 200);exit;	
	}
}
   public function updateAdmin()
    {

      $this->_validate2();
      $admin_mobile_no             	=$_POST['admin_mobile_no'];
      $email_id        				=$_POST['email_id'];
      $name              			=$_POST['name'];
      $admin_id               		=$_POST['admin_id'];
      $admi_password                =$_POST['admi_password'];
      $saltArray              =array(
                                   'email'=>$email_id,
                                   'phone'=>$admin_mobile_no,
                                   );
      $salt                   =createHash($saltArray);
      $user_password  =$this->encrypt->encode($admi_password, $salt);

      $admin_array            =array(
                                  'user_fullname'   =>$name,
                                  'mobile_no'       =>$admin_mobile_no,
                                  'user_email'      =>$email_id,
                                  'user_password'   =>$user_password,
                                  'salt'            =>$salt,
                                  'modified_date'   =>date('Y-m-d')
                              );
  
      $result=$this->SuperAdmin_model->updateAdmin($admin_id,$admin_array);
      if($result)
      {
         echo json_encode(array('status'=>1,'message'=>'Updated successfully'));exit;
      }else
      {
         echo json_encode(array('status'=>1,'result'=>'Not updated'));exit;
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


?>