<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class StaffListController extends CI_Controller
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
       $this->load->model('StaffList_model');
       $this->load->model('Menu_model');
       $this->load->model('Gst_model');
       $this->load->model('Convenience_model');
       $this->load->helper('main_helper');   
       if(empty($_SESSION['mobile_no']))
       {
       	redirect(base_url());
       }
    }

    public function staffList()
    {
    	$session_data=$this->session->all_userdata();
    	$result['adminList']=$this->StaffList_model->getAllAdmin();
		$this->load->view('header',$session_data);
		$this->load->view('leftSidebar',$session_data);
		$this->load->view('staffList',$result);
		$this->load->view('footer');
    }
    public function getStaffList()
    {
		   try
		    {	
				$restaurantList =$this->StaffList_model->get_datatables();
				// echo '<pre>';print_r($restaurantList);exit;
				$data = array();
				$no = $_POST['start'];
				foreach ($restaurantList as $list) {
					$no++;
					$row = array();
					$row[] 	=$no;
					$row[] 	=$list->name;
					// $row[] 	=$list->email;
					// $row[] 	=$list->mobile_no;
     //      $row[]  = (!empty($list->password)&& !empty($list->salt))?$this->encrypt->decode($list->password,$list->salt):'';
					// $row[] 	=$list->aadhar_no;
					// $row[] 	=$list->pan_number;
          $row[]  = !empty($list->image)?'<a href="'.base_url().'uploads/'.$list->image.'"target="_blank">view</a>':'';
          $row[]  = !empty($list->document)?'<a href="'.base_url().'uploads/'.$list->document.'"target="_blank">view</a>':'';
          $row[]  =$list->permanent_address;
					$row[] 	=$list->restaurant_name;
					$row[] 	=$list->create_date;
					$row[] 	=$list->user_type;
					// $row[] 	=$this->encrypt->decode($list->user_password, $list->salt);
					// $row[] 	=$list->user_createdate;
					// $row[] 	=$list->user_role=='2'?'Super Admin':'Admin';
		      		$button =$list->status==1?'Active':'Deactive';
					$row[] 	='<div class="row text-center" style="width: max-content;margin-right: auto; margin-left: auto;"><a class="btn btn-sm btn-primary btn-xs" href="javascript:void(0)" title="Edit" onclick="edit('."'".$list->id."'".')"style="height: 33px"><i class="glyphicon glyphicon-eye-open"></i>Edit</a>
				                  <a class="btn btn-info btn-xs" href="javascript:void(0)" title="'.$button.'" onclick="change('."'".$list->id."'".','."'".$list->status."'".','."'".$list->admin_id."'".')"style="height: 33px;"><i class="glyphicon glyphicon-saved"></i>'.$button.'</a>
				                  <a class="btn btn-danger btn-xs" href="javascript:void(0)" title="Delete" onclick="remove('."'".$list->mobile_no."'".','."'".$list->admin_id."'".')"style="height: 33px;display:none;"><i class="glyphicon glyphicon-deleted"></i>Delete</a></div>';
					$data[]	=$row;
				}
				$output = array(
								"draw" => $_POST['draw'],
								"recordsTotal" => $this->StaffList_model->count_all(),
								"recordsFiltered" => $this->StaffList_model->count_filtered(),
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
  public function addStaff()
  {
  	try
  	{
  		 $this->_validate();
  		$mobile_no 			              =$_POST['staff_mobile_no'];
  		$email_id 			              =$_POST['staff_email'];
  		$name 				                =$_POST['rest_staff_name'];
  		$password 			              =$_POST['staff_password'];
  		$uaid_no 			                =$_POST['staff_uaid_no'];
  		$pan_no 			                =$_POST['staff_pan_no'];
  		$admin_id 			              =$_POST['admin_id'];
  		$address 			                =$_POST['staff_address'];
      $dob                          =$_POST['staff_dob'];
      $gender                       =$_POST['staff_Gender'];
      $role                         =$_POST['staff_role'];
      $staff_cur_address            =$_POST['staff_cur_address'];
      $resto_staff_image            =$_POST['resto_staff_image'];
      $resto_staff_document         =$_POST['resto_staff_document'];
      if(!empty($resto_staff_image)){
      $t                        =time()."".date('Ymd');
      $path                     ='uploads/';
      $image_parts              =explode(";base64,",$resto_staff_image);
      $image_base64             =base64_decode($image_parts[1]);
      $img_name                 ='resto'."_".$t.".jpeg";
      $file                     ='uploads/'.$img_name;
      file_put_contents($file, $image_base64);
      }else{
        $img_name='';
      }
      if(!empty($resto_staff_document)){
      $t                        =time()."".date('Ymd');
      $path                     ='uploads/';
      $image_parts              =explode(";base64,",$resto_staff_document);
      $image_base64             =base64_decode($image_parts[1]);
      $document                 ='resto'."_".$t.".jpeg";
      $file                     ='uploads/'.$img_name;
      file_put_contents($file, $image_base64);
      }else{
        $document='';
      }
  		$staff             				    =$this->StaffList_model->checkStaff($mobile_no);
  		if(empty($staff))
  		{

  			$saltArray   =array(
                         'email'=>$email_id,
                         'phone'=>$mobile_no,
                         );
            $salt           =createHash($saltArray);
            $user_password  =$this->encrypt->encode($password, $salt);
  			$array=array(
  				'admin_id'=>$admin_id,
  				'name'=>$name,
  				'username'=>'',
          'salt'=>$salt,
  				'mobile_no'=>$mobile_no,
  				'email'=>$email_id,
  				'password'=>$user_password,
  				'date_of_birth'=>$dob,
  				'aadhar_no'=>$uaid_no,
  				'pan_number'=>$pan_no,
  				'desingination'=>$role,
          'user_type  '=>$role,
  				'gender'=>$gender,
          'image'=>$img_name,
          'document'=>$document,
  				'permanent_address'=>$address,
  				'current_address'=>$staff_cur_address,
  				'create_date'=>date('Y-m-d'),
  				'status'=>1
  			);
        $superAdmin=$this->StaffList_model->checkSuperAdmin($mobile_no);
        if(empty($superAdmin))
        {
             $result=$this->StaffList_model->addStaff($array);
              if(!$result)
              {
                $arry=array('status'=>'1','message'=>'Staff created successfully.','inputerror'=>array());
                   echo json_encode($arry);exit;
              }else
              {
                $arry=array('status'=>'0','message'=>'Staff Exists.','inputerror'=>array());
                   echo json_encode($arry);exit;
              }
        }else
        {
          $arry=array('status'=>'0','message'=>'Staff Exists.','inputerror'=>array());
             echo json_encode($arry);exit;
        }
       
  		}else
  		{
  			$arry=array('status'=>'0','message'=>'Staff Exists.','inputerror'=>array());
             echo json_encode($arry);exit;
  		}

  	}catch(Eception $e)
  	{
  		$e->getMessage();
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
		$id=$_POST['id'];
		$status=$_POST['status'];
    $admin_id=$_POST['admin_id'];
		if(!empty($id))
		{

			$result=$this->StaffList_model->change($id,($status==1?'0':'1'),$admin_id);
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
public function getStaff()
{
	try
	{
		$id=$_POST['id'];
		if(!empty($id))
		{

            
			$result=$this->StaffList_model->getStaff($id);
			//echo '<pre>';print_r($result);exit;
			if(!empty($result))
			{
				foreach($result as $value)
		          {
                $array['id']              =$value['id'];
		            $array['admin_id']              =$value['admin_id'];
		            $array['name']        =$value['name'];
		            $array['mobile_no']            =$value['mobile_no'];
		            $array['email']           =$value['email'];
                $array['date_of_birth']           =$value['date_of_birth'];
                $array['aadhar_no']           =$value['aadhar_no'];
                $array['pan_number']           =$value['pan_number'];
                $array['gender']           =$value['gender'];
                $array['desingination']           =$value['desingination'];
                $array['permanent_address']           =$value['permanent_address'];
                $array['current_address']           =$value['current_address'];
                $array['user_type']           =$value['user_type'];

		            if(!empty($value['password']) && !empty($value['salt']))
		            {
		                $array['password']               =$this->encrypt->decode($value['password'], $value['salt']);
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
				$arry=array('status'=>'0','message'=>'Staff not active');
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
   public function updateStaff()
    {
      $doc_array=array();
      $img_array=array();
      $this->_validate();
      $id                    =$_POST['id'];
      $mobile_no                    =$_POST['staff_mobile_no'];
      $email_id                     =$_POST['staff_email'];
      $name                         =$_POST['rest_staff_name'];
      $password                     =$_POST['staff_password'];
      $uaid_no                      =$_POST['staff_uaid_no'];
      $pan_no                       =$_POST['staff_pan_no'];
      $address                      =$_POST['staff_address'];
      $dob                          =$_POST['staff_dob'];
      $gender                       =$_POST['staff_Gender'];
      $role                         =$_POST['staff_role'];
      $staff_cur_address            =$_POST['staff_cur_address'];
      $resto_staff_document         =$_POST['resto_staff_document'];
      $resto_staff_image            =$_POST['resto_staff_image'];
      if(!empty($resto_staff_image)){
      $t                        =time()."".date('Ymd');
      $path                     ='uploads/';
      $image_parts              =explode(";base64,",$resto_staff_image);
      $image_base64             =base64_decode($image_parts[1]);
      $img_name                 ='resto'."_".$t.".jpeg";
      $file                     ='uploads/'.$img_name;
      file_put_contents($file, $image_base64);
      $img_array               =array('image'=>$img_name);
      }
    if(!empty($resto_staff_document)){
      $t                        =time()."".date('Ymd');
      $path                     ='uploads/';
      $image_parts              =explode(";base64,",$resto_staff_document);
      // print_r($image_parts );exit;
      $image_base64             =base64_decode($image_parts[1]);
      $document                 ='resto'."_".$t.".jpeg";
      $file                     ='uploads/'.$document;
      file_put_contents($file, $image_base64);
      $doc_array               =array('document'=>$document);
      }
      $staff                        =$this->StaffList_model->checkStaff($mobile_no);

      if(!empty($staff))
      {


        $saltArray   =array(
                         'email'=>$email_id,
                         'phone'=>$mobile_no,
                         );
            $salt           =createHash($saltArray);
            $user_password  =$this->encrypt->encode($password, $salt);
            $array=array(
              'name'=>$name,
              'username'=>'',
              'salt'=>$salt,
              'email'=>$email_id,
              'password'=>$user_password,
              'date_of_birth'=>$dob,
              'aadhar_no'=>$uaid_no,
              'pan_number'=>$pan_no,
              'desingination'=>$role,
              'user_type  '=>$role,
              'gender'=>$gender,
              'permanent_address'=>$address,
              'current_address'=>$staff_cur_address,
              'modified_date'=>date('Y-m-d'),
              'status'=>1
            );
        $superAdmin=$this->StaffList_model->checkSuperAdmin($mobile_no);
        if(empty($superAdmin))
        {
             $result=$this->StaffList_model->updateStaff(($array+$img_array+$doc_array),$mobile_no,$id);
              if($result)
              {
                $arry=array('status'=>'1','message'=>'Staff updated successfully.','inputerror'=>array());
                   echo json_encode($arry);exit;
              }else
              {
                $arry=array('status'=>'0','message'=>'Staff not updated','inputerror'=>array());
                   echo json_encode($arry);exit;
              }
        }else
        {
          $arry=array('status'=>'0','message'=>'Something went wrong.','inputerror'=>array());
             echo json_encode($arry);exit;
        }
      }else
      {

      }
     
      $result=$this->SuperAdmin_model->updateAdmin($admin_id,$admin_array);
      if($result)
      {
         echo json_encode(array('status'=>1,'message'=>'Updated successfully'));exit;
      }else
      {
         echo json_encode(array('status'=>1,'result'=>'Not updated'));exit;
      }

    }
  public function checkRestaurant() 
  {
    $admin_id=$_POST['admin_id'];
    
    if(!empty($admin_id))
    {
      $result=$this->StaffList_model->getRestaurant($admin_id);

      if(!empty($result))
      {
        $categoryresult=$this->Menu_model->getMenuCategory($admin_id);

        $menuGst=$this->Menu_model->getMenuGST($admin_id);

        $restGst=$this->Gst_model->getrestGst();

        $convenienceFees=$this->Convenience_model->getConvenienceFees();  

        echo json_encode(array('status'=>1,'result'=>$result,'menuList'=>$categoryresult,'gstList'=>$menuGst,'restGst'=>$restGst,'convenienceFees'=>$convenienceFees));exit;
      }else
      {
         echo json_encode(array('status'=>0,'result'=>'There is no record found!'));exit;
      }
     
    }else
    {
      echo json_encode(array('status'=>0,'result'=>'Something went wrong'));exit;
    }
  }
  public function checkResto() 
  {
    $admin_id=$_POST['admin_id'];
    
    if(!empty($admin_id))
    {
      $result=$this->StaffList_model->getRestaurant2($admin_id);

      if(!empty($result))
      {
        $categoryresult=$this->Menu_model->getMenuCategory($admin_id);

        $menuGst=$this->Menu_model->getMenuGST($admin_id);

        $restGst=$this->Gst_model->getrestGst();

        $convenienceFees=$this->Convenience_model->getConvenienceFees();  

        echo json_encode(array('status'=>1,'result'=>$result,'menuList'=>$categoryresult,'gstList'=>$menuGst,'restGst'=>$restGst,'convenienceFees'=>$convenienceFees));exit;
      }else
      {
         echo json_encode(array('status'=>0,'result'=>'There is no record found!'));exit;
      }
     
    }else
    {
      echo json_encode(array('status'=>0,'result'=>'Something went wrong'));exit;
    }
  }
     private function _validate()
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;
 
        if($this->input->post('staff_mobile_no') == '')
        {
            $data['inputerror'][] = 'staff_mobile_no';
            $data['error_string'][] = 'Mobile no  is required';
            $data['status'] = FALSE;
        }

        if($this->input->post('staff_email') == '')
        {
            $data['inputerror'][] = 'staff_email';
            $data['error_string'][] = 'Email is required';
            $data['status'] = FALSE;
        }
        if($this->input->post('staff_password') == '')
        {
            $data['inputerror'][] = 'staff_password';
            $data['error_string'][] = 'Password is required';
            $data['status'] = FALSE;
        }
        if($this->input->post('staff_address') == '')
        {
            $data['inputerror'][] = 'staff_address';
            $data['error_string'][] = 'Address is required';
            $data['status'] = FALSE;
        }
        if($this->input->post('staff_cur_address') == '')
        {
            $data['inputerror'][] = 'staff_cur_address';
            $data['error_string'][] = 'Current address is required';
            $data['status'] = FALSE;
        }
        if($this->input->post('staff_dob') == '')
        {
            $data['inputerror'][] = 'staff_dob';
            $data['error_string'][] = 'DOB is required';
            $data['status'] = FALSE;
        }
        if($this->input->post('staff_role') == '')
        {
            $data['inputerror'][] = 'staff_role';
            $data['error_string'][] = 'Role is required';
            $data['status'] = FALSE;
        }
        if($this->input->post('staff_Gender') == '')
        {
            $data['inputerror'][] = 'staff_Gender';
            $data['error_string'][] = 'Gender is required';
            $data['status'] = FALSE;
        }
        if($this->input->post('rest_staff_name') == '')
        {
            $data['inputerror'][] = 'rest_staff_name';
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