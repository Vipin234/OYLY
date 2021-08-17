<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller
{
	
	function __construct()  
    {   
       parent::__construct();
       $this->load->library('session');
       $this->load->library('form_validation'); 
       $this->load->library('encrypt');     
       $this->load->model('Login_model'); 
       $this->load->model('Admin_model');
       $this->load->model('Menu_model');
       $this->load->helper('main_helper');   
       if(empty($_SESSION['mobile_no']))
       {
       	redirect(base_url());
       }

    }

		public function dashboard() 
		{   
			$session_data=$this->session->all_userdata();
			$result['emenities']=$this->Admin_model->getAmenities();
			$result['food_type']=$this->Admin_model->getFoodType();
      // print_r($result);exit;
      // $result['misc_category']=$this->Menu_model->getMenuCategory();
      $result['rest_list']=$this->Admin_model->getRestaurantList();
			$this->load->view('header',$session_data);
			$this->load->view('leftSidebar',$session_data);
			$this->load->view('dashboard',$result);
			$this->load->view('footer');
		}
		public	function logout()
		{
			// print_r('expression');exit;
	        $session_data = $this->session->all_userdata();
	        $this->session->sess_destroy();
	        redirect(base_url());
    	}
   public function restaurantList() 
  {
    try
    {	
        
		$restaurantList =$this->Admin_model->get_datatables();
		// print_r($restaurantList);exit;
		$data = array();
		$no = $_POST['start'];
		foreach ($restaurantList as $list) {
			$no++;
			$row = array();
			$row[] 	=$no;
			$row[] 	=$list->restaurant_name;
      $row[]  =$list->rating;
			/*$row[] 	=$list->user_email;
			$row[] 	=$list->mobile_no;
			$row[] 	=$list->gst_no;
			$row[] 	=$list->pan_no;*/
			$row[] 	=$list->address;
			$row[] 	=$list->create_date;
			$row[] 	=$list->user_role=='2'?'Super Admin':'Admin';
      $row[] 	=$list->kyc_status=='1'?'Approved':'Pending';
      $button =$list->status==1?'Active':'Deactive';
      if($_SESSION['user_role'] !=1){
              $row[]  ='<div class="row text-center" style="width: max-content;margin-right: auto; margin-left: auto;"><a class="btn btn-sm btn-primary btn-xs" href="javascript:void(0)" title="Edit" onclick="edit('."'".$list->admin_id."'".')"style="height: 33px"><i class="glyphicon glyphicon-eye-open"></i>Edit</a>
                      <a class="btn btn-success btn-xs" href="javascript:void(0)" title="Add Staff" onclick="open_staff_modal('."'".$list->admin_id."'".')"style="
    height: 33px;
"
"></i>Add Staff</a>
<a class="btn btn-primary btn-xs" href="javascript:void(0)" title="Add Menu" onclick="open_add_menu('."'".$list->admin_id."'".')"style="
    height: 33px;
"
"></i>Add Menu</a>
<a class="btn btn-primary btn-xs" href="javascript:void(0)" title="Add Category" onclick="open_addCat('."'".$list->admin_id."'".')"style="
    height: 33px;
"
"></i>Add Category</a>
<a class="btn btn-primary btn-xs" href="javascript:void(0)" title="Add Sub Category" onclick="open_addSubCat('."'".$list->admin_id."'".')"style="
    height: 33px;
"
"></i>Add SubCategory</a></div>';
      }else{
              $row[]  ='<div class="row text-center" style="width: max-content;margin-right: auto; margin-left: auto;"><a class="btn btn-sm btn-primary btn-xs" href="javascript:void(0)" title="Edit" onclick="edit('."'".$list->admin_id."'".')"style="height: 33px"><i class="glyphicon glyphicon-eye-open"></i>Edit</a>
                      <a class="btn btn-info btn-xs" href="javascript:void(0)" title="'.$button.'" onclick="open_status_modal('."'".$list->admin_id."'".','."'".$list->status."'".')"style="
    height: 33px;
"><i class="glyphicon glyphicon-saved"></i>'.$button.'</a>
                      <a class="btn btn-success btn-xs" href="javascript:void(0)" title="Add Staff" onclick="open_staff_modal('."'".$list->admin_id."'".')"style="
    height: 33px;
"
"></i>Add Staff</a>
<a class="btn btn-primary btn-xs" href="javascript:void(0)" title="Add Menu" onclick="open_add_menu('."'".$list->admin_id."'".')"style="
    height: 33px;
"
"></i>Add Menu</a>
<a class="btn btn-primary btn-xs" href="javascript:void(0)" title="Add Category" onclick="open_addCat('."'".$list->admin_id."'".')"style="
    height: 33px;
"
"></i>Add Category</a>
<a class="btn btn-primary btn-xs" href="javascript:void(0)" title="Add Sub Category" onclick="open_addSubCat('."'".$list->admin_id."'".')"style="
    height: 33px;
"
"></i>Add SubCategory</a></div>';
      }

			$data[]	=$row;
		}
		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->Admin_model->count_all(),
						"recordsFiltered" => $this->Admin_model->count_filtered(),
						"data" => $data,
				);
	echo json_encode($output);
            
    }catch(Eception $e)
    {
		    echo $e->getMessage();
		    $error = array('status' =>'0', "data" => "Internal Server Error - Please try Later.","StatusCode"=> "HTTP405");
		    $this->response($error, 200);
    }
  }
