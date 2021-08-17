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

   }
       

  /*......... Login Api For Customer Hawker ---- */
   public function login_post()
    {
    $response = new StdClass();
    $result = new StdClass();
    $mobile_no = $this->input->post('mobile_no');
    $device_id=$this->input->post('device_id');
    $notification_id=$this->input->post('notification_id');
    date_default_timezone_set('Asia/kolkata'); 
    $now = date('Y-m-d H:i:s');
    $getdata1  =  $this->db  
                  ->select('*')
                  ->from("tbl_registration_customer")
                  ->where(['mobile_no'=>$mobile_no,'active_status'=>'1'])
                  ->get()->result_array();
      if(!empty($mobile_no))
      {
        
      if(!empty($getdata1))
      {
      foreach ($getdata1 as $rowdata)
      {
      $otpValue=mt_rand(1000, 9999);
      $data1->device_id = $device_id;
      $data1->notification_id = $notification_id;
      $data1->login_time=$now;
      $data1->cus_id=$rowdata['cus_id'];
      $data1->name=$rowdata['name'];
      $data1->mobile_no=$rowdata['mobile_no'];
      $data1->email_id=$rowdata['email_id'];
      $data1->address=$rowdata['address'];
      $data1->otp=$otpValue;
      $res = $this->Customer->Add_registration_custumer_data($data1);
      $res3 = $this->Customer->send_otp($mobile_no,$otpValue);
      if($res3!='')
      {
      $res4 = $this->Customer->otpgetdata($data1);
      }
      $data['cus_id'] =  $rowdata['cus_id'];
      $data['name'] =  empty($rowdata['name'])?'Dear Customer':$rowdata['name'];
      $data['mobile_no'] =  $rowdata['mobile_no'];
      $data['email_id'] =  empty($rowdata['email_id'])?'hawkers.nearme@gmail.com':$rowdata['email_id'];      
      $data['message']  ='Success';
      $data['status']  ='1';
      array_push($result,$data);
      }
        $response->data = $result;
      }
      else
      { 
         $otpValue=mt_rand(1000, 9999);
         $data2->mobile_no=$mobile_no;
         $data2->active_status='1';
         $data2->create_date=$now;
         $result1 = $this->Customer->customer_add($data2);
         $alphanumerric='CUS_0000'.$result1;
         if(!empty($result1))
        {
        
         $data3->device_id = $device_id;
         $data3->notification_id = $notification_id;
         $data3->mobile_no=$mobile_no;
         $data3->cus_id=$alphanumerric;
         $data3->login_time=$now;
         $data3->otp=$otpValue;
         $res3 = $this->Customer->send_otp($mobile_no,$otpValue);
         if($res3!='')
          {
          $res4 = $this->Customer->otpgetdata($data3);
          }
         $updatedata = $this->Customer->update_customer_id($alphanumerric,$result1);
         $res = $this->Customer->Add_registration_custumer_data($data3);
         $alphanumerric='CUS_0000'.$result1;
	 $getdata2  =  $this->db
                  ->select('*')
                  ->from("tbl_registration_customer")
                  ->where(['mobile_no'=>$mobile_no,'active_status'=>'1'])
                  ->get()->result_array();
        // $data['id']  =$result1;
         //print_r($getdata2);exit;
 	$data['name'] =  empty($getdata2[0]['name'])?'Dear Customer':$getdata2[0]['name'];
         $data['mobile_no'] = $getdata2[0]['mobile_no'];
         $data['email_id'] = empty($getdata2[0]['email_id'])?'hawkers.nearme@gmail.com':$getdata2[0]['email_id'];       
	// $data['id']  =$result1;
         $data['cus_id']  =$alphanumerric;
         $data['status']  ='1';
         $data['message']  ='Success';
         array_push($result , $data);
         $response->data = $data;
         }
         else
         {
           $data['status']  ='0';
           $data['message']  ='registration failed';
           array_push($result,$data);
           $response->data = $data;
         }
        }
       }
        else
         {
            $data['status']  ='0';
       array_push($result , $data);
         }
            $response->data = $data;
            echo json_output($response);
      }
    
      /*.........Login Api For Customer Hawker ---- */

   /*.........Register_customer  Api For Fixer  ---- */
  public function customer_profile_update_post()
  {
    $response = new StdClass();
    $result = new StdClass();
    $cus_id = $this->input->post('cus_id');
    $name = $this->input->post('name');
    $state=$this->input->post('state');
    $city=$this->input->post('city');
    $address=$this->input->post('address');
    $gender=$this->input->post('gender');
    $date_of_birth=$this->input->post('date_of_birth');
    $area=$this->input->post('area');
    $pincode=$this->input->post('pincode');
    $mobile_no = $this->input->post('mobile_no');
    $email=$this->input->post('email');
    $cus_image=$this->input->post('cus_image');
    $data->cus_id = $cus_id;
    $data->name = $name;
    $data->state = $state;
    $data->city = $city;
    $data->address = $address;
    $data->gender = $gender;
    $data->date_of_birth = $date_of_birth;
    $data->area = $area;
    $data->pincode = $pincode;
    $data->mobile_no= $mobile_no;
    $data->email=$email;
    $data->cus_image =$cus_image;
    if(!empty($mobile_no))
    {
      $result1 = $this->Customer->update_customer_profile($data);
      $data1->status ='1';
      $data1->message = 'Profile successfully update';
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
     /*.........Register_customer  Api For Fixer  ---- */

    /*......... profile update status  api for restaurant  ---- */
  public function check_profile_update_status_for_customer_post()
   {
      $response = new StdClass();
      $cus_id = $this->input->post('cus_id');
      $mobile_no = $this->input->post('mobile_no');
      
      $res = $this->Customer->check_profile_update_status($cus_id,$mobile_no);
      $profile_update_status=$res->profile_update_status;
      if($profile_update_status=='1')
      {
      $response->status ='1';
      $response->message = 'update';
      }
      else if($profile_update_status=='0')
      {
        $response->status ='0';
      $response->message = 'not update';
      }
      
    echo json_output($response);
   }

   /*......... Get Validate CustomerUser Api For Fixer  ---- */

  /*.........get fixer registartion data by catID   Api For Fixer  ---- */
  public function get_customer_profile_data_post()
    {
    $response   =   new StdClass();
    $result       =    new StdClass();
    $cus_id =$this->input->post('cus_id');
    $mobile_no =$this->input->post('mobile_no');
    $getdata = $this->Customer->customer_profile($cus_id,$mobile_no);
    if(!empty($getdata))
    {
     $data->cus_id=$getdata->cus_id;
     $dataimage=$getdata->cus_image;
    
   /* $cus_image_data=$getdata->cus_iamge;
    print_r($cus_image_data);
    die();*/
    if(!empty($dataimage))
    {
     $data->cus_image=$dataimage;
    }
    else
    {
      $data->cus_image='';
    }
    $name=$getdata->name;
    if($name=='')
    {
      $data->name='';
    }
    else
    {
       $data->name=$getdata->name;
    }
    $email_id=$getdata->email_id;
    if($email_id=='')
    {
       $data->email_id='';
    }
    else
    {
       $data->email_id=$getdata->email_id;
    }
    $date_of_birth=$getdata->date_of_birth;
    if($date_of_birth=='')
    {
       $data->date_of_birth='';
    }
    else
    {
      $data->date_of_birth=$getdata->date_of_birth;
    }
    $city=$getdata->city;
    if($city=='')
    {
       $data->city='';
    }
    else
    {
      $data->city=$getdata->city;
    }

    $state=$getdata->state;
    if($state=='')
    {
       $data->state='';
    }
    else
    {
      $data->state=$getdata->state;
    }
    $address=$getdata->address;
    if($address=='')
    {
       $data->address='';
    }
    else
    {
      $data->address=$getdata->address;
    }
    $area=$getdata->area;
    if($area=='')
    {
       $data->area='';
    }
    else
    {
      $data->area=$getdata->area;
    }
    $gender=$getdata->gender;
    if($gender=='')
    {
       $data->gender='';
    }
    else
    {
      $data->gender=$getdata->gender;
    }
    $pincode=$getdata->pincode;
    if($pincode=='')
    {
       $data->pincode='';
    }
    else
    {
      $data->pincode=$getdata->pincode;
    }
    
    $data->mobile_no=$getdata->mobile_no;
   /* $image_url=$cus_image;
    if($image_url=='')
    {
       $data->cus_image='';
    }
    else
    {
       $data->$cus_image;
    }*/
    $data->status='1';
    array_push($result,$data);
    $response->data = $data;
    }       
    else
    {
       $data->status='0';
      //$data['message'] = 'failed';
      array_push($result , $data);
    }
      $response->data = $data;
      echo json_output($response);
    }

/*........get fixer registartion data by catID  Api For Fixer  ---- */

/*.........Add order for customer  restaurant  for Restaurant Api  ---- */
    public function add_order_detail_for_restaurant_post()
    {   
        $response = new StdClass();
        $result2 = new StdClass();
        $admin_id=$this->input->post('admin_id');
        $cus_id=$this->input->post('cus_id');
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
        $customer_mobile_no=$this->input->post('customer_mobile_no');
        $menu_id=$this->input->post('menu_id');
        
        date_default_timezone_set('Asia/kolkata'); 
        $now = date('Y-m-d H:i:s');
        $now1 = date('Y-m-d');
        $data->admin_id=$admin_id;
        $data->cus_id=$cus_id;
        $data->table_no=ltrim($table_no,'0');
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
        $data->customer_mobile_no=$customer_mobile_no;
        $data->create_date=$now;
        $data->date=$now1;
        $data->status='1';
        $que=$this->db->query("select * from tbl_order_detail_for_restaurant where table_no='".$table_no."' and order_status NOT IN('Closed','Rejected') and admin_id='$admin_id' and payment_status!='1'");

         $row = $que->num_rows();
        if($row>0)
         {
            $data1->status ='2';
            $data1->message = 'This table is all ready book.';
            array_push($result2,$data1);
            $response->data = $data1;
            echo  json_output($response);
         }
         else
         {
          $prvDate=$this->Supervisor->getPrvOrderDate($admin_id);
          $getMaxOrderId=$this->Customer->getMaxOrderId($admin_id,$prvDate[0]['date']);
          $result =empty($getMaxOrderId)?'1':$getMaxOrderId;
          if($result <= 9){
            $alphanumerric=$admin_id.'-'.substr(str_replace('-', '',date('Y-m-d')),2).'000'.$result;
          }else if($result >= '9' && $result <= '99'){
            $alphanumerric=$admin_id.'-'.'00'.substr(str_replace('-', '',date('Y-m-d')),2).$result;
          }else if($result >= '99' && $result <= '999'){
           $alphanumerric=$admin_id.'-'.substr(str_replace('-', '',date('Y-m-d')),2).'0'.$result;
          }else
            $alphanumerric=$admin_id.'-'.$result;
          }
          $data->order_id=$alphanumerric;
          $updated=$this->Customer->add_order_detail_restaurant($data);
        if(!empty($result))
        {  

                    $menu_item_array      =explode(",",rtrim($menu_item_name,","));
                    $gst_amount_array     =explode(",",rtrim($gst_amount,","));
                    $menu_price           =explode(",",rtrim($menu_price,","));
                    $quantity             =explode(",",rtrim($quantity,","));
                    $menu_ids             =explode(",",rtrim($menu_id,","));
                    $half_and_full_status =explode(",",rtrim($half_and_full_status,","));


                         for($i=0;$i<count($menu_item_array);$i++)
                          {

                            $insert_array[]=array(
                                            'menu_item_name'=>$menu_item_array[$i],
                                            'quantity'=>$quantity[$i],
                                            // 'half_and_full_status'=>$half_and_full_status[$i],
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
                        $getCustmoerData    =$this->Customer->getCustmoerData($alphanumerric,$admin_id);
                        $custData           =$this->Customer->getCustData($getCustmoerData[0]['customer_mobile_no']);
                        $notificationData   =$this->Customer->getRestaurantStaffNotification($admin_id);
                        $adminData          =$this->Customer->getAdminData($admin_id);
                        $order_id2          =str_replace($admin_id.'-','',$order_id);
                        $notification_data=array_merge_recursive($custData,array_merge_recursive($notificationData,$adminData));
                        // echo "<pre>";print_r($notification_data);exit;
                        foreach($notification_data as $notification)
                            {
                              if($notification['user_type']=='customer'){
                                  $title ='OYLY';
                                  $message ='Your order for table no '.$table_no.' has been placed.';
                              }else if($notification['user_type']=='Waiter'){
                                  $title ='OYLY';
                                  $message ='Table no '.$table_no.' placed an order.';
                              }else if($notification['user_type']=='Supervisor'){
                                 $title ='OYLY';
                                  $message ='Table no '.$table_no.' order id '.$order_id2.' is placed.';
                              }else if($notification['user_type']=='admin'){
                                  $title ='OYLY';
                                  $message ='Order has been created for table no '.$table_no;
                              }
                              $result=sendPushNotification($title,$message,$notification['notification_id']);
                              if($result)
                              {
                                $array[]=array(
                                        'mobile_no'=>$notification['mobile_no'],
                                        'admin_id'=>$admin_id,
                                        'status'=>1,
                                        'order_id'=>$order_id2,
                                        'table_no'=>$table_no,
                                        'title'=>$title,
                                        'message'=>$message,
                                        'customer_mobile_no'=>$customer_mobile_no,
                                        'date_time'=>date('Y-m-d H:i:s')
                                   ); 
                              }
                            }  
                            if(!empty($array))
                            {
                              $this->Customer->insertNotification($array);                          
                            }
            $data2->status ='1';
            $data2->message = 'Order Placed successfully';
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

   /*.........Add order for customer  restaurant  for Restaurant Api  ---- */
    public function change_order_for_particular_customer_post()
    {   
        $response = new StdClass();
        $result2 = new StdClass();
        $order_id=$this->input->post('order_id');
        $admin_id=$this->input->post('admin_id');
        // $cus_id=$this->input->post('cus_id');
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
        $customer_mobile_no=$this->input->post('customer_mobile_no');
        $cus_id=$this->Customer->getCustId($order_id,$admin_id,$customer_mobile_no);
        $max_id=$this->Supervisor->getMax($order_id,$admin_id);
        $menu_id=$this->input->post('menu_id');
        date_default_timezone_set('Asia/kolkata'); 
        $now = date('Y-m-d H:i:s');
        $now1 = date('Y-m-d');
        $data->order_id=$order_id;
        $data->admin_id=$admin_id;
        if($max_id <= 9)
        {
                  $data->sub_order_id='000'.($max_id+1);
                  $sub_order_id='000'.($max_id+1);

        }else if($max_id <= 99 && $max_id >= 9)
        {
                  $data->sub_order_id='00'.($max_id+1);
                  $sub_order_id='00'.($max_id+1);

        }else if($max_id <= 999 && $max_id >= 99)
        {
                  $data->sub_order_id='0'.($max_id+1);
                  $sub_order_id='0'.($max_id+1);
        }
        else 
        {
                  $data->sub_order_id=($max_id+1);
                  $sub_order_id=($max_id+1);

        }
        $data->cus_id=$cus_id;
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
        $data->order_status='Pending';
        $data->customer_mobile_no=$customer_mobile_no;
        $data->create_date=$now;
        $data->date=$now1;
        $data->status='1';
        $result = $this->Customer->add_sub_order_detail_restaurant($data);
       
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
              $checkOrderStatus=$this->Customer->checkOrderStatus($admin_id,$order_id);
              if(!empty($checkOrderStatus))
              {
                $notificationData=$this->Customer->getRestaurantStaffNotification($admin_id);
              }else
              {
                $notificationData=$this->Customer->getRestaurantWaiterNotification($admin_id,$order_id);
              }
                $getCustmoerData    =$this->Customer->getCustmoerData($order_id,$admin_id);
                $custData           =$this->Customer->getCustData($getCustmoerData[0]['customer_mobile_no']);
                $adminData          =$this->Customer->getAdminData($admin_id);
                $order_id2          =str_replace($admin_id.'-','',$order_id);
                $SupervisorData     =$this->Customer->getSupervisorData($admin_id);
                $notification_data=array_merge_recursive($custData,array_merge_recursive($notificationData,$adminData),$SupervisorData);
              foreach($notification_data as $notification)
                  {
                    if($notification['user_type']=='customer'){
                      $title ='OYLY';
                      $message ='Table No '.$table_no.' placed more items.';
                      }else if($notification['user_type']=='Waiter'){
                      $title ='OYLY';
                      $message ='Table No '.$table_no.' placed more items.';
                      }else if($notification['user_type']=='Supervisor'){
                      $title ='OYLY';
                      $message ='Table No '.$table_no.' order id '.$order_id2.' is placed more items.';
                      }else if($notification['user_type']=='admin'){
                      $title ='OYLY';
                      $message ='More items has been created for table '.$table_no;
                      }
                    $result=sendPushNotification($title,$message,$notification['notification_id']);
                    $array[]=array(
                                  'mobile_no'=>$notification['mobile_no'],
                                  'admin_id'=>$admin_id,
                                  'status'=>1,
                                  'order_id'=>$order_id2,
                                  'sub_order_id'=>$sub_order_id,
                                  'table_no'=>$table_no,
                                  'title'=>$title,
                                  'message'=>$message,
                                  'customer_mobile_no'=>$customer_mobile_no,
                                  'date_time'=>date('Y-m-d H:i:s')
                         );  
                   }
                if(!empty($array))
                  {
                    $this->Customer->insertNotification($array);                          
                  }
            $data2->status ='1';
            $data2->message = 'Order Placed successfully';
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

  /*.........get_order_detail_for_restaurant customer  Restaurant Api  ---- */
     public function get_order_detail_for_customer_post()
      {

        $result               =array();
        $result3              =array();
        $result4              =array();
        $result5              =array();
        $result6              =array();
        $result8              =array();
        $arr                  =array();
        $finalgstArray        =array();
        $finalarray           =array();
        $gst_amount=0;
        $customer_mobile_no   =$this->input->post('customer_mobile_no');
        $customer_mobile_no1  =substr($customer_mobile_no, -6);
        $data = $this->Customer->getGroupData($customer_mobile_no,$customer_mobile_no1);
        //print_r($_POST);exit;
        if(empty($data))
        {
          $response->status = 0;
          $response->message = "failed";
         //$response->data = $arr;
        }
        else
        {
          $OrderCalulationResult=0;
          $totalAmount=0;
          $totalNetPayableAmount=0;
          $totalGstAmount=0;
          for($i=0;$i<count($data);$i++)
          {
              // print_r($data);exit;
              $result['order_id']                 =$data[$i]['order_id'];
              $result['admin_id']                 =$data[$i]['admin_id'];
              $result['phone']                    =$data[$i]['phone'];
              $result['address']                  =$data[$i]['address'];
              $result['is_reviewed']              =$data[$i]['is_reviewed'];
              $result['new_order_id']             =str_replace($data[$i]['admin_id'].'-','',$data[$i]['order_id']);
              $result['cus_id']                   =$data[$i]['cus_id'];

              $OrderGstResult   =$this->Customer->getGstInforForOrder($result['order_id'],$result['admin_id']);
             
              foreach($OrderGstResult as $value3)
              {

                $gst_amount=$value3['menu_price']*$value3['gst']/100;

                $result3['gst_amount']  =$gst_amount;
               
                $result3['gst']         =$value3['gst'];
               
                $result6[]              =$result3;
              }
             
              // print_r($result6);exit;
              $MwnuItemResult                                         =$this->Customer->getMenuItemForOrder($result['order_id'],$result['admin_id']);
                  foreach($MwnuItemResult as $menuValue)
                  {
                    $menuImages[]                                     =$menuValue['menu_item_name'];
                    $menuquantity[]                                   =$menuValue['quantity'];
                    $menuhalf_and_full_status[]                       =$menuValue['half_and_full_status'];
                    $menumenu_price[]                                 =$menuValue['menu_price'];
                    $menumenu_id[]                                    =$menuValue['id'];
                    $menumenu_status[]                                =$menuValue['status'];
                    $menu_item_gst[]                                  =$menuValue['gst'];
                    $is_reviewed[]                                    =$menuValue['is_reviewed'];

                  }
              $result['id']                                           =implode(',',$menumenu_id).',';
              $result['menu_item_name']                               =implode(',',$menuImages).',';
              $result['quantity']                                     =implode(',',$menuquantity).',';
              $result['half_and_full_status']                         =implode(',',$menuhalf_and_full_status).',';
              $result['menu_price']                                   =implode(',',$menumenu_price).',';    
              $result['menuOrderItemStatus']                          =implode(',',$menumenu_status).',';    
              // $result['is_reviewed']                                  =implode(',',$is_reviewed).',';    
              $result['total_item']                                   =$data[$i]['total_item'];
              $result['table_no']                                     =$data[$i]['table_no'];
              $result['total_price']                                  =$data[$i]['total_price'];
              $result['net_pay_amount']                               =$data[$i]['net_pay_amount'];
              $result['order_status']                                 =$data[$i]['order_status'];
              $result['waiter_mobile_no']                             =$data[$i]['waiter_mobile_no'];
              $result['customer_mobile_no']                           =$data[$i]['customer_mobile_no'];
              $result['confirm_order_by']                             =$data[$i]['confirm_order_by'];
              $result['create_slip_by']                               =$data[$i]['create_slip_by'];
              $result['order_ready_to_serve_by']                      =$data[$i]['order_ready_to_serve_by'];
              $result['order_complete_by']                            =$data[$i]['order_complete_by'];
              $result['order_delete_by']                              =$data[$i]['order_delete_by'];
              $result['order_change_by']                              =$data[$i]['order_change_by'];
              $result['create_date']                                  =$data[$i]['create_date'];
              $result['date']                                         =$data[$i]['date'];
              $result['modified_date']                                =$data[$i]['modified_date'];
              $result['slip_status']                                  =$data[$i]['slip_status'];
              $result['payment_status']                               =$data[$i]['payment_status'];
              $result['notification_status_by_staff']                 =$data[$i]['notification_status_by_staff'];
              $result['NS_for_complete_by_waiter']                    =$data[$i]['NS_for_complete_by_waiter'];
              $result['NS_for_kot_for_staff']                         =$data[$i]['NS_for_kot_for_staff'];
              $result['NS_for_complete_by_waiter_for_customer']       =$data[$i]['NS_for_complete_by_waiter_for_customer'];
              $result['NS_for_kitchen_for_staff']                     =$data[$i]['NS_for_kitchen_for_staff'];
              $result['NS_for_complete_by_chef']                      =$data[$i]['NS_for_complete_by_chef'];
              $result['NS_for_kitchen_for_waiter']                    =$data[$i]['NS_for_kitchen_for_waiter'];
              $result['notification_status_by_customer']              =$data[$i]['notification_status_by_customer'];
              $result['NS_for_kot_for_customer']                      =$data[$i]['NS_for_kot_for_customer'];
              $result['payment_by']                                   =$data[$i]['payment_by'];
              $result['get_payment']                                  =$data[$i]['get_payment'];
              $result['status']                                       =$data[$i]['status'];
              $result['inv_created_by']                               =$data[$i]['inv_created_by'];
              $result['RestaurentName']                               =$data[$i]['RestaurentName'];
              $result['discount']                                     =$data[$i]['discount'];
              $result['gst_array']                                    =implode(',',$menu_item_gst).',';
              $OrderCalulationResult                                  =$this->Customer->getOrderCalculation($data[$i]['order_id'],$data[$i]['admin_id']);
             // print_r($OrderCalulationResult);
              $totalAmount=0;
              $totalGstAmount=0;
              $totalqty=0;
              $totalGst=0;
              foreach($OrderCalulationResult as $gstCalulation)
              {

                $totalAmount=$totalAmount+$gstCalulation['menu_price'];
                $totalGstAmount=$totalGstAmount+$gstCalulation['menu_price']*$gstCalulation['gst']/100;
                $totalqty=$totalqty+$gstCalulation['quantity'];
                $totalGst=$totalGst+$gstCalulation['gst'];

              }

              $totalNetPayableAmount                                     =$totalAmount;
              $result['gst_amount']                                      ="$totalGst";
              $result['gst_amount_price']                                ="$totalGstAmount";

               $SubOrderGstResult   =$this->Customer->getGstInforForSubOrder($result['order_id'],$result['admin_id']);
               // print_r($SubOrderGstResult);exit;

                      foreach($SubOrderGstResult as $value4)
                      {
                        $gst_amount2=$value4['menu_price']*$value4['gst']/100;
                        $result4['gst_amount']  =$gst_amount2;
                        $result4['gst']         =$value4['gst'];;
                        $result5[]               =$result4;
                      }
                     
                      // print_r($result5);exit;
              $subOrderRes                                            =$this->Customer->getSubOrder($data[$i]['order_id'],$data[$i]['admin_id']);
               if(!empty($subOrderRes))
                  {

                
                    $subtotalAmount=0;
                    $subtotalqty =0;
                    $subtotalNetPayableAmount=0;  
                    $subtotalGstAmount=0;
                    foreach ($subOrderRes as $value)
                    {
                      $result2['order_id']                              =$data[$i]['order_id'];
                      $result2['admin_id']                              =$data[$i]['admin_id'];
                      $result2['sub_order_id']                          =$value['sub_order_id'];
                     

                      $SubOrderCalulationResult                         =$this->Customer->getSubOrderCalculation($data[$i]['order_id'],$data[$i]['admin_id'],$value['sub_order_id']);
                      //print_r($SubOrderCalulationResult);
                      $subtotalAmount=0;
                      $subtotalGstAmount=0;
                      $subtotalqty=0;
                      $totalSubGst=0;
                       foreach($SubOrderCalulationResult as $SubgstCalulation)
                        {
                            
                        $subtotalAmount=$subtotalAmount+$SubgstCalulation['menu_price'];
                        $subtotalGstAmount=$subtotalGstAmount+$SubgstCalulation['menu_price']*$SubgstCalulation['gst']/100;
                        $subtotalqty=$subtotalqty+$SubgstCalulation['quantity'];
                        $totalSubGst=$totalSubGst+$SubgstCalulation['gst'];

                        }

                      $subtotalAmount               =$subtotalAmount;
                      $subtotalqty                  =$subtotalqty;
                      $subtotalNetPayableAmount     =$subtotalNetPayableAmount+$subtotalAmount+$subtotalGstAmount;                   
                      


                      $menuResult2 =$this->Customer->getMenuItemForSubOrder($data[$i]['order_id'],$data[$i]['admin_id'],$result2['sub_order_id']);

                      foreach($menuResult2 as $menuValue2)
                      {
                        $menuImages2[]                                  =$menuValue2['menu_item_name'];
                        $menuquantity2[]                                =$menuValue2['quantity'];
                        $menuhalf_and_full_status2[]                    =$menuValue2['half_and_full_status'];
                        $menumenu_price2[]                              =$menuValue2['menu_price'];
                        $menu_id[]                                      =$menuValue2['id'];
                        $menuSubOrderStatus[]                           =$menuValue2['status'];
                        $menu_item_gst2[]                               =$menuValue2['gst'];
                        // $is_reviewed2[]                                  =$menuValue2['is_reviewed'];

                      } 
                      $result2['gst_amount']                            ="$totalSubGst";
                      $result2['menu_item_name']                        =implode(',',$menuImages2).',';
                      $result2['menu_item_id']                          =implode(',',$menu_id).',';
                      $result2['quantity']                              =implode(',',$menuquantity2).',';
                      $result2['half_and_full_status']                  =implode(',',$menuhalf_and_full_status2).',';
                      $result2['menu_price']                            =implode(',',$menumenu_price2).',';
                      $result2['menuSubOrderItemStatus']                =implode(',',$menuSubOrderStatus).',';
                      // $result2['is_reviewed']                           =implode(',',$is_reviewed2).',';
                      $result2['total_item']                            =$value['total_item'];
                      $result2['net_pay_amount']                        ="$subtotalNetPayableAmount";
                      $result2['gst_amount_price']                      ="$subtotalGstAmount";
                      $result2['order_status']                          =$value['order_status'];
                      $result2['waiter_mobile_no']                      =$value['waiter_mobile_no'];
                      $result2['customer_mobile_no']                    =$value['customer_mobile_no'];
                      $result2['create_slip_by']                        =$value['create_slip_by'];
                      $result2['order_complete_by']                     =$value['order_complete_by'];
                      $result2['order_delete_by']                       =$value['order_delete_by'];
                      $result2['date']                                  =$value['date'];
                      $result2['modified_date']                         =$value['modified_date'];
                      $result2['slip_status']                           =$value['slip_status'];
                      $result2['payment_status']                        =$value['payment_status'];
                      $result2['notification_status_by_staff']          =$value['notification_status_by_staff'];
                      $result2['NS_for_complete_by_waiter']             =$value['NS_for_complete_by_waiter'];
                      $result2['NS_for_kot_for_staff']                  =$value['NS_for_kot_for_staff'];
                      $result2['NS_for_kitchen_for_staff']              =$value['NS_for_kitchen_for_staff'];
                      $result2['NS_for_complete_by_chef']               =$value['NS_for_complete_by_chef'];
                      $result2['NS_for_kitchen_for_waiter']             =$value['slip_status'];
                      $result2['notification_status_by_customer']       =$value['notification_status_by_customer'];
                      $result2['NS_for_complete_by_waiter_for_customer']=$value['NS_for_complete_by_waiter_for_customer'];
                      $result2['NS_for_kot_for_customer']               =$value['NS_for_kot_for_customer'];
                      $result2['NS_for_kitchen_for_customer']           =$value['NS_for_kitchen_for_customer'];
                      $result2['payment_by']                            =$value['payment_by'];
                      $result2['get_payment']                           =$value['get_payment'];
                      $result2['gst_array']                            =implode(',',$menu_item_gst2).',';

                      $result2['status']                                =$value['status'];
                      $result2['total_price']                           ="$subtotalAmount";
                      // $result2['sub_order_gst']     =$result5;
                      

                      $finalarray[]=$result2;
                      $menuImages2=array();
                      $menuquantity2=array();
                      $menuhalf_and_full_status2=array();
                      $menumenu_price2=array();
                      $menu_item_gst2=array();
                      $menu_id=array();
                      $menuSubOrderStatus=array();
                      $menu_item_gst=array();
                      $is_reviewed2=array();

                    }
              
                  }
                   // print_r(array_merge($result5,$result6));
                  $merged_array=array();
                  $merged_array=array_merge($result5,$result6);
                  $masterGst=$this->Customer->getMasterGst($data[$i]['admin_id']);
                  $finalgstArray=array();

                  $gst_amount=0;
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
                    //echo $gst_amount."<br>";
                    if(!empty($gst_amount))
                    {
                    $result8['gst_amount']=$gst_amount;
                    $result8['cgst_amount']=$gst_amount/2;
                    $result8['sgst_amount']=$gst_amount/2;
                    $result8['gst']=$value7['gst'];
                    $result8['cgst']=$value7['gst']/2;
                    $result8['sgst']=$value7['gst']/2;
                    $finalgstArray[]=$result8; 
                    }
                    

                  }
                  
                  // print_r($finalgstArray);exit;
                  $result['sub_order_data']     =$finalarray;
                  $result['gst_info']           =$finalgstArray;
                  // print_r($totalNetPayableAmount).'<br>';
                  // print_r($subtotalAmount);exit;
                  $result['totalOfOrderamount'] =($totalNetPayableAmount+$subtotalAmount);
                  $result['totalofqty']         =$subtotalqty+$totalqty;
                  $result['amount_with_disc']   =($subtotalAmount+$totalAmount)-(($subtotalAmount+$totalAmount)*$data[$i]['discount'])/100;
                  $result['orderDiscount']      =(($subtotalAmount+$totalAmount)*$data[$i]['discount'])/100;

                  $result['totalofgst']         = $subtotalGstAmount+$totalGstAmount;
                  $result['totalofnetpay']      =$result['amount_with_disc']+($data[$i]['discount']==100.00?00:$subtotalGstAmount+$totalGstAmount);
                  array_push($arr, $result);
                  $finalarray=array();
                  $menuImages=array();
                  $menuquantity=array();
                  $menuhalf_and_full_status=array();
                  $menumenu_price=array();
                  $menumenu_id=array();
                  $menumenu_status=array();
                  $result5=array();
                  $result6=array();
                  $is_reviewed=array();
                  $totalNetPayableAmount=0;
                  $subtotalAmount=0;
                  $subtotalqty=0;
                  $totalqty=0;
          }
                $response->status = 1;
                $response->message = "success";
                $response->data = $arr;

        }
        // exit;
        echo json_output($response);
        
        }



     public function get_detail_for_particular_order_for_customer_post()
      {
        $response         =new StdClass();
        $response1        =new StdClass();
        $result           =array();
        $result2          =array();
        $order_id         =$this->input->post('order_id');
        $data             =$this->Customer->getGroupDatas($order_id);
        //print_r($data);exit;
        $arr              =array();
        if(empty($data))
        {
          $response->status = 0;
          $response->message = "failed";
         //$response->data = $arr;
        }
        else
        {
                for($i=0;$i<count($data);$i++)
                {
                    $result['order_id']           =$data[$i]['order_id'];
                    $result['admin_id']           =$data[$i]['admin_id'];
                    $result['RestaurentName']     =$data[$i]['RestaurentName'];
                    $result['table_no']           =$data[$i]['table_no'];
                    $result['status']             =$data[$i]['status'];
                    $result['new_order_id']       =str_replace($data[$i]['admin_id'].'-','',$data[$i]['order_id']);
                    $MenuItemResult               =$this->Customer->getDataOrderWises($data[$i]['order_id'],$data[$i]['admin_id']);

                    foreach($MenuItemResult as $menuValue)
                    {
                        $menuImages[]               =$menuValue['menu_item_name'];
                        $menuquantity[]             =$menuValue['quantity'];
                        $menuhalf_and_full_status[] =$menuValue['half_and_full_status'];
                        $menumenu_price[]           =$menuValue['menu_price'];
                        $menumenu_id[]              =$menuValue['id'];
                    }
                        $result['id']                       =implode(',',$menumenu_id).',';
                        $result['menu_item_name']           =implode(',',$menuImages).',';
                        $result['cus_id']                   =$data[$i]['cus_id'];
                        $result['quantity']                 =implode(',',$menuquantity).',';
                        $result['half_and_full_status']     =implode(',',$menuhalf_and_full_status).',';
                        $result['menu_price']               =implode(',',$menumenu_price).',';

                        $subOrderResult=$this->Customer->getSubOrder($data[$i]['order_id'],$data[$i]['admin_id']);
                        foreach($subOrderResult as $value2)
                        {
                            $result2['order_id']           =$value2[$i]['order_id'];
                            $result2['sub_order_id']       =$value2[$i]['sub_order_id'];
                            $result2['admin_id']           =$value2[$i]['admin_id'];
                            $MenuSubItemResult             =$this->Customer->getSubOrderMenuItems($value2[$i]['order_id'],$value2[$i]['sub_order_id'],$value2[$i]['admin_id']);

                                foreach($MenuItemResult2 as $menuValue2)
                                {
                                    $menuImages2[]               =$menuValue2['menu_item_name'];
                                    $menuquantity2[]             =$menuValue2['quantity'];
                                    $menuhalf_and_full_status2[] =$menuValue2['half_and_full_status'];
                                    $menumenu_price2[]           =$menuValue2['menu_price'];
                                    $menumenu_id2[]              =$menuValue2['id'];
                                }

                                  $result2['id']                       =implode(',',$menumenu_id2).',';
                                  $result2['menu_item_name']           =implode(',',$menuImages2).',';
                                  $result2['quantity']                 =implode(',',$menuquantity2).',';
                                  $result2['half_and_full_status']     =implode(',',$menuhalf_and_full_status2).',';
                                  $result2['menu_price']               =implode(',',$menumenu_price2).',';
                                  $result2['status']                   =$value2[$i]['status'];
                                  $menuImages2=array();
                                  $menuquantity2=array();
                                  $menuhalf_and_full_status2=array();
                                  $menumenu_price2=array();
                                  $menumenu_id2=array();

                        }

                       
                       $result['sub_order_data']=$result2;
                       $menuImages=array();
                       $menuquantity=array();
                       $menuhalf_and_full_status=array();
                       $menumenu_price=array();
                       $menumenu_id=array();
                       array_push($arr, $result);
                  
                }     
        $response->status = 1;
        $response->message = "success";
        $response->data = $arr;

        }
        
        echo json_output($response);
      }


        public function get_detail_for_particular_order_by_customer_post()
        {
              $response         =new StdClass();
        $response1        =new StdClass();
        $result           =array();
        $result2          =array();
        $order_id         =$this->input->post('order_id');
        $data             =$this->Customer->getGroupDatas($order_id);
        // print_r($data);exit;
        $arr              =array();
        if(empty($data))
        {
          $response->status = 0;
          $response->message = "failed";
         //$response->data = $arr;
        }
        else
        {
                for($i=0;$i<count($data);$i++)
                {
                    $result['order_id']           =$data[$i]['order_id'];
                    $result['admin_id']           =$data[$i]['admin_id'];
                    $result['RestaurentName']     =$data[$i]['RestaurentName'];
                    $result['table_no']           =$data[$i]['table_no'];
                    $result['status']             =$data[$i]['status'];
                    $result['new_order_id']       =str_replace($data[$i]['admin_id'].'-','',$data[$i]['order_id']);
                    $MenuItemResult               =$this->Customer->getDataOrderWises($data[$i]['order_id'],$data[$i]['admin_id']);

                    foreach($MenuItemResult as $menuValue)
                    {
                        $menuImages[]               =$menuValue['menu_item_name'];
                        $menuquantity[]             =$menuValue['quantity'];
                        $menuhalf_and_full_status[] =$menuValue['half_and_full_status'];
                        $menumenu_price[]           =$menuValue['menu_price'];
                        $menumenu_id[]              =$menuValue['id'];
                    }
                        $result['id']                       =implode(',',$menumenu_id).',';
                        $result['menu_item_name']           =implode(',',$menuImages).',';
                        $result['cus_id']                   =$data[$i]['cus_id'];
                        $result['quantity']                 =implode(',',$menuquantity).',';
                        $result['half_and_full_status']     =implode(',',$menuhalf_and_full_status).',';
                        $result['menu_price']               =implode(',',$menumenu_price).',';

                        $subOrderResult=$this->Customer->getSubOrder($data[$i]['order_id'],$data[$i]['admin_id']);
                        foreach($subOrderResult as $value2)
                        {
                            $result2['order_id']           =$value2[$i]['order_id'];
                            $result2['sub_order_id']       =$value2[$i]['sub_order_id'];
                            $result2['admin_id']           =$value2[$i]['admin_id'];
                            $MenuSubItemResult             =$this->Customer->getSubOrderMenuItems($value2[$i]['order_id'],$value2[$i]['sub_order_id'],$value2[$i]['admin_id']);

                                foreach($MenuItemResult2 as $menuValue2)
                                {
                                    $menuImages2[]               =$menuValue2['menu_item_name'];
                                    $menuquantity2[]             =$menuValue2['quantity'];
                                    $menuhalf_and_full_status2[] =$menuValue2['half_and_full_status'];
                                    $menumenu_price2[]           =$menuValue2['menu_price'];
                                    $menumenu_id2[]              =$menuValue2['id'];
                                }

                                  $result2['id']                       =implode(',',$menumenu_id2).',';
                                  $result2['menu_item_name']           =implode(',',$menuImages2).',';
                                  $result2['quantity']                 =implode(',',$menuquantity2).',';
                                  $result2['half_and_full_status']     =implode(',',$menuhalf_and_full_status2).',';
                                  $result2['menu_price']               =implode(',',$menumenu_price2).',';
                                  $result2['status']                   =$value2[$i]['status'];
                                  $menuImages2=array();
                                  $menuquantity2=array();
                                  $menuhalf_and_full_status2=array();
                                  $menumenu_price2=array();
                                  $menumenu_id2=array();

                        }

                       
                       $result['sub_order_data']=$result2;
                       $menuImages=array();
                       $menuquantity=array();
                       $menuhalf_and_full_status=array();
                       $menumenu_price=array();
                       $menumenu_id=array();
                       array_push($arr, $result);
                  
                }     
        $response->status = 1;
        $response->message = "success";
        $response->data = $arr;

        }
        
        echo json_output($response);
      }

      /*..........Get particular order detail  For Restaurant  ---- */

   /*.........get menu data Api For Restaurant ---- */
     public function menu_list_data_customer_post()
      {
        $response   =   new StdClass();
        $result       =   array();
        $admin_id=$this->input->post('admin_id');
        $menu_list = $this->Customer->get_menu_list_data($admin_id);
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
              $menu_full_price_gst='';
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
            $data['menu_name'] =   $row['menu_name'];
            $data['rating'] =   $row['rating'];
            $data['menu_image'] =   $row['menu_image'] !=''?base_url().'uploads/'.$row['menu_image']:'';
            $data['menu_detail'] =   $row['menu_detail'];
            $data['menu_half_price'] =   $menu_half_price;
            $data['menu_full_price'] =  $menu_full_price;
            $data['menu_fix_price'] =   $menu_fix_price;
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

      /*.........super sub Category   Api For hawker  ---- */
     /*.........get Restaurant City For Restaurant ---- */
     public function get_city_for_restaurant_post()
      {
        $response   =   new StdClass();
        $result       =   array();
        $get_city = $this->Customer->get_city_list_data();
        if(!empty($get_city))
        {
          $data['city'] = 'All Cities';
          $data['message'] = 'Success';
          $data['status']  ='1';
          array_push($result,$data);
         foreach ($get_city as $row)
           {
            $data['city'] =   $row['city'];
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

      /*.........get Restaurant City For Restaurant   ---- */
      /*.........get Restaurant City For Restaurant ---- */
     public function get_restaurant_list_post()
      {
        $response   =   new StdClass();
        $result       =   array();
        $city=$this->input->post('city');
        
        $get_restaurant_data = $this->Customer->get_restaurant_list_data($city);
       
    
        if(!empty($get_restaurant_data))
        {
         foreach ($get_restaurant_data as $row)
           {
            $data['admin_id'] =   $row['admin_id'];
            $data['city'] =   $row['city'];
            $data['spotId'] =   $row['spotId'];
            $data['trending'] =   $row['trending'];
            $data['name'] =   $row['name'];
            $data['image'] =   $row['image'] !=''?base_url().'uploads/'.$row['image']:'';
            $data['rating'] =   $row['rating'];
            $data['lat'] =   $row['lat'];
            $data['lng'] =   $row['lng'];
            $data['location'] =   $row['location'];
            $data['cuisines'] =   $row['cuisines'];
            $data['priceLevel'] =   $row['priceLevel'];
            $data['cost'] =   $row['cost'];
            $data['openStatus'] =   $row['openStatus'];
            $data['openingTime'] =   $row['openingTime'];
            $data['closingTime'] =   $row['closingTime'];
            $data['phone'] =   $row['phone'];
            $data['address'] =   $row['address'];
            $data['amenities'] =  explode(",", $row['amenities']);
            $data['verified'] =   $row['verified'];
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

      /*.........get Restaurant City For Restaurant   ---- */


     /*.........get banner image  Api For hawker  ---- */
       public function get_banner_image_post()
        {
        $response   =   new StdClass();
        $result       =   array();
        $getbannerimage       =  $this->db->where(['status'=>'1'])
                              ->get('tbl_restaurant_banner_image')->result_array();
        if(!empty($getbannerimage))
        {
         foreach ($getbannerimage as $row)
        {

        $data['banner_image'] =   $row['banner_image'];
        $data['message'] =   'success';
        $data['status']  = '1';
         array_push($result,$data);
         } 
         $response->data = $result;

         }  
         else
         {
        $data['message'] =   'failed';
         $data['status']  ='0';
         //$data['message'] = 'failed';
         array_push($result , $data);
         }
         $response->data = $result;
         echo json_output($response);
         }

       /*.........send  Dieloge box message  Api For hawker  ---- */

      /*.........Verification OTP Api For customer  ---- */

     public function verification_otp_customer_post()
     {
      $response   =   new StdClass();
      $result       =  new StdClass();
      $mobile_no =$this->input->post('mobile_no');
      $device_id =$this->input->post('device_id');
      $otp =$this->input->post('otp');
      $data1->device_id = $device_id;
      $data1->mobile_no = $mobile_no;
      $data1->otp=$otp;
      $dataotp = $this->Customer->verification_otp($data1);
      $cus_id=$dataotp->cus_id;
      $id=$dataotp->id;
      $modified_date=$dataotp->modified_date;
      $now=date('Y-m-d H:i:s');
      $minutes=round(abs((strtotime($now)-strtotime($modified_date))))/60;
      if(!empty($dataotp))
      {
        if($minutes > 2)
        {
          $data->cus_id=$cus_id;
          $data->id=$id;
          $data->message = 'Otp has expired';
          $data->status = '0';
          array_push($result,$data);
          $response->data = $data;
        }else
        {
          $data->cus_id=$cus_id;
          $data->id=$id;
          $data->message = 'success';
          $data->status = '1';
          array_push($result,$data);
          $response->data = $data;
        }
       
      }
      else
      {
        $data->message = 'Invalid Otp';
        $data->status = '0';
        array_push($result,$data);
        $response->data = $data;
      }  
        echo json_output($response);
      }

       /*.........Verification OTP Api For customer  ---- */

       public function gst_amount_detail_customer_post()
     {
      $response   =   new StdClass();
      $result       =  new StdClass();
      $dataotp = $this->Customer->get_gst_amount();
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

      /*.........Resend OTP Api For customer  ---- */
      public function resend_otp_customer_post()
      {
      $response   =   new StdClass();
      $result       =  new StdClass();
      $device_id =$this->input->post('device_id');
      $mobile_no =$this->input->post('mobile_no');
      $otpValue=mt_rand(1000, 9999);
      $data1->device_id = $device_id;
      $data1->mobile_no=$mobile_no;
      $data1->otp=$otpValue;
      $res = $this->Customer->send_otp($mobile_no,$otpValue);
      if(!empty($mobile_no))
      {
      $res1 = $this->Customer->resend_otp($data1);

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

      /*.........Remove OTP Api For customer  ---- */
       public function otp_expire_post()
       {
        $response   =   new StdClass();
        $result       =  new StdClass();
        $device_id =$this->input->post('device_id');
        $mobile_no =$this->input->post('mobile_no');
        $data1->device_id = $device_id;
        $data1->mobile_no=$mobile_no;
        $res = $this->Customer->remove_otp($data1);
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

       /*.........Remove OTP Api For customer  ---- */

     /*........Get Restaurant Detail BLE brodcast data Api  For Restaurant ---- */
     public function get_detail_for_restaurant_by_BLE_brodcast_post()
      {
       $response = new StdClass();
        $result2 = new StdClass();
        $BLE_id=$this->input->post('BLE_id');
        $detail_for_restaurant = $this->Customer->get_detail_for_restaurant_by_BLE_brodcast($BLE_id);
        $admin_id=$detail_for_restaurant->admin_id;
        $name=$detail_for_restaurant->name;
        $image=$detail_for_restaurant->image;
        $lat=$detail_for_restaurant->lat;
        $lng=$detail_for_restaurant->lng;
        $location=$detail_for_restaurant->location;
        $cuisines=$detail_for_restaurant->cuisines;
        $city=$detail_for_restaurant->city;
        $openStatus=$detail_for_restaurant->openStatus;
        $openingTime=$detail_for_restaurant->openingTime;
        $closingTime=$detail_for_restaurant->closingTime;
        $phone=$detail_for_restaurant->phone;
        $address=$detail_for_restaurant->address;
        $amenities=$detail_for_restaurant->amenities;
        $restaurant_name=$detail_for_restaurant->restaurant_name;
        if(!empty($detail_for_restaurant))
        {
           $data2->admin_id =$admin_id;
           $data2->name =$name;
           $data2->image =$image;
           $data2->lat =$lat;
           $data2->lng =$lng;
           $data2->location =$location;
           $data2->cuisines =$cuisines;
           $data2->city =$city;
           $data2->openStatus =$openStatus;
           $data2->openingTime =$openingTime;
           $data2->closingTime =$closingTime;
           $data2->phone =$phone;
           $data2->address =$address;
           $data2->amenities =$amenities;
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

      /*........Get Restaurant Detail BLE brodcast data Api  For Restaurant   ---- */

  /*......... Get Check Version data   ---- */
  public function app_check_version_post()
   {
    $response = new StdClass();
    $result2 = new StdClass();
    $version_name = $this->input->post('version_name');
    $version_code =$this->input->post('version_code');
    $result->version_name = $version_name;
    $result->version_code = $version_code;
    $res = $this->Customer->Validate_version_data($result);
    if($res!='')
    {
    $data1->status ='1';
    $data1->message = 'success';
    array_push($result2,$data1);
    $response->data = $data1;
      }
    
    else
    {
      $data1->status ='0';
      $data1->message = 'Update Your Application';
      array_push($result2,$data1);
      $response->data = $data1;
    }
    echo json_output($response);
   }
      /*......... Get Check Version data  ---- */


     /*......... logout Api For Door hawker ---- */
    public function data_logout_for_customer_post()
    {
    $response = new StdClass();
    $result = array();
    $device_id =$this->input->post('device_id');
    $cus_id =$this->input->post('cus_id');
    date_default_timezone_set('Asia/kolkata'); 
    $now = date('Y-m-d H:i:s');
    $data->cus_id = $cus_id;
    $data->device_id = $device_id;
    $data->logout_time=$now;
    $resdata1 = $this->Customer->logout_customer_data($data);
    if(!empty($device_id) and !empty($cus_id))
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

    /*......... logout Api For Door hawker ---- */
    public function service_request_city_by_customer_post()
    {
    $response = new StdClass();
    $result = array();
    $city =$this->input->post('city');
    $notification_id =$this->input->post('notification_id');
    date_default_timezone_set('Asia/kolkata'); 
    $now = date('Y-m-d H:i:s');
    $data->city = $city;
    $data->notification_id = $notification_id;
    $data->date_time=$now;
    $resdata1 = $this->Customer->request_city_by_customer_data($data);
    if($resdata1!='')
    {
    $data1->status ='1';
    $data1->message=' success';
    array_push($result,$data1);
    $response->data = $data1;
      }
        else
        {
          $data1->status ='0';
          $data1->message ='failed';
      array_push($result,$data1);
       $response->data = $data1;
        }
    echo json_output($response);
   }

    /*......... logout data From Wifi-module Api For Door Unlock ---- */

    /*......... feedback Api for restaurant by  customer  ---- */
  public function feedback_detail_for_customer_post()
   {
      $response = new StdClass();
      $result = new StdClass();
      $cus_id = $this->input->post('cus_id');
      $detail = $this->input->post('detail');
      $customer_mobile_no = $this->input->post('customer_mobile_no');
      date_default_timezone_set('Asia/kolkata'); 
      $now = date('Y-m-d H:i:s');
      $data->cus_id = $cus_id;
      $data->detail = $detail;
      $data->customer_mobile_no = $customer_mobile_no;
      $data->create_date = $now;
      $data->status = '1';
      $res = $this->Customer->feedback_data($data);
      if(!empty($res))
      {

      $data1->status ='1';
      $data1->message ='Your feedback has been send successfully.';
      array_push($result,$data1);
      $response->data = $data1;
      }
      else
      {
      $data1->status ='0';
      $data1->message ='failed';
      array_push($result,$data1);
      $response->data = $data1;
      }
    echo json_output($response);
   }

   /*.........  feedback Api for hawker customer  ---- */

   /*........Rating for customer for restaurant ---- */

    public function rating_for_restaurant_by_customer_post()
    {
     $response = new StdClass();
     $result = new StdClass();
     $admin_id  = $this->input->post('admin_id');
     $res_id  = $this->input->post('res_id');
     $cus_id  = $this->input->post('cus_id');
     $customer_mobile_no  = $this->input->post('customer_mobile_no');
     $rating_point  = $this->input->post('rating_point');
     $detail  = $this->input->post('detail');
     date_default_timezone_set('Asia/kolkata'); 
     $now = date('Y-m-d H:i:s');
     $data->admin_id  = $admin_id;
     $data->res_id  = $res_id;
     $data->cus_id  = $cus_id;
     $data->customer_mobile_no  = $customer_mobile_no;
     $data->rating_point  = $rating_point;
     $data->customer_mobile_no  = $customer_mobile_no;
     $data->detail  = $detail;
     $data->create_date = $now;
     $data->status='1';
     $res = $this->Customer->rating_for_restaurant_customer($data);
   
     if(!empty($customer_mobile_no))
      {
       $query = $this->db->query("SELECT AVG(rating_point) as AVGRATE from tbl_rating_for_customer where admin_id='$admin_id' and res_id='$res_id'");
      $current_data=$query->result_array();
     $array = implode(',', $current_data[0]);
     $arraydata=number_format($array,2);

      $rating_point = $this->Customer->average_rating_for_restaurant($arraydata,$admin_id);
       $data2->status ='1';
       $data2->message = 'success';
      array_push($result,$data2);
      $response->data = $data2;
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
    /*........Rating for customer for restaurant ---- */
    /*.........Add order for customer  restaurant  for Restaurant Api  ---- */
    public function add_contact_detail_for_customer_post()
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
        $name=$this->input->post('name');
        $email=$this->input->post('email');
        $mobile_no=$this->input->post('mobile_no');
        $message=$this->input->post('message');
        date_default_timezone_set('Asia/kolkata'); 
        $now = date('Y-m-d H:i:s');
        $data->name=$name;
        $data->email=$email;
        $data->mobile_no=$mobile_no;
        $data->message=$message;
        $data->create_date=$now;
        $data->status='1';
        $result = $this->Customer->add_contact_detail($data);
        if(!empty($result))
        {  
            $mail->From ='smarterdine@gmail.com';
            $mail->FromName ='OYLY Admin';
            $mail->addAddress($email,'Admin');
            $mail->addCC('smarterdine@gmail.com');
            // $mail->addBCC('pankaj.kumar@goolean.tech');
            $mail->isHTML(true);
            $mail-> Subject= 'OYLY';
            $mail-> Body="Thanks for showing your interest with us.We will contact you soon.";
            $mail->send();

            $data2->status ='1';
            $data2->message = 'your query successfully saved';
            array_push($result2,$data2);
            $response->data = $data2;
        }else
          {
              $data2->status ='0';
              $data2->message = 'failed';
              array_push($result2,$data2);
              $response->data = $data2;
          }
        echo  json_output($response);
    }

   /*.........Role Api For Restaurant ---- */
    /*.........Update  payment status for restaurant---- */
    public function update_payment_for_customer_post()
     {
        $response   =   new StdClass();
        $result       =  new StdClass();
        $order_id =$this->input->post('order_id');
        $admin_id =$this->input->post('admin_id');
        $payment_status =$this->input->post('payment_status');
        $data->order_id = $order_id;
        $data->admin_id=$admin_id;
        $data->payment_status=$payment_status;
        $res1 = $this->Customer->update_payment_status($data);
        if($order_id!='')
        {
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

    /*......... check data for customer  ---- */
  public function customer_data_post()
   {
      $response = new StdClass();
      $result = new StdClass();
      $mobile_no = $this->input->post('mobile_no');
      $res = $this->Customer->customer_data($mobile_no);
      if($mobile_no=='')
      {
      $data1->status ='2';
      array_push($result,$data1);
      $response->data = $data1;
      }
      else if(!empty($res))
      {
      $data1->status ='1';
      array_push($result,$data1);
      $response->data = $data1;
      }
      else
      {
      $data1->status ='0';
      $data1->message ='Please Re-Login / Restart Application \n Press OK';
      array_push($result,$data1);
      $response->data = $data1;
      }
    echo json_output($response);
   }

   /*......... check data for customer ---- */

   public function show_notification_by_count_post()
    {
    $response =   new StdClass();
    $result       =  new StdClass();
    $customer_mobile_no =$this->input->post('customer_mobile_no');
    $resdata1 = $this->Customer->check_total_count_notification($customer_mobile_no);
    $resdata2 = $this->Customer->check_total_count_notification2($customer_mobile_no);
    $resdata=$resdata1+$resdata2;
    // print_r($resdata);exit;
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

   /*.........notification list for staff in  Restaurant ---- */
     public function get_notification_list_for_order_post()
      {
        $response   =   new StdClass();
        $result       =   array();
        $customer_mobile_no=$this->input->post('customer_mobile_no');
        $get_notification_data1 = $this->Customer->get_notification_data($customer_mobile_no);
        $get_notification_data2 = $this->Customer->get_notification_data2($customer_mobile_no);
        $get_notification_data=array_merge_recursive($get_notification_data1,$get_notification_data2);
        if(!empty($get_notification_data))
        {
         foreach ($get_notification_data as $row)
           {
           $admin_id=$row['admin_id'];
           $get_restaurant_name = $this->Customer->get_restaurantName($admin_id);
           $name=$get_restaurant_name->name;
            // $data['order_id'] =   $row['order_id'];
            $data['order_id']=$row['order_id'];
            $data['restaurant_name'] = $name;
            $data['customer_mobile_no'] =   $row['customer_mobile_no'];
            $data['title'] =   $row['title'];
            $data['message'] =   $row['message'];
            $data['create_date'] =   $row['date_time'];
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
                return [$a['create_date']] <= [$b['create_date']];
            });
           $response->data = $result;
           echo json_output($response);
        }

      /*.........super sub Category   Api For hawker  ---- */

       public function get_notification_status_by_restaurant_post()
    {
    $response =   new StdClass();
    $result       =  new StdClass();
    $customer_mobile_no =$this->input->post('customer_mobile_no');
    $check_status =$this->input->post('check_status');
    if($check_status=='1' and $customer_mobile_no!='')
    {
       $resdata = $this->Customer->check_status_for_notification($check_status,$customer_mobile_no);
       $this->Customer->check_status_for_notification2($check_status,$customer_mobile_no);
       if($resdata){
              $data1->status ='1';
              $data1->message = 'success';
              array_push($result,$data1);
              $response->data = $data1;
       }else{

           $data1->status ='0';
        $data1->message = 'failed';
        array_push($result,$data1);
        $response->data = $data1;
       }
    
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
       /*.........food tyoe api for  Restaurant ---- */
     public function get_food_type_post()
      {
        $response   =   new StdClass();
        $result       =   array();
        $food_type = $this->Customer->get_food_type();
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

      /*.........super sub Category   Api For hawker  ---- */
  public function GenrateRSA_post()
  {
    try
    {

       $order_id=$this->input->post('order_id');
       $path=APPPATH."libraries/cacert.pem";
        if(!empty($order_id))
        {
            $result=paymentTransaction($order_id,$path);
            $arry['data']=array('status'=>'1','message'=>$result);
            $this->response($arry, 200);
            // print_r($result);exit;
        }else
        {
          $arry['data']=array('status'=>'0','message'=>'failed');
          $this->response($arry, 200);
        }
    }catch(Exception $e)
    {
      echo $e->getMessage(); 
      $error = array('status' =>'0', "message" => "Internal Server Error - Please try Later.","StatusCode"=> "HTTP405");
      $this->response($error, 200);
    }
   
  }

  public function ccAvenueResponseHandler_post()
  {
     try
     {
      $working_key=$this->config->item('working_key');
      $encResponse=$_POST["encResp"];
      $data['response']=array(
                'working_key'  =>$working_key,
                'encResp'      =>$encResponse
          );
      // print_r($data);exit;
      $this->load->view('ccavResponseHandler',$data);
     }catch(Ecception $e)
     {
        $e->getMessage();
        $error = array('status' =>'0', "message" => "Internal Server Error - Please try Later.","StatusCode"=> "HTTP405");
        $this->response($error, 200);

     }
      
  }

  public function getCCavenuParameter_get()
  {
    try
    {
    $working_key=$this->config->item('working_key');
    $access_code=$this->config->item('access_code');
    $merchant_id=$this->config->item('merchant_id');
    $api_key    =$this->config->item('api_key');
    $app_id     =$this->config->item('app_id');
    $array=array(
              // 'working_key'=>$working_key,
              // 'access_code'=>$access_code,
              // 'merchant_id'=>$merchant_id,
              'client_id' =>$api_key,
              'app_id'=>$app_id
              );
            if(!empty($array))
            {
                  $arry=array('status'=>'1','data'=>$array);
                  $this->response($arry, 200);
            }else
            {
                  $arry['data']=array('status'=>'0','data'=>'failed');
                  $this->response($arry, 200);
            }
    }catch(Ecception $e)
    {
        $e->getMessage();
        $error = array('status' =>'0', "message" => "Internal Server Error - Please try Later.","StatusCode"=> "HTTP405");
        $this->response($error, 200);
    }
    
  }

  public function getTokenForCashFree_post()
  {
    try
    {
      $split_array=array();
      $order_id   =$this->input->post('order_id');
      $amount     =$this->input->post('amount');
      $admin_id   =$this->input->post('admin_id');
      $mobile_no  =$this->input->post('mobile_no');      
      $currency   ="INR";
      if(!empty($order_id)&&!empty($amount)&&!empty($currency) && !empty($admin_id) && !empty($mobile_no))
      {
            $api_key      =$this->config->item('api_key');
            $app_id       =$this->config->item('app_id');
            $payload=array(

                          'orderId'=>$order_id,
                          'orderAmount'=>$amount,
                          'orderCurrency'=>$currency
                        );
          $json=json_encode($payload);
          $token=generateToken($api_key,$app_id,$json);
          $result=$this->Customer->getCustInfo($mobile_no);
          $name=empty($result[0]['name'])?'Dear Customer':$result[0]['name'];
          //$mobile_no=$result[0]['mobile_no'];
          $email_id=empty($result[0]['email_id'])?'hawkers.nearme@gmail.com':$result[0]['email_id'];
          $arry=array(
              'status'    =>'1',
              'app_id'    =>$app_id,
              'name'      =>$name,
              'mobile_no' =>$mobile_no,
              'email_id'  =>$email_id,
              'data'      =>json_decode($token)
            );
          $this->response($arry, 200);

      }else
      {
              $arry=array('status'=>'0','data'=>'All fields are required.');
              $this->response($arry, 200);
      }
    }catch(Ecception $e)
    {
        $e->getMessage();
        $error = array('status' =>'0', "message" => "Internal Server Error - Please try Later.","StatusCode"=> "HTTP405");
        $this->response($error, 200);
    }
  }
public function getRestaurantCategory_post()
{
  try
  {
      $admin_id=$this->input->post('admin_id');

      $cat_result=$this->Customer->getCatIds($admin_id);

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
            $result=$this->Customer->getRestaurantCategory($admin_id,rtrim($string,','));
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
  public function getMenuListDataCustomer_post()
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
                            $data2['menu_image'] = $value2['menu_image']!=''?base64_encode(file_get_contents(base_url().'uploads/'.$value2['menu_image'])):'';
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
                            $data3['menu_image'] = $value3['menu_image']!=''?base64_encode(file_get_contents(base_url().'uploads/'.$value3['menu_image'])):'';

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
  public function getCashFreeResponse_post()
{

  date_default_timezone_set('Asia/kolkata'); 
  $order_id         =$this->input->post('order_id');
  $amount           =$this->input->post('amount');
  $reference_id     =$this->input->post('reference_id');
  $txn_status       =$this->input->post('txn_status');
  $payment_mode     =$this->input->post('payment_mode');
  $txn_message      =$this->input->post('txn_message');
  $txn_time         =$this->input->post('txn_time');
  $txn_type         =$this->input->post('txn_type');
  $signature        =$this->input->post('signature');
  $creation_date    =date('Y-m-d H:i:s');
  if(!empty($reference_id))
  {
    $array=array(
              'order_id'        =>$order_id,
              'amount'          =>$amount,
              'reference_id'    =>$reference_id,
              'txn_status'      =>$txn_status,
              'payment_mode'    =>$payment_mode,
              'txn_message'     =>$txn_message,
              'txn_time'        =>$txn_time,
              'txn_type'        =>$txn_type,
              'signature'       =>$signature,
              'creation_date'   =>$creation_date 
      );
  
        if($txn_status=='SUCCESS')
        {
          $string1="Update tbl_order_detail_for_restaurant set order_status='Closed',status='7',payment_status=1 WHERE order_id='$order_id' and status !=0";
          $string2="Update tbl_sub_order_detail_for_restaurant set order_status='Closed',status='7',payment_status=1 WHERE order_id='$order_id' AND  status !=0";
          $this->db->query($string1);
          $this->db->query($string2);
        }
        $result=$this->Customer->txnRecord($array);

        if(!empty($result))
          {
                       
                        $order_date         =date('Y-m-d',strtotime($this->Supervisor->getOrderDate($order_id)));
                        $admin_id           =$this->Customer->getAdmin($order_id);
                        $adminData          =$this->Customer->getAdminData($admin_id);
                        $getCustmoerData    =$this->Customer->getCustmoerData($order_id,$admin_id);
                        $order_id2          =str_replace($admin_id.'-','',$order_id);
                        $custData           =$this->Customer->getCustData($getCustmoerData[0]['customer_mobile_no']);
                        $notification_data   =$this->Supervisor->getWaiterNotification($getCustmoerData[0]['confirm_order_by']);
                        $array_merge_recursive=array_merge_recursive($notification_data,array_merge_recursive($custData,$adminData));
                        // echo '<pre>';print_r($array_merge_recursive);exit;
                        foreach($array_merge_recursive as $notification)
                            {
                             if($notification['user_type']=='customer'){
                                  $title ='Payment status';
                                  $message ='Payment is compelete';
                                  }else if($notification['user_type']=='Waiter'){
                                  $title ='Payment status';
                                  $message ='Payment done from table no '.$getCustmoerData[0]['table_no'];
                                  }else if($notification['user_type']=='admin'){
                                  $title ='Payment status';
                                  $message ='Payment is compelete';
                                  }
                             $result=sendPushNotification($title,$message,$notification['notification_id']);
                              if($result)
                              {
                                $array2[]=array(
                                        'mobile_no'=>$notification['mobile_no'],
                                        'admin_id'=>$admin_id,
                                        'status'=>1,
                                        'order_id'=>$order_id2,
                                        'table_no'=>$getCustmoerData[0]['table_no'],
                                        'title'=>$title,
                                        'message'=>$message,
                                        'customer_mobile_no'=>$getCustmoerData[0]['customer_mobile_no'],
                                        'date_time'=>date('Y-m-d H:i:s')
                                   ); 
                              }
                            }   
                           // echo '<pre>'; print_r($array2);exit;
                            if(!empty($array2))
                            {
                              $this->Customer->insertNotification($array2);                          
                            }
            $arry['data']=array('status'=>'1','data'=>'success');
                  $this->response($arry, 200);
          }else
          {
             $arry['data']=array('status'=>'0','data'=>'failed');
                  $this->response($arry, 200);
          }

  }else
  {
            $arry['data']=array('status'=>'0','data'=>'failed');
            $this->response($arry, 200);
  }

}
public function companyPolicy_get()
{
  $result=$this->Customer->getCompanyPolicy();
  if(!empty($result))
  {
            $arry=array('status'=>'1','data'=>$result);
            $this->response($arry, 200);
  }else
  {
    $arry=array('status'=>'0','data'=>'failed');
            $this->response($arry, 200);
  }
}
public function sendPushNtification_post()
{
  $firebase = new Firebase();
  $push = new Push();
  $payload = array();
  $payload['team'] = 'India';
  $payload['score'] = '5.6';
  $title ='Testing';
  $message ='Testing';
  $push_type ='individual';
  $include_image = isset($_POST['include_image']) ? TRUE : FALSE;
  $push->setTitle($title);
  $push->setMessage($message);
  if ($include_image) {
      $push->setImage('https://api.androidhive.info/images/minion.jpg');
  } else {
      $push->setImage('');
  }
  $push->setIsBackground(FALSE);
  $push->setPayload($payload);
  $json = '';
  $response = '';

  if ($push_type == 'topic') {
      $json = $push->getPush();
      $response = $firebase->sendToTopic('global', $json);
  } else if ($push_type == 'individual') {
      $json = $push->getPush();
      $response = $firebase->send('d3mNWHf1YUc:APA91bGhrq1shPnZEbbv-MoBz6gBtWupe2C3qFxGHCRlKyeXKCOpEQN1DrgelT9t5_eSiNmlh-v2KKT45nxmqtlOTCHyLG-7U_Eljoa1hESzQHjf-HbF6N4Y-o3zHftBobKZnrlZLTK-', $json);
      print_r($response);exit;
}
}
public function getNearBySpots_post()
{
  try
  {     
        $city=$this->input->post('city');
        $result=$this->Customer->getNearBySpots($city);
        if(!empty($result))
        {
           foreach($result as $value)
           {
                $array['admin_id']      =$value['admin_id'];
                $array['city']          =$value['city'];
                $array['spotId']        =$value['spotId'];
                $array['trending']      =$value['trending'];
                $array['name']          =$value['name'];
                $array['image']         =empty($value['image'])?'':base_url().'uploads/'.$value['image'];
                $array['rating']        =$value['rating'];
                $array['lat']           =$value['lat'];
                $array['lng']           =$value['lng'];
                $array['location']      =$value['location'];
                $array['cuisines']      =$value['cuisines'];
                $array['priceLevel']    =$value['priceLevel'];
                $array['cost']          =$value['cost'];
                $array['openStatus']    =$value['openStatus'];
                $array['cost']          =$value['cost'];
                $array['openingTime']   =$value['openingTime'];
                $array['closingTime']   =$value['closingTime'];
                $array['phone']         =$value['phone'];
                $array['address']       =$value['address'];
                $array['imageList']     =explode(",", $value['imageList']);
                $array['amenities']     =explode(",", $value['amenities']);
                $array['verified']      =$value['verified'];
                $finalarray[]           =$array;

                } 
           $arry=array('time'=>date('H:i d-m-Y'),'spots'=>$finalarray);
                  $this->response($arry, 200);
        }else
        {
           $arry=array('time'=>date('H:i d-m-Y'),'spots'=>'failed');
                  $this->response($arry, 200);
        }

  }catch(Ececption $e)
  {
              echo $e->getMessage();
              $error = array('status' =>'0', "spots" => "Internal Server Error - Please try Later.","StatusCode"=> "HTTP405");
              $this->response($error, 200);
  }
  
}
public function getItemsForFeedback_post()
{
  try
  {
      $order_id         =$this->input->post('order_id');
      if(!empty($order_id))
      {
      $orderMenuIds     =$this->Customer->getMenuIdsForOrder($order_id);
      $subOrderMenuIds  =$this->Customer->getMenuIdsForrSubOrder($order_id);
      $array_merge=array_merge($orderMenuIds,$subOrderMenuIds);
      foreach($array_merge AS $value)
      {
        $array[]=$value['menu_id'];
      }
      $distinctIds=array_unique($array);
      $array_values=array_values($distinctIds);
      for($i=0;$i<count($array_values);$i++)
      {
        $string .= "'".$array_values[$i]."'".',';
      }
       // print_r($string);exit;
          $result=$this->Customer->getMenuItemsForFeedback(rtrim($string,','));
          $arry=array('status'=>1,'result'=>$result);
          $this->response($arry, 200);
      }else
      {
           $arry=array('status'=>0,'result'=>'failed');
           $this->response($arry, 200);
      }
  

  }catch(Ececption $e)
  {
              echo $e->getMessage();
              $error = array('status' =>'0', "result" => "Internal Server Error - Please try Later.","StatusCode"=> "HTTP405");
              $this->response($error, 200);
  }
}

public function customerFeedback_post()
{
  try
  {
      $admin_id   =$this->input->post('admin_id');
      $order_id   =$this->input->post('order_id');
      $menu_id    =$this->input->post('menu_id');
      $rating     =$this->input->post('rating');
      $message    =$this->input->post('message');
      if(!empty($admin_id) && !empty($order_id) && !empty($menu_id))
      {
          
          $menu_ids  =explode(",",rtrim($menu_id,","));
          $ratings   =explode(",",rtrim($rating,","));
          $messages  =explode(",",rtrim($message,","));
          for($i=0;$i<count($menu_ids);$i++)
          {
            $array=array(
                       'admin_id'         =>$admin_id, 
                       'order_id'         =>$order_id, 
                       'menu_id'          =>$menu_ids[$i], 
                       'rating'           =>$ratings[$i], 
                       'message'          =>'', 
                       'creation_date'    =>date('Y-m-d'), 
                       'status'           =>1 
            );
            $result=$this->Customer->customerRating($array);
            // print_r($result);exit;
            if($result)
            {
              $rating=$this->Customer->getMenuRating($menu_ids[$i]);
              $update=array('rating'=>$rating);
              $this->Customer->updateRating($admin_id,$menu_ids[$i],$update,$order_id);
            }
          }
          $this->Customer->updateOrderOnCustomerFeedback($admin_id,$order_id);
          $arry=array('status'=>1,'result'=>'Success');
          $this->response($arry, 200);

      }else
      {
          $arry=array('status'=>0,'result'=>'failed');
           $this->response($arry, 200);
      }
  }catch(Ececption $e)
  {
    $e->getMessage();
    $error = array('status' =>'0', "result" => "Internal Server Error - Please try Later.","StatusCode"=> "HTTP405");
    $this->response($error, 200);

  }

}
public function laterReview_post()
{
  try
  {
    $order_id=$this->input->post('order_id');
    if(!empty($order_id))
    {
        $orderMenuIds=$this->Customer->getMenuIdsForOrder($order_id);
        $subOrderMenuIds=$this->Customer->getMenuIdsForrSubOrder($order_id);
         // print_r($subOrderMenuIds);exit;
        $FeedbackMenuIds=$this->Customer->getCustomerFeedbackMenuIds($order_id);
       
        foreach($FeedbackMenuIds AS $value2)
        {
          $array2[]=$value2['menu_id'];
        }
        $distinctIds2=array_unique($array2);
        $array_values2=array_values($distinctIds2);
        // print_r($array_values2);exit;
        $array_merge=array_merge($orderMenuIds,$subOrderMenuIds);
        foreach($array_merge AS $value)
        {
          $array[]=$value['menu_id'];
        }
        // print_r($array);exit;
        $distinctIds=array_unique($array);
        $array_values=array_values($distinctIds);
         
        $out1 = array_diff($array_values, $array_values2);
        $out2 = array_diff($array_values2, $array_values);
        $output = array_merge($out1, $out2);
        
        if(!empty($output))
        {

          for($j=0;$j<count($output);$j++)
          {
             $string .= "'".$output[$j]."'".',';
          }
          $rtrim=rtrim($string,',');
          // print_r($rtrim);exit;
          $this->Customer->customerNotReview($order_id,$rtrim);

        }
        $arry=array('status'=>1,'result'=>'Success');
        $this->response($arry, 200);
    }else
    {
      $arry=array('status'=>0,'result'=>'failed');
           $this->response($arry, 200);
    }

  }catch(Ececption $e)
  {
    $e->getMessage();
    $error = array('status' =>'0', "result" => "Internal Server Error - Please try Later.","StatusCode"=> "HTTP405");
    $this->response($error, 200);

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
      $result=$this->Customer->getRestaurantTypes();
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
function checkLoginStatus_post(){
  try{
  $mobile_no    =$_POST['mobile_no'];
  $device_id    =$_POST['device_id'];
  $cus_id       =$_POST['cus_id'];
  if(!empty($mobile_no)&&!empty($device_id)&& !empty($cus_id)){
    $result=$this->Customer->checkLoginStatus($mobile_no,$device_id,$cus_id);
        //print_r($result);exit;
        if(!empty($result[0]['notification_id'])){
          echo json_encode(array('status'=>1,'message'=>'success'));exit();
        }else{
          echo json_encode(array('status'=>0,'message'=>'logout success'));exit();
        }
  }else{
    echo json_encode(array('status'=>0,'message'=>'logout success'));exit();
  }
  }catch(Ececption $e){
      $e->getMessage();
      $error = array('status' =>'0', "data" => "Internal Server Error - Please try Later.","StatusCode"=> "HTTP405");
      $this->response($error, 200);
  }
}
}
