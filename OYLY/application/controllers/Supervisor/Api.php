<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/firebase.php';
require APPPATH . 'libraries/push.php';
require APPPATH . 'libraries/mailer/PHPMailer/PHPMailerAutoload.php';


class Api extends REST_Controller {

 function __construct($config = 'rest') 
 {  
  header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
  header('Access-Control-Max-Age: 1000');
  header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
  ini_set('error_reporting', E_STRICT); 
  date_default_timezone_set('Asia/kolkata'); 
  parent::__construct($config);    
  $this->load->helper('date');
  $this->load->helper('text');
  $this->load->library('upload');    
  $this->load->helper('url'); 
  $this->load->helper('main_helper');   
  $this->load->library('form_validation');
  $this->load->library('encrypt'); 

  //$this->output->cache(5);        
  
}

/*......... Login Api For Restaurant ---- */
public function login_post()  
{
  $response = new StdClass();   
  $result = new StdClass();
  $mobile_no = $this->input->post('mobile_no');
  $device_id=$this->input->post('device_id');
  $notification_id=$this->input->post('notification_id');
  date_default_timezone_set('Asia/kolkata'); 
  $now = date('Y-m-d H:i:s');
  $query= $this->db->get_where(json_decode(TABLES)->table4, array('mobile_no' =>$mobile_no , 'status' => '1'));
  $query2= $this->db->get_where(json_decode(TABLES)->table4, array('mobile_no' => $mobile_no , 'status' => '0'));
  $query1= $this->db->get_where(json_decode(TABLES)->table25, array('mobile_no' => $mobile_no , 'status' => '1'));
  $num_rows5=$query2->num_rows();

  $num_rows=$query->num_rows();
  $current_data=$query->result_array();
  $num_rows1=$query1->num_rows();
  $current_data1=$query1->result_array();
  $data1->device_id = $device_id;
  $data1->notification_id = $notification_id;
  $data1->login_time=$now;
  // print_r($current_data);exit;
  if(!empty($mobile_no))
  {

   if(!empty($current_data))
   {

     foreach ($current_data as $row)
     { 
      // $otpValue=mt_rand(1000, 9999);
      $otpValue=1234;
      $data1->device_id = $device_id;
      $data1->notification_id = $notification_id;
      $data1->login_time=$now;
      $data1->name=$row['user_fullname'];
      $data1->mobile_no=$row['mobile_no'];
      $data1->user_type='Admin';
      $data1->admin_id=$row['admin_id'];
      $user_type='Admin';
      $data1->otp=$otpValue;
      $res3 = $this->Supervisor->send_otp($mobile_no,$otpValue);
      if($res3!='')
      {
        $res4 = $this->Supervisor->otpgetdata($data1);
      }
      $term_data=$this->Supervisor->getTermsAndCondition();
      $res = $this->Supervisor->manage_login_data($data1);
      $data['admin_id'] =  $row['admin_id'];
      $data['name'] =  $row['user_fullname'];
      $data['mobile_no'] =  $row['mobile_no'];
      $data['user_type'] =  $user_type;
      $data['term_condition'] =  $term_data[0]['term_condtion'];
      $data['message']='success';
      $data['status']  ='1';
      $multipleData=$this->Supervisor->getMultipleRoles($row['admin_id'],$mobile_no,'');
      // print_r($multipleData);exit;
      $AdminData=$this->Supervisor->getRoleImages('Admin');
      // print_r($AdminData);exit;
      $array_admin=array(array('images'=>base_url().'RoleImages/'.$AdminData[0]['image'],'user_type'=>'Admin','name'=>$row['user_fullname']));
      if(!empty($multipleData)){
        foreach($multipleData as $role){
        $images=$this->Supervisor->getRoleImages($role['user_type']);
        $images_array['images']=base_url().'RoleImages/'.$images[0]['image'];
        $images_array['user_type']=$role['user_type'];
        $images_array['name']=$role['name'];
        $finalarray[]=$images_array;
        }
        $data['roles']=array_merge_recursive($finalarray,$array_admin);
      }else{
        $data['roles']=$array_admin;
      }
      
      array_push($result,$data);
     
    }
    $response->data = $result;
  }

  else if(!empty($current_data1))
  {
    // echo '<pre>';print_r($current_data1);exit;
   foreach ($current_data1 as $row1)
   { 
    // $otpValue=mt_rand(1000, 9999);
    $otpValue=1234;
    $data1->device_id = $device_id;
    $data1->notification_id = $notification_id;
    $data1->login_time=$now;
    $data1->name=$row1['name'];
    $data1->mobile_no=$row1['mobile_no'];
    $data1->admin_id =  $row1['admin_id'];
    $mobile_no=$row1['mobile_no'];
    $data1->user_type=$row1['user_type'];
    $user_type=$row1['user_type'];
    $data1->otp=$otpValue;
    $res3 = $this->Supervisor->send_otp($mobile_no,$otpValue);
    if($res3!='')
    {
      $res4 = $this->Supervisor->otpgetdata($data1);
    }

    $res = $this->Supervisor->manage_login_data($data1);
    $term_data=$this->Supervisor->getTermsAndCondition();
    $data['admin_id'] =  $row1['admin_id'];
    $data['name'] =  $row1['name'];
    $data['mobile_no'] =  $row1['mobile_no'];
    $data['user_type'] =  $user_type;
    $result2=$this->Supervisor->getUserTypeLable($user_type);
    $data['user_lable'] = $result2[0]['user_type'];
    $data['term_condition'] =  $term_data[0]['term_condtion'];
    $data['message']='success';
    $data['status']  ='1';
    $multipleData=$this->Supervisor->getMultipleRoles($row1['admin_id'],$mobile_no,$row1['user_type']);
    foreach($multipleData as $role){
        $images=$this->Supervisor->getRoleImages($role['user_type']);
        $images_array['images']=base_url().'RoleImages/'.$images[0]['image'];
        $images_array['user_type']=$role['user_type'];
        $images_array['name']=$role['name'];
        $finalarray[]=$images_array;
    }
    $data['roles']=$finalarray;
    array_push($result,$data);
    $response->data = $result;   
  }
}
else if ($num_rows5>0)
{
 $data->status ='2';
 $data->message = 'Your number has been in-active';
 array_push($result,$data);
 $response->data = $data;
}
else
{
 // $otpValue=mt_rand(1000, 9999);
  $otpValue=1234;
 $data2->mobile_no=$mobile_no;
 $data3->device_id = $device_id;
 $data3->notification_id = $notification_id;
 $data3->mobile_no=$mobile_no;
 $data3->otp=$otpValue;
 $res3 = $this->Supervisor->send_otp($mobile_no,$otpValue);
 if($res3!='')
 {
  $res4 = $this->Supervisor->otpgetdata($data3);
}
$term_data=$this->Supervisor->getTermsAndCondition();
$AdminData=$this->Supervisor->getRoleImages('Admin');
$data['admin_id'] = '';
$data['name'] =  '';
$data['mobile_no'] =$mobile_no;
$data['user_type'] =  '';
$data['term_condition'] = '';
$data['message']='success';
$data['status']  ='1';
$data['roles']=array(array('images'=>base_url().'RoleImages/'.$AdminData[0]['image'],'user_type'=>'Admin','name'=>''));
$data['term_condition'] =  $term_data[0]['term_condtion'];
array_push($result , $data);
$response->data = $data;

}
}
else
{
  $data['message']='failed';
  $data['status']  ='0';
  array_push($result , $data);
}
$response->data = $data;

echo json_output($response);
}


/*.........order change by staff Api for Restaurant ---- */
public function order_update_by_staff_post()
{
  $response = new StdClass();
  $result = new StdClass();
  $order_id = $this->input->post('order_id');
  $admin_id = $this->input->post('admin_id');
  $table_no=$this->input->post('table_no');
  $menu_item_name=$this->input->post('menu_item_name');
  $quantity=$this->input->post('quantity');
  $menu_price=$this->input->post('menu_price');
  $total_item=$this->input->post('total_item');
  $total_price=$this->input->post('total_price');
  $gst_amount=$this->input->post('gst_amount');
  $order_status = $this->input->post('order_status');
  $order_change_by=$this->input->post('order_change_by');
  $slip_status=$this->input->post('slip_status');
  $data->order_id = $order_id;
  $data->admin_id = $admin_id;
  $data->table_no = $table_no;
  $data->menu_item_name = $menu_item_name;
  $data->quantity = $quantity;
  $data->menu_price = $menu_price;
  $data->total_item = $total_item;
  $data->total_price = $total_price;
  $data->gst_amount = $gst_amount;
  $data->order_status= $order_status;
  $data->order_change_by=$order_change_by;
  $data->slip_status=$slip_status;
  $result1 = $this->Supervisor->order_update_for_customer_by_staff($data);
  if(!empty($order_id))
  {
    $data1->status ='1';
    $data1->message = 'order successfully update';
    array_push($result,$data1);
    $response->data = $data1;
  }
  else
  {
    $response->status ='0';
    $response->message = 'register failed';
  }
  echo json_output($response);
}


/*.........Admin Registration  Api  ---- */
public function admin_registration_post()
{   

  $response = new StdClass();
  $result2 = new StdClass();
  $name=ucfirst($this->input->post('name'));
  $restaurant_name=ucfirst($this->input->post('restaurant_name'));
  $mobile_no=$this->input->post('mobile_no');
  $email=$this->input->post('email');
  $device_id=$this->input->post('device_id');
  $notification_id=$this->input->post('notification_id');
  $city=ucfirst($this->input->post('city'));
  // $user_password=$this->input->post('user_password');
  date_default_timezone_set('Asia/kolkata'); 
  $now = date('Y-m-d H:i:s');
  $data->name=$name;
  $data->mobile_no=$mobile_no;
  $data->email=$user_email;
  $data->user_createdate=$now;
  
  $que=$this->db->query("select * from tbl_admin where mobile_no='".$mobile_no."'");

  $quedata=$this->db->query("select * from tbl_restaurant_staff_registration where mobile_no='".$mobile_no."'");

  $master_user=$this->db->query("select * from master_user where mobile_no='".$mobile_no."'");

  // print_r($master_user);exit;

  $row = $que->num_rows();

  $row1 = $quedata->num_rows();

  $row2 = $master_user->num_rows();

  // print_r($row2);exit;

  if($row>0)
  {
    $data1->status ='2';
    $data1->message = 'This Number already exists';
    array_push($result2,$data1);
    $response->data = $data1;
  }

  else if($row1>0)
  {
    $data1->status ='2';
    $data1->message = 'This Number already exists';
    array_push($result2,$data1);
    $response->data = $data1;
  }
  else if($row2 > 0)
  {
    $data1->status ='2';
    $data1->message = 'This Number already exists';
    array_push($result2,$data1);
    $response->data = $data1;
  }
  else
  {
    if(!empty($city)){

    date_default_timezone_set('Asia/kolkata'); 
    $now = date('Y-m-d H:i:s');
    $saltArray   = array(
                     'email'=>$email,
                     'phone'=>$mobile_no,
                     );
   
    $salt           =createHash($saltArray);
    $user_password  =$this->encrypt->encode($this->input->post('user_password'), $salt);
    $data->name=$name;
    $data->restaurant_name=$restaurant_name;
    $data->mobile_no=$mobile_no;
    $data->user_password=$user_password;
    $data->salt=$salt;
    $data->email=$email;
    $data->user_role='2';
    $data->user_active='1';
    $data->user_createdate=$now;
    $data->status='1';
    $prefixResult=$this->Supervisor->getPrefix($city);
    // echo "<pre>";print_r($prefixResult);exit;
    if(!empty($prefixResult)){
    $city_prefix=$prefixResult[0]['city_prefix'];
    $state_prefix=$prefixResult[0]['state_prefix'];
    $maxAdmin=$this->Supervisor->getMaxAdminData($city_prefix,$state_prefix);
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
      $data2->status ='0';
      $data2->message = 'Please enter correct city name.';
      array_push($result2,$data2);
      $response->data = $data2;
      echo  json_output($response);
    }
     // echo "<pre>";print_r($admin_id);exit;
    $data->admin_id=$admin_id;
    $result=$this->Supervisor->admin_registration($data);
    $data1->device_id = $device_id;
    $data1->notification_id = $notification_id;
    $data1->login_time=$now;
    $data1->name=$name;
    $data1->mobile_no=$mobile_no;
    $data1->user_type='Admin';
    $res=$this->Supervisor->manage_login_data($data1);
    if(!empty($result))
    {  
      $data2->name=$name;
      $data2->mobile_no=$mobile_no;
      $data2->user_type='Admin';
      $data2->admin_id=$admin_id;
      $data2->status ='1';
      $data2->message = 'register Successfully';
      array_push($result2,$data2);
      $response->data = $data2;
    }
    else
    {
      $data2->status ='0';
      $data2->message = 'register failed';
      array_push($result2,$data2);
      $response->data = $data2;
    }
     echo  json_output($response);
  }
  else{
      $data2->status ='0';
      $data2->message = 'You are not service able area.We will provide service in your area comming soon.';
      array_push($result2,$data2);
      $response->data = $data2;
      echo  json_output($response);
}
}
 echo  json_output($response);
}
/*.........Admin  Registration  Api  ---- */

/*.........staff Registration  Api  ---- */
public function staff_registration_post()
{   
  $response = new StdClass();
  $result2 = new StdClass();
  $name=ucfirst($this->input->post('name'));
  $admin_id=$this->input->post('admin_id');
  $mobile_no=$this->input->post('mobile_no');
  $email=$this->input->post('email');
  $device_id=$this->input->post('device_id');
  $notification_id=$this->input->post('notification_id');
  $date_of_birth=$this->input->post('date_of_birth');
  $aadhar_no=$this->input->post('aadhar_no');
  $pan_number=$this->input->post('pan_number');
  $desingination=$this->input->post('desingination');
  $gender=$this->input->post('gender');
  $permanent_address=$this->input->post('permanent_address');
  $current_address=$this->input->post('current_address');
  $user_type=ucfirst($this->input->post('user_type')); 
  // echo '<pre>';print_r($user_type);exit;
  $master_user=$this->db->query("select id from master_user where mobile_no='".$mobile_no."'");
  $row2 = $master_user->num_rows();
  $staffData=$this->Supervisor->checkStaffData($admin_id,$mobile_no);
  if(!empty($staffData) && count($staffData) >=5){
    $data1->status ='2';
    $data1->message = 'All role assigend to this number.';
    array_push($result2,$data1);
    $response->data = $data1;
    echo  json_output($response);exit;
  }
  else if($row2 > 0)
  {
    $data1->status ='2';
    $data1->message = 'This Number already exists as super admin';
    array_push($result2,$data1);
    $response->data = $data1;
  }
  else
  {
    date_default_timezone_set('Asia/kolkata'); 
    $now = date('Y-m-d H:i:s');
    $adminPhoneNumber=$this->Supervisor->getAdminMobileNo($admin_id);
    $adminNotification=$this->Supervisor->getNotificationIdForUserRole($adminPhoneNumber[0]['mobile_no']);
    $explode=explode(',',$user_type);
    for($i=0;$i<count($explode);$i++){
      // print_r($userType);exit;
        $data=array( 
        'admin_id'=>trim($admin_id),
        'name'=>trim($name),
        'mobile_no'=>trim($mobile_no),
        'email'=>trim($email),
        'date_of_birth'=>trim($date_of_birth),
        'aadhar_no'=>$aadhar_no,
        'pan_number'=>$pan_number,
        'desingination'=>trim($explode[$i]),
        'gender'=>$gender,
        'permanent_address'=>$permanent_address,
        'current_address'=>$current_address,
        'user_type'=>trim($explode[$i]),
        'create_date'=>$now,
        'status'=>1
        );
        if($adminPhoneNumber[0]['mobile_no']==$mobile_no){
          $user_array=array(
            'name'=>trim($name),
            'mobile_no'=>trim($mobile_no),
            'device_id'=>$adminNotification[0]['device_id'],
            'notification_id'=>$adminNotification[0]['notification_id'],
            'user_type'=>trim($explode[$i]),
            'login_time'=>date('Y-m-d H:i:s'),
            'active_status'=>1,
            'status'=>1,
          );
        }
      if(trim($explode[$i]) !='Waiter'){
        $staffRole=$this->Supervisor->checkStaffDataRole($admin_id,trim($explode[$i]));
        if(empty($staffRole)){
           $result = $this->Supervisor->staff_registration($data);
           if($adminPhoneNumber[0]['mobile_no']==$mobile_no){
            $this->Supervisor->addUserRole($user_array);
           }
           
        }else{
          $data2->status ='0';
          $data2->message = 'Role already exists for '.$explode[$i].' Please select suitable role.';
          array_push($result2,$data2);
          $response->data = $data2;
          echo  json_output($response);exit;
        }
      }else{
         $staffDataForWaiter=$this->Supervisor->staffDataForWaiter($mobile_no,'Waiter');
         if(empty($staffDataForWaiter)){
           $result = $this->Supervisor->staff_registration($data);
          if($adminPhoneNumber[0]['mobile_no']==$mobile_no){
              $this->Supervisor->addUserRole($user_array);
             }
         }else{
          $data2->status ='0';
          $data2->message = 'Role already exists for waiter.Please select suitable role.';
          array_push($result2,$data2);
          $response->data = $data2;
          echo  json_output($response);exit;
         }
        
      }
    }
    $data1->device_id = $device_id;
    $data1->notification_id = $notification_id;
    $data1->login_time=$now;
    $data1->name=$name;
    $data1->mobile_no=$mobile_no;
    $data1->user_type=$user_type;
    $res = $this->Supervisor->manage_login_data($data1);
    if(!empty($result))
    {  
      $data2->admin_id =$admin_id;
      $data2->name =$name;
      $data2->mobile_no =$mobile_no;
      $data2->user_type =$user_type;

      $data2->status ='1';
      $data2->message = 'register Successfully';
      array_push($result2,$data2);
      $response->data = $data2;
    }
    else
    {
      $data2->status ='0';
      $data2->message = 'register failed';
      array_push($result2,$data2);
      $response->data = $data2;
    }
  }
  echo  json_output($response);
}
/*.........Admin  Registration  Api  ---- */

/*.........Add restaurant  for Restaurant Api  ---- */
public function add_restaurant_post()
{   
  $response = new StdClass();
  $result2 = new StdClass();

  if($this->form_validation->run('addRestaurent')==FALSE)
  {
    $data2->status ='0';
    $data2->message = 'failed';
    $data->error=validation_errors();
    array_push($result2,$data2);
    $response->data = $data2;
    echo  json_output($response);exit;
  }else{
    //$gstValuesArray=json_decode(GSTVALUE);
    //$gstNameArray=json_decode(GSTNAME);
    $name=$this->input->post('name');
    $image=$this->input->post('image');
         if(empty($image))
         {
            $img_name='';
         }else
         {
              $t = time()."".date('Ymd');
              $path ='uploads/';
              $image_parts =explode(";base64,",$image);
              $image_type_aux=explode("image/", $image_parts[0]);
              $image_base64 = base64_decode($image_parts[0]);
              $img_name ='resto'."_".$t.".jpeg";
              $file = 'uploads/'.$img_name;
              file_put_contents($file, $image_base64);
         }
    $lat=$this->input->post('lat');
    $lng=$this->input->post('lng');
    $location=$this->input->post('location');
    $cuisines=$this->input->post('cuisines');
    $gst_no=$this->input->post('gst_no');
    $pan_no=$this->input->post('pan_no');
    $cost=$this->input->post('cost');
    $openStatus=$this->input->post('openStatus');
    $openingTime=$this->input->post('openingTime');
    $closingTime=$this->input->post('closingTime');
    $phone=$this->input->post('phone');
    $address=$this->input->post('address');
    $amenities=$this->input->post('amenities');
    $verified=$this->input->post('verified');
    $city=$this->input->post('city');
    $trending=$this->input->post('trending');
    $admin_id=$this->input->post('admin_id');
    $food_code=$this->input->post('food_code');
    date_default_timezone_set('Asia/kolkata'); 
    $now = date('Y-m-d H:i:s');
    $data->name=$name;
    $data->image=$img_name;
    $data->lat=$lat;
    $data->lng=$lng;
    $data->location=$location;
    $data->gst_no=$gst_no;
    $data->pan_no=$pan_no;
    $data->cuisines=$cuisines;
    $data->cost=$cost;
    $data->food_code=$food_code;
    $data->openStatus=$openStatus;
    $data->openingTime=$openingTime;
    $data->closingTime=$closingTime;
    $data->phone=$phone;
    $data->address=$address;
    $data->amenities=$amenities;
    $data->verified=$verified;
    $data->city=$city;
    $data->trending=$trending;
    $data->admin_id=$admin_id;
    $data->create_date=$now;
    $result = $this->Supervisor->add_restaurant($data);

    if(!empty($result))
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
      $this->Supervisor->insertGstDetails($array);
      $data2->status ='1';
      $data2->message = 'Restaurant added Successfully';
      array_push($result2,$data2);
      $response->data = $data2;
    }
    else
    {
      $data2->status ='0';
      $data2->message = 'failed';
      array_push($result2,$data2);
      $response->data = $data2;
    }


    echo  json_output($response);
  }
}

/*........add restaurant For Restaurant ---- */

/*.........Add menu for  restaurant  for Restaurant Api  ---- */
public function add_menu_item_for_restaurant_post()
{   
  $response = new StdClass();
  $result2 = new StdClass();
  date_default_timezone_set('Asia/kolkata'); 
  $admin_id=$this->input->post('admin_id');
  $menu_food_type=$this->input->post('menu_food_type');
  $menu_name=$this->input->post('menu_name');
  $menu_image=$this->input->post('menu_image');
  $menu_detail=$this->input->post('menu_detail');
  $menu_half_price=$this->input->post('menu_half_price');
  $menu_full_price=$this->input->post('menu_full_price');
  $menu_fix_price=$this->input->post('menu_fix_price');
  $nutrient_counts=$this->input->post('nutrient_counts');
  $id=$this->input->post('id');
  $cat_id=$this->input->post('cat_id');
  $sub_cat_id=$this->input->post('sub_cat_id');
  $getRes=$this->Supervisor->getRestaurant($admin_id);
  $now = date('Y-m-d H:i:s');
  $t = time()."".date('Ymd');
      if(!empty($menu_image))
      {
      $path ='uploads/';
      $image_parts =explode(";base64,",$menu_image);
      $image_type_aux=explode("image/", $image_parts[0]);
      $image_base64 = base64_decode($image_parts[0]);
      $img_name ='menu'."_".$t.".jpeg";
      $file = 'uploads/'.$img_name;
      file_put_contents($file, $image_base64);
      }else
      {
        $img_name='';
      }
        $data->admin_id=$admin_id;
        $data->menu_food_type=$menu_food_type;
        $data->menu_name=$menu_name;
        $data->menu_image=$img_name;
        $data->menu_detail=$menu_detail;
        $data->menu_half_price=$menu_half_price;
        $data->menu_full_price=$menu_full_price;
        $data->menu_fix_price=$menu_fix_price;
        $data->nutrient_counts=$nutrient_counts;
        $data->create_date=$now;
        $data->menu_category_id=$id;
        $data->sub_cat_id= !empty($sub_cat_id)?$sub_cat_id:'NA';
        $data->cat_id=$cat_id;
        $data->status='1';

        $result = $this->Supervisor->add_menu_item_restaurant($data);
        $alphanumerric='MENU_0000'.$result;
        $updatemenudata = $this->Supervisor->update_menu_id($alphanumerric,$result);
        if(!empty($result))
        {  
          $data2->status ='1';
          $data2->message = ' menu added Successfully';
          array_push($result2,$data2);
          $response->data = $data2;
        }
        else
        {
          $data2->status ='0';
          $data2->message = 'failed';
          array_push($result2,$data2);
          $response->data = $data2;
        }

  echo  json_output($response);
}

/*.........Role Api For Restaurant ---- */   
/*.........Add menu for  restaurant  for Restaurant Api  ---- */
public function get_restaurant_data_post()
{   
  $response = new StdClass();
  $result2 = new StdClass();
  $admin_id=$this->input->post('admin_id');

  $result = $this->Supervisor->get_restaurant_data($admin_id);
  $result1 = $this->Supervisor->check_data_for_restaurant($admin_id);
  $restaurant_name=$result->restaurant_name;
  //print_r($result1);exit;
  if(!empty($result))
  {  
    $data2->restaurant_name =$restaurant_name;
    if(!empty($result1))
    {
     $data2->restaurant_status ='1';
   }
   else
   {
    $data2->restaurant_status ='0';
  }
  $data2->status ='1';
  $data2->message = 'Success';
  array_push($result2,$data2);
  $response->data = $data2;
}
else
{
  $data2->status ='0';
  $data2->message = 'failed';
  array_push($result2,$data2);
  $response->data = $data2;
}
echo  json_output($response);
}
/*.........Role Api For Restaurant ---- */
public function user_type_list_post()
{
  $response   =   new StdClass();
  $result       =   array();
  $user_type = $this->Supervisor->get_user_type();
  if(!empty($user_type))
  {
   foreach ($user_type as $row)
   {
    $data['user_type'] =   $row['user_type'];
    $data['user_value'] =   $row['user_value'];
    $data['message'] = 'Success';
    $data['status']  ='1';

    array_push($result,$data);

  } 

  $response->data = $result;
}
else
{
  $data['message'] = 'failed';
  $data['status']  ='0';
  array_push($result , $data);
}
$response->data = $result;
echo json_output($response);
}

/*........Get Restaurant Detail Api  For Restaurant ---- */
public function get_detail_for_restaurant_data_post()
{
 $response = new StdClass();
 $result2 = new StdClass();
 $admin_id=$this->input->post('admin_id');
 $detail_for_restaurant = $this->Supervisor->get_detail_for_restaurant($admin_id);
 $admin_id=$detail_for_restaurant->admin_id;
 $name=$detail_for_restaurant->name;
 $image=$detail_for_restaurant->image;
 $lat=$detail_for_restaurant->lat;
 $lng=$detail_for_restaurant->lng;
 $gst_no=$detail_for_restaurant->gst_no;
 $result=$this->Supervisor->getAmenitiesType();

        // print_r($result);exit;
 foreach($result as $value)
 {
         // echo $value['amenities_type'];exit;
  $implode .=$value['amenities_type'].',';
}
      // print_r(trim($implode,','));exit;
$cuisines_result=$this->Supervisor->getFoodType();
foreach($cuisines_result as $cuisines_value)
{
         // echo $value['amenities_type'];exit;
  $implode_cuisines .=$cuisines_value['food_type'].',';
}

if(empty($gst_no))
{
  $gst_no_data='';
}
else
{
  $gst_no_data=$gst_no;
}
$pan_no=$detail_for_restaurant->pan_no;
if(empty($pan_no))
{
  $pan_no_data='';
}
else
{
  $pan_no_data=$pan_no;
}
$location=$detail_for_restaurant->location;
$cuisines=trim($implode_cuisines,',');
$restaurantcuisines=$detail_for_restaurant->cuisines;
$city=$detail_for_restaurant->city;
$openStatus=$detail_for_restaurant->openStatus;
$openingTime=$detail_for_restaurant->openingTime;
$closingTime=$detail_for_restaurant->closingTime;
$phone=$detail_for_restaurant->phone;
$address=$detail_for_restaurant->address;
$amenities=trim($implode,',');
$restaurantamenities=$detail_for_restaurant->amenities;
$restaurant_name=$detail_for_restaurant->restaurant_name;
$food_code=$detail_for_restaurant->food_code;
$fodd_data=$this->Supervisor->getRestaurantFoodType($food_code);
if(!empty($detail_for_restaurant))
{
 $data2->admin_id =$admin_id;
 $data2->name =$name;
 if(!empty($image))
 {
   $data2->image = $image !=''?base64_encode(file_get_contents(base_url().'uploads/'.$image)):'';
 }else
 {
   $data2->image='';
 }

 $data2->lat =$lat;
 $data2->lng =$lng;
 $data2->gst_no =$gst_no_data;
 $data2->pan_no =$pan_no_data;
 $data2->location =$location;
 $data2->cuisines =$cuisines;
 $data2->restaurantcuisines =$restaurantcuisines;
 $data2->city =$city;
 $data2->openStatus =$openStatus;
 $data2->openingTime =$openingTime;
 $data2->closingTime =$closingTime;
 $data2->phone =$phone;
 $data2->address =$address;
 $data2->amenities =$amenities;
 $data2->food_code =$food_code;
 if(!empty($fodd_data)){
  $food_type=$fodd_data[0]['food_type'];
  $data2->food_type =$food_type;
  }else{
    $data2->food_type ='';
  }
 $data2->restaurantamenities =$restaurantamenities;
 $data2->message ='success';
 $data2->status ='1';
 array_push($result2,$data2);
 $response->data = $data2;
}
else
{
 $data2->status ='0';
 $data2->message ='failed';
 array_push($result2,$data2);
 $response->data = $data2;
}

echo json_output($response);
}

/*.........Get Restaurant Detail Api  For Restaurant  ---- */
/*........Get staff  Detail Api  For Restaurant ---- */
public function get_staff_detail_for_restaurant_post()
{
 $response = new StdClass();
 $result2 = new StdClass();
 $admin_id=$this->input->post('admin_id');
 $mobile_no=$this->input->post('mobile_no');
 $user_type=$this->input->post('user_type');
 $detail_for_restaurant = $this->Supervisor->get_staff_detail($admin_id,$mobile_no,($user_type=='Waiting Staff'?'Waiter':$user_type));
 $id=$detail_for_restaurant->id;
 $admin_id=$detail_for_restaurant->admin_id;
 $name=$detail_for_restaurant->name;
 $username=$detail_for_restaurant->username;
 $mobile_no=$detail_for_restaurant->mobile_no;
 $email=$detail_for_restaurant->email;
 $password=$detail_for_restaurant->password;
 $date_of_birth=$detail_for_restaurant->date_of_birth;
 $aadhar_no=$detail_for_restaurant->aadhar_no;
 $pan_number=$detail_for_restaurant->pan_number;
 $desingination=$detail_for_restaurant->desingination;
 $gender=$detail_for_restaurant->gender;
 $permanent_address=$detail_for_restaurant->permanent_address;
 $current_address=$detail_for_restaurant->current_address;
 $user_type=$detail_for_restaurant->user_type;
 if(empty($password)){
  $password_data='';
}else{
  $password_data=$password;
}
if(empty($pan_number)){
  $pan_no_data='';
}else{
  $pan_no_data=$pan_number;
}
if(!empty($detail_for_restaurant)){
 $data2->id =$id;
 $data2->admin_id =$admin_id;
 $data2->name =$name;
 $data2->username =$username;
 $data2->mobile_no =$mobile_no;
 $data2->email =$email;
 $data2->password =$password_data;
 $data2->date_of_birth =$date_of_birth;
 $data2->aadhar_no =$aadhar_no;
 $data2->pan_number =$pan_no_data;
 $data2->desingination =$desingination;
 $data2->gender =$gender;
 $data2->permanent_address =$permanent_address;
 $data2->current_address =$current_address;
 $data2->user_type =$user_type;
 $data2->message ='success';
 $data2->status ='1';
 array_push($result2,$data2);
 $response->data = $data2;
}
else
{
 $data2->status ='0';
 $data2->message = 'failed';
 array_push($result2,$data2);
 $response->data = $data2;
}

echo json_output($response);
}

/*.........Get Restaurant Detail Api  For Restaurant  ---- */
/*.........menu Edit for restaurant Api  ---- */
public function menu_update_post()
{
  $response = new StdClass();
  $result = new StdClass();
  $menu_id = $this->input->post('menu_id');
  $admin_id = $this->input->post('admin_id');
  $menu_food_type=$this->input->post('menu_food_type');
  $menu_name=$this->input->post('menu_name');
  $menu_image=$this->input->post('menu_image');
  $menu_detail=$this->input->post('menu_detail');
  $menu_half_price=$this->input->post('menu_half_price');
  $menu_full_price=$this->input->post('menu_full_price');
  $menu_fix_price=$this->input->post('menu_fix_price');
  $nutrient_counts=$this->input->post('nutrient_counts');
  $id=$this->input->post('id');
  $cat_id=$this->input->post('cat_id');
  $sub_cat_id=$this->input->post('sub_cat_id');
  $t = time()."".date('Y-m-d');
    if(!empty($menu_image))
    {
      $path ='uploads/';
      $image_parts =explode(";base64,",$menu_image);
      $image_type_aux=explode("image/", $image_parts[0]);
      $image_base64 = base64_decode($image_parts[0]);
      $img_name ='menu'."_".$t.".jpeg";
      $file = 'uploads/'.$img_name;
      file_put_contents($file, $image_base64);
      $getmenuImage=$this->Supervisor->getMenuImage($menu_id,$admin_id);
      unlink(FCPATH.'uploads/'.$getmenuImage);
    }else
    {
      $img_name='';
    }
  
  $data->menu_id = $menu_id;
  $data->admin_id = $admin_id;
  $data->menu_food_type = $menu_food_type;
  $data->menu_name = $menu_name;
  $data->menu_image = $img_name;
  $data->menu_detail = $menu_detail;
  $data->menu_half_price = $menu_half_price;
  $data->menu_full_price = $menu_full_price;
  $data->menu_fix_price = $menu_fix_price;
  $data->nutrient_counts = $nutrient_counts;
  $data->menu_category_id = $id;
  $data->sub_cat_id=  !empty($sub_cat_id)?$sub_cat_id:'NA';
  $data->cat_id=$cat_id;
  $result1 = $this->Supervisor->update_menu_profile($data);
  if(!empty($menu_id))
  {
    $data1->status ='1';
    $data1->message = 'menu successfully update';
    array_push($result,$data1);
    $response->data = $data1;
  }
  else
  {
   $data1->status ='0';
   $data1->message = 'failed';
   array_push($result,$data1);
   $response->data = $data1;
 }
 echo json_output($response);
}
/*.........menu Edit for restaurant Api   ---- */

/*.........staff  Edit for restaurant Api  ---- */
public function staff_update_post()
{
  $response = new StdClass();
  $result = new StdClass();
  date_default_timezone_set('Asia/kolkata'); 
  $admin_id = $this->input->post('admin_id');
  $id = $this->input->post('id');
  $name = $this->input->post('name');
  $username=$this->input->post('username');
  $mobile_no=$this->input->post('mobile_no');
  $email=$this->input->post('email');
  $password=$this->input->post('password');
  $date_of_birth=$this->input->post('date_of_birth');
  $aadhar_no=$this->input->post('aadhar_no');
  $pan_number=$this->input->post('pan_number');
  $desingination=$this->input->post('desingination');
  $gender=$this->input->post('gender');
  $permanent_address=$this->input->post('permanent_address');
  $current_address=$this->input->post('current_address');
  $user_type=$this->input->post('user_type');
  $master_user=$this->db->query("select id from master_user where mobile_no='".$mobile_no."'");
  $row2 =$master_user->num_rows();
  if($row2 > 0)
  {
    $data1->status ='2';
    $data1->message = 'This Number already exists as super admin';
    array_push($result2,$data1);
    $response->data = $data1;
  }else{
        $explode=explode(',',$user_type);
          if(count($explode)==1){
            $data=array(
            'admin_id'=>$admin_id,
            'username'=>$username,
            'name'=>$name,
            'mobile_no'=>$mobile_no,
            'email'=>$email,
            'password'=>$password,
            'date_of_birth'=>$date_of_birth,
            'aadhar_no'=>$aadhar_no,
            'pan_number'=>$pan_number,
            'desingination'=>$explode[0],
            'gender'=>$gender,
            'permanent_address'=>$permanent_address,
            'current_address'=>$current_address,
            'id'=>$id,
            'user_type'=>$explode[0]
          );

              if($explode[0] !='Waiter'){
                    $StaffNumberData=$this->Supervisor->getStaffDataForUpdate($id);
                    if($StaffNumberData[0]['mobile_no']==$mobile_no){
                      $staffData=$this->Supervisor->getStaffDataForUpdate($id);
                      if(!empty($staffData) && $staffData[0]['user_type']==$explode[0]){
                       $result1 =$this->Supervisor->update_staff_profile($data);
                        $adminPhoneNumber=$this->Supervisor->getAdminMobileNo($admin_id);
                       $adminNotification=$this->Supervisor->getNotificationIdForUserRole($adminPhoneNumber[0]['mobile_no']);
                       $user_array=array(
                              'name'=>trim($name),
                              'mobile_no'=>trim($mobile_no),
                              'device_id'=>$adminNotification[0]['device_id'],
                              'notification_id'=>$adminNotification[0]['notification_id'],
                              'user_type'=>trim($explode[0]),
                              'login_time'=>date('Y-m-d H:i:s'),
                              'active_status'=>1,
                              'status'=>1,
                            );
                        $this->Supervisor->addUserRole($user_array);
                        $data1->status ='1';
                        $data1->message = 'success';
                        array_push($result,$data1);
                        $response->data = $data1;
                        echo json_output($response);exit;
                      }
                     else if(!empty($staffData)){
                      $data1->status ='0';
                      $data1->message = 'Role updation not allowed because role already exists.';
                      array_push($result,$data1);
                      $response->data = $data1;
                      echo json_output($response);exit;
                     }else{
                      $result1 =$this->Supervisor->update_staff_profile($data);
                      $data1->status ='1';
                      $data1->message = 'success';
                      array_push($result,$data1);
                      $response->data = $data1;
                      echo json_output($response);exit;
                     }
                    }else{
                      $result1 =$this->Supervisor->update_staff_profile($data);
                      $adminPhoneNumber=$this->Supervisor->getAdminMobileNo($admin_id);
                      $adminNotification=$this->Supervisor->getNotificationIdForUserRole($adminPhoneNumber[0]['mobile_no']);
                      $user_array=array(
                              'name'=>trim($name),
                              'mobile_no'=>trim($mobile_no),
                              'device_id'=>$adminNotification[0]['device_id'],
                              'notification_id'=>$adminNotification[0]['notification_id'],
                              'user_type'=>trim($explode[0]),
                              'login_time'=>date('Y-m-d H:i:s'),
                              'active_status'=>1,
                              'status'=>1,
                            );
                      $this->Supervisor->addUserRole($user_array);
                      $data1->status ='1';
                      $data1->message = 'success';
                      array_push($result,$data1);
                      $response->data = $data1;
                      echo json_output($response);exit;
                    }
                    
              }else{
                
                 $StaffNumberData=$this->Supervisor->getStaffDataForUpdate($id);
                   if($StaffNumberData[0]['mobile_no']==$mobile_no){
                    $flagData=$this->Supervisor->getStaffDataForUpdate($id);
                    if(!empty($flagData)&&$flagData[0]['user_type']==trim($explode[0])){
                      $result1 =$this->Supervisor->update_staff_profile($data);
                      $adminPhoneNumber=$this->Supervisor->getAdminMobileNo($admin_id);
                      $adminNotification=$this->Supervisor->getNotificationIdForUserRole($adminPhoneNumber[0]['mobile_no']);
                      $user_array=array(
                                'name'=>trim($name),
                                'mobile_no'=>trim($mobile_no),
                                'device_id'=>$adminNotification[0]['device_id'],
                                'notification_id'=>$adminNotification[0]['notification_id'],
                                'user_type'=>trim($explode[0]),
                                'login_time'=>date('Y-m-d H:i:s'),
                                'active_status'=>1,
                                'status'=>1,
                              );
                    
                    $this->Supervisor->addUserRole($user_array);
                      $data1->status ='1';
                      $data1->message = 'success';
                      array_push($result,$data1);
                      $response->data = $data1;
                      echo json_output($response);exit;
                    }else if(!empty($flagData)&&$flagData[0]['user_type']=='Waiter'){
                      $data1->status ='0';
                      $data1->message = 'Number already exists for waiter.';
                      array_push($result,$data1);
                      $response->data = $data1;
                      echo json_output($response);exit;
                  }else{
                          $staffDataForWaiter=$this->Supervisor->staffDataForWaiter($mobile_no,'Waiter');
                          if(!empty($staffDataForWaiter)){
                                $data1->status ='0';
                                $data1->message = 'Number already exists for waiter.';
                                array_push($result,$data1);
                                $response->data = $data1;
                                echo json_output($response);exit;
                          }else{
                          $result1 =$this->Supervisor->update_staff_profile($data);
                          $data1->status ='1';
                          $data1->message = 'success';
                          array_push($result,$data1);
                          $response->data = $data1;
                          echo json_output($response);exit;
                          }
                  }
                }else{
                   $result1 =$this->Supervisor->update_staff_profile($data);
                    $adminPhoneNumber=$this->Supervisor->getAdminMobileNo($admin_id);
                      $adminNotification=$this->Supervisor->getNotificationIdForUserRole($adminPhoneNumber[0]['mobile_no']);
                      $user_array=array(
                                'name'=>trim($name),
                                'mobile_no'=>trim($mobile_no),
                                'device_id'=>$adminNotification[0]['device_id'],
                                'notification_id'=>$adminNotification[0]['notification_id'],
                                'user_type'=>trim($explode[0]),
                                'login_time'=>date('Y-m-d H:i:s'),
                                'active_status'=>1,
                                'status'=>1,
                              );
                    
                    $this->Supervisor->addUserRole($user_array);
                   $data1->status ='1';
                    $data1->message = 'success';
                    array_push($result,$data1);
                    $response->data = $data1;
                    echo json_output($response);exit;
                 }
                }
          }else{
            $data1->status ='0';
            $data1->message = 'Please select only one role.';
            array_push($result,$data1);
            $response->data = $data1;
            echo json_output($response);exit;
          }
          }
  if(!empty($mobile_no)){
    $data1->status ='1';
    $data1->message = 'staff updated successfully';
    array_push($result,$data1);
    $response->data = $data1;
  }else{
   $data1->status ='0';
   $data1->message = 'failed';
   array_push($result,$data1);
   $response->data = $data1;
 }
 echo json_output($response);
}
/*.........menu Edit for restaurant Api   ---- */
/*.........restaurant update  for restaurant Api  ---- */
public function restaurant_update_detail_post()
{
  $response = new StdClass();
  $result = new StdClass();
  $city = $this->input->post('city');
  $admin_id = $this->input->post('admin_id');
  $name=$this->input->post('name');
  $image=$this->input->post('image');
  $gst_no=$this->input->post('gst_no');
  $pan_no=$this->input->post('pan_no');
  $lat=$this->input->post('lat');
  $lng=$this->input->post('lng');
  $location=$this->input->post('location');
  $cuisines=$this->input->post('cuisines');
  $cost=$this->input->post('cost');
  $openingTime=$this->input->post('openingTime');
  $closingTime=$this->input->post('closingTime');
  $phone=$this->input->post('phone');
  $address=$this->input->post('address');
  $amenities=$this->input->post('amenities');
  $update_by=$this->input->post('update_by');
  $food_code=$this->input->post('food_code');
  $t = time()."".date('Ymd');
  $path ='uploads/';
      if(!empty($image))
      {
      $image_parts =explode(";base64,",$image);
      $image_type_aux=explode("image/", $image_parts[0]);
      $image_base64 = base64_decode($image_parts[0]);
      $img_name ='resto'."_".$t.".jpeg";
      $file = 'uploads/'.$img_name;
      file_put_contents($file, $image_base64);
      $delImage=$this->Supervisor->getFileName($admin_id);
      unlink(FCPATH.'uploads/'.$delImage);
      }else
      {
      $img_name='';
      }
  $data->image = $img_name;
  $data->city = $city;
  $data->admin_id = $admin_id;
  $data->name = $name;
  $data->gst_no = $gst_no;
  $data->pan_no = $pan_no;
  $data->lat = $lat;
  $data->lng = $lng;
  $data->location = $location;
  $data->cuisines = $cuisines;
  $data->cost = $cost;
  $data->openingTime = $openingTime;
  $data->closingTime = $closingTime;
  $data->phone = $phone;
  $data->address = $address;
  $data->amenities = $amenities;
  $data->update_by = $update_by;
  $data->food_code = $food_code;
  $result1 = $this->Supervisor->update_restaurant_data($data);
  if(!empty($admin_id))
  {
    $data1->status ='1';
    $data1->message = 'restaurant data successfully updated';
    array_push($result,$data1);
    $response->data = $data1;
  }
  else
  {
   $data1->status ='0';
   $data1->message = 'failed';
   array_push($result,$data1);
   $response->data = $data1;
 }
 echo json_output($response);
}
/*.........menu Edit for restaurant Api   ---- */


/*.........get menu data Api For Restaurant ---- */
public function menu_list_data_post()
{
  $response   =   new StdClass();
  $result       =   array();
  $admin_id=$this->input->post('admin_id');
  $menu_list = $this->Supervisor->get_menu_list_data($admin_id);
  if(!empty($menu_list))
  {
   foreach ($menu_list as $row)
   {
      $gst       =$this->Supervisor->getGst($row['menu_category_id'],$admin_id);

     $menuhalfprice=$row['menu_half_price'];
     if(!empty($menuhalfprice))
     {
      $menu_half_price=$row['menu_half_price'];
       $menu_half_price_gst =($menu_half_price)*$gst/100;
    }
    else
    {
      $menu_half_price='';
      $menu_half_price_gst='';
    }
    $menufullprice=$row['menu_full_price'];
    if(!empty($menufullprice))
    {
      $menu_full_price=$row['menu_full_price'];
      $menu_full_price_gst =($menu_full_price)*$gst/100;
    }
    else
    {
      $menu_full_price='';
      $menu_full_price_gst ='';
    }
    $menufixprice=$row['menu_fix_price'];
    if(!empty($menufixprice))
    {
      $menu_fix_price=$row['menu_fix_price'];
      $menu_fix_price_gst =($menu_fix_price)*$gst/100;
    }
    else
    {
      $menu_fix_price='';
      $menu_fix_price_gst='';
    }
    $nutrientcounts=$row['nutrient_counts'];
    if(!empty($nutrientcounts))
    {
      $nutrient_counts=$row['nutrient_counts'];
    }
    else
    {
      $nutrient_counts='';
    }
    $data['menu_id'] =   $row['menu_id'];
    $data['admin_id'] =   $row['admin_id'];
    $data['menu_category_id'] =   $row['menu_category_id'];
    $data['menu_food_type'] =   $row['menu_food_type'];
    $data['menu_name'] =   ucfirst($row['menu_name']);
    $data['rating'] =   $row['rating'];
    $data['cat_id'] =   $row['cat_id'];
    $data['sub_cat_id'] =   $row['sub_cat_id'];
    if(!empty($row['cat_id']))
    {
       $cat_name=$this->Supervisor->getCatName($row['cat_id'],$admin_id);
    }else
    {
       $cat_name="";
    }
    if(!empty($row['sub_cat_id']))
    {
      $sub_cat_name=$this->Supervisor->getSubCatName($row['sub_cat_id'],$admin_id);
    }else
    {
      $sub_cat_name="";
    }
   
    
    $data['menu_image'] = $row['menu_image']!=''?base64_encode(file_get_contents(base_url().'uploads/'.$row['menu_image'])):'';
    // $data['menu_image'] = base_url().'uploads/'.$row['menu_image'];
    
    $data['menu_detail'] =   $row['menu_detail'];
    $data['menu_full_price'] =   $menu_full_price;
    $data['menu_half_price'] =   $menu_half_price;
    $data['menu_fix_price'] =   $menu_fix_price;
    $data['sub_cat_name'] =  empty($sub_cat_name)?'NA':$sub_cat_name;;
    $data['cat_name'] =   $cat_name;
    $data['nutrient_counts'] =   $nutrient_counts;
    $data['gst'] =  "$gst";
    $data['menu_half_price_gst'] = "$menu_half_price_gst";
    $data['menu_full_price_gst'] = "$menu_full_price_gst";
    $data['menu_fix_price_gst'] =  "$menu_fix_price_gst";
    $data['message'] = 'Success';
    $data['status']  ='1';

    array_push($result,$data);

  } 

  $response->data = $result;
}
else
{
  $data['message'] = 'failed';
  $data['status']  ='0';
  array_push($result , $data);
}
$response->data = $result;
echo json_output($response);
}



/*.........Add order for customer  restaurant  for Restaurant Api  ---- */
public function add_order_detail_waiter_for_restaurant_post()
{   
  $response = new StdClass();
  $result2 = new StdClass();
  $admin_id=$this->input->post('admin_id');
  $waiter_mobile_no=$this->input->post('waiter_mobile_no');
  $customer_mobile_no=$this->input->post('customer_mobile_no');
  $table_no=$this->input->post('table_no');
  $menu_item_name=$this->input->post('menu_item_name');
  $quantity=$this->input->post('quantity');
  $half_and_full_status=$this->input->post('half_and_full_status');
  $menu_price=$this->input->post('menu_price');
  $total_item=$this->input->post('total_item');
  $total_price=$this->input->post('total_price');
  $gst_amount=$this->input->post('gst_amount');
  $gst_amount_price=$this->input->post('gst_amount_price');
  $net_pay_amount=$this->input->post('net_pay_amount');
  $order_status=$this->input->post('order_status');
  $menu_id=$this->input->post('menu_id');
  date_default_timezone_set('Asia/kolkata'); 
  $now = date('Y-m-d H:i:s');
  $now1 = date('Y-m-d');
  $data->admin_id=$admin_id;
  $data->waiter_mobile_no=$waiter_mobile_no;
  $data->confirm_order_by=$waiter_mobile_no;
  $data->customer_mobile_no=$customer_mobile_no;
  $data->table_no=$table_no;
  $data->menu_item_name=$menu_item_name;
  $data->quantity=$quantity;
  $data->half_and_full_status=$half_and_full_status;
  $data->menu_price=$menu_price;
  $data->total_item=$total_item;
  $data->total_price=$total_price;
  $data->gst_amount=$gst_amount;
  $data->gst_amount_price=$gst_amount_price;
  $data->net_pay_amount=$net_pay_amount;
  $data->order_status=$order_status;
  $data->create_date=$now;
  $data->date=$now1;
  $data->status='2';
  $que=$this->db->query("select * from tbl_order_detail_for_restaurant where table_no='".$table_no."' and order_status NOT IN('Closed','Rejected') and admin_id='$admin_id' and payment_status!='1'");
  $row = $que->num_rows();
  if($row>0)
  {
    $data1->status ='2';
    $data1->message = 'This table is already book.';
    array_push($result2,$data1);
    $response->data = $data1;
     echo  json_output($response);

  }
  else
  {
    $prvDate=$this->Supervisor->getPrvOrderDate($admin_id);
    // print_r($prvDate);exit;
    $getMaxOrderId=$this->Supervisor->getMaxOrderId($admin_id,$prvDate[0]['date']);
    $result =empty($getMaxOrderId)?'1':$getMaxOrderId;
    //print_r( $result);exit;
    if($result <= 9){
      $alphanumerric=$admin_id.'-'.substr(str_replace('-', '',date('Y-m-d')),2).'000'.$result;
    }else if($result >= '9' && $result <= '99'){
      $alphanumerric=$admin_id.'-'.substr(str_replace('-', '',date('Y-m-d')),2).'00'.$result;
    }else if($result >= '99' && $result <= '999'){
     $alphanumerric=$admin_id.'-'.substr(str_replace('-', '',date('Y-m-d')),2).'0'.$result;
    }else
      $alphanumerric=$admin_id.'-'.$result;
    }
    $data->order_id=$alphanumerric;
    $updated=$this->Supervisor->add_order_detail_for_waiter($data);
    if(!empty($result))
    {  
      $menu_item_array      =explode(",",rtrim($menu_item_name,","));
      $menu_price           =explode(",",rtrim($menu_price,","));
      $quantity             =explode(",",rtrim($quantity,","));
      $menu_ids             =explode(",",rtrim($menu_id,","));
      $half_and_full_status =explode(",",rtrim($half_and_full_status,","));
      $gst_amount_array     =explode(",",rtrim($gst_amount,","));
      for($i=0;$i<count($menu_item_array);$i++)
      {
        $insert_array[]=array(
          'menu_item_name'=>$menu_item_array[$i],
          'quantity'=>$quantity[$i],
          'half_and_full_status'=>$half_and_full_status[$i]=='FF'?'F':$half_and_full_status[$i],
          'menu_price'=>$menu_price[$i],
          'order_id'=>$alphanumerric,
          'status'=>'1',
          'admin_id'=>$admin_id,
          'menu_id'=>$menu_ids[$i],
          'gst'=>$gst_amount_array[$i],
          'creation_date'=>date('Y-m-d H:s:i')
        );

      }
              // print_r($insert_array);exit;
      $this->Supervisor->insertBatchOrder($insert_array);
      $order_id           =$alphanumerric;
      $staffData1         =$this->Supervisor->getStaffNotification($admin_id,'KOT');
      $getCustmoerData    =$this->Supervisor->getCustmoerData($order_id,$admin_id);
      $custData           =$this->Supervisor->getCustData($getCustmoerData[0]['customer_mobile_no']);
      $staffData2         =$this->Supervisor->getStaffNotification($admin_id,'Supervisor');
      $adminData          =$this->Supervisor->getAdminData($admin_id);
      $order_date         =date('Y-m-d',strtotime($this->Supervisor->getOrderDate($order_id)));
      $waiterData         =$this->Supervisor->getwaiterData($waiter_mobile_no,'Waiter');
      $order_id2          =str_replace($admin_id.'-','',$order_id);
      $array_merge_recursive=array_merge_recursive($adminData,array_merge_recursive($custData,array_merge_recursive($staffData1,$staffData2)));
      // echo "<pre>";print_r($array_merge_recursive);exit;
      foreach($array_merge_recursive as $notification){
        if($notification['user_type']=='customer'){
              $title ='OYLY';
              $message ='Your order for table no '.$table_no.' has been placed.';
              }else if($notification['user_type']=='KOT'){
              $title ='OYLY';
              $message ='Table no '.$table_no.' ('.$waiterData[0]['name'].')'.' Confirmed order';
              }else if($notification['user_type']=='Supervisor'){
              $title ='OYLY';
              $message ='Table no '.$table_no.' order id '. $order_id2.' placed an order.';
              }else if($notification['user_type']=='Admin'){
              $title ='OYLY';
              $message ='Order has been created for table no '.$table_no;
              }
               $result=sendPushNotification($title,$message,$notification['notification_id']);
               if(!empty($result))
              {
                $array=array(
                'send_from'=>$waiter_mobile_no,
                'staff_mobile_no'=>$notification['mobile_no'],
                'admin_id'=>$admin_id,
                'status'=>1,
                'order_id'=>$order_id2,
                'table_no'=>$table_no,
                'title'=>$title ,
                'message'=>$message,
                'user_type'=>$notification['user_type'],
                'customer_mobile_no'=>$customer_mobile_no,
                'date_time'=>date('Y-m-d H:i:s')
                ); 
                if(!empty($array))
                {
                  $this->Supervisor->insertNotification($array);                          
                }  
        }  
      }
      $data2->status ='1';
      $data2->message = 'success';
      array_push($result2,$data2);
      $response->data = $data2;
    }
    else
    {
      $data2->status ='0';
      $data2->message = 'failed';
      array_push($result2,$data2);
      $response->data = $data2;
    }

  echo  json_output($response);
}

/*.........Role Api For Restaurant ---- */

/*.........change order particular customer  for  restaurant  Api  ---- */
public function change_order_for_particular_customer_post()
{   
        // echo 'nc';exit;
  $response = new StdClass();
  $result2 = new StdClass();
  $order_id=$this->input->post('order_id');
  $admin_id=$this->input->post('admin_id');
  $waiter_mobile_no=$this->input->post('waiter_mobile_no');
  $customer_mobile_no=$this->input->post('customer_mobile_no');
  $table_no=$this->input->post('table_no');
  $menu_item_name=$this->input->post('menu_item_name');
  $quantity=$this->input->post('quantity');
  $half_and_full_status=$this->input->post('half_and_full_status');
  $menu_price=$this->input->post('menu_price');
  $total_item=$this->input->post('total_item');
  $total_price=$this->input->post('total_price');
  $gst_amount=$this->input->post('gst_amount');
  $gst_amount_price=$this->input->post('gst_amount_price');
  $net_pay_amount=$this->input->post('net_pay_amount');
  $order_status=$this->input->post('order_status');
  $cus_id=$this->Supervisor->getCustId($order_id,$admin_id,$customer_mobile_no);
  $max_id=$this->Supervisor->getMax($order_id,$admin_id);
  $menu_id=$this->input->post('menu_id');
  $now = date('Y-m-d H:i:s');
  $now1 = date('Y-m-d');
  $data->order_id=$order_id;
  $data->admin_id=$admin_id;
  if($max_id <='9')
  {
    $data->sub_order_id='000'.($max_id+1);
    $sub_order_id='000'.($max_id+1);
  }else if($max_id <= '99' && $max_id >= 9)
  {
    $data->sub_order_id='00'.($max_id+1);
    $sub_order_id='00'.($max_id+1);
  }else if($max_id <= '999' && $max_id >= 9999)
  {
    $data->sub_order_id='0'.($max_id+1);
    $sub_order_id='0'.($max_id+1);
  }
  else 
  {
    $data->sub_order_id=($max_id+1);
    $sub_order_id=($max_id+1);
  }
  // $data->cus_id=$cus_id;
  $data->waiter_mobile_no=$waiter_mobile_no;
  $data->customer_mobile_no=$customer_mobile_no;
  $data->table_no=$table_no;
  $data->menu_item_name=$menu_item_name;
  $data->quantity=$quantity;
  $data->half_and_full_status=$half_and_full_status;
  $data->menu_price=$menu_price;
  $data->total_item=$total_item;
  $data->total_price=$total_price;
  $data->gst_amount=$gst_amount;
  $data->gst_amount_price=$gst_amount_price;
  $data->net_pay_amount=$net_pay_amount;
  $data->order_status='Confirm';
  $data->create_date=$now;
  $data->date=$now1;
  $data->status='2';
  $check=$this->Supervisor->checkOrderStatus($order_id,$admin_id);
  if(!empty($check))
  {
    $data2->status ='0';
    $data2->message = 'Order should be confirmed ';
    array_push($result2,$data2);
    $response->data = $data2; 
    echo  json_output($response);exit;
  }else
  {
    $result = $this->Supervisor->add_order_detail_restaurant($data);
  }
  // $result=1;
  if(!empty($result))
  {  
    $menu_item_array      =explode(",",rtrim($menu_item_name,","));
    $menu_price           =explode(",",rtrim($menu_price,","));
    $quantity             =explode(",",rtrim($quantity,","));
    $menu_ids             =explode(",",rtrim($menu_id,","));
    $half_and_full_status =explode(",",rtrim($half_and_full_status,","));
    $gst_amount_array     =explode(",",rtrim($gst_amount,","));

    for($i=0;$i<count($menu_item_array);$i++)
    {

      $insert_array[]=array(
        'menu_item_name'=>$menu_item_array[$i],
        'quantity'=>$quantity[$i],
        'half_and_full_status'=>$half_and_full_status[$i]=='FF'?'F':$half_and_full_status[$i],
        'menu_price'=>$menu_price[$i],
        'sub_order_id'=>$sub_order_id,
        'order_id'=>$order_id,
        'status'=>'1',
        'admin_id'=>$admin_id,
        'menu_id'=>$menu_ids[$i],
        'gst'=>$gst_amount_array[$i],
        'creation_date'=>date('Y-m-d H:s:i')
      );

    }
    // print_r($insert_array);exit;
      $this->Supervisor->insertBatchSubOrder($insert_array);
      $staffData1         =$this->Supervisor->getStaffNotification($admin_id,'KOT');
      $getCustmoerData    =$this->Supervisor->getCustmoerData($order_id,$admin_id);
      $custData           =$this->Supervisor->getCustData($getCustmoerData[0]['customer_mobile_no']);
      $staffData2         =$this->Supervisor->getStaffNotification($admin_id,'Supervisor');
      $adminData          =$this->Supervisor->getAdminData($admin_id);
      $order_id2          =str_replace($admin_id.'-','',$order_id);
      $waiterData         =$this->Supervisor->getwaiterData($waiter_mobile_no,'Waiter');
      $array_merge_recursive=array_merge_recursive($adminData,array_merge_recursive($custData,array_merge_recursive($staffData1,$staffData2)));
      // echo "<pre>";print_r($notification_data);exit;
      foreach($array_merge_recursive as $notification){
        if($notification['user_type']=='customer'){
              $title ='OYLY';
              $message ='Your order for table no '.$table_no.' has been more item placed.';

              }else if($notification['user_type']=='Supervisor'){
              $title ='OYLY';
              $message ='Table no '.$table_no.' order id '.$order_id2.' has been more item placed.';
              }else if($notification['user_type']=='Admin'){
              $title ='OYLY';
              $message ='More item has been placed for table '.$table_no;
              }
              else if($notification['user_type']=='KOT'){
              $title ='OYLY';
              $message ='Table no '.$getCustmoerData[0]['table_no'].' ('.$waiterData[0]['name'].')'.' Confirmed order';
              }
               $result=sendPushNotification($title,$message,$notification['notification_id']);
               if(!empty($result))
              {
                $array=array(
                'send_from'=>$waiter_mobile_no,
                'staff_mobile_no'=>$notification['mobile_no'],
                'admin_id'=>$admin_id,
                'status'=>1,
                'order_id'=>$order_id2,
                'sub_order_id'=>$sub_order_id,
                'table_no'=>$getCustmoerData[0]['table_no'],
                'title'=>$title ,
                'message'=>$message,
                'user_type'=>$notification['user_type'],
                'customer_mobile_no'=>$getCustmoerData[0]['customer_mobile_no'],
                'date_time'=>date('Y-m-d H:i:s')
                ); 
                if(!empty($array))
                {
                  $this->Supervisor->insertNotification($array);                          
                }  
        }  
      }

    $data2->status ='1';
    $data2->message = 'Order Confirmed';
    array_push($result2,$data2);
    $response->data = $data2;
  }
  else
  {
    $data2->status ='0';
    $data2->message = 'failed';
    array_push($result2,$data2);
    $response->data = $data2;
  }
  echo  json_output($response);
}

/*.........Role Api For Restaurant ---- */

/*.........get_order_detail_for_restaurant Restaurant Api  ---- */
public function get_order_detail_for_restaurant_post()
{

  $response         =new StdClass();
  $result           =array();
  $result2          =array();
  $finalarray       =array();
  $admin_id=$this->input->post('admin_id');
  $order_status=$this->input->post('order_status');
  $mobile_no=$this->input->post('mobile_no');
  $table_no=ltrim($this->input->post('table_no'),'0');

  if(!empty($mobile_no))
  {
    
    $role=$this->Supervisor->getEmpRole($mobile_no,$admin_id);

    if($role='Waiter')
    {

      if(!empty($table_no))
      {
              $data = $this->Supervisor->getGroupData2($admin_id,$order_status,$table_no);
        }else
        {
           // print_r($mobile_no);exit;
           $data = $this->Supervisor->getGroupData3($admin_id,$order_status,$mobile_no);
        }
      
    }else
    {
      $data = $this->Supervisor->getGroupData($admin_id,$order_status);
    }
  }else
  {
     $data = $this->Supervisor->getGroupData($admin_id,$order_status);
  }
  $arr = array();
  $arr2 = array();
  $menuImages = array();
  $menuquantity = array();
  $menuhalf_and_full_status = array();
  $menumenu_price = array();
  if(empty($data))
  {
    $response->status = 0;
    $response->message = "failed";

  }
  else
  {
   $response->status = 1;
   $response->message = "success";
   for($i=0;$i<count($data);$i++)
   {
    // $result['id']           =$data[$i]['id'];
    $result['order_id']     =$data[$i]['order_id'];
    $result['table_no']     =$data[$i]['table_no'];
    $result['order_status'] =$data[$i]['order_status'];
    $result['admin_id']     =$data[$i]['admin_id'];
    $result['new_order_id'] =str_replace($data[$i]['admin_id'].'-','',$data[$i]['order_id']);
    $menuResult =$this->Supervisor->getMenuItemForOrder($data[$i]['order_id'],$admin_id);
    foreach($menuResult as $menuValue)
    {
      $menuImages[]               =$menuValue['menu_item_name'];
      $menuquantity[]             =$menuValue['quantity'];
      $menuhalf_and_full_status[] =$menuValue['half_and_full_status'];
      $menumenu_price[]           =$menuValue['menu_price'];
      $menumenu_id[]              =$menuValue['id'];
      $menumenu_status[]          =$menuValue['status'];
      $menu_order_id[]            =$data[$i]['order_id'];
      $menu_order_table[]         =$data[$i]['table_no'];
      $main_order_status[]        =$data[$i]['status'];
    }
    // print_r(implode(',',$menuImages).',');exit;
    $result['menu_order_id']                =implode(',',$menu_order_id).',';
    $result['menu_order_table']                =implode(',',$menu_order_table).',';
    $result['id']                =implode(',',$menumenu_id).',';
    $result['menu_item_name']       =implode(',',$menuImages).',';
    $result['item_status']       =implode(',',$menumenu_status).',';
                // $result['menu_item_name']       =$data[$i]['menu_item_name'];
    $result['cus_id']       =$data[$i]['cus_id'];
    $result['quantity']       =implode(',',$menuquantity).',';
    $result['half_and_full_status']       =implode(',',$menuhalf_and_full_status).',';
    $result['main_order_status']       =implode(',',$main_order_status).',';
    $result['menu_price']       =implode(',',$menumenu_price).',';
    $result['total_item']       =$data[$i]['total_item'];
    $result['net_pay_amount']       =$data[$i]['net_pay_amount'];
    $result['gst_amount']       =$data[$i]['gst_amount'];
    $result['gst_amount_price']       =$data[$i]['gst_amount_price'];
    $result['order_status']       =$data[$i]['order_status'];
    $result['waiter_mobile_no']       =$mobile_no;
    $result['customer_mobile_no']       =$data[$i]['customer_mobile_no'];
    $result['create_slip_by']       =$data[$i]['create_slip_by'];
    $result['order_complete_by']       =$data[$i]['order_complete_by'];
    $result['order_delete_by']       =$data[$i]['order_delete_by'];
    $result['date']       =$data[$i]['date'];
    $result['modified_date']       =$data[$i]['modified_date'];
    $result['slip_status']       =$data[$i]['slip_status'];
    $result['payment_status']       =$data[$i]['payment_status'];
    $result['notification_status_by_staff']       =$data[$i]['notification_status_by_staff'];
    $result['NS_for_complete_by_waiter']       =$data[$i]['NS_for_complete_by_waiter'];
    $result['NS_for_kot_for_staff']       =$data[$i]['NS_for_kot_for_staff'];
    $result['NS_for_kitchen_for_staff']       =$data[$i]['NS_for_kitchen_for_staff'];
    $result['NS_for_complete_by_chef']       =$data[$i]['NS_for_complete_by_chef'];
    $result['NS_for_kitchen_for_waiter']       =$data[$i]['slip_status'];
    $result['notification_status_by_customer']       =$data[$i]['notification_status_by_customer'];
    $result['NS_for_complete_by_waiter_for_customer']       =$data[$i]['NS_for_complete_by_waiter_for_customer'];
    $result['NS_for_kot_for_customer']       =$data[$i]['NS_for_kot_for_customer'];
    $result['NS_for_kitchen_for_customer']       =$data[$i]['NS_for_kitchen_for_customer'];
    $result['payment_by']       =$data[$i]['payment_by'];
    $result['get_payment']       =$data[$i]['get_payment'];
    $result['status']       =$data[$i]['status'];
    $result['total_price']       =$data[$i]['total_price'];
    $result['discount']           =$data[$i]['discount'];

    $subOrderRes=$this->Supervisor->getSubOrder($data[$i]['order_id'],$admin_id);
    if(!empty($subOrderRes))
    {
      foreach ($subOrderRes as $value)
      {
        $result2['order_id']     =$data[$i]['order_id'];
        $result2['admin_id']     =$data[$i]['admin_id'];
        $result2['sub_order_id'] =$value['sub_order_id'];
        $menuResult2 =$this->Supervisor->getMenuItemForSubOrder($data[$i]['order_id'],$admin_id,$result2['sub_order_id']);
        foreach($menuResult2 as $menuValue2)
        {
          $menuImages2[]               =$menuValue2['menu_item_name'];
          $menuquantity2[]             =$menuValue2['quantity'];
          $menuhalf_and_full_status2[] =$menuValue2['half_and_full_status'];
          $menumenu_price2[]           =$menuValue2['menu_price'];
          $menu_id[]                   =$menuValue2['id'];
          $order_data[]                =$data[$i]['order_id'];
          $sub_order_id[]              =$value['sub_order_id'];
          $sub_order_status[]          =$menuValue2['status'];
          $main_sub_order_status[]     =$value['status'];

        } 
                    // print_r(implode(',',$menuImages2).',');exit;
        $result2['menu_item_name']=implode(',',$menuImages2).',';
        $result2['order_data_id']=implode(',',$order_data).',';
        $result2['sub_order_data_array']=implode(',',$sub_order_id).',';
        $result2['sub_order_status']=implode(',',$sub_order_status).',';
        $result2['menu_item_id']=implode(',',$menu_id).',';
                    // $result2['menu_item_name']=$value['menu_item_name'];
        $result2['quantity']=implode(',',$menuquantity2).',';
        $result2['half_and_full_status']=implode(',',$menuhalf_and_full_status2).',';
        $result2['main_sub_order_status']=implode(',', $main_sub_order_status).',';
        $result2['menu_price']=implode(',',$menumenu_price2).',';
        $result2['total_item']       =$value['total_item'];
        $result2['net_pay_amount']       =$value['net_pay_amount'];
        $result2['gst_amount']       =$value['gst_amount'];
        $result2['gst_amount_price']       =$value['gst_amount_price'];
        $result2['order_status']       =$value['order_status'];
        $result2['waiter_mobile_no']       =$value['waiter_mobile_no'];
        $result2['customer_mobile_no']       =$value['customer_mobile_no'];
        $result2['create_slip_by']       =$value['create_slip_by'];
        $result2['order_complete_by']       =$value['order_complete_by'];
        $result2['order_delete_by']       =$value['order_delete_by'];
        $result2['date']       =$value['date'];
        $result2['modified_date']       =$value['modified_date'];
        $result2['slip_status']       =$value['slip_status'];
        $result2['payment_status']       =$value['payment_status'];
        $result2['notification_status_by_staff']       =$value['notification_status_by_staff'];
        $result2['NS_for_complete_by_waiter']       =$value['NS_for_complete_by_waiter'];
        $result2['NS_for_kot_for_staff']       =$value['NS_for_kot_for_staff'];
        $result2['NS_for_kitchen_for_staff']       =$value['NS_for_kitchen_for_staff'];
        $result2['NS_for_complete_by_chef']       =$value['NS_for_complete_by_chef'];
        $result2['NS_for_kitchen_for_waiter']       =$value['slip_status'];
        $result2['notification_status_by_customer']       =$value['notification_status_by_customer'];
        $result2['NS_for_complete_by_waiter_for_customer']       =$value['NS_for_complete_by_waiter_for_customer'];
        $result2['NS_for_kot_for_customer']       =$value['NS_for_kot_for_customer'];
        $result2['NS_for_kitchen_for_customer']       =$value['NS_for_kitchen_for_customer'];
        $result2['payment_by']       =$value['payment_by'];
        $result2['get_payment']       =$value['get_payment'];
        $result2['status']       =$value['status'];
        $result2['total_price']       =$value['total_price'];
        $result2['table_no']     = $data[$i]['table_no'];
        $finalarray[]=$result2;
        $menuImages2=array();
        $menuquantity2=array();
        $menuhalf_and_full_status2=array();
        $menumenu_price2=array();
        //$menumenu_status=array();
        $menu_id=array();
        $order_data=array();
        $sub_order_id=array();
        $sub_order_status=array();
        $main_sub_order_status=array();
      }

    }
    $result['sub_order_data']     =$finalarray;
    array_push($arr, $result);
    $finalarray=array();
    $menuImages=array();
    $menuquantity=array();
    $menuhalf_and_full_status=array();
    $menumenu_price=array();
    $menumenu_id=array();
    $menu_order_id=array();
    $menu_order_table=array();
    $menumenu_status=array();
    $main_order_status=array();

  }
  $response->data = $arr;

}
echo json_output($response);
}

/*.........get_order_detail_for_restaurant Restaurant Api---- */

/*.........food tyoe api for  Restaurant ---- */
public function get_food_type_post()
{
  $response   =   new StdClass();
  $result       =   array();
  $food_type = $this->Supervisor->get_food_type();
  if(!empty($food_type))
  {
   foreach ($food_type as $row)
   {
    $data['food_type'] =   $row['food_type'];
    $data['message'] = 'Success';
    $data['status']  ='1';
    array_push($result,$data);
  } 
  $response->data = $result;
}
else
{
  $data['message'] = 'failed';
  $data['status']  ='0';
  array_push($result , $data);
}
$response->data = $result;
echo json_output($response);
}
/*.........food tyoe api for  Restaurant ---- */
public function get_amenities_type_post()
{
  $response   =   new StdClass();
  $result       =   array();
  $amenities_type = $this->Supervisor->get_amenities_type();
  if(!empty($amenities_type))
  {
   foreach ($amenities_type as $row)
   {
    $data['amenities_type'] =   $row['amenities_type'];
    $data['message'] = 'Success';
    $data['status']  ='1';
    array_push($result,$data);

  } 

  $response->data = $result;
}
else
{
  $data['message'] = 'failed';
  $data['status']  ='0';
  array_push($result , $data);
}
$response->data = $result;
echo json_output($response);
}
/*........Staff list Api For Restaurant ---- */
public function get_staff_data_post()
{
  $response   =   new StdClass();
  $result       =   array();
  $admin_id=$this->input->post('admin_id');
  $staff_data = $this->Supervisor->get_staff_data($admin_id);
 // echo '<pre>'; print_r($staff_data);exit;
  if(!empty($staff_data))
  {
   foreach ($staff_data as $row)
   {
    $data['admin_id'] =   $row['admin_id'];
    $data['name'] =   $row['name'];
    $data['mobile_no'] =   $row['mobile_no'];
    $data['email'] =   $row['email'];
    $data['date_of_birth'] =   $row['date_of_birth'];
    $data['aadhar_no'] =   $row['aadhar_no'];
    $data['pan_number'] =   $row['pan_number'];
    $result2=$this->Supervisor->getUserTypeLable($row['user_type']);
    $data['desingination'] =   $result2[0]['user_type'];
    $data['gender'] =   $row['gender'];
    $data['permanent_address'] =   $row['permanent_address'];
    $data['current_address'] =   $row['current_address'];
    $data['user_type'] =   $result2[0]['user_type'];
    $data['message'] = 'Success';
    $data['status']  ='1';
    array_push($result,$data);
  } 
  $response->data = $result;
}
else
{
  $data['message'] = 'failed';
  $data['status']  ='0';
  array_push($result , $data);
}
$response->data = $result;
echo json_output($response);
}

/*........Staff list Api For Restaurant ---- */

/*....... Waiter confirm order Api for restaurant  ---- */
public function confirm_order_by_waiter_post()
{   
  $response = new StdClass();
  $result2 = new StdClass();
  $waiter_mobile_no=$this->input->post('waiter_mobile_no');
  $order_status=$this->input->post('order_status');
  $admin_id=$this->input->post('admin_id');
  $order_id=$this->input->post('order_id');
  $data->waiter_mobile_no=$waiter_mobile_no;
  $data->order_status=$order_status;
  $data->admin_id=$admin_id;
  $data->order_id=$order_id;
  $subOrderResult=$this->Supervisor->getSubOrderDetails($order_id,$admin_id);
  $result = $this->Supervisor->confirm_order_by_waiter($data);
  if(!empty($order_status))
  {  
    if(!empty($subOrderResult))
    {
      $subOrderResult=$this->Supervisor->confirmSubOrderBywaiter($order_id,$admin_id);
    }
          $waiterData         =$this->Supervisor->getwaiterData($waiter_mobile_no,'Waiter');
          $staffData          =$this->Supervisor->getStaffNotification($admin_id,'KOT');
          $getCustmoerData    =$this->Supervisor->getCustmoerData($order_id,$admin_id);
          $order_id2          =str_replace($admin_id.'-','',$order_id);
          $title              ='Order Status';
          $message            ='Table No '.$getCustmoerData[0]['table_no'].' ('.$waiterData[0]['name'].')'.' Confirmed order';
          $result=sendPushNotification($title,$message,$staffData[0]['notification_id']);
                if(!empty($result))
                {
                  $array=array(
                                'send_from'=>$waiter_mobile_no,
                                'staff_mobile_no'=>$staffData[0]['mobile_no'],
                                'admin_id'=>$admin_id,
                                'status'=>1,
                                'order_id'=>$order_id2,
                                'table_no'=>$getCustmoerData[0]['table_no'],
                                'title'=>$title ,
                                'message'=>$message,
                                'user_type'=>'KOT',
                                'customer_mobile_no'=>$getCustmoerData[0]['customer_mobile_no'],
                                'date_time'=>date('Y-m-d H:i:s')
                                ); 
                                if(!empty($array))
                                {
                                  $this->Supervisor->insertNotification($array);                          
                                }  
                }  
          $data2->status ='1';
          $data2->message = 'Success';
          array_push($result2,$data2);
          $response->data = $data2;                 
  }
  else
  {
    $data2->status ='0';
    $data2->message = 'failed';
    array_push($result2,$data2);
    $response->data = $data2;
  }

  echo  json_output($response);
}
/*....... Waiter confirm order Api for restaurant  ---- */

/*....... BLE Brodcast api for  restaurant  ---- */
public function BLE_brodcast_for_restaurant_post()
{   
  $response = new StdClass();
  $result2 = new StdClass();

  $admin_id=$this->input->post('admin_id');
  $BLE_id=$this->input->post('BLE_id');

  $data->admin_id=$admin_id;
  $data->BLE_id=$BLE_id;
  $result = $this->Supervisor->BLE_brodcast_for_restaurants($data);


  if(!empty($BLE_id))
  {  
    $data2->status ='1';
    $data2->message = 'Success';
    array_push($result2,$data2);
    $response->data = $data2;
  }
  else
  {
    $data2->status ='0';
    $data2->message = 'failed';
    array_push($result2,$data2);
    $response->data = $data2;
  }


  echo  json_output($response);
}
/*....... BLE Brodcast api for  restaurant  ---- */

/*.......Order compelete by supervisor Api for restaurant  ---- */
public function order_complete_by_supervisor_post()
{   
  $response = new StdClass();
  $result2 = new StdClass();
  $supervisor_mobile_no=$this->input->post('supervisor_mobile_no');
  $admin_id=$this->input->post('admin_id');
  $order_id=$this->input->post('order_id');

  $data->supervisor_mobile_no=$supervisor_mobile_no;
  $data->admin_id=$admin_id;
  $data->order_id=$order_id;
  $result = $this->Supervisor->complete_order_by_supervisor($data);


  if(!empty($order_id))
  {  

    $data2->status ='1';
    $data2->message = 'Success';
    array_push($result2,$data2);
    $response->data = $data2;
  }
  else
  {
    $data2->status ='0';
    $data2->message = 'failed';
    array_push($result2,$data2);
    $response->data = $data2;
  }


  echo  json_output($response);
}
/*....... Waiter confirm order Api for restaurant  ---- */
public function order_complete_by_chef_post()
{   
  $response = new StdClass();
  $result2 = new StdClass();
  $chef_mobile_no=$this->input->post('chef_mobile_no');
  $admin_id=$this->input->post('admin_id');
  $order_id=$this->input->post('order_id');
  $orderResult=$this->Supervisor->getOrderByChef($order_id,$admin_id);
  $data->chef_mobile_no=$chef_mobile_no;
  $data->admin_id=$admin_id;
  $data->order_id=$order_id;
  $result = $this->Supervisor->complete_order_by_chef($data);       
  if(!empty($order_id) and !empty($chef_mobile_no) and !empty($admin_id))
  {  

    if(!empty($orderResult))
    {
      $this->Supervisor->readyToserveOrder($order_id,$admin_id);
    }
          $waiter_mobile_no      =$this->Supervisor->getWaiterMobileNo($order_id,$admin_id);
          $notification_data     =$this->Supervisor->getWaiterNotification($waiter_mobile_no);
          $getCustmoerData       =$this->Supervisor->getCustmoerData($order_id,$admin_id);
          $custData              =$this->Supervisor->getCustData($getCustmoerData[0]['customer_mobile_no']);
          $order_id2             =str_replace($admin_id.'-','',$order_id);
          $array_merge_recursive=array_merge_recursive($notification_data,$custData);
          // echo "<pre>";print_r($array_merge_recursive);exit;
          foreach($array_merge_recursive as $notification){
             // echo "<pre>";print_r($notification['user_type']);exit;
            if($notification['user_type']=='customer'){
                $title ='Enjoy your meal';
                $message ='Your order ready to serve.';
                $result=sendPushNotification($title,$message,$notification['notification_id']);
                if($result)
                {
                    $array=array(
                                'send_from'=>$chef_mobile_no,
                                'staff_mobile_no'=>$notification['mobile_no'],
                                'admin_id'=>$admin_id,
                                'status'=>1,
                                'order_id'=>$order_id2,
                                'table_no'=>$getCustmoerData[0]['table_no'],
                                'title'=>$title ,
                                'message'=>$message,
                                'user_type'=>$notification['user_type'],
                                'customer_mobile_no'=>$getCustmoerData[0]['customer_mobile_no'],
                                'date_time'=>date('Y-m-d H:i:s')
                                 ); 
                                if(!empty($array))
                                {
                                  $this->Supervisor->insertNotification($array);                          
                                } 
                }
                }else if($notification['user_type']=='Waiter'){
                $title ='OYLY';
                $message ='table No '.$getCustmoerData[0]['table_no'].' order ready to serve.';
                $result=sendPushNotification($title,$message,$notification['notification_id']);
                if($result)
                {
                    $array=array(
                                'send_from'=>$chef_mobile_no,
                                'staff_mobile_no'=>$notification['mobile_no'],
                                'admin_id'=>$admin_id,
                                'status'=>1,
                                'order_id'=>$order_id2,
                                'table_no'=>$getCustmoerData[0]['table_no'],
                                'title'=>$title ,
                                'message'=>$message,
                                'user_type'=>$notification['user_type'],
                                'customer_mobile_no'=>$getCustmoerData[0]['customer_mobile_no'],
                                'date_time'=>date('Y-m-d H:i:s')
                                 ); 
                                if(!empty($array))
                                {
                                  $this->Supervisor->insertNotification($array);                          
                                } 
                }
                }
            }
    $data2->status ='1';
    $data2->message = 'Success';
    array_push($result2,$data2);
    $response->data = $data2;
  }
  else
  {
    $data2->status ='0';
    $data2->message = 'failed';
    array_push($result2,$data2);
    $response->data = $data2;
  }
  echo  json_output($response);
}

/*....... create slip for supervisor Api for restaurant  ---- */
public function create_slip_supervisor_for_chef_post()
{   
  $response = new StdClass();
  $result2 = new StdClass();
  $data2 = new StdClass();
  $order_id=$this->input->post('order_id');
  $admin_id=$this->input->post('admin_id');
  $mobile_no=$this->input->post('mobile_no');
  $data->admin_id=$admin_id;
  $data->order_id=$order_id;
  $data->mobile_no=$mobile_no;
  $result = $this->Supervisor->create_slip($data);
  $result4=$this->Supervisor->getSubOrderChef($order_id,$admin_id,'Confirm');  
  
  if(!empty($order_id))
  {  

    // if(!empty($result4))
    // {

          $this->Supervisor->prepareAllOrder($order_id,$admin_id,'Confirm');
          $order_id2               =str_replace($admin_id.'-','',$order_id);
          $title                    ='Order Status';
          $staffData                =$this->Supervisor->getStaffNotification($admin_id,'Chef');
          $getCustmoerData          =$this->Supervisor->getCustmoerData($order_id,$admin_id);         
          // print_r($getCustmoerData);exit;
          $waiterData               =$this->Supervisor->getwaiterData($mobile_no,'KOT');
          $message                  ='Table No '.$getCustmoerData[0]['table_no'].' Order Id '.$order_id2.' '.$waiterData[0]['name'].' created slip';
          // print_r( $message);exit;
          $result3=sendPushNotification($title,$message,$staffData[0]['notification_id']);
          if(!empty($result3))
          {
             $array=array(

                          'send_from'=>$mobile_no,
                          'staff_mobile_no'=>$staffData[0]['mobile_no'],
                          'admin_id'=>$admin_id,
                          'status'=>1,
                          'order_id'=>$order_id2,
                          'table_no'=>$getCustmoerData[0]['table_no'],
                          'title'=>$title ,
                          'message'=>$message,
                          'user_type'=>'Chef',
                          'customer_mobile_no'=>$getCustmoerData[0]['customer_mobile_no'],
                          'date_time'=>date('Y-m-d H:i:s')
                           ); 
                          if(!empty($array))
                          {
                            $this->Supervisor->insertNotification($array);                          
                          }  
          }
    // }

    $data2->status ='1';
    $data2->message = 'Success';
    array_push($result2,$data2);
    $response->data = $data2;
   
  }
  else
  {
    
    $data2->status ='0';
    $data2->message = 'failed';
    array_push($result2,$data2);
    $response->data = $data2;
  }
  
  echo  json_output($response);
}
/*.......create slip for supervisor Api for restaurant  ---- */


/*....... delete order  Api for restaurant  ---- */
public function delete_order_for_restaurant_post()
{   
  $response = new StdClass();
  $result2 = new StdClass();
  $order_id=$this->input->post('order_id');
  $admin_id=$this->input->post('admin_id');
  $mobile_no=$this->input->post('mobile_no');
  $data->admin_id=$admin_id;
  $data->order_id=$order_id;
  $data->mobile_no=$mobile_no;
  $result = $this->Supervisor->delete_order($data);
  // print_r($result);exit;
  if(!empty($result))
  {  
    $this->Supervisor->deletedSubOrder($order_id,$admin_id);
    $this->Supervisor->deletedOrderWithMenu($order_id,$admin_id);
    $this->Supervisor->deletedSubOrderWithMenu($order_id,$admin_id);
    $getCustmoerData    =$this->Supervisor->getCustmoerData($order_id,$admin_id);
    $custData           =$this->Supervisor->getCustData($getCustmoerData[0]['customer_mobile_no']);
    $adminData          =$this->Supervisor->getAdminData($admin_id);
    $supervisorData     =$this->Supervisor->getSupervisordata($admin_id);
    $role=$this->Supervisor->getEmpRole($mobile_no,$admin_id);
    if($role=='Supervisor'){
      $notificationData   =$this->Supervisor->getRestaurantStaffNotification($admin_id,$getCustmoerData[0]['confirm_order_by']);
    }else{
      $notificationData   =$this->Supervisor->getRestaurantStaffNotification($admin_id,$getCustmoerData[0]['order_delete_by']);
    }
    $order_id2          =str_replace($admin_id.'-','',$order_id);
    $notification_data=array_merge_recursive($custData,array_merge_recursive($notificationData,$adminData),$supervisorData);
    // echo "<Pre>";print_r($notification_data);exit;
    $title ='Order Status';
    $message = 'Order id '.$order_id2.' has been denied';
    foreach( $notification_data as $notification){
      $result=sendPushNotification($title,$message,$notification['notification_id']);
      if(!empty($result))
        {
          $array=array(
                          'send_from'=>$mobile_no,
                          'staff_mobile_no'=>$notification['mobile_no'],
                          'admin_id'=>$admin_id,
                          'status'=>1,
                          'order_id'=>$order_id2,
                          'table_no'=>$getCustmoerData[0]['table_no'],
                          'title'=>$title,
                          'message'=>$message,
                          'user_type'=>$notification['user_type'],
                          'customer_mobile_no'=>$getCustmoerData[0]['customer_mobile_no'],
                          'date_time'=>date('Y-m-d H:i:s')
                         ); 
                        if(!empty($array))
                        {
                          $this->Supervisor->insertNotification($array);                          
                        } 
        }
    }
    $data2->status ='1';
    $data2->message = 'Order declined';
    array_push($result2,$data2);
    $response->data = $data2;
  }
  else
  {
    $data2->status ='0';
    $data2->message = 'failed';
    array_push($result2,$data2);
    $response->data = $data2;
  }


  echo  json_output($response);
}
/*.......create slip for supervisor Api for restaurant  ---- */
/*.........Update  payment status for restaurant---- */
public function update_payment_for_customer_by_staff_post()
{
  $response               =new StdClass();
  $result                 =new StdClass();
  $order_id               =$this->input->post('order_id');
  $admin_id               =$this->input->post('admin_id');
  $payment_status         ='1';
  $payment_by             =$this->input->post('payment_by');
  $get_payment            =$this->input->post('get_payment');
  $data->order_id         =$order_id;
  $data->admin_id         =$admin_id;
  $data->payment_status   =$payment_status;
  $data->payment_by       =$payment_by;
  $data->order_closed_by  =$payment_by;
  // $data->get_payment      =$get_payment;
  $res1 =$this->Supervisor->update_payment_status_by_staff($data,$get_payment);
  $res2 =$this->Supervisor->update_payment_status_by_staff2($data,$get_payment);

  if($order_id!='')
  {  
          $title              ='Payment Status';
          $order_date         =date('Y-m-d',strtotime($this->Supervisor->getOrderDate($order_id)));
          $admin_id           =$this->Supervisor->getAdmin($order_id);
          $adminData          =$this->Supervisor->getAdminData($admin_id);
          $getCustmoerData    =$this->Supervisor->getCustmoerData($order_id,$admin_id);
          $order_id2         =str_replace($admin_id.'-','',$order_id);
          $custData          =$this->Supervisor->getCustData($getCustmoerData[0]['customer_mobile_no']);
          $waiterData        =$this->Supervisor->getRestaurantStaffNotification($admin_id,$getCustmoerData[0]['confirm_order_by']);
          $array_merge_recursive=array_merge_recursive($adminData,array_merge_recursive($custData,$waiterData));
          // echo '<pre>';print_r($array_merge_recursive);exit;
          foreach($array_merge_recursive as $notification)
              {
                if($notification['user_type']=='customer'){
                $title ='Payment status';
                $message ='Payment is successfull';
                }else if($notification['user_type']=='Waiter'){
                $title ='Payment status';
                $message ='Payment done from table no '.$getCustmoerData[0]['table_no'];
                }else if($notification['user_type']=='Admin'){
                $title ='Payment status';
                $message ='Payment is successfull';
                }
                $result=sendPushNotification($title,$message,$notification['notification_id']);
                if($result)
                {
                  $array[]=array(
                          'mobile_no'=>$notification['mobile_no'],
                          'admin_id'=>$admin_id,
                          'status'=>1,
                          'order_id'=>$order_id2,
                          'table_no'=>$getCustmoerData[0]['table_no'],
                          'title'=>$title,
                          'message'=>$message,
                          'user_type'=>$notification['user_type'],
                          'customer_mobile_no'=>$getCustmoerData[0]['customer_mobile_no'],
                          'date_time'=>date('Y-m-d H:i:s')
                     ); 
                }
              } 
      if(!empty($array)){
        $this->Customer->insertNotification($array);
      }
      
    $data1->status = '1';
    $data1->message = 'Success';
    array_push($result,$data1);
    $response->data = $data1;
  }
  else
  {
   $data1->status = '0';
   $data1->message = 'failed';
   array_push($result,$data1);
   $response->data = $data1;
 }  
 echo json_output($response);
}
      /*..........Update  payment status for restaurant- ---- *


      /*......... logout Api For staff  ---- */
      public function data_logout_for_staff_post()
      {
        $response = new StdClass();
        $result = array();
        $device_id =$this->input->post('device_id');
        $mobile_no =$this->input->post('mobile_no');
        date_default_timezone_set('Asia/kolkata'); 
        $now = date('Y-m-d H:i:s');
        $data->mobile_no = $mobile_no;
        $data->device_id = $device_id;
        $data->logout_time=$now;
        $resdata1 = $this->Supervisor->logout_staff_data($data);
        if(!empty($mobile_no) and !empty($mobile_no))
        {
          $data1->status ='1';
          $data1->message='logout success';
          array_push($result,$data1);
          $response->data = $data1;
        }
        else
        {
          $data1->status ='0';
          $data1->message ='logout failed';
          array_push($result,$data1);
          $response->data = $data1;
        }
        echo json_output($response);
      }

      /*......... logout data From Wifi-module Api For Door Unlock ---- */


      /*.........Verification OTP Api For Hawker  ---- */
      public function verification_otp_data_post()
      {
        $response   =   new StdClass(); 
        $result       =  new StdClass();
        date_default_timezone_set('Asia/kolkata'); 
        $mobile_no =$this->input->post('mobile_no');
        $device_id =$this->input->post('device_id');
        $otp =$this->input->post('otp');
        $data1->device_id = $device_id;
        $data1->mobile_no = $mobile_no;
        $data1->otp=$otp;
        $now=date('Y-m-d H:i:s');
        $dataotp = $this->Supervisor->verification_otp($data1);
        $modified_date=$dataotp->modified_date;

        $minutes=round(abs((strtotime($now)-strtotime($modified_date))))/60;

        if(!empty($dataotp))
        { 
          $query= $this->db->get_where('tbl_admin', array('mobile_no' => $mobile_no , 'status' => '1'));
          $query1= $this->db->get_where('tbl_restaurant_staff_registration', array('mobile_no' => $mobile_no , 'status' => '1'));
          $num_rows=$query->num_rows();
          $current_data=$query->result_array();
          $num_rows1=$query1->num_rows();
          $current_data1=$query1->result_array();
                    if(!empty($current_data))
                    {
                     foreach ($current_data as $row)
                     { 
                      $user_type='Admin';
                      $data['admin_id'] =  $row['admin_id'];
                      $data['name'] =  $row['user_fullname'];
                      $data['mobile_no'] =  $row['mobile_no'];
                      $data['user_type'] =  $user_type;
                      $result2=$this->Supervisor->getUserTypeLable($user_type);
                      $data['user_lable'] = $result2[0]['user_type'];
                    }
                  }
                  else if(!empty($current_data1))
                  {
                    foreach ($current_data1 as $row1)
                    { 

                      $user_type=$row1['user_type'];
                      $data['admin_id'] =  $row1['admin_id'];
                      $data['name'] =  $row1['name'];
                      $data['mobile_no'] =  $row1['mobile_no'];
                      $data['user_type'] =  $user_type;
                      $result2=$this->Supervisor->getUserTypeLable($user_type);
                      $data['user_lable'] = $result2[0]['user_type'];
                    }
                  }else{

                   $data['admin_id'] = '';
                   $data['name'] = '';
                   $data['mobile_no'] =$mobile_no;
                   $data['user_type'] ='';
                   $data['user_lable'] ='';
                 }
       if($minutes > 2)
       {
         $data['message'] = 'Otp has expired';
         $data['status']= '0';
         array_push($result,$data);
         $response->data = $data;
       }else
       {
         $data['message'] = 'success';
         $data['status']= '1';
         array_push($result,$data);
         $response->data = $data;
        
       }
     }
     else
     {
       $data['message'] = 'Invalid Otp';
       $data['status']= '0';
       array_push($result,$data);
       $response->data = $data;
     }  
     echo json_output($response);
   }




   /*.........Resend OTP Api For Hawker  ---- */
   public function resend_otp_data_post()
   {
    $response   =   new StdClass();
    $result       =  new StdClass();
    $device_id =$this->input->post('device_id');
    $mobile_no =$this->input->post('mobile_no');
    // $otpValue=mt_rand(1000, 9999);
    $otpValue=1234;
    $data1->device_id = $device_id;
    $data1->mobile_no=$mobile_no;
    $data1->otp=$otpValue;
    $res3 = $this->Supervisor->send_otp($mobile_no,$otpValue);
    if(!empty($mobile_no))
    {
     $res1 = $this->Supervisor->resend_otp($data1);

     $data->message = 'success';
     $data->status = '1';
     array_push($result,$data);
     $response->data = $data;
   }

   else
   {
     $data->message = 'failed';
     $data->status = '0';
     array_push($result,$data);
     $response->data = $data;
   }  
   echo json_output($response);
 }

 /*.........Resend OTP Api For Hawker  ---- */



 /*.........Remove OTP Api For Hawker  ---- */
 public function otp_expire_post()
 {
  $response   =   new StdClass();
  $result       =  new StdClass();
  $device_id =$this->input->post('device_id');
  $mobile_no =$this->input->post('mobile_no');
  $data1->device_id = $device_id;
  $data1->mobile_no=$mobile_no;
  $res = $this->Supervisor->remove_otp($data1);
  if(!empty($mobile_no))
  {
    $data->message = 'success';
    $data->status = '1';
    array_push($result,$data);
    $response->data = $data;
  }
  else
  {
    $data->message = 'failed';
    $data->status = '0';
    array_push($result,$data);
    $response->data = $data;
  }  
  echo json_output($response);
}

/*.........Remove OTP Api For Hawker  ---- */


/*......... Get Check Version data   ---- */
public function remove_staff_post()
{
  $response = new StdClass();
  $result2 = new StdClass();
  $mobile_no = $this->input->post('mobile_no');
  $user_type = $this->input->post('user_type');
  $res = $this->Supervisor->remove_staff($mobile_no,($user_type=='Waiting Staff'?'Waiter':$user_type));
  if($mobile_no!='')
  {
    $data1->status ='1';
    $data1->message = 'success';
    array_push($result2,$data1);
    $response->data = $data1;
  }

  else
  {
    $data1->status ='0';
    $data1->message = 'failed';
    array_push($result2,$data1);
    $response->data = $data1;
  }
  echo json_output($response);
}
/*......... Get Check Version data  ---- */

/*......... Remove menu item by staff   ---- */
public function remove_menu_item_by_staff_post()
{
  $response = new StdClass();
  $result2 = new StdClass();
  $menu_id = $this->input->post('menu_id');
  $admin_id = $this->input->post('admin_id');
  $res = $this->Supervisor->remove_menu_item_staff($menu_id,$admin_id);
  if($menu_id!='')
  {
    $data1->status ='1';
    $data1->message = 'success';
    array_push($result2,$data1);
    $response->data = $data1;
  }

  else
  {
    $data1->status ='0';
    $data1->message = 'failed';
    array_push($result2,$data1);
    $response->data = $data1;
  }
  echo json_output($response);
}
/*......... Remove menu item by staff ---- */


public function show_order_by_count_post()
{
  $response =   new StdClass();
  $result       =  new StdClass();
  $admin_id =$this->input->post('admin_id');
  $order_status =$this->input->post('order_status');
  $resdata = $this->Supervisor->check_total_count($admin_id,$order_status);
  if($resdata>0)
  {    
    $data1->count=$resdata;
    $data1->status ='1';
    array_push($result,$data1);
    $response->data = $data1;
  }
  else if($resdata==0)
  {
    $data1->count ='0';
    $data1->status = '1';
    array_push($result,$data1);
    $response->data = $data1;
  }
  else 
  {
    $data1->status ='0';
    $data1->message = 'failed';
    array_push($result,$data1);
    $response->data = $data1;
  }

  echo json_output($response);
}
/*.........notification list for staff in  Restaurant ---- */
public function get_notification_list_for_order_post()
{
  $response   =   new StdClass();
  $result       =   array();
  $staff_mobile_no=$this->input->post('staff_mobile_no');
  $admin_id=$this->input->post('admin_id');
  $user_type=$this->input->post('user_type');
  $get_notification_data1 = $this->Supervisor->get_notification_data($staff_mobile_no,$user_type);
  $get_notification_data2 = $this->Supervisor->get_notification_data2($staff_mobile_no,$user_type);
  $get_notification_data  = array_merge_recursive($get_notification_data1,$get_notification_data2);
 // echo "<pre>";print_r($get_notification_data);exit;
  if(!empty($get_notification_data))
  {
   foreach ($get_notification_data as $row)
   {
    $data['order_id']=$row['order_id'];
    $data['customer_mobile_no'] =   $row['customer_mobile_no'];
    $data['date_time'] =   $row['date_time'];
    $data['title'] =   $row['title'];
    $data['message'] =   $row['message'];
    $data['status']  ='1';
    array_push($result,$data);
  } 

  $response->data = $result;
}
else
{
  $data['message'] = 'failed';
  $data['status']  ='0';
  array_push($result , $data);
}
usort($result, function($a, $b) {
    return [$a['date_time']]<=[$b['date_time']];
});
// echo '<pre>';print_r($result);exit;
$response->data = $result;
echo json_output($response);
}
public function show_notification_by_count_post()
{
  $response =   new StdClass();
  $result       =  new StdClass();
  $staff_mobile_no=$this->input->post('staff_mobile_no');
  $admin_id=$this->input->post('admin_id');
  $user_type=$this->input->post('user_type');
  $resdata1 = $this->Supervisor->check_total_count_notifications($staff_mobile_no,$user_type);
  $resdata2 = $this->Supervisor->check_total_count_notifications2($staff_mobile_no,$user_type);
  $resdata=$resdata2+$resdata1;
  if($resdata>0)
  {    
    $data1->count=$resdata;
    $data1->status ='1';
    array_push($result,$data1);
    $response->data = $data1;
  }
  else if($resdata==0)
  {
    $data1->count ='';
    $data1->status = '1';
    array_push($result,$data1);
    $response->data = $data1;
  }
  else 
  {
    $data1->status ='0';
    $data1->message = 'failed';
    array_push($result,$data1);
    $response->data = $data1;
  }

  echo json_output($response);
}


public function update_notification_status_by_restaurant_post()
{
  $response =   new StdClass();
  $result       =  new StdClass();
  $staff_mobile_no =$this->input->post('staff_mobile_no');
  $check_status =$this->input->post('check_status');
  $admin_id=$this->input->post('admin_id');
  $user_type=$this->input->post('user_type');
  if($check_status=='1' and $staff_mobile_no!='')
  {
  $this->Supervisor->check_status_for_notifications($check_status,$staff_mobile_no,$user_type);
  $this->Supervisor->check_status_for_notifications2($check_status,$staff_mobile_no,$user_type);
   $data1->status ='1';
   $data1->message = 'success';
   array_push($result,$data1);
   $response->data = $data1;
 }
 else 
 {
  $data1->status ='0';
  $data1->message = 'failed';
  array_push($result,$data1);
  $response->data = $data1;
}

echo json_output($response);
}


public function gst_amount_detail_for_staff_post()
{
  $response   =   new StdClass();
  $result       =  new StdClass();
  $dataotp = $this->Supervisor->get_gst_amount();
  $gst_amount=$dataotp->gst_amount;
  if(!empty($dataotp))
  {

    $data->gst_percentage=$gst_amount;
    $data->message = 'success';
    $data->status = '1';
    array_push($result,$data);
    $response->data = $data;
  }

  else
  {
    $data->message = 'failed';
    $data->status = '0';
    array_push($result,$data);
    $response->data = $data;
  }  
  echo json_output($response);
}
public function update_sub_order_by_waiter_post()
{
  $waiter_mobile_no=$this->input->post('waiter_mobile_no');
  $order_id=$this->input->post('order_id');
  $admin_id=$this->input->post('admin_id');
  $sub_order_id=$this->input->post('sub_order_id');
  $sub_order_status=$this->input->post('sub_order_status');

  $status=$this->Supervisor->getOrderStatusByWaiter($order_id,$admin_id);
 
  if($sub_order_status=='Rejected')
  {
    $order_array=array(
      'waiter_mobile_no'=>$waiter_mobile_no,
      'order_status'=>$sub_order_status,
      'order_delete_by'=>$waiter_mobile_no,
      'status'=>($sub_order_status=='Confirm'?'2':'0')
    );
    $result=$this->Supervisor->updateSubOrderByWaiter($order_array,$order_id,$admin_id,$sub_order_id);
    $SubOrderTotalItemResult=$this->Supervisor->getSubOrderTotalItemPrice($order_id,$admin_id,$sub_order_id);
    $menu_price=$SubOrderTotalItemResult[0]['menu_price'];
    $quantity=$SubOrderTotalItemResult[0]['quantity'];
     

     $SubOrderPriceResult   =$this->Supervisor->getSubOrderPrice($order_id,$admin_id,$sub_order_id);
     $total_item            =$SubOrderPriceResult[0]['total_item'];         
     $total_price           =$SubOrderPriceResult[0]['total_price'];         
     $net_pay_amount        =$SubOrderPriceResult[0]['net_pay_amount'];         
     $gst_amount            =$SubOrderPriceResult[0]['gst_amount'];         
     $gst_amount_price      =$SubOrderPriceResult[0]['gst_amount_price']; 

     $netGst                =$menu_price*$gst_amount/100;


     $subOrserArray=array('total_item'=>($total_item- $quantity),'total_price'=>($total_price-$menu_price),'gst_amount_price'=>($gst_amount_price-$netGst),'net_pay_amount'=>($net_pay_amount-$netGst));

    $this->Supervisor->updateSubOrderAmount($subOrserArray,$order_id,$admin_id,$sub_order_id);

    if(!empty($result))
    {
      $this->Supervisor->deletedSubOrderWithMenu2($order_id,$admin_id,$sub_order_id);
      $getCustmoerData    =$this->Supervisor->getCustmoerData($order_id,$admin_id);
      $custData           =$this->Supervisor->getCustData($getCustmoerData[0]['customer_mobile_no']);
      $adminData          =$this->Supervisor->getAdminData($admin_id);
      $supervisorData     =$this->Supervisor->getSupervisordata($admin_id);
      $notificationData   =$this->Supervisor->getRestaurantStaffNotification($admin_id,$getCustmoerData[0]['order_delete_by']);
      $order_id2         =str_replace($admin_id.'-','',$order_id);
      $notification_data=array_merge_recursive($custData,array_merge_recursive($notificationData,$adminData),$supervisorData);
      // echo "<Pre>";print_r($notification_data);exit;
      $title ='Sub Order Status';
      $message = 'Order id '.$order_id2.' has been denied';
      foreach( $notification_data as $notification){
        $result=sendPushNotification($title,$message,$notification['notification_id']);
        if(!empty($result))
          {
            $array=array(
                            'send_from'=>$mobile_no,
                            'staff_mobile_no'=>$notification['mobile_no'],
                            'admin_id'=>$admin_id,
                            'status'=>1,
                            'order_id'=>$order_id,
                            'table_no'=>$getCustmoerData[0]['table_no'],
                            'title'=>$title,
                            'message'=>$message,
                            'user_type'=>$notification['user_type'],
                            'customer_mobile_no'=>$getCustmoerData[0]['customer_mobile_no'],
                            'date_time'=>date('Y-m-d H:i:s')
                           ); 
                          if(!empty($array))
                          {
                            $this->Supervisor->insertNotification($array);                          
                          } 
          } 
        }
     $arry['data']=array('status'=>'1','message'=>'Order declined successfully');

     $this->response($arry, 200);
   }else
   {
    $arry['data']=array('status'=>'0','message'=>'failed');
    $this->response($arry, 200);
  }
}
else if($status==2 || $status==3 || $status==5)
{

 $waiter_mobile_no   =$this->Supervisor->getWaiterMobileNo($order_id,$admin_id);
 $order_array=array(
  'waiter_mobile_no'=>$waiter_mobile_no,
  'order_status'=>$sub_order_status,
  'confirm_order_by'=>$waiter_mobile_no,
  'status'=>($sub_order_status=='Confirm'?'2':'0')
);

 $result=$this->Supervisor->updateSubOrderByWaiter($order_array,$order_id,$admin_id,$sub_order_id);

 if(!empty($result))
 {

      $waiterData         =$this->Supervisor->getwaiterData($waiter_mobile_no,'Waiter');
      $getCustmoerData    =$this->Supervisor->getCustmoerData($order_id,$admin_id);
      $staffData          =$this->Supervisor->getStaffNotification($admin_id,'KOT');
      $order_id2          =str_replace($admin_id.'-','',$order_id);
      $custData           =$this->Customer->getCustData($getCustmoerData[0]['customer_mobile_no']);
      $title              ='Sub Order Status';
      $message            ='Table No '.$getCustmoerData[0]['table_no'].' ('.$waiterData[0]['name'].')'.' Confirmed order';
      $result=sendPushNotification($title,$message,$staffData[0]['notification_id']);
      if(!empty($result))
                {
                  $array=array(
                    'send_from'=>$waiter_mobile_no,
                    'staff_mobile_no'=>$staffData[0]['mobile_no'],
                    'admin_id'=>$admin_id,
                    'status'=>1,
                    'order_id'=>$order_id2,
                    'sub_order_id'=>$sub_order_id,
                    'table_no'=>$getCustmoerData[0]['table_no'],
                    'title'=>$title ,
                    'message'=>$message,
                    'user_type'=>'KOT',
                    'customer_mobile_no'=>$getCustmoerData[0]['customer_mobile_no'],
                    'date_time'=>date('Y-m-d H:i:s')
                    ); 
                    if(!empty($array))
                    {
                      $this->Supervisor->insertNotification($array);                          
                    }  
                }  
          
   $arry['data']=array('status'=>'1','message'=>'Order placed successfully');
   $this->response($arry, 200);
 }else
 {


   $arry['data']=array('status'=>'0','message'=>'failed');
   $this->response($arry, 200);
 }
}else
{
  $arry['data']=array('status'=>'0','message'=>'failed');
  $this->response($arry, 200);
}


}
public function sub_order_create_slip_supervisor_for_chef_post()
{   

  $order_id=$this->input->post('order_id');
  $admin_id=$this->input->post('admin_id');
  $mobile_no=$this->input->post('mobile_no');
  $sub_order_id=$this->input->post('sub_order_id');

  $arr=array(
    'create_slip_by'=>$mobile_no,
    'slip_status'=>'1',
    'modified_date'=>date('Y-m-d'),
    'order_status'=>'Prepare',
    'order_ready_to_serve_by'=>$mobile_no,
    'status'=>'3'
  );

  $status=$this->Supervisor->getOrderStatusByWaiter($order_id,$admin_id);

  if($status==3 || $status==5)
  {


    $result=$this->Supervisor->subOrderCreateSlip($arr,$order_id,$admin_id,$sub_order_id);
    if(!empty($result))
    {


          $order_id2                =str_replace($admin_id.'-','',$order_id);
          $title                    ='Sub Order Status';
          $staffData                =$this->Supervisor->getStaffNotification($admin_id,'Chef');
          $getCustmoerData          =$this->Supervisor->getCustmoerData($order_id,$admin_id);
          // print_r($getCustmoerData);exit;
          $waiterData               =$this->Supervisor->getwaiterData($mobile_no,'KOT');
          $message                  ='Table No '.$getCustmoerData[0]['table_no'].' Order Id '.$order_id2.' '.$staffData[0]['name'].' created slip';
          // print_r( $message);exit;
          $result3=sendPushNotification($title,$message,$staffData[0]['notification_id']);
          if(!empty($result3))
          {
             $array=array(

                          'send_from'=>$mobile_no,
                          'staff_mobile_no'=>$staffData[0]['mobile_no'],
                          'admin_id'=>$admin_id,
                          'status'=>1,
                          'order_id'=>$order_id2,
                          'table_no'=>$getCustmoerData[0]['table_no'],
                          'title'=>$title ,
                          'message'=>$message,
                          'user_type'=>'Chef',
                          'customer_mobile_no'=>$getCustmoerData[0]['customer_mobile_no'],
                          'date_time'=>date('Y-m-d H:i:s')
                           ); 
                          if(!empty($array))
                          {
                            $this->Supervisor->insertNotification($array);                          
                          }  
          }
         
     $arry['data']=array('status'=>'1','message'=>'success');
     $this->response($arry, 200);
   }else
   {
     $arry['data']=array('status'=>'0','message'=>'failed');
     $this->response($arry, 200);
   }
 }else
 {
  $arry['data']=array('status'=>'0','message'=>'failed');
  $this->response($arry, 200);
}

}
public function sub_order_complete_by_chef_post()
{

  $order_id             =$this->input->post('order_id');
  $sub_order_id         =$this->input->post('sub_order_id');
  $admin_id             =$this->input->post('admin_id');
  $chef_mobile_no       =$this->input->post('chef_mobile_no');
  $array=array(
    'order_ready_to_serve_by'=>$chef_mobile_no,
    'status'=>'5',
    'order_status'=>'Ready to Serve'
  );
  $status=$this->Supervisor->getOrderStatusByWaiter($order_id,$admin_id);
  
  if($status==3 || $status==5)
  {

    $result=$this->Supervisor->complete_sub_order_by_chef($array,$admin_id,$sub_order_id,$order_id);

    if(!empty($result))
    {

          $waiter_mobile_no      =$this->Supervisor->getWaiterMobileNo($order_id,$admin_id);
          $notificationData      =$this->Supervisor->getWaiterNotification($waiter_mobile_no);
          $getCustmoerData       =$this->Supervisor->getCustmoerData($order_id,$admin_id);
          $custData              =$this->Supervisor->getCustData($getCustmoerData[0]['customer_mobile_no']);
          $order_id2             =str_replace($admin_id.'-','',$order_id);
          $array_merge_recursive=array_merge_recursive($custData,$notificationData);
          // echo "<pre>";print_r($array_merge_recursive);exit;
          foreach( $array_merge_recursive as $notification){
            if($notification['user_type']=='customer'){
               $title='Enjoy Your Meal';
               $message='Your order is ready to serve.';
            }else if($notification['user_type']=='Waiter'){
              $title='Order status';
              $message='Table No '.$getCustmoerData[0]['table_no'].' Sub Order Id '.$sub_order_id.' ready to serve';
            }
         if($result)
          {
                $array=array(
                            'send_from'=>$chef_mobile_no,
                            'staff_mobile_no'=>$notification['mobile_no'],
                            'admin_id'=>$admin_id,
                            'status'=>1,
                            'order_id'=>$order_id2,
                            'sub_order_id'=>$sub_order_id,
                            'table_no'=>$getCustmoerData[0]['table_no'],
                            'title'=>$title ,
                            'message'=>$message,
                            'user_type'=>$notification['user_type'],
                            'customer_mobile_no'=>$getCustmoerData[0]['customer_mobile_no'],
                            'date_time'=>date('Y-m-d H:i:s')
                             ); 
                            if(!empty($array))
                            {
                              $this->Supervisor->insertNotification($array);                          
                            } 
              }
        }

      $arry['data']=array('status'=>'1','message'=>'success');
      $this->response($arry, 200);
    }else
    {
      $arry['data']=array('status'=>'0','message'=>'failed');
      $this->response($arry, 200);
    }
  }else
  {
    $arry['data']=array('status'=>'0','message'=>'failed');
    $this->response($arry, 200);
  }


}
public function completeOrderByWaiter_post()
{

  $order_id=$this->input->post('order_id');
  $admin_id=$this->input->post('admin_id');
  $waiter_mobile_no=$this->input->post('waiter_mobile_no');
  $result2=$this->Supervisor->getCompletOrder($order_id,$admin_id);
  if(empty($result2))
  {
    $this->Supervisor->completAllSubOrder($order_id,$admin_id,'Rejected',$waiter_mobile_no);
    $result=$this->Supervisor->completAllOrder($order_id,$admin_id,'Rejected',$waiter_mobile_no);
    if(!empty($result))
    {
          $staffData          =$this->Supervisor->getStaffNotification($admin_id,'Cashier');
          $supervisorData     =$this->Supervisor->getSupervisordata($admin_id);
          $getCustmoerData    =$this->Supervisor->getCustmoerData($order_id,$admin_id);
          $order_id2         =str_replace($admin_id.'-','',$order_id);
          $array_merge_recursive=array_unique(array_merge_recursive($supervisorData,$staffData));
          // echo '<pre>';print_r($array_merge_recursive);exit;
          foreach($array_merge_recursive as $notification){

            if($notification['user_type']='Cashier'){
                 $title='Order completed';
                 $message='Table No '.$getCustmoerData[0]['table_no'].' Order Id '.$order_id2.' has been completed';
            }else if($notification['user_type']='Supervisor'){
              $title='Order completed';
              $message='Table No '.$getCustmoerData[0]['table_no'].' Order Id '.$order_id2.' has been completed';
            }

             $result             =sendPushNotification($title,$message,$notification['notification_id']);

             if($result)
              {
               $array=array(
                            'send_from'=>$waiter_mobile_no,
                            'staff_mobile_no'=>$notification['mobile_no'],
                            'admin_id'=>$admin_id,
                            'status'=>1,
                            'order_id'=>$order_id2,
                            'table_no'=>$getCustmoerData[0]['table_no'],
                            'title'=>$title ,
                            'message'=>$message,
                            'user_type'=>$notification['user_type'],
                            'customer_mobile_no'=>$getCustmoerData[0]['customer_mobile_no'],
                            'date_time'=>date('Y-m-d H:i:s')
                           ); 
                          if(!empty($array))
                          {
                            $this->Supervisor->insertNotification($array);                          
                          }  
              }
          }          
        $arry['data']=array('status'=>'1','message'=>'success');
       $this->response($arry, 200);
    }else
    {
      $arry['data']=array('status'=>'0','message'=>'failed');
      $this->response($arry, 200);
    }
  }else
  {
    $arry['data']=array('status'=>'0','message'=>'Please reject or ready to serve all sub orders.');
    $this->response($arry, 200);
  }

}

public function generateInvoiceOrderBycashier_post()
{
  $arr    =array();
  $arr2   =array(); 
  $result4=array();
  $result5=array();
  $result6=array();
  $result8=array();
  $array  =array();
  $arry=array();
  $finalarray=array();
  $order_id=$this->input->post('order_id');
  $admin_id=$this->input->post('admin_id');
  $cashier_mobile_no=$this->input->post('mobile_no');
  $this->Supervisor->invoiceCreatedForOrders($order_id,$admin_id,$cashier_mobile_no);
  $this->Supervisor->invoiceCreatedForSubOrders($order_id,$admin_id,$cashier_mobile_no);
  $result=$this->Supervisor->getOrderForCashier($order_id,$admin_id);
  if(!empty($result))
  {
    foreach($result as $value)
    {
      $arry['order_id']=$value['order_id'];
      $arry['admin_id']=$value['admin_id'];
      $arry['table_no']=$value['table_no'];
      $arry['new_order_id']=str_replace($value['admin_id'].'-','',$value['order_id']);
      $OrderGstResult   =$this->Supervisor->getGstInforForOrder($value['order_id'],$value['admin_id']);
             
      foreach($OrderGstResult as $value3)
      {

        $gst_amount=$value3['menu_price']*$value3['gst']/100;

        $result3['gst_amount']  =$gst_amount;
       
        $result3['gst']         =$value3['gst'];
       
        $result6[]              =$result3;
      }
      $menuResult =$this->Supervisor->getMenuItemForOrderInvoice($value['order_id'],$admin_id);
      // $gst_amount_sum=0;
      $total_price=0;
      foreach($menuResult as $menuValue)
      {
        $menuImages[]               =$menuValue['menu_item_name'];
        $menuquantity[]             =$menuValue['quantity'];
        $menuhalf_and_full_status[] =$menuValue['half_and_full_status'];
        $menumenu_price[]           =$menuValue['menu_price'];
        $menu_item_gst[]            =$menuValue['gst'];
        $gst_amount_sum[]           =$menuValue['menu_price']*$menuValue['gst']/100;
        $total_price                =$total_price+$menuValue['menu_price'];
        
      }
      // print_r(implode(',',$menuImages).',');exit;
      $arry['menu_item_name']=implode(',',$menuImages).',';
      $arry['quantity']=implode(',',$menuquantity).',';
      $arry['half_and_full_status']=implode(',',$menuhalf_and_full_status).',';
      $arry['menu_price']=implode(',',$menumenu_price).',';
      $arry['total_item']=$value['total_item'];
      $arry['net_pay_amount']=$value['net_pay_amount'];
      $arry['gst_amount']=implode(',',$menu_item_gst).',';
      $arry['gst_amount_price']=implode(',',$gst_amount_sum).',';
      $arry['order_status']=$value['order_status'];
      $arry['payment_status']=$value['payment_status'];
      $arry['status']=$value['status'];
      $arry['total_price']="$total_price";
      $arry['discount']=$value['discount'];
      // $arry['menu_item_gst']=implode(',',$menu_item_gst).',';

     $SubOrderGstResult   =$this->Supervisor->getGstInforForSubOrder($value['order_id'],$value['admin_id']);
      foreach($SubOrderGstResult as $value4)
      {
        $gst_amount2=$value4['menu_price']*$value4['gst']/100;
        $result4['gst_amount']  =$gst_amount2;
        $result4['gst']         =$value4['gst'];;
        $result5[]              =$result4;
      }
                     
      $result2=$this->Supervisor->getSubOrderForCashier($order_id);
      if(!empty($result2))
      {
        foreach($result2 as $value2)
        {
          $arry2['order_id']=$value2['order_id'];
          $arry2['sub_order_id']=$value2['sub_order_id'];
          $arry2['admin_id']=$value2['admin_id'];
          $menuResult2 =$this->Supervisor->getMenuItemForSubOrderInvoice($value2['order_id'],$admin_id,$value2['sub_order_id']);
          // $gst_amount_sum2=0;
          $total_price1=0;
          foreach($menuResult2 as $menuValue2)
          {
            $menuImages2[]               =$menuValue2['menu_item_name'];
            $menuquantity2[]             =$menuValue2['quantity'];
            $menuhalf_and_full_status2[] =$menuValue2['half_and_full_status'];
            $menumenu_price2[]           =$menuValue2['menu_price'];
            $menu_item_gst2[]            =$menuValue2['gst'];
            $gst_amount_sum2[]           =$menuValue2['menu_price']*$menuValue2['gst']/100;
            $total_price1                =$total_price1+$menuValue2['menu_price'];

          } 
          // print_r(implode(',',$menuImages2).',');exit;
          $arry2['table_no']=$value2['table_no'];
          $arry2['menu_item_name']=implode(',',$menuImages2).',';
          $arry2['quantity']=implode(',',$menuquantity2).',';
          $arry2['half_and_full_status']=implode(',',$menuhalf_and_full_status2).',';
          $arry2['menu_price']=implode(',',$menumenu_price2).',';
          $arry2['total_item']=$value2['total_item'];
          $arry2['net_pay_amount']=$value2['net_pay_amount'];
          $arry2['gst_amount']=implode(',',$menu_item_gst2).',';
          $arry2['gst_amount_price']=implode(',',$gst_amount_sum2).',';
          $arry2['order_status']=$value2['order_status'];
          $arry2['payment_status']=$value2['payment_status'];
          $arry2['status']=$value2['status'];
          $arry2['total_price']="$total_price1";
          // $arry2['menu_item_gst']=implode(',',$menu_item_gst2).',';;
          $finalarray[]=$arry2;
          $menuImages2=array();
          $menuquantity2=array();
          $menuhalf_and_full_status2=array();
          $menumenu_price2=array();
          $menu_item_gst2=array();
          $gst_amount_sum2=array();
        }
        $menuImages=array();
        $menuquantity=array();
        $menuhalf_and_full_status=array();
        $menumenu_price=array();
        $menu_item_gst=array();
        $gst_amount_sum=array();
      }
      $merged_array=array_merge($result5,$result6);
      $masterGst=$this->Supervisor->getMasterGst($admin_id);
      $finalgstArray=array();
      foreach($masterGst as $value7)
      {
        $gst_amount=0;

        foreach($merged_array as $value8)
        {
          
          if($value7['gst']==$value8['gst'])
          {
            $gst_amount=$gst_amount+$value8['gst_amount'];
          }
        }
        if(!empty($gst_amount))
        {
        $result8['gst_amount']="$gst_amount";
        $result8['cgst_amount']=$gst_amount/2;
        $result8['sgst_amount']=$gst_amount/2;
        $result8['gst']=$value7['gst'];
        $result8['cgst']=$value7['gst']/2;
        $result8['sgst']=$value7['gst']/2;
        $finalgstArray[]=$result8;
        }
        
      }
      $arry['sub_order']=$finalarray;
      $arry['gst_info']=$finalgstArray;
      $result5=array();
      $result6=array();

    }
    $total_price    =$arry['total_price'];
    $discount       =$arry['discount'];
    $SubOrderArray  =$arry['sub_order'];
    $SubOrderPrice=0;
    $totalOrderAmount=0;
    $discountAmount=0;
    foreach($SubOrderArray AS $invData)
    {
      $SubOrderPrice=$SubOrderPrice+$invData['total_price'];
    }
    $GstArray       =$arry['gst_info'];
    $gstTotalPrice=0;
    foreach($GstArray AS $invGst)
    {
      $gstTotalPrice =$gstTotalPrice+$invGst['gst_amount'];
    }
    $totalOrderAmount=$total_price+$SubOrderPrice;
    $discountAmount=$totalOrderAmount*$discount/100;
    $inv_result=$this->Supervisor->getInvoiceData($admin_id,$order_id);
    // print_r($inv_result);exit;
    if(empty($inv_result))
    {
      $max_inv=$this->Supervisor->getMaxInvNumber();
      // print_r($max_inv);exit;
      $inv_array=array(
      'inv_no'=>'inv'.(!empty($max_inv)?$max_inv+1:'1'),
      'order_id'=>$order_id,
      'admin_id'=>$admin_id,
      'total_order_amount'=>$totalOrderAmount,
      'discount_amount'=>$discountAmount,
      'total_gst'=>$gstTotalPrice,
      'status'=>1,
      'service_charge'=>0,
      'creation_date'=>date('Y-m-d H:i:s')
      );
      // print_r($inv_array);exit;
      $insetedId=$this->Supervisor->insertInvoiceDetails($inv_array);
      if($insetedId)
      {
            $getCustmoerData            =$this->Supervisor->getCustmoerData($order_id,$admin_id);
            $order_id2                  =str_replace($admin_id.'-','',$order_id);
            $custData                   =$this->Supervisor->getCustData($getCustmoerData[0]['customer_mobile_no']);
            $notification_data          =$this->Supervisor->getWaiterNotification($getCustmoerData[0]['confirm_order_by']);
            $array_merge_recursive=array_merge_recursive($notification_data,$custData);
            // echo '<pre>';print_r($array_merge_recursive);exit;
             foreach($array_merge_recursive as $notification){
                  if($notification['user_type']=='customer'){
                      $title='OYLY';
                      $message='invoice created successfully.';
                      $result=sendPushNotification($title,$message,$notification['notification_id']);
                if(!empty($result))
                {
                    $array=array(
                    'send_from'=>$cashier_mobile_no,
                    'staff_mobile_no'=>$notification['mobile_no'],
                    'admin_id'=>$admin_id,
                    'status'=>1,
                    'order_id'=>$order_id2,
                    'table_no'=>$getCustmoerData[0]['table_no'],
                    'title'=>$title,
                    'message'=>$message,
                    'user_type'=>$notification['user_type'],
                    'customer_mobile_no'=>$getCustmoerData[0]['customer_mobile_no'],
                    'date_time'=>date('Y-m-d H:i:s')
                    ); 
                    if(!empty($array))
                    {
                      $this->Supervisor->insertNotification($array);                          
                    }  
                  }
                  }elseif($notification['user_type']=='Waiter'){
                      $title='OYLY';
                      $message='Table No '.$getCustmoerData[0]['table_no'].' invoice created.';
                      $result=sendPushNotification($title,$message,$notification['notification_id']);
                if(!empty($result))
                {
                    $array=array(
                    'send_from'=>$cashier_mobile_no,
                    'staff_mobile_no'=>$notification['mobile_no'],
                    'admin_id'=>$admin_id,
                    'status'=>1,
                    'order_id'=>$order_id2,
                    'table_no'=>$getCustmoerData[0]['table_no'],
                    'title'=>$title,
                    'message'=>$message,
                    'user_type'=>$notification['user_type'],
                    'customer_mobile_no'=>$getCustmoerData[0]['customer_mobile_no'],
                    'date_time'=>date('Y-m-d H:i:s')
                    ); 
                    if(!empty($array))
                    {
                      $this->Supervisor->insertNotification($array);                          
                    }  
                  }
                  }
                
              }
           }
        }
    // echo "string";exit;
    if(!empty($arry))
    {
    // print_r($array);exit;
     $array=array('status'=>'1','data'=>$arry);
     $this->response($array, 200);
   }else
   {
    $array=array('status'=>'0','data'=>array());
    $this->response($array, 200);
  }
}else
{

  $array=array('status'=>'0','data'=>array());
  $this->response($array, 200);
}
}

public function deleteItemForOrder_post()
{
  try
  {
    $order_id     =$this->input->post('order_id');
    $admin_id     =$this->input->post('admin_id');
    $item_name    =$this->input->post('item_name');
    $mobile_no    =$this->input->post('mobile_no');
    $id           =$this->input->post('id');
    
   if(!empty($order_id)&&!empty($admin_id)&&!empty($id) && !empty($mobile_no))
    {

      $result2=$this->Supervisor->getItemMenuOrderDetails($order_id,$admin_id);

      if(!empty($result2))
      {
        $result=$this->Supervisor->cancelOrderItem($order_id,$admin_id,$item_name,$id);

        if(!empty($result))
        {

          $orderResult        =$this->Supervisor->getOrderPrice($order_id,$admin_id);
          $total_price        =$orderResult[0]['total_price'];
          $total_item         =$orderResult[0]['total_item'];
          $net_pay_amount     =$orderResult[0]['net_pay_amount'];
          $gst_amount         =$orderResult[0]['gst_amount'];
          $gst_amount_price   =$orderResult[0]['gst_amount_price'];


          $orderItemResult    =$this->Supervisor->getOrderItemPrice($order_id,$admin_id,$id);
          $menu_price         =$orderItemResult[0]['menu_price'];
          $quantity           =$orderItemResult[0]['quantity'];
          $actualPrice        =$total_price-$menu_price;
          $actualNetPay       =$actualPrice+$actualPrice*$gst_amount/100;
          $actualGstAmountPrice =$actualPrice*$gst_amount/100;
          $updateArray        =array(
            'total_item'=>$total_item-$quantity,
            'total_price'=>$actualPrice,
            'net_pay_amount'=>$actualNetPay,     
            'gst_amount_price'=>$actualGstAmountPrice
          );
          $this->Supervisor->updateMenuListDetails($updateArray,$order_id,$admin_id);
          $getCustmoerData    =$this->Supervisor->getCustmoerData($order_id,$admin_id);
          $waiterData         =$this->Supervisor->getwaiterData($waiter_mobile_no,'');
          $staffData1         =$this->Supervisor->getWaiterNotification($getCustmoerData[0]['confirm_order_by']);
          $staffData2         =$this->Supervisor->getStaffNotification($admin_id,'KOT');
          $custData           =$this->Supervisor->getCustData($getCustmoerData[0]['customer_mobile_no']);
          $order_id2          =str_replace($admin_id.'-','',$order_id);
          $supervisorData     =$this->Supervisor->getStaffNotification($admin_id,'Supervisor');
          $array_merge_recursive=array_merge_recursive($custData,array_merge_recursive($staffData1,$staffData2),$supervisorData);
          // echo '<pre>';print_r($array_merge_recursive);exit;
          foreach($array_merge_recursive as $notification){
              if($notification['user_type']=='customer'){
                  $title ='OYLY';
                  $message ='Item not available.';
                  }else if($notification['user_type']=='Waiter'){
                  $title ='OYLY';
                  $message ='Table No '.$getCustmoerData[0]['table_no'].' item not available.';
                  }else if($notification['user_type']=='KOT'){
                  $title ='OYLY';
                  $message ='Table No '.$getCustmoerData[0]['table_no'].' item not available';
                  }else if($notification['user_type']=='Supervisor'){
                    $title ='OYLY';
                    $message ='Table No '.$getCustmoerData[0]['table_no'].' item not available';
                  }
                  if(in_array($notification['user_type'],array('Waiter','KOT','Supervisor','customer'))){
                      $result=sendPushNotification($title,$message,$notification['notification_id']);
                      if(!empty($result)){
                        $array=array(
                                      'send_from'=>$mobile_no,
                                      'staff_mobile_no'=>$notification['mobile_no'],
                                      'admin_id'=>$admin_id,
                                      'status'=>1,
                                      'order_id'=>$order_id2,
                                      'table_no'=>$getCustmoerData[0]['table_no'],
                                      'title'=>$title ,
                                      'message'=>$message,
                                      'user_type'=>$notification['user_type'] !='',
                                      'customer_mobile_no'=>$getCustmoerData[0]['customer_mobile_no'],
                                      'date_time'=>date('Y-m-d H:i:s')
                                      ); 
                                      if(!empty($array))
                                      {
                                        $this->Supervisor->insertNotification($array);                          
                                      }  
                      } 
                  }
          } 
          $arry['data']=array('status'=>'1','message'=>'Item cancelled');
          $this->response($arry, 200);
        }else
        {
          $arry['data']=array('status'=>'0','message'=>'failed');
          $this->response($arry, 200);
        }
      }else
      {
        $arry['data']=array('status'=>'0','message'=>'failed');
        $this->response($arry, 200);
      }


    }else
    {
      $arry['data']=array('status'=>'0','message'=>'failed');
      $this->response($arry, 200);
    }
  }catch(Ececption $e)
  {
    echo $e->getMessage();
    $error = array('status' =>'0', "message" => "Internal Server Error - Please try Later.","StatusCode"=> "HTTP405");
    $this->response($error, 200);
  }
}

public function deleteItemForSubOrder_post()
{
  try
  {
    $order_id     =$this->input->post('order_id');
    $sub_order_id =$this->input->post('sub_order_id');
    $admin_id     =$this->input->post('admin_id');
    $item_name    =$this->input->post('item_name');
    $id           =$this->input->post('id');
    $mobile_no    =$this->input->post('mobile_no');
    if(!empty($order_id)&&!empty($admin_id)&&!empty($id)&&!empty($sub_order_id))
    {
         if(!empty($mobile_no))
         {
              $role=$this->Supervisor->getEmpRole($mobile_no,$admin_id);

              if($role=='Supervisor')
              {
                $result2=$this->Supervisor->getItemMenuSubOrderDetails2($order_id,$admin_id,$sub_order_id);
              }else
              {
                $result2=$this->Supervisor->getItemMenuSubOrderDetails($order_id,$admin_id,$sub_order_id);
              }
            
         }else
         {
          $result2=$this->Supervisor->getItemMenuSubOrderDetails($order_id,$admin_id,$sub_order_id);
         }
          
          if(!empty($result2))
          {
	         $result=$this->Supervisor->cancelSubOrderItem($order_id,$admin_id,$item_name,$sub_order_id,$id);

              $CheckItem=$this->Supervisor->checkSubOrderItem($order_id,$admin_id,$sub_order_id);

              if(empty($CheckItem))
              {
                $this->Supervisor->RejectSubOrder($order_id,$admin_id,$sub_order_id,$mobile_no,$id);
              }
              

              if(!empty($result))
              {

                    $orderResult        =$this->Supervisor->getSubOrderPrice($order_id,$admin_id,$sub_order_id);
                    $total_price        =$orderResult[0]['total_price'];
                    $total_item         =$orderResult[0]['total_item'];
                    $net_pay_amount     =$orderResult[0]['net_pay_amount'];
                    $gst_amount         =$orderResult[0]['gst_amount'];
                    $gst_amount_price   =$orderResult[0]['gst_amount_price'];

                    $orderItemResult    =$this->Supervisor->getSubOrderItemPrice($order_id,$admin_id,$id,$sub_order_id);
                    $menu_price         =$orderItemResult[0]['menu_price'];
                     $quantity          =$orderItemResult[0]['quantity'];
                    $actualPrice        =$total_price-$menu_price;
                    $actualNetPay       =$actualPrice+$actualPrice*$gst_amount/100;
                    $actualGstAmountPrice =$actualPrice*$gst_amount/100;
                    $updateArray        =array(
                      'total_item'=>$total_item-$quantity,
                      'total_price'=>$actualPrice,
                      'net_pay_amount'=>$actualNetPay,     
                      'gst_amount_price'=>$actualGstAmountPrice
                    );
                $this->Supervisor->updateMenuSubListDetails($updateArray,$order_id,$admin_id,$sub_order_id);
                $getCustmoerData    =$this->Supervisor->getCustmoerData($order_id,$admin_id);
                $waiterData         =$this->Supervisor->getwaiterData($waiter_mobile_no,'');
                $staffData1         =$this->Supervisor->getWaiterNotification($getCustmoerData[0]['confirm_order_by']);
                $staffData2         =$this->Supervisor->getStaffNotification($admin_id,'KOT');
                $custData           =$this->Supervisor->getCustData($getCustmoerData[0]['customer_mobile_no']);
                $order_id2          =str_replace($admin_id.'-','',$order_id);
                $supervisorData     =$this->Supervisor->getStaffNotification($admin_id,'Supervisor');
                $array_merge_recursive=array_merge_recursive($custData,array_merge_recursive($staffData1,$staffData2),$supervisorData);
                // echo '<pre>';print_r($array_merge_recursive);exit;
                foreach($array_merge_recursive as $notification){
                    if($notification['user_type']=='customer'){
                        $title ='OYLY';
                        $message ='Item not available.';
                        }else if($notification['user_type']=='Waiter'){
                        $title ='OYLY';
                        $message ='Table no '.$getCustmoerData[0]['table_no'].' item not available.';
                        }else if($notification['user_type']=='KOT'){
                        $title ='OYLY';
                        $message ='Table no '.$getCustmoerData[0]['table_no'].' item not available';
                        }else if($notification['user_type']=='Supervisor'){
                        $title ='OYLY';
                        $message ='Table No '.$getCustmoerData[0]['table_no'].' item not available';
                       }
                        if(in_array($notification['user_type'],array('Waiter','KOT','Supervisor','customer'))){
                          $result=sendPushNotification($title,$message,$notification['notification_id']);
                              if(!empty($result))
                              {
                                $array=array(
                                              'send_from'=>$mobile_no,
                                              'staff_mobile_no'=>$notification['mobile_no'],
                                              'admin_id'=>$admin_id,
                                              'status'=>1,
                                              'order_id'=>$order_id2,
                                              'sub_order_id'=>$sub_order_id,
                                              'table_no'=>$getCustmoerData[0]['table_no'],
                                              'title'=>$title ,
                                              'message'=>$message,
                                              'user_type'=>$notification['user_type'],
                                              'customer_mobile_no'=>$getCustmoerData[0]['customer_mobile_no'],
                                              'date_time'=>date('Y-m-d H:i:s')
                                              ); 
                                              if(!empty($array))
                                              {
                                                $this->Supervisor->insertNotification($array);                          
                                              }  
                              } 
                        }
                    
                } 
                $arry['data']=array('status'=>'1','message'=>'Item cancelled');
                $this->response($arry, 200);
              }else
              {
                $arry['data']=array('status'=>'0','message'=>'failed');
                $this->response($arry, 200);
              }
          }else
          {
            $arry['data']=array('status'=>'0','message'=>'failed');
                $this->response($arry, 200);
          }

    }else
    {
      $arry['data']=array('status'=>'0','message'=>'failed');
      $this->response($arry, 200);
    }
  }catch(Ececption $e)
  {
    echo $e->getMessage();
    $error = array('status' =>'0', "message" => "Internal Server Error - Please try Later.","StatusCode"=> "HTTP405");
    $this->response($error, 200);
  }
}
public function discountForCustomer_post()
{
    try
    {
      $order_id     =$this->input->post('order_id');
      $admin_id     =$this->input->post('admin_id');
      $mobile_no    =$this->input->post('mobile_no');
      $discount     =$this->input->post('discount');
      $array        =array('discount'=>$discount,'discount_by'=>$mobile_no);
      $result       =$this->Supervisor->offerDiscount($order_id,$admin_id,$array);
      if(!empty($result))
      {
          
          $getCustmoerData    =$this->Supervisor->getCustmoerData($order_id,$admin_id);
          $waiterData         =$this->Supervisor->getwaiterData($mobile_no,'Supervisor');
          // print_r($waiterData);exit;
          $custData           =$this->Supervisor->getCustData($getCustmoerData[0]['customer_mobile_no']);
          $adminData          =$this->Supervisor->getAdminData($admin_id);
          $order_id2          =str_replace($admin_id.'-','',$order_id);
          $array_merge_recursive=array_merge_recursive($custData,$adminData);
          // echo '<pre>';print_r($array_merge_recursive);exit;
          foreach( $array_merge_recursive as $value){
              if($value['user_type']=='customer'){
                $title   ='Wow';
                $message ='You got a discount of '.$discount.'%';
              }else if($value['user_type']=='Admin'){
                   $title   ='Discount applied';
                  $message ='Supervisor('.$waiterData[0]['name'].')'.'created discount of '.$discount.'%';
              }
             $result=sendPushNotification($title,$message,$value['notification_id']);
                if(!empty($result))
                {
                  $array=array(
                                  'send_from'=>$mobile_no,
                                  'staff_mobile_no'=>$value['mobile_no'],
                                  'admin_id'=>$admin_id,
                                  'status'=>1,
                                  'order_id'=>$order_id2,
                                  'table_no'=>$getCustmoerData[0]['table_no'],
                                  'title'=>$title,
                                  'message'=>$message,
                                  'user_type'=>$value['user_type'],
                                  'customer_mobile_no'=>$getCustmoerData[0]['customer_mobile_no'],
                                  'date_time'=>date('Y-m-d H:i:s')
                                 ); 
                                if(!empty($array))
                                {
                                  $this->Supervisor->insertNotification($array);                          
                                } 
                }
          } 
         $arry['data']=array('status'=>'1','message'=>'success');
          $this->response($arry, 200);
      }else
      {
           $arry['data']=array('status'=>'0','message'=>'failed');
          $this->response($arry, 200);
      }
    }catch(Ececption $e)
    {
      echo $e->getMessage();
      $error = array('status' =>'0', "message" => "Internal Server Error - Please try Later.","StatusCode"=> "HTTP405");
      $this->response($error, 200);

    }
    
  }
  public function get_order_detail_by_date_for_restaurant_post()
{

  $response         =new StdClass();
  $result           =array();
  $result2          =array();
  $finalarray       =array();
  $arr = array();
  $arr2 = array();
  $menuImages = array();
  $menuquantity = array();
  $menuhalf_and_full_status = array();
  $menumenu_price = array();
  //$start_date       =$this->input->post('start_date');
  //$end_date         =$this->input->post('end_date');
  $admin_id         =$this->input->post('admin_id');
  $start_date       =date('Y-m-d', strtotime($this->input->post('start_date')));
  $end_date         =date('Y-m-d', strtotime($this->input->post('end_date')));
  $data = $this->Supervisor->getGroupDataFromDateWise($admin_id,$start_date,$end_date);
  if(empty($data))
  {
    $response->status = 0;
    $response->message = "failed";

  }
  else
  {
   $response->status = 1;
   $response->message = "success";
   for($i=0;$i<count($data);$i++)
   {
    // $result['id']           =$data[$i]['id'];
    $result['order_id']     =$data[$i]['order_id'];
    $result['table_no']     =$data[$i]['table_no'];
    $result['order_status'] =$data[$i]['order_status'];
    $result['admin_id']     =$data[$i]['admin_id'];
    $result['new_order_id'] =str_replace($data[$i]['admin_id'].'-','',$data[$i]['order_id']);
    $menuResult =$this->Supervisor->getMenuItemForOrder($data[$i]['order_id'],$admin_id);

    foreach($menuResult as $menuValue)
    {
      $menuImages[]               =$menuValue['menu_item_name'];
      $menuquantity[]             =$menuValue['quantity'];
      $menuhalf_and_full_status[] =$menuValue['half_and_full_status'];
      $menumenu_price[]           =$menuValue['menu_price'];
      $menumenu_id[]              =$menuValue['id'];
      $menumenu_status[]          =$menuValue['status'];
      $menu_order_id[]            =$data[$i]['order_id'];
      $menu_order_table[]         =$data[$i]['table_no'];
      $main_order_status[]        =$data[$i]['status'];

    }
                // print_r(implode(',',$menuImages).',');exit;
    $result['menu_order_id']                =implode(',',$menu_order_id).',';
    $result['menu_order_table']                =implode(',',$menu_order_table).',';
    $result['id']                =implode(',',$menumenu_id).',';
    $result['menu_item_name']       =implode(',',$menuImages).',';
    $result['item_status']       =implode(',',$menumenu_status).',';
                // $result['menu_item_name']       =$data[$i]['menu_item_name'];
    $result['cus_id']       =$data[$i]['cus_id'];
    $result['quantity']       =implode(',',$menuquantity).',';
    $result['half_and_full_status']       =implode(',',$menuhalf_and_full_status).',';
    $result['main_order_status']       =implode(',',$main_order_status).',';
    $result['menu_price']       =implode(',',$menumenu_price).',';
    $result['total_item']       =$data[$i]['total_item'];
    $result['net_pay_amount']       =$data[$i]['net_pay_amount'];
    $result['gst_amount']       =$data[$i]['gst_amount'];
    $result['gst_amount_price']       =$data[$i]['gst_amount_price'];
    $result['order_status']       =$data[$i]['order_status'];
    $result['waiter_mobile_no']       =$mobile_no;
    $result['customer_mobile_no']       =$data[$i]['customer_mobile_no'];
    $result['create_slip_by']       =$data[$i]['create_slip_by'];
    $result['order_complete_by']       =$data[$i]['order_complete_by'];
    $result['order_delete_by']       =$data[$i]['order_delete_by'];
    $result['date']       =$data[$i]['date'];
    $result['modified_date']       =$data[$i]['modified_date'];
    $result['slip_status']       =$data[$i]['slip_status'];
    $result['payment_status']       =$data[$i]['payment_status'];
    $result['notification_status_by_staff']       =$data[$i]['notification_status_by_staff'];
    $result['NS_for_complete_by_waiter']       =$data[$i]['NS_for_complete_by_waiter'];
    $result['NS_for_kot_for_staff']       =$data[$i]['NS_for_kot_for_staff'];
    $result['NS_for_kitchen_for_staff']       =$data[$i]['NS_for_kitchen_for_staff'];
    $result['NS_for_complete_by_chef']       =$data[$i]['NS_for_complete_by_chef'];
    $result['NS_for_kitchen_for_waiter']       =$data[$i]['slip_status'];
    $result['notification_status_by_customer']       =$data[$i]['notification_status_by_customer'];
    $result['NS_for_complete_by_waiter_for_customer']       =$data[$i]['NS_for_complete_by_waiter_for_customer'];
    $result['NS_for_kot_for_customer']       =$data[$i]['NS_for_kot_for_customer'];
    $result['NS_for_kitchen_for_customer']       =$data[$i]['NS_for_kitchen_for_customer'];
    $result['payment_by']       =$data[$i]['payment_by'];
    $result['get_payment']       =$data[$i]['get_payment'];
    $result['status']       =$data[$i]['status'];
    $result['total_price']       =$data[$i]['total_price'];
    $result['discount']           =$data[$i]['discount'];

    $subOrderRes=$this->Supervisor->getSubOrder($data[$i]['order_id'],$admin_id);
    if(!empty($subOrderRes))
    {
      foreach ($subOrderRes as $value)
      {
        $result2['order_id']     =$data[$i]['order_id'];
        $result2['admin_id']     =$data[$i]['admin_id'];
        $result2['sub_order_id'] =$value['sub_order_id'];
        $menuResult2 =$this->Supervisor->getMenuItemForSubOrder($data[$i]['order_id'],$admin_id,$result2['sub_order_id']);
        foreach($menuResult2 as $menuValue2)
        {
          $menuImages2[]               =$menuValue2['menu_item_name'];
          $menuquantity2[]             =$menuValue2['quantity'];
          $menuhalf_and_full_status2[] =$menuValue2['half_and_full_status'];
          $menumenu_price2[]           =$menuValue2['menu_price'];
          $menu_id[]                   =$menuValue2['id'];
          $order_data[]                =$data[$i]['order_id'];
          $sub_order_id[]              =$value['sub_order_id'];
          $sub_order_status[]          =$menuValue2['status'];
          $main_sub_order_status[]     =$value['status'];

        } 
                    // print_r(implode(',',$menuImages2).',');exit;
        $result2['menu_item_name']=implode(',',$menuImages2).',';
        $result2['order_data_id']=implode(',',$order_data).',';
        $result2['sub_order_data_array']=implode(',',$sub_order_id).',';
        $result2['sub_order_status']=implode(',',$sub_order_status).',';
        $result2['menu_item_id']=implode(',',$menu_id).',';
                    // $result2['menu_item_name']=$value['menu_item_name'];
        $result2['quantity']=implode(',',$menuquantity2).',';
        $result2['half_and_full_status']=implode(',',$menuhalf_and_full_status2).',';
        $result2['main_sub_order_status']=implode(',', $main_sub_order_status).',';
        $result2['menu_price']=implode(',',$menumenu_price2).',';
        $result2['total_item']       =$value['total_item'];
        $result2['net_pay_amount']       =$value['net_pay_amount'];
        $result2['gst_amount']       =$value['gst_amount'];
        $result2['gst_amount_price']       =$value['gst_amount_price'];
        $result2['order_status']       =$value['order_status'];
        $result2['waiter_mobile_no']       =$value['waiter_mobile_no'];
        $result2['customer_mobile_no']       =$value['customer_mobile_no'];
        $result2['create_slip_by']       =$value['create_slip_by'];
        $result2['order_complete_by']       =$value['order_complete_by'];
        $result2['order_delete_by']       =$value['order_delete_by'];
        $result2['date']       =$value['date'];
        $result2['modified_date']       =$value['modified_date'];
        $result2['slip_status']       =$value['slip_status'];
        $result2['payment_status']       =$value['payment_status'];
        $result2['notification_status_by_staff']       =$value['notification_status_by_staff'];
        $result2['NS_for_complete_by_waiter']       =$value['NS_for_complete_by_waiter'];
        $result2['NS_for_kot_for_staff']       =$value['NS_for_kot_for_staff'];
        $result2['NS_for_kitchen_for_staff']       =$value['NS_for_kitchen_for_staff'];
        $result2['NS_for_complete_by_chef']       =$value['NS_for_complete_by_chef'];
        $result2['NS_for_kitchen_for_waiter']       =$value['slip_status'];
        $result2['notification_status_by_customer']       =$value['notification_status_by_customer'];
        $result2['NS_for_complete_by_waiter_for_customer']       =$value['NS_for_complete_by_waiter_for_customer'];
        $result2['NS_for_kot_for_customer']       =$value['NS_for_kot_for_customer'];
        $result2['NS_for_kitchen_for_customer']       =$value['NS_for_kitchen_for_customer'];
        $result2['payment_by']       =$value['payment_by'];
        $result2['get_payment']       =$value['get_payment'];
        $result2['status']       =$value['status'];
        $result2['total_price']       =$value['total_price'];
        $result2['table_no']     = $data[$i]['table_no'];
        $finalarray[]=$result2;
        $menuImages2=array();
        $menuquantity2=array();
        $menuhalf_and_full_status2=array();
        $menumenu_price2=array();
        //$menumenu_status=array();
        $menu_id=array();
        $order_data=array();
        $sub_order_id=array();
        $sub_order_status=array();
        $main_sub_order_status=array();
      }

    }
    $result['sub_order_data']     =$finalarray;
    array_push($arr, $result);
    $finalarray=array();
    $menuImages=array();
    $menuquantity=array();
    $menuhalf_and_full_status=array();
    $menumenu_price=array();
    $menumenu_id=array();
    $menu_order_id=array();
    $menu_order_table=array();
    $menumenu_status=array();
    $main_order_status=array();

  }
  $response->data = $arr;

}
echo json_output($response);
}

public function getMasterCategory_post()
{

  try
  {
      $admin_id=$this->input->post('admin_id');
      if(!empty($admin_id))
      {
        $result=$this->Supervisor->getMasterCategory($admin_id);
        if(!empty($result))
        {
          $arry=array('status'=>'1','data'=>$result);
          $this->response($arry, 200);
        }else
        {
          $arry=array('status'=>'0','data'=>'failed');
          $this->response($arry, 200);
        }

      }else
      {
        $arry=array('status'=>'0','data'=>'failed');
        $this->response($arry, 200);
      }
  }catch(Ecception $e)
  {
      $e->getMessage();
        $error = array('status' =>'0', "data" => "Internal Server Error - Please try Later.","StatusCode"=> "HTTP405");
        $this->response($error, 200);
  }
}
  public function get_closed_order_detail_for_restaurant_post()
{

  $response         =new StdClass();
  $result           =array();
  $result2          =array();
  $finalarray       =array();
  $arr = array();
  $arr2 = array();
  $menuImages = array();
  $menuquantity = array();
  $menuhalf_and_full_status = array();
  $menumenu_price = array();
  $admin_id         =$this->input->post('admin_id');
  $data = $this->Supervisor->getClosedOrder($admin_id);
  if(empty($data))
  {
    $response->status = 0;
    $response->message = "failed";

  }
  else
  {
   $response->status = 1;
   $response->message = "success";
   for($i=0;$i<count($data);$i++)
   {
    $result['order_id']     =$data[$i]['order_id'];
    $result['table_no']     =$data[$i]['table_no'];
    $result['order_status'] =$data[$i]['order_status'];
    $result['admin_id']     =$data[$i]['admin_id'];
    $result['new_order_id'] =str_replace($data[$i]['admin_id'].'-','',$data[$i]['order_id']);
    $menuResult =$this->Supervisor->getMenuItemForOrder($data[$i]['order_id'],$admin_id);

    foreach($menuResult as $menuValue)
    {
      $menuImages[]               =$menuValue['menu_item_name'];
      $menuquantity[]             =$menuValue['quantity'];
      $menuhalf_and_full_status[] =$menuValue['half_and_full_status'];
      $menumenu_price[]           =$menuValue['menu_price'];
      $menumenu_id[]              =$menuValue['id'];
      $menumenu_status[]          =$menuValue['status'];
      $menu_order_id[]            =$data[$i]['order_id'];
      $menu_order_table[]         =$data[$i]['table_no'];
      $main_order_status[]        =$data[$i]['status'];

    }
                // print_r(implode(',',$menuImages).',');exit;
    $result['menu_order_id']                =implode(',',$menu_order_id).',';
    $result['menu_order_table']                =implode(',',$menu_order_table).',';
    $result['id']                =implode(',',$menumenu_id).',';
    $result['menu_item_name']       =implode(',',$menuImages).',';
    $result['item_status']       =implode(',',$menumenu_status).',';
                // $result['menu_item_name']       =$data[$i]['menu_item_name'];
    $result['cus_id']       =$data[$i]['cus_id'];
    $result['quantity']       =implode(',',$menuquantity).',';
    $result['half_and_full_status']       =implode(',',$menuhalf_and_full_status).',';
    $result['main_order_status']       =implode(',',$main_order_status).',';
    $result['menu_price']       =implode(',',$menumenu_price).',';
    $result['total_item']       =$data[$i]['total_item'];
    $result['net_pay_amount']       =$data[$i]['net_pay_amount'];
    $result['gst_amount']       =$data[$i]['gst_amount'];
    $result['gst_amount_price']       =$data[$i]['gst_amount_price'];
    $result['order_status']       =$data[$i]['order_status'];
    $result['waiter_mobile_no']       =$mobile_no;
    $result['customer_mobile_no']       =$data[$i]['customer_mobile_no'];
    $result['create_slip_by']       =$data[$i]['create_slip_by'];
    $result['order_complete_by']       =$data[$i]['order_complete_by'];
    $result['order_delete_by']       =$data[$i]['order_delete_by'];
    $result['date']       =$data[$i]['date'];
    $result['modified_date']       =$data[$i]['modified_date'];
    $result['slip_status']       =$data[$i]['slip_status'];
    $result['payment_status']       =$data[$i]['payment_status'];
    $result['notification_status_by_staff']       =$data[$i]['notification_status_by_staff'];
    $result['NS_for_complete_by_waiter']       =$data[$i]['NS_for_complete_by_waiter'];
    $result['NS_for_kot_for_staff']       =$data[$i]['NS_for_kot_for_staff'];
    $result['NS_for_kitchen_for_staff']       =$data[$i]['NS_for_kitchen_for_staff'];
    $result['NS_for_complete_by_chef']       =$data[$i]['NS_for_complete_by_chef'];
    $result['NS_for_kitchen_for_waiter']       =$data[$i]['slip_status'];
    $result['notification_status_by_customer']       =$data[$i]['notification_status_by_customer'];
    $result['NS_for_complete_by_waiter_for_customer']       =$data[$i]['NS_for_complete_by_waiter_for_customer'];
    $result['NS_for_kot_for_customer']       =$data[$i]['NS_for_kot_for_customer'];
    $result['NS_for_kitchen_for_customer']       =$data[$i]['NS_for_kitchen_for_customer'];
    $result['payment_by']       =$data[$i]['payment_by'];
    $result['get_payment']       =$data[$i]['get_payment'];
    $result['status']       =$data[$i]['status'];
    $result['total_price']       =$data[$i]['total_price'];
    $result['discount']           =$data[$i]['discount'];

    $subOrderRes=$this->Supervisor->getSubOrder($data[$i]['order_id'],$admin_id);
    if(!empty($subOrderRes))
    {
      foreach ($subOrderRes as $value)
      {
        $result2['order_id']     =$data[$i]['order_id'];
        $result2['admin_id']     =$data[$i]['admin_id'];
        $result2['sub_order_id'] =$value['sub_order_id'];
        $menuResult2 =$this->Supervisor->getMenuItemForSubOrder($data[$i]['order_id'],$admin_id,$result2['sub_order_id']);
        foreach($menuResult2 as $menuValue2)
        {
          $menuImages2[]               =$menuValue2['menu_item_name'];
          $menuquantity2[]             =$menuValue2['quantity'];
          $menuhalf_and_full_status2[] =$menuValue2['half_and_full_status'];
          $menumenu_price2[]           =$menuValue2['menu_price'];
          $menu_id[]                   =$menuValue2['id'];
          $order_data[]                =$data[$i]['order_id'];
          $sub_order_id[]              =$value['sub_order_id'];
          $sub_order_status[]          =$menuValue2['status'];
          $main_sub_order_status[]     =$value['status'];

        } 
                    // print_r(implode(',',$menuImages2).',');exit;
        $result2['menu_item_name']=implode(',',$menuImages2).',';
        $result2['order_data_id']=implode(',',$order_data).',';
        $result2['sub_order_data_array']=implode(',',$sub_order_id).',';
        $result2['sub_order_status']=implode(',',$sub_order_status).',';
        $result2['menu_item_id']=implode(',',$menu_id).',';
                    // $result2['menu_item_name']=$value['menu_item_name'];
        $result2['quantity']=implode(',',$menuquantity2).',';
        $result2['half_and_full_status']=implode(',',$menuhalf_and_full_status2).',';
        $result2['main_sub_order_status']=implode(',', $main_sub_order_status).',';
        $result2['menu_price']=implode(',',$menumenu_price2).',';
        $result2['total_item']       =$value['total_item'];
        $result2['net_pay_amount']       =$value['net_pay_amount'];
        $result2['gst_amount']       =$value['gst_amount'];
        $result2['gst_amount_price']       =$value['gst_amount_price'];
        $result2['order_status']       =$value['order_status'];
        $result2['waiter_mobile_no']       =$value['waiter_mobile_no'];
        $result2['customer_mobile_no']       =$value['customer_mobile_no'];
        $result2['create_slip_by']       =$value['create_slip_by'];
        $result2['order_complete_by']       =$value['order_complete_by'];
        $result2['order_delete_by']       =$value['order_delete_by'];
        $result2['date']       =$value['date'];
        $result2['modified_date']       =$value['modified_date'];
        $result2['slip_status']       =$value['slip_status'];
        $result2['payment_status']       =$value['payment_status'];
        $result2['notification_status_by_staff']       =$value['notification_status_by_staff'];
        $result2['NS_for_complete_by_waiter']       =$value['NS_for_complete_by_waiter'];
        $result2['NS_for_kot_for_staff']       =$value['NS_for_kot_for_staff'];
        $result2['NS_for_kitchen_for_staff']       =$value['NS_for_kitchen_for_staff'];
        $result2['NS_for_complete_by_chef']       =$value['NS_for_complete_by_chef'];
        $result2['NS_for_kitchen_for_waiter']       =$value['slip_status'];
        $result2['notification_status_by_customer']       =$value['notification_status_by_customer'];
        $result2['NS_for_complete_by_waiter_for_customer']       =$value['NS_for_complete_by_waiter_for_customer'];
        $result2['NS_for_kot_for_customer']       =$value['NS_for_kot_for_customer'];
        $result2['NS_for_kitchen_for_customer']       =$value['NS_for_kitchen_for_customer'];
        $result2['payment_by']       =$value['payment_by'];
        $result2['get_payment']       =$value['get_payment'];
        $result2['status']       =$value['status'];
        $result2['total_price']       =$value['total_price'];
        $result2['table_no']     = $data[$i]['table_no'];
        $finalarray[]=$result2;
        $menuImages2=array();
        $menuquantity2=array();
        $menuhalf_and_full_status2=array();
        $menumenu_price2=array();
        //$menumenu_status=array();
        $menu_id=array();
        $order_data=array();
        $sub_order_id=array();
        $sub_order_status=array();
        $main_sub_order_status=array();
      }

    }
    $result['sub_order_data']     =$finalarray;
    array_push($arr, $result);
    $finalarray=array();
    $menuImages=array();
    $menuquantity=array();
    $menuhalf_and_full_status=array();
    $menumenu_price=array();
    $menumenu_id=array();
    $menu_order_id=array();
    $menu_order_table=array();
    $menumenu_status=array();
    $main_order_status=array();

  }
  $response->data = $arr;

}
echo json_output($response);
}
  public function get_closed_order_detail_for_restaurant_for_waiter_post()
{

  $response         =new StdClass();
  $result           =array();
  $result2          =array();
  $finalarray       =array();
  $arr = array();
  $arr2 = array();
  $menuImages = array();
  $menuquantity = array();
  $menuhalf_and_full_status = array();
  $menumenu_price = array();
  $admin_id         =$this->input->post('admin_id');
  $mobile_no        =$this->input->post('mobile_no');
  $data = $this->Supervisor->getClosedOrderForWaiter($admin_id,$mobile_no);
  if(empty($data))
  {
    $response->status = 0;
    $response->message = "failed";

  }
  else
  {
   $response->status = 1;
   $response->message = "success";
   for($i=0;$i<count($data);$i++)
   {
    // $result['id']           =$data[$i]['id'];
    $result['order_id']     =$data[$i]['order_id'];
    $result['table_no']     =$data[$i]['table_no'];
    $result['order_status'] =$data[$i]['order_status'];
    $result['admin_id']     =$data[$i]['admin_id'];
    $result['new_order_id'] =str_replace($data[$i]['admin_id'].'-','',$data[$i]['order_id']);
    $menuResult =$this->Supervisor->getMenuItemForOrder($data[$i]['order_id'],$admin_id);

    foreach($menuResult as $menuValue)
    {
      $menuImages[]               =$menuValue['menu_item_name'];
      $menuquantity[]             =$menuValue['quantity'];
      $menuhalf_and_full_status[] =$menuValue['half_and_full_status'];
      $menumenu_price[]           =$menuValue['menu_price'];
      $menumenu_id[]              =$menuValue['id'];
      $menumenu_status[]          =$menuValue['status'];
      $menu_order_id[]            =$data[$i]['order_id'];
      $menu_order_table[]         =$data[$i]['table_no'];
      $main_order_status[]        =$data[$i]['status'];

    }
                // print_r(implode(',',$menuImages).',');exit;
    $result['menu_order_id']                =implode(',',$menu_order_id).',';
    $result['menu_order_table']                =implode(',',$menu_order_table).',';
    $result['id']                =implode(',',$menumenu_id).',';
    $result['menu_item_name']       =implode(',',$menuImages).',';
    $result['item_status']       =implode(',',$menumenu_status).',';
                // $result['menu_item_name']       =$data[$i]['menu_item_name'];
    $result['cus_id']       =$data[$i]['cus_id'];
    $result['quantity']       =implode(',',$menuquantity).',';
    $result['half_and_full_status']       =implode(',',$menuhalf_and_full_status).',';
    $result['main_order_status']       =implode(',',$main_order_status).',';
    $result['menu_price']       =implode(',',$menumenu_price).',';
    $result['total_item']       =$data[$i]['total_item'];
    $result['net_pay_amount']       =$data[$i]['net_pay_amount'];
    $result['gst_amount']       =$data[$i]['gst_amount'];
    $result['gst_amount_price']       =$data[$i]['gst_amount_price'];
    $result['order_status']       =$data[$i]['order_status'];
    $result['waiter_mobile_no']       =$mobile_no;
    $result['customer_mobile_no']       =$data[$i]['customer_mobile_no'];
    $result['create_slip_by']       =$data[$i]['create_slip_by'];
    $result['order_complete_by']       =$data[$i]['order_complete_by'];
    $result['order_delete_by']       =$data[$i]['order_delete_by'];
    $result['date']       =$data[$i]['date'];
    $result['modified_date']       =$data[$i]['modified_date'];
    $result['slip_status']       =$data[$i]['slip_status'];
    $result['payment_status']       =$data[$i]['payment_status'];
    $result['notification_status_by_staff']       =$data[$i]['notification_status_by_staff'];
    $result['NS_for_complete_by_waiter']       =$data[$i]['NS_for_complete_by_waiter'];
    $result['NS_for_kot_for_staff']       =$data[$i]['NS_for_kot_for_staff'];
    $result['NS_for_kitchen_for_staff']       =$data[$i]['NS_for_kitchen_for_staff'];
    $result['NS_for_complete_by_chef']       =$data[$i]['NS_for_complete_by_chef'];
    $result['NS_for_kitchen_for_waiter']       =$data[$i]['slip_status'];
    $result['notification_status_by_customer']       =$data[$i]['notification_status_by_customer'];
    $result['NS_for_complete_by_waiter_for_customer']       =$data[$i]['NS_for_complete_by_waiter_for_customer'];
    $result['NS_for_kot_for_customer']       =$data[$i]['NS_for_kot_for_customer'];
    $result['NS_for_kitchen_for_customer']       =$data[$i]['NS_for_kitchen_for_customer'];
    $result['payment_by']       =$data[$i]['payment_by'];
    $result['get_payment']       =$data[$i]['get_payment'];
    $result['status']       =$data[$i]['status'];
    $result['total_price']       =$data[$i]['total_price'];
    $result['discount']           =$data[$i]['discount'];

    $subOrderRes=$this->Supervisor->getSubOrder($data[$i]['order_id'],$admin_id);
    if(!empty($subOrderRes))
    {
      foreach ($subOrderRes as $value)
      {
        $result2['order_id']     =$data[$i]['order_id'];
        $result2['admin_id']     =$data[$i]['admin_id'];
        $result2['sub_order_id'] =$value['sub_order_id'];
        $menuResult2 =$this->Supervisor->getMenuItemForSubOrder($data[$i]['order_id'],$admin_id,$result2['sub_order_id']);
        foreach($menuResult2 as $menuValue2)
        {
          $menuImages2[]               =$menuValue2['menu_item_name'];
          $menuquantity2[]             =$menuValue2['quantity'];
          $menuhalf_and_full_status2[] =$menuValue2['half_and_full_status'];
          $menumenu_price2[]           =$menuValue2['menu_price'];
          $menu_id[]                   =$menuValue2['id'];
          $order_data[]                =$data[$i]['order_id'];
          $sub_order_id[]              =$value['sub_order_id'];
          $sub_order_status[]          =$menuValue2['status'];
          $main_sub_order_status[]     =$value['status'];

        } 
                    // print_r(implode(',',$menuImages2).',');exit;
        $result2['menu_item_name']=implode(',',$menuImages2).',';
        $result2['order_data_id']=implode(',',$order_data).',';
        $result2['sub_order_data_array']=implode(',',$sub_order_id).',';
        $result2['sub_order_status']=implode(',',$sub_order_status).',';
        $result2['menu_item_id']=implode(',',$menu_id).',';
                    // $result2['menu_item_name']=$value['menu_item_name'];
        $result2['quantity']=implode(',',$menuquantity2).',';
        $result2['half_and_full_status']=implode(',',$menuhalf_and_full_status2).',';
        $result2['main_sub_order_status']=implode(',', $main_sub_order_status).',';
        $result2['menu_price']=implode(',',$menumenu_price2).',';
        $result2['total_item']       =$value['total_item'];
        $result2['net_pay_amount']       =$value['net_pay_amount'];
        $result2['gst_amount']       =$value['gst_amount'];
        $result2['gst_amount_price']       =$value['gst_amount_price'];
        $result2['order_status']       =$value['order_status'];
        $result2['waiter_mobile_no']       =$value['waiter_mobile_no'];
        $result2['customer_mobile_no']       =$value['customer_mobile_no'];
        $result2['create_slip_by']       =$value['create_slip_by'];
        $result2['order_complete_by']       =$value['order_complete_by'];
        $result2['order_delete_by']       =$value['order_delete_by'];
        $result2['date']       =$value['date'];
        $result2['modified_date']       =$value['modified_date'];
        $result2['slip_status']       =$value['slip_status'];
        $result2['payment_status']       =$value['payment_status'];
        $result2['notification_status_by_staff']       =$value['notification_status_by_staff'];
        $result2['NS_for_complete_by_waiter']       =$value['NS_for_complete_by_waiter'];
        $result2['NS_for_kot_for_staff']       =$value['NS_for_kot_for_staff'];
        $result2['NS_for_kitchen_for_staff']       =$value['NS_for_kitchen_for_staff'];
        $result2['NS_for_complete_by_chef']       =$value['NS_for_complete_by_chef'];
        $result2['NS_for_kitchen_for_waiter']       =$value['slip_status'];
        $result2['notification_status_by_customer']       =$value['notification_status_by_customer'];
        $result2['NS_for_complete_by_waiter_for_customer']       =$value['NS_for_complete_by_waiter_for_customer'];
        $result2['NS_for_kot_for_customer']       =$value['NS_for_kot_for_customer'];
        $result2['NS_for_kitchen_for_customer']       =$value['NS_for_kitchen_for_customer'];
        $result2['payment_by']       =$value['payment_by'];
        $result2['get_payment']       =$value['get_payment'];
        $result2['status']       =$value['status'];
        $result2['total_price']       =$value['total_price'];
        $result2['table_no']     = $data[$i]['table_no'];
        $finalarray[]=$result2;
        $menuImages2=array();
        $menuquantity2=array();
        $menuhalf_and_full_status2=array();
        $menumenu_price2=array();
        //$menumenu_status=array();
        $menu_id=array();
        $order_data=array();
        $sub_order_id=array();
        $sub_order_status=array();
        $main_sub_order_status=array();
      }

    }
    $result['sub_order_data']     =$finalarray;
    array_push($arr, $result);
    $finalarray=array();
    $menuImages=array();
    $menuquantity=array();
    $menuhalf_and_full_status=array();
    $menumenu_price=array();
    $menumenu_id=array();
    $menu_order_id=array();
    $menu_order_table=array();
    $menumenu_status=array();
    $main_order_status=array();

  }
  $response->data = $arr;

}
echo json_output($response);
}
public function addItemCategory_post()
{
  $item_category    =$_POST['item_category'];
  $gst              =$_POST['gst'];
  $admin_id         =$_POST['admin_id'];
  if(!empty($item_category)&&!empty($admin_id)&!empty($gst))
  {

    $array=array(
                'admin_id'=>$admin_id,
                'category_name'=>$item_category,
                'gst'=>$gst,
                'status'=>1,
                'creation_date'=>date('Y-m-d H:i:s')
              );
    $result=$this->Supervisor->addItemCategory($array);
    if($result)
    {
      $arry=array('status'=>'1','data'=>'success');
        $this->response($arry, 200);
    }else
    {
      $arry=array('status'=>'0','data'=>'failed');
        $this->response($arry, 200);
    }
        
  }else
  {
        $arry=array('status'=>'0','data'=>'failed');
        $this->response($arry, 200);
  }
}
public function updateGstStatus_post()
{
   $admin_id         =$_POST['admin_id'];
   $id               =$_POST['id'];
   $status           =$_POST['status'];
   if(!empty($admin_id) && !empty($id) && !empty($status))
   {
    $result=$this->Supervisor->updateStatus($admin_id,$id,$status);
      if($result)
      {
        $arry=array('status'=>'1','data'=>'success');
        $this->response($arry, 200);
      }else
      {
         $arry=array('status'=>'0','data'=>'failed');
         $this->response($arry, 200);
      }
   }else
   {
    $arry=array('status'=>'0','data'=>'failed');
        $this->response($arry, 200);
   }
}
public function loginStatus_post()
{
  $mobile_no=$this->input->post('mobile_no');
  $device_id=$this->input->post('device_id');
  $admin_id =$this->input->post('admin_id');
  if(!empty($mobile_no)&& !empty($device_id))
  {
    $result=$this->Supervisor->checkLoginStatus($mobile_no,$device_id);
    $result2=$this->Supervisor->checkLoginStatusForEmp($mobile_no,$admin_id);
    $result3=$this->Supervisor->checkLoginStatusForStoff($mobile_no,$admin_id);
    if(!empty($result)&&(!empty($result3) || !empty($result2)))
    {
      $arry=array('status'=>'1','data'=>'success');
      $this->response($arry, 200);
    }else
    {
        $arry=array('status'=>'0','data'=>'failed');
        $this->response($arry, 200);
    }
  }else
  {
    $arry=array('status'=>'0','data'=>'failed');
    $this->response($arry, 200);
  }
}
public function addRestaurantCategory_post()
{
  // print_r('expression');exit;
  try
  {
    $admin_id=$this->input->post('admin_id');
    $cat_name=$this->input->post('cat_name');
    if(empty($admin_id)||empty($cat_name))
      {

         $error=array('status'=>'0','message'=>'failed');
         $this->response($error, 200);

      }else
      {
        $max_id=$this->Supervisor->getMaxCatId();
        $array=array(
                    'cat_id'        =>empty($max_id)?'1':$max_id,
                    'cat_name'      =>$cat_name,
                    'admin_id'      =>$admin_id,
                    'creation_date' =>date('Y-m-d H:i:s'),
                    'status'        =>1
        );
        // print_r($array);exit;
        $result=$this->Supervisor->addCategory($array);
        if(!empty($result))
        {
          $aray=array('status'=>'1','message'=>'success');
          $this->response($aray, 200);
        }else
        {
          $aray=array('status'=>'0','message'=>'failed');
          $this->response($aray, 200);
        }


      }
  }catch(Ececption $e)
  {
    echo $e->getMessage();
    $error = array('status' =>'0', "data" => "Internal Server Error - Please try Later.","StatusCode"=> "HTTP405");
    $this->response($error, 200);

  }

}
public function addRestaurantSubCategory_post()
{
  try
  {
        $admin_id=$this->input->post('admin_id');
        $cat_id=$this->input->post('cat_id');
        $sub_cat_name=$this->input->post('sub_cat_name');

    if(empty($admin_id)||empty($cat_id)||empty($sub_cat_name))
      {
         
         $error=array('status'=>'0','message'=>'failed');
         $this->response($error, 200);

      }else
      {
 
        $max_id=$this->Supervisor->getMaxSubCatId();
        $array=array(
                    'cat_id'            =>$cat_id,
                    'sub_cat_id'        =>empty($max_id)?'1':$max_id,
                    'sub_cat_name'      =>$sub_cat_name,
                    'admin_id'          =>$admin_id,
                    'creation_date'     =>date('Y-m-d H:i:s'),
                    'status'            =>1
        );
        $result=$this->Supervisor->addSubCategory($array);
        if(!empty($result))
        {
          $aray=array('status'=>'1','message'=>'success');
          $this->response($aray, 200);
        }else
        {
          $aray=array('status'=>'0','message'=>'failed');
          $this->response($aray, 200);
        }

      }
  }catch(Ececption $e)
  {
    echo $e->getMessage();
    $error = array('status' =>'0', "data" => "Internal Server Error - Please try Later.","StatusCode"=> "HTTP405");
    $this->response($error, 200);

  }

}
public function getRestaurantCategory_post()
{
  try
  {
      $admin_id=$this->input->post('admin_id');

      $cat_result=$this->Supervisor->getCatIds($admin_id);

      $string='';

      if(!empty($cat_result))
      {
            foreach($cat_result AS $value)
          {

             $string .= "'".$value['cat_id']."'".',';
          }
      }

      if(!empty($admin_id))
      {

          
          if(!empty($string))
          {
            $result=$this->Supervisor->getRestaurantCategory($admin_id,rtrim($string,','));
            if(!empty($result))
            {
                 $aray=array('status'=>'1','data'=>$result,'message'=>'Success');
                 $this->response($aray, 200);
            }else
            {
              $aray=array('status'=>'0','message'=>'failed');
             $this->response($aray, 200);
            }
          }else
          {
            $aray=array('status'=>'0','message'=>'failed');
             $this->response($aray, 200);
          }


          if(!empty($result))
          {
               $aray=array('status'=>'1','data'=>$result);
               $this->response($aray, 200);
          }else
          {
            $aray=array('status'=>'0','message'=>'failed');
          $this->response($aray, 200);
          }
         

      }else
      {
          $aray=array('status'=>'0','message'=>'failed');
          $this->response($aray, 200);
      }
  }catch(Ececption $e)
  {
    echo $e->getMessage();
    $error = array('status' =>'0', "data" => "Internal Server Error - Please try Later.","StatusCode"=> "HTTP405");
    $this->response($error, 200);
  }
  
}
public function getRestaurantSubCategory_post()
{
  try
  {
      $admin_id=$this->input->post('admin_id');
      $cat_id=$this->input->post('cat_id');
      if(!empty($admin_id)&&!empty($cat_id))
      {

          $result=$this->Supervisor->getRestaurantSubCategory($admin_id,$cat_id);
          // print_r($result);
          $newArray[count($result)]=array(
                                    'id'      =>'NA',
                                    'sub_cat_id'  =>'NA',
                                    'cat_id'    =>'NA',
                                    'sub_cat_name'  =>'NA',
                                    'admin_id'    =>'NA',
                                    'creation_date'=>'NA',
                                    'status'=>'1'
                                  );
           
          if(!empty(array_merge($result,$newArray)))
          {
               $aray=array('status'=>'1','data'=>array_merge($result,$newArray),'message'=>'success');
               $this->response($aray, 200);
          }else
          {
            $aray=array('status'=>'0','message'=>'failed');
            $this->response($aray, 200);
          }

      }else
      {
          $aray=array('status'=>'0','message'=>'failed');
          $this->response($aray, 200);
      }
  }catch(Ececption $e)
  {
    echo $e->getMessage();
    $error = array('status' =>'0', "data" => "Internal Server Error - Please try Later.","StatusCode"=> "HTTP405");
    $this->response($error, 200);
  }
  
}
  public function getMenuListDataRestaurant_post()
  {
       $admin_id=$this->input->post('admin_id');
      $cat_id=$this->input->post('cat_id');
      $data=array();
      $result=array();
      $array=array();
      $array3=array();
      $data2=array();
      $data3=array();
      $data5=array();
      $data4=array(); 
      $result3=array();
      if(!empty($admin_id)&&!empty($cat_id))
      {
  
          $result=$this->Customer->getSubcatId($admin_id,$cat_id);
          // print_r($result);exit;
          if(!empty($result))
          {         
                    $i=0;
                    foreach($result as $value)
                    {
                      $data['sub_cat_name']=$value['sub_cat_name'];
                      $data['sub_cat_id']=$value['sub_cat_id'];
                      $result2=$this->Customer->getSubCatMenuItems($value['sub_cat_id'],$cat_id,$admin_id);
                      // print_r($result2);exit;
                      if(!empty($result2))
                      {
                          foreach($result2 as $value2)
                          {
                            $data2['menu_name']=$value2['menu_name'];
                            $data2['menu_category_id']=$value2['menu_category_id'];
                            
                            $gst       =$this->Supervisor->getGst($value2['menu_category_id'],$admin_id);
                            $menuhalfprice=$value2['menu_half_price'];
                            if(!empty($menuhalfprice))
                              {
                                $menu_half_price=$value2['menu_half_price'];
                                $menu_half_price_gst =($menu_half_price)*$gst/100;
                              }
                              else
                              {
                                $menu_half_price='';
                                 $menu_half_price_gst='';
                              }
                              $menufullprice=$value2['menu_full_price'];
                              if(!empty($menufullprice))
                              {
                                $menu_full_price=$value2['menu_full_price'];
                                $menu_full_price_gst =($menu_full_price)*$gst/100;

                              }
                              else
                              {
                                $menu_full_price='';
                                $menu_full_price_gst='';
                              }
                              $menufixprice=$value2['menu_fix_price'];
                              if(!empty($menufixprice))
                              {
                                $menu_fix_price=$value2['menu_fix_price'];
                                $menu_fix_price_gst =($menu_fix_price)*$gst/100;
                              }
                              else
                              {
                                $menu_fix_price='';
                                $menu_fix_price_gst='';
                              }
                              $nutrientcounts=$value2['nutrient_counts'];
                              if(!empty($nutrientcounts))
                                {
                                  $nutrient_counts=$value2['nutrient_counts'];
                                }
                                else
                                {
                                  $nutrient_counts='';
                                }
                            $data2['menu_id'] =   $value2['menu_id'];
                            $data2['cat_id'] =   $value2['cat_id'];
                            if(!empty($value2['cat_id']))
                                {
                                   $cat_name=$this->Customer->getCatName($value2['cat_id'],$admin_id);
                                }else
                                {
                                   $cat_name="";
                                }
                            $data2['sub_cat_id'] = $value2['sub_cat_id'];
                            $data2['admin_id'] =   $value2['admin_id'];
                            $data2['qty'] =   '0';
                            $data2['half_qty'] =  '0';
                            $data2['full_qty'] =   '0';
                            $data2['positions'] =   "$i";
                            $data2['shalfFull'] =   '';
                            $data2['quantityStatus'] =   '';
                            $data2['quantityStatusHalf'] =   '';
                            $data2['quantityStatusFull'] =   '';
                            $data2['halfQuantityStatus'] =   '';
                            $data2['fullType'] =   '';
                            $data2['fullQuantityStatus'] =   '';
                            $data2['admin_id'] =   $value2['admin_id'];
                            $data2['menu_food_type']=$value2['menu_food_type'];
                            $data2['cat_name'] =   $cat_name;
                            $data2['menu_name'] =   $value2['menu_name'];
                            // $data2['menu_image'] =   $value2['menu_image'] !=''?base_url().'uploads/'.$value2['menu_image']:'';
                           $data2['menu_image'] =   $value2['menu_image'] !=''?base_url().'uploads/'.$value2['menu_image']:'';
                            $data2['menu_detail'] =   $value2['menu_detail'];
                            $data2['menu_half_price'] =   $menu_half_price;
                            $data2['menu_full_price'] =  $menu_full_price;
                            $data2['menu_fix_price'] =   $menu_fix_price;
                            $data2['nutrient_counts'] =   $nutrient_counts;
                            $data2['gst'] =  "$gst";
                            $data2['menu_half_price_gst'] = "$menu_half_price_gst";
                            $data2['menu_full_price_gst'] = "$menu_full_price_gst";
                            $data2['menu_fix_price_gst'] =  "$menu_fix_price_gst";          
                            $data2['message'] = 'Success';
                            $data2['status']  ='1';
                            $array[]=$data2;
                          }
                      }
                      $i=$i+1;
                       $data['foodItem']=$array;
                       array_push($result3, $data);
                       $data2=array();
                       $array=array();
                       $data=array();

                    }
          }
          $result4=$this->Customer->getNaSubCatMenuItems($cat_id,$admin_id);
          if(!empty($result4))
          {
                $data4['sub_cat_name']='Others';
                $data4['sub_cat_id']='1';
                if(!empty($result)){
                    $j=$i;
                }else{
                  $j=0;
                }                
            foreach($result4 as $value3)
            {           
                            $data3['menu_name']=$value3['menu_name'];
                            $data3['menu_category_id']=$value3['menu_category_id'];
                            $gst3       =$this->Supervisor->getGst($value3['menu_category_id'],$admin_id);
                            $menuhalfprice3=$value3['menu_half_price'];
                            if(!empty($menuhalfprice3))
                              {
                                $menu_half_price3=$value3['menu_half_price'];
                                $menu_half_price_gst3 =($menu_half_price3)*$gst3/100;
                              }
                              else
                              {
                                $menu_half_price3='';
                                $menu_half_price_gst3='';
                              }
                              $menufullprice3=$value3['menu_full_price'];
                              if(!empty($menufullprice3))
                              {
                                $menu_full_price3=$value3['menu_full_price'];
                                $menu_full_price_gst3 =($menu_full_price3)*$gst3/100;

                              }
                              else
                              {
                                $menu_full_price3='';
                                $menu_full_price_gst3='';
                              }
                              $menufixprice3=$value3['menu_fix_price'];
                              if(!empty($menufixprice3))
                              {
                                $menu_fix_price3=$value3['menu_fix_price'];
                                $menu_fix_price_gst3 =($menu_fix_price3)*$gst3/100;
                              }
                              else
                              {
                                $menu_fix_price3='';
                                $menu_fix_price_gst3='';
                              }
                              $nutrientcounts3=$value3['nutrient_counts'];
                              if(!empty($nutrientcounts3))
                                {
                                  $nutrient_counts3=$value3['nutrient_counts'];
                                }
                                else
                                {
                                  $nutrient_counts3='';
                                }
                            $data3['menu_id'] =   $value3['menu_id'];
                            $data3['cat_id'] =   $value3['cat_id'];
                            if(!empty($value3['cat_id']))
                                {
                                   $cat_name3=$this->Customer->getCatName($value3['cat_id'],$admin_id);
                                }else
                                {
                                   $cat_name3="";
                                }
                            $data3['sub_cat_id'] = '1';
                            $data3['admin_id'] =   $value3['admin_id'];
                            $data3['qty'] =   '0';
                            $data3['half_qty'] =  '0';
                            $data3['full_qty'] =   '0';
                            $data3['positions'] =   "$j";
                            $data3['shalfFull'] =   '';
                            $data3['quantityStatus'] =   '';
                            $data3['quantityStatusHalf'] =   '';
                            $data3['quantityStatusFull'] =   '';
                            $data3['halfQuantityStatus'] =   '';
                            $data3['fullType'] =   '';
                            $data3['fullQuantityStatus'] =   '';
                            $data3['menu_food_type']=$value3['menu_food_type'];
                            $data3['cat_name'] =   $cat_name3;

                            $data3['menu_name'] =   $value3['menu_name'];
                            // $data3['menu_image'] =   $value3['menu_image'] !=''?base_url().'uploads/'.$value3['menu_image']:'';
                            $data3['menu_image'] =   $value3['menu_image'] !=''?base_url().'uploads/'.$value3['menu_image']:'';
                            $data3['menu_detail'] =   $value3['menu_detail'];
                            $data3['menu_half_price'] =   $menu_half_price3;
                            $data3['menu_full_price'] =  $menu_full_price3;
                            $data3['menu_fix_price'] =   $menu_fix_price3;
                            $data3['nutrient_counts'] =   $nutrient_counts3;
                            $data3['gst'] =  "$gst3";
                            $data3['menu_half_price_gst'] = "$menu_half_price_gst3";
                            $data3['menu_full_price_gst'] = "$menu_full_price_gst3";
                            $data3['menu_fix_price_gst'] =  "$menu_fix_price_gst3";          
                            $data3['message'] = 'Success';
                            $data3['status']  ='1';
                            $data4['foodItem'][]=$data3;
                            $j=$j+1;
            }
          }
           if(!empty($data4))
           {
               $data5[count($result3)]=$data4;         
               $response->status ='1';
               $response->data = array_merge($result3,$data5);
               echo json_output($response);
           }else
           {        
              $response->status ='1';
              $response->data = array_merge($result3);
              echo json_output($response);
           }
           
          // }else
          // {
          //   $arry['data']=array('status'=>'0','data'=>'failed');
          //   $this->response($arry, 200);
          // }
          
      }else
      {
            $arry['data']=array('status'=>'0','data'=>'failed');
            $this->response($arry, 200);
      }      

  }

  function getAllItemsOfPerticulatOrder_post()
  {

    try{
      $order_array=array();
      $sub_order_array=array();

      if($this->form_validation->run('order_validation')==FALSE){

            $arry=array('status'=>'0','data'=>'failed');
            $this->response($arry, 200);
      }else{
        $order_id           =$_POST['order_id'];
        $admin_id           =$_POST['admin_id'];
        $OrderItemData      =$this->Supervisor->getOrderItems($order_id,$admin_id);
        $subOrderItemData   =$this->Supervisor->getSubOrderItems($order_id,$admin_id);
        $arry=array('status'=>'1','data'=>array_merge_recursive($OrderItemData, $subOrderItemData));
        $this->response($arry, 200);
      }
    }catch(Ececption $e){
      $e->getMessage();
      $error = array('status' =>'0', "data" => "Internal Server Error - Please try Later.","StatusCode"=> "HTTP405");
      $this->response($error, 200);
    }

  }
   public function getRestaurantTypes_Post(){
    try{
      $result=$this->Supervisor->getRestaurantTypes();
      if(!empty($result)){
        echo json_encode(array('status'=>1,'data'=>$result));
      }else{
        echo json_encode(array('status'=>0,'data'=>'No record found.'));
      }
    }catch(Ececption $e){
      $e->getMessage();
      $error = array('status' =>'0', "data" => "Internal Server Error - Please try Later.","StatusCode"=> "HTTP405");
      $this->response($error, 200);
    }
  }
  public function getCategoryForRestaurant_post()
{
  try
  {
      $admin_id=$this->input->post('admin_id');

      if(!empty($admin_id))
      {
         $result=$this->Supervisor->getCategoryRestaurant($admin_id);
          if(!empty($result))
          {
               $aray=array('status'=>'1','data'=>$result,'message'=>'success');
               $this->response($aray, 200);
          }else
          {
            $aray=array('status'=>'0','message'=>'failed');
          $this->response($aray, 200);
          }
         

      }else
      {
          $aray=array('status'=>'0','message'=>'failed');
          $this->response($aray, 200);
      }
  }catch(Ececption $e)
  {
    echo $e->getMessage();
    $error = array('status' =>'0', "data" => "Internal Server Error - Please try Later.","StatusCode"=> "HTTP405");
    $this->response($error, 200);
  }
  
}
public function getFoodType_get(){
 try{
   $result=$this->Supervisor->getFoodCategory();
   if(!empty($result)){
          $aray=array('status'=>'1','message'=>'success','data'=>$result);
          $this->response($aray, 200);
   }else{
          $aray=array('status'=>'0','message'=>'failed');
          $this->response($aray, 200);
   }
 }catch(Ececption $e){
    echo $e->getMessage();
    $error = array('status' =>'0', "data" => "Internal Server Error - Please try Later.","StatusCode"=> "HTTP405");
    $this->response($error, 200);
 }
}
public function sendEmail_Post(){
  try{
    $valid=validate_mobile($_POST['mobile_num']);
    if($valid=='0' || $valid==0)
    {
      echo json_encode(array('status'=>0,'data'=>'Please enter a valid mobile number'));
      exit;
    }
    $admin=$this->Supervisor->getAdminInfo($_POST['mobile_num']);
    if(!empty($admin)){
      $this->mailData($admin[0]);
    }
    else{
      $master=$this->Supervisor->getMasterInfo($_POST['mobile_num']);
      if(!empty($master)){
        $this->mailData($master[0]);
      }else{
        $staff=$this->Supervisor->getStaffInfo($_POST['mobile_num']);
        if(!empty($staff)){
          $this->mailData($staff[0]);
        }
        else{
          echo json_encode(array('status'=>0,'data'=>'This mobile no. is not exist'));
        }
      }
    }
  }catch(Ececption $e){
    $e->getMessage();
    $error = array('status' =>0, "data" => "Internal Server Error - Please try Later.","StatusCode"=> "HTTP405");
    $this->response($error, 200);
  }
}
public function mailData($data)
{        
    $mail_data=array(); 
    $mail_data['name']=$data['user_fullname'];
    $mail_data['email']=$data['user_email'];
    $mail_data['message']='';
    $mail_data['subject']='Reset password';
    $email=$data['user_email'];
    $mobile_no=$data['mobile_no'];
    $now = date('Y-m-d H:i:s');
    $fetchData['data']=array('email'=>$email,'mobile_no'=>$mobile_no,'time'=>$now);
    $fetchData=json_encode($fetchData);
    $fetchData=base64_encode($fetchData);
    $body.='Hello '.ucfirst($data['user_fullname']).',<br><br>
    Forgot your password? 
    To reset your password, click on the below link:
    <br><br>
      <a href="'.base_url().'index.php/Login/resetPassword/'.$fetchData.'">click here</a>
<br><br>
      Thanks & Regards
      <br>
      OYLY';
    $mail_data['body']=$body;
    $this->mailSend($mail_data);
}
public function mailSend($info)
{   
    $response = new StdClass();
    $result2 = new StdClass();
    $mail = new PHPMailer;
    $mail->isSMTP();                                     
    $mail->Host = 'smtp.ipage.com';  
    $mail->SMTPAuth = true;                              
    $mail->Username = 'admin.2@goolean.com';                
    $mail->Password = 'Abcd1234';                          
    $mail->SMTPSecure = 'tls';                        
    $mail->Port = 587; 
    $name=$info['name'];
    $email=$info['email'];
    $message=$info['message'];
    date_default_timezone_set('Asia/kolkata'); 
    $now = date('Y-m-d H:i:s');
    $data->name=$name;
    $data->email=$email;
    $data->message=$message;
    $data->create_date=$now;
    $data->status='1';
        $mail->From ='anjali.rawat@goolean.tech';
        $mail->FromName ='OYLY Admin';
        $mail->addAddress($email,'Admin');
       //$mail->addCC('anjali.rawat@goolean.tech');
        // $mail->addBCC('pankaj.kumar@goolean.tech');
        $mail->isHTML(true);
        $mail-> Subject= $info['subject'];
        $mail-> Body=$info['body'];
        if(!$mail->Send())
        {
          $data2->status =0;
          $data2->data = "Error sending: " . $mail->ErrorInfo;
          array_push($result2,$data2);
          $response= $data2;
        }
        else
        {
          $data2->status =1;
          $data2->data = 'Email sent successfully';
          array_push($result2,$data2);
          $response= $data2;
        }
    echo  json_output($response);
}
public function kycVerification_post(){
  try{
    $admin_id=$this->input->post('admin_id');
    if(!empty($admin_id))
    {
      $get_kyc=$this->Supervisor->checkKycExist($admin_id);
      if(!empty($get_kyc))
      {
        $aray=array('status'=>'0','data'=>'Kyc allready done');
        $this->response($aray, 200);
      }else{
        $docId=$this->Supervisor->getMaxDocId();
        $kyc['doc_id']=$docId;
        $kyc['admin_id']=$this->input->post('admin_id');
    
        $get_registration_doc=$this->input->post('registration_doc');
        if(!empty($get_registration_doc)){
          $registration_doc=image_upload($get_registration_doc, $kyc['admin_id'],'registrationDoc/');
        }else{
          $registration_doc='';
        }
        $kyc['registration_doc']=$registration_doc;
        $kyc['registration_no	']=$this->input->post('registration_no');
    
        $get_licence_doc=$this->input->post('licence_doc');
        if(!empty($get_licence_doc)){
          $licence_doc=image_upload($get_licence_doc, $kyc['admin_id'],'licenceDoc/');
        }else{
          $licence_doc='';
        }
        $kyc['licence_doc']=$licence_doc;
    
        $kyc['licence_no']=$this->input->post('licence_no');
    
        // $get_gst_doc=$this->input->post('gst_doc');
        // if(!empty($get_registration_doc)){
        //   $gst_doc=image_upload($get_gst_doc, $kyc['admin_id'],'gstDoc/');
        // }else{
        //   $gst_doc='';
        // }
        // $kyc['gst_doc']=$gst_doc;
    
        // $kyc['gst_no']=$this->input->post('gst_no');
        $kyc['gst_doc']='';
        $kyc['gst_no']='';
        $get_shop_act_licenece_doc=$this->input->post('shop_act_licenece_doc');
        if(!empty($get_shop_act_licenece_doc)){
          $shop_act_licenece_doc=image_upload($get_shop_act_licenece_doc, $kyc['admin_id'],'shopActLicenceDoc/');
        }else{
          $shop_act_licenece_doc='';
        }
        $kyc['shop_act_licenece_doc']=$shop_act_licenece_doc;
    
        $kyc['shop_act_licenece_no']=$this->input->post('shop_act_licenece_no');
        $get_uaid_doc=$this->input->post('uaid_doc');
        if(!empty($get_uaid_doc)){
          $uaid_doc=image_upload($get_uaid_doc, $kyc['admin_id'],'uaidDoc/');
        }else{
          $uaid_doc='';
        }
        $kyc['uaid_doc']=$uaid_doc;
    
        $kyc['uaid_no']=$this->input->post('uaid_no');
    
        $get_pan_doc=$this->input->post('pan_doc');
        if(!empty($get_pan_doc)){
          $pan_doc=image_upload($get_pan_doc, $kyc['admin_id'],'panDoc/');
        }else{
          $pan_doc='';
        }
        $kyc['pan_doc']=$pan_doc;
    
        $kyc['pan_no']=$this->input->post('pan_no');
    
        // $get_acc_doc=$this->input->post('acc_doc');
        // if(!empty($get_acc_doc)){
        //   $acc_doc=image_upload($get_acc_doc, $kyc['admin_id'],'accountDoc/');
        // }else{
        //   $acc_doc='';
        // }
        // $kyc['acc_doc']=$acc_doc;
        $kyc['acc_doc']='';
        $kyc['acc_no']=$this->input->post('acc_no');
        // $kyc['acc_holder_name']=$this->input->post('acc_holder_name');
        $kyc['acc_holder_name']='';
        $kyc['ifsc']=$this->input->post('ifsc');
        $kyc['creation_date']=date('Y-m-d H:i:s');
        $kyc['status']=0;
        $masterId=$this->Supervisor->addKycInfo($kyc);
        if(!empty($masterId)){
          $result=$this->Supervisor->updateSpots($docId,$kyc['admin_id']);
          if($result)
          {
            $arry=array('status'=>'1','data'=>'success');
            $this->response($arry, 200);
          }else
          {
             $arry=array('status'=>'0','data'=>'failed');
             $this->response($arry, 200);
          }
        }else{
               $aray=array('status'=>'0','data'=>'failed');
               $this->response($aray, 200);
        }
      }
    }
    else{
      $aray=array('status'=>'0','data'=>'failed');
      $this->response($aray, 200);
    }


  }catch(Ececption $e){
     echo $e->getMessage();
     $error = array('status' =>'0', "data" => "Internal Server Error - Please try Later.","StatusCode"=> "HTTP405");
     $this->response($error, 200);
  }
 }
 public function salesReportData_post()
{
  try
  {
      $admin_id=$this->input->post('admin_id');
      $from_date=$this->input->post('from_date');
      $to_date=$this->input->post('to_date');
      if(!empty($admin_id)&&!empty($from_date)&&!empty($to_date))
      {

          $result=$this->Supervisor->getsalesReport($admin_id,$from_date,$to_date);
          // print_r($result);
          // die;
          if(!empty($result))
          {
            $final_array=array();
            $count=count($result);
            $i=1;
            foreach($result as $value)
            {
              $create_date=date('Y-m-d',strtotime($value['creation_date']));
              $final_array[$create_date]['date']=$create_date;
              $final_array[$create_date]['total_amount']+=round($value['total_order_amount'],2);
             // Discount%=(Original Price - Sale price)/Original price*100
              $final_array[$create_date]['discount']+=$value['discount'];
              $final_array[$create_date]['discount_amount']+=round($value['discount_amount'],2);
              $final_array[$create_date]['net_sales']+=round(($value['total_order_amount']-$value['discount_amount']),2);
              if(isset($value['get_payment']) && $value['get_payment']!=NULL && $value['get_payment']!='')
              {
                if(isset($final_array[$create_date]['channel']))
                {
                  $final_array[$create_date]['channel'].=','.$value['get_payment'];
                }else{
                  $final_array[$create_date]['channel'].=$value['get_payment'];

                }
              }
              if(isset($value['payment_mode']) && $value['payment_mode']!=NULL && $value['payment_mode']!='')
              {
                if(isset($final_array[$create_date]['channel']))
                {
                  $final_array[$create_date]['channel'].=','.$value['payment_mode'];
                }else{
                  $final_array[$create_date]['channel'].=$value['payment_mode'];

                }
              }
              if($i==$count)
              {
                $final_array[$create_date]['discount']=round(($final_array[$create_date]['discount_amount']/$final_array[$create_date]['total_amount'])*100,2);
                $final_array[$create_date]['channel']=implode(',',array_unique(explode(',', $final_array[$create_date]['channel'])));
              }
              $i++;
            }
            $final_res=array();
            foreach($final_array as $val)
            {
              $val['channel'] = isset($val['channel'])?$val['channel']:''; 
              array_push($final_res,$val);
            }
               $aray=array('status'=>'1','data'=>$final_res,'message'=>'success');
               $this->response($aray, 200);
          }else
          {
            $aray=array('status'=>'0','message'=>'failed');
            $this->response($aray, 200);
          }

      }else
      {
          $aray=array('status'=>'0','message'=>'failed');
          $this->response($aray, 200);
      }
  }catch(Ececption $e)
  {
    echo $e->getMessage();
    $error = array('status' =>'0', "data" => "Internal Server Error - Please try Later.","StatusCode"=> "HTTP405");
    $this->response($error, 200);
  }
  
}
public function getRolesByNumber_post()
  {
    try {
      $mobile_no = $this->input->post('mobile_no');
      $admin_id = $this->input->post('admin_id');
      if (!empty($mobile_no)) {
        $admin = $this->Supervisor->getRolesAdmin($mobile_no,$admin_id);
        $staff = $this->Supervisor->getRolesStaff($mobile_no,$admin_id);
        $array_merge_recursive=array_merge_recursive($admin,$staff);
        $i=0;
        foreach($array_merge_recursive as $value){

              $waiter='"'.trim($value['user_type']).'"';
              if(trim($value['user_type'])=="Waiter"){
                  $j=$i;
              }
              $i=$i+1;
        }
        $array_merge=array_merge($admin, $staff);
        $array_merge[$j]['user_type']='Waiting Staff';
        if (!empty(array_merge($admin, $staff))) {
          $aray = array('status' => '1', 'message' => 'success', 'data' =>array_merge($admin, $staff));
          $this->response($aray, 200);
        } else {
          $aray = array('status' => '0', 'message' => 'failed');
          $this->response($aray, 200);
        }
      } else {
        $aray = array('status' => '0', 'message' => 'failed');
        $this->response($aray, 200);
      }
    } catch (Ececption $e) {
      echo $e->getMessage();
      $error = array('status' => '0', "data" => "Internal Server Error - Please try Later.", "StatusCode" => "HTTP405");
      $this->response($error, 200);
    }
  }
}