public function getTermsAndConditino(){
    $this->load->view('termsAndCondition'); 
  }
  public function addRestaurant()
  {
  	try
    {
      $this->_validate();
      $staff_name               =$_POST['staff_name'];
      $restaurant_name          =$_POST['restaurant_name'];
      $mobile_no                =$_POST['mobile_no'];
      $email                    =$_POST['email'];
      $password                 =$_POST['password'];
      $gst_no                   =$_POST['gst_no'];
      $pan_no                   =$_POST['pan_no'];
      $address                  =$_POST['address'];
      $start_time               =$_POST['start_time'];
      $end_time                 =$_POST['end_time'];
      $amenities                =implode(",",$_POST['amenities']);
      $food_type                =implode(",",$_POST['food_type']);
      $city                     =$_POST['city'];
      $imageName                =$_POST['imageName'];
      if(!empty($imageName))
      {
      $t                        =time()."".date('Ymd');
      $path                     ='uploads/';
      $image_parts              =explode(";base64,",$imageName);
      $image_base64             =base64_decode($image_parts[1]);
      $img_name                 ='resto'."_".$t.".jpeg";
      $file                     ='uploads/'.$img_name;
      file_put_contents($file, $image_base64);
      }else
      {
        $img_name='';
      }
      
      $result=$this->Admin_model->checkAdmin($mobile_no);
      if(empty($result))
      {
            $saltArray   =array(
                         'email'=>$email,
                         'phone'=>$mobile_no,
                         );
            $salt           =createHash($saltArray);
            $user_password  =$this->encrypt->encode($password, $salt);

            /*Generate admin_id */
            $prefixResult=$this->Admin_model->getPrefix($city);
            if(!empty($prefixResult)){ 
            $city_prefix=$prefixResult[0]['city_prefix'];
            $state_prefix=$prefixResult[0]['state_prefix'];
            $maxAdmin=$this->Admin_model->getMaxAdminData($city_prefix,$state_prefix);
            if($maxAdmin[0]['admin_id'] <=9){
              $admin_id=$state_prefix.$city_prefix.'0000'.($maxAdmin[0]['admin_id']+1);
            }else if($maxAdmin[0]['admin_id'] >=9 && $maxAdmin[0]['admin_id'] <=99){
              $admin_id=$state_prefix.$city_prefix.'000'.($maxAdmin[0]['admin_id']+1);
            }else if($maxAdmin[0]['admin_id'] >=99 && $maxAdmin[0]['admin_id'] <=999){
               $admin_id=$state_prefix.$city_prefix.'00'.($maxAdmin[0]['admin_id']+1);
            }else if($maxAdmin[0]['admin_id'] >=999 && $maxAdmin[0]['admin_id'] <=9999){
              $admin_id=$state_prefix.$city_prefix.'0'.($maxAdmin[0]['admin_id']+1);
            }
          }else{
            $arry=array('status'=>'0','message'=>'Please enter correct city name.','inputerror'=>array());
              echo json_encode($arry);exit;
          }
            /*Generate admin_id */
            $admin_array    =array(
                            'admin_id'        =>$admin_id,
                            'user_fullname'   =>$staff_name,
                            'restaurant_name' =>$restaurant_name,
                            'mobile_no'       =>$mobile_no,
                            'user_email'      =>$email,
                            'user_password'   =>$user_password,
                            'salt'            =>$salt,
                            'user_role'       =>2,
                            'user_active'     =>1,
                            'user_type'       =>'Admin',
                            'user_createdate' =>date('Y-m-d H:i:s'),
                            'status'          =>1

                    );
                $last_insert_id=$this->Admin_model->addAdmin($admin_array);
                if($last_insert_id)
                {
                   $restau_array    =array(
                                  'admin_id'        =>$admin_id,
                                  'city'            =>$city,
                                  'name'            =>$restaurant_name,
                                  'image'           =>$img_name,
                                  'gst_no'          =>$gst_no,
                                  'pan_no'          =>$pan_no,
                                  'lat'             =>'',
                                  'lng'             =>'',
                                  'location'        =>$address,
                                  'cuisines'        =>$food_type,
                                  'openingTime'     =>$start_time,
                                  'closingTime'     =>$end_time,
                                  'phone'           =>$mobile_no,
                                  'address'         =>$address,
                                  'amenities'       =>$amenities,
                                  'create_date'     =>date('Y-m-d H:i:s'),
                                  'status'          =>0

                          );
                  $resto=$this->Admin_model->addRestaurant($restau_array);
                   if($resto)
                   {
                      $gstValuesArray[]=json_decode(GSTVALUE,TRUE);
                      $gstNameArray=json_decode(GSTNAME);
                      $i=1;
                      foreach($gstNameArray as $valueGstName)
                      {
                        
                        $array[]=array(
                                  'admin_id'      =>$admin_id,
                                  'category_name' =>$valueGstName,
                                  'gst'           =>$gstValuesArray[0]['GST'.$i],
                                  'status'        =>1,
                                  'creation_date' =>date('Y-m-d H:i:s'),

                        );
                        $i=$i+1;
                      }
                      $this->Admin_model->insertGstDetails($array);
                      $arry=array('status'=>'1','message'=>'Restaurant added successfully.');
                      echo json_encode($arry);exit;
                   }else
                   {
                    $arry=array('status'=>'0','message'=>'Restaurant not added.','inputerror'=>array());
                       echo json_encode($arry);exit;
                   }
                }else 
                {
                  $arry=array('status'=>'0','message'=>'Admin not created.','inputerror'=>array());
                  echo json_encode($arry);exit;
                }
         

      }else
      {
         $arry=array('status'=>'0','message'=>'Admin already exists.','inputerror'=>array());
         echo json_encode($arry);exit;
      }
      
    }catch(Eception $e)
    {
      $e->getMessage();
      echo $e->getMessage();
      $error = array('status' =>'0', "data" => "Internal Server Error - Please try Later.","StatusCode"=> "HTTP405");
      echo json_encode($error);exit;

    }
  }
  public function getImageName()
  {
    // echo "<pre>";print_r($_POST['image']);exit;
    $image=$_POST['image'];
    echo json_encode($image);exit;
  }
  public function editRestaurant()
  {
   
    try
    {
      $admin_id=$_POST['admin_id'];
      if(!empty($admin_id))
      {
          $result=$this->Admin_model->getRestaurant($admin_id);
          foreach($result as $value)
          {
            $array['address']              =$value['address'];
            $array['cuisines']    =explode(',',rtrim($value['cuisines'],','));
            $array['amenities']   =explode(',',rtrim($value['amenities'],','));
            $array['city']        =$value['city'];
            $array['closingTime'] =$value['closingTime'];
            $array['gst_no']              =$value['gst_no'];
            $array['mobile_no']              =$value['mobile_no'];
            $array['user_fullname']              =$value['user_fullname'];
            $array['openingTime']              =$value['openingTime'];
            $array['pan_no']              =$value['pan_no'];
            $array['restaurant_name']              =$value['restaurant_name'];
            $array['user_email']              =$value['user_email'];
            $array['admin_id']              =$value['admin_id'];
            if(!empty($value['user_password']) && !empty($value['salt']))
            {
                $array['password']               =$this->encrypt->decode($value['user_password'], $value['salt']);
            }else
            {
                $array['password']='';
            }
            
            $resultant[]          =$array;    

          }
          // echo '<pre>';print_r($resultant);exit;
          if(!empty($resultant))
          {
                  $cat_result=$this->Supervisor->getCatIds($admin_id);

                  $string='';

                  if(!empty($cat_result))
                  {
                        foreach($cat_result AS $value)
                      {

                         $string .= "'".$value['cat_id']."'".',';
                      }
                  }

            $catResult=$this->Supervisor->getRestaurantCategory($admin_id,rtrim($string,','));

            echo json_encode(array('status'=>1,'result'=>$resultant,'carResult'=>$catResult));exit;
          }
          else
          {
            echo json_encode(array('status'=>0,'result'=>array()));exit;
          }
          
      }else
      {
        echo json_encode(array('status'=>0,'result'=>array()));exit;
      }
    }catch(Eception $e)
    {
      $e->getMessage();
      echo $e->getMessage();
      $error = array('status' =>'0', "result" => "Internal Server Error - Please try Later.","StatusCode"=> "HTTP405");
      echo json_encode($error);exit;
    }
  }
   public function updateRestaurant()
    {

      $this->_validate();
      $img_array=array();
      $staff_name             =$_POST['staff_name'];
      $restaurant_name        =$_POST['restaurant_name'];
      $mobile_no              =$_POST['mobile_no'];
      $email                  =$_POST['email'];
      $password               =$_POST['password'];
      $gst_no                 =$_POST['gst_no'];
      $pan_no                 =$_POST['pan_no'];
      $address                =$_POST['address'];
      $start_time             =$_POST['start_time'];
      $end_time               =$_POST['end_time'];
      $city                   =$_POST['city'];
      $admin_id               =$_POST['admin_id'];
      $imageName              =$_POST['imageName'];

      if(!empty($imageName))
      {
      $t                        =time()."".date('Ymd');
      $path                     ='uploads/';
      $image_parts              =explode(";base64,",$imageName);
      $image_base64             =base64_decode($image_parts[1]);
      $img_name                 ='resto'."_".$t.".jpeg";
      $file                     ='uploads/'.$img_name;
      file_put_contents($file, $image_base64);
      $img_array               =array('image'=>$img_name);
      }
      // print_r($img_name);exit;
      $amenities              =implode(",",$_POST['amenities']);
      $food_type              =implode(",",$_POST['food_type']);
      $saltArray              =array(
                                   'email'=>$email,
                                   'phone'=>$mobile_no,
                                   );
      $salt                   =createHash($saltArray);
      $user_password  =$this->encrypt->encode($password, $salt);
      $admin_array            =array(
                                  'user_fullname'   =>$staff_name,
                                  'restaurant_name' =>$restaurant_name,
                                  'mobile_no'       =>$mobile_no,
                                  'user_email'      =>$email,
                                  'user_password'   =>$user_password,
                                  'salt'            =>$salt,
                                  'modified_date'   =>date('Y-m-d')
                               );
      $restau_array           =array(
                                  'city'            =>$city,
                                  'name'            =>$restaurant_name,
                                  'gst_no'          =>$gst_no,
                                  'pan_no'          =>$pan_no,
                                  'lat'             =>'',
                                  'lng'             =>'',
                                  'location'        =>$address,
                                  'cuisines'        =>$food_type,
                                  'openingTime'     =>$start_time,
                                  'closingTime'     =>$end_time,
                                  'phone'           =>$mobile_no,
                                  'address'         =>$address,
                                  'modified_date'   =>date('Y-m-d'),
                                  'amenities'       =>$amenities
                          );
      // echo '<pre>';print_r($restau_array+$img_array);exit;
      $result=$this->Admin_model->updateRestaurant($admin_id,($restau_array+$img_array),$admin_array);
      if($result)
      {
         echo json_encode(array('status'=>1,'message'=>'Updated successfully'));exit;
      }else
      {
         echo json_encode(array('status'=>1,'result'=>'Not updated'));exit;
      }

    }
    public function changeRestaurant()
    {
      
      $admin_id=$_POST['admin_id'];
      $change=$_POST['change'];
      $convenience_code=$_POST['convenience_fees'];
      $gst_code=$_POST['gst'];
      if(!empty($admin_id) && !empty($convenience_code) && !empty($gst_code))
      {
        if($change=='1')
        {
           $checkKyc=$this->Admin_model->checkKyc($admin_id);
          if(($checkKyc=='0' ||$checkKyc==0|| $checkKyc==''))
          {
            echo json_encode(array('status'=>2,'result'=>'Please verify KYC first'));exit;
          }
          
        }
        $result=$this->Admin_model->changeStatus($admin_id,$change,$convenience_code,$gst_code);

        if(!empty($result))
        {
           echo json_encode(array('status'=>1,'result'=>'Updated successfully'));exit;
        }else
        {
           echo json_encode(array('status'=>0,'result'=>'No change'));exit;
        }

      }else
      {
         echo json_encode(array('status'=>0,'result'=>'Somthing went wrong'));exit;
      }
    }
    private function _validate()
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;
 
        if($this->input->post('staff_name') == '')
        {
            $data['inputerror'][] = 'staff_name';
            $data['error_string'][] = 'Name  is required';
            $data['status'] = FALSE;
        }
        if($this->input->post('restaurant_name') == '')
        {
            $data['inputerror'][] = 'restaurant_name';
            $data['error_string'][] = 'Restaurant Name  is required';
            $data['status'] = FALSE;
        }
 
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
          if($this->input->post('amenities') == '')
        {
            $data['inputerror'][] = 'amenities';
            $data['error_string'][] = 'Amenities is required';
            $data['status'] = FALSE;
        }
          if($this->input->post('food_type') == '')
        {
            $data['inputerror'][] = 'food_type';
            $data['error_string'][] = 'Food type is required';
            $data['status'] = FALSE;
        }
    if($this->input->post('start_time') == '')
        {
            $data['inputerror'][] = 'start_time';
            $data['error_string'][] = 'Opening Time  is required';
            $data['status'] = FALSE;
        }
 
        if($this->input->post('end_time') == '')
        {
            $data['inputerror'][] = 'end_time';
            $data['error_string'][] = 'Closing Time is required';
            $data['status'] = FALSE;
        }
        if($this->input->post('password') == '')
        {
            $data['inputerror'][] = 'password';
            $data['error_string'][] = 'Password is required';
            $data['status'] = FALSE;
        }
 
        if($this->input->post('address') == '')
        {
            $data['inputerror'][] = 'address';
            $data['error_string'][] = 'Address is required';
            $data['status'] = FALSE;
        }
        if($this->input->post('city') == '')
        {
            $data['inputerror'][] = 'city';
            $data['error_string'][] = 'City is required';
            $data['status'] = FALSE;
        }
        if($this->input->post('food_type') == '')
        {
            $data['inputerror'][] = 'food_type';
            $data['error_string'][] = 'Food is required';
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