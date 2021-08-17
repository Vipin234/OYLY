<?php

class Supervisor extends CI_Model
{    
    
    function admin_registration($data)
    {

        $name =$data ->name;
        $restaurant_name=$data->restaurant_name;
        $mobile_no=$data->mobile_no;
        $user_email=$data->email;
        $user_password=$data->user_password; 
        $user_role=$data->user_role;
        $user_active=$data->user_active;
        $user_createdate=$data->user_createdate;        
        $status=$data->status;
        $salt=$data->salt;
        $admin_id=$data->admin_id;
        $user_type='Admin';
        $query="insert into tbl_admin(user_fullname,restaurant_name,mobile_no,user_email,user_password,user_role,user_active,user_type,user_createdate,status,salt,admin_id) values('$name','$restaurant_name','$mobile_no','$user_email','$user_password','$user_role','$user_active','$user_type','$user_createdate','$status','$salt','$admin_id')";
        $this->db->query($query);    
      return $query?$this->db->insert_id():false;   
      
    }
     function add_order_detail_restaurant($data)
    {
        
        $json_decode =json_decode(TABLES);
        $insert = $this->db->insert($json_decode->table30, $data);
	       // print_r($this->db->last_query());exit;
        return $insert?$this->db->insert_id():false;
      
    }


    function update_menu_id($alphanumerric,$result)
    {   
      $json_decode =json_decode(TABLES);
      $this->db->query("UPDATE $json_decode->table24 SET menu_id='$alphanumerric' where id='$result'");
    }
    function update_payment_status_by_staff($data,$get_payment)
    { 
      $json_decode =json_decode(TABLES);
      $order_id         =$data->order_id;
      $admin_id         =$data ->admin_id;
      $payment_status   =$data->payment_status;
      $payment_by       =$data->payment_by;
      $order_closed_by  =$data->payment_by;
      $get_payment      =empty($get_payment)?'':$get_payment;

      $this->db->query("UPDATE $json_decode->table17 SET payment_status='$payment_status',payment_by='$payment_by',get_payment='$get_payment',status='7',order_closed_by='$order_closed_by',order_status='Closed' where order_id='$order_id' and admin_id='$admin_id' AND status='6'");
    }
    function update_payment_status_by_staff2($data,$get_payment)
    { 
      $json_decode =json_decode(TABLES);    
      $order_id         =$data->order_id;
      $admin_id         =$data ->admin_id;
      $payment_status   =$data->payment_status;
      $payment_by       =$data->payment_by;
      $order_closed_by  =$data->payment_by;
      $get_payment      =empty($get_payment)?'':$get_payment;

      $this->db->query("UPDATE $json_decode->table30 SET payment_status='$payment_status',payment_by='$payment_by',get_payment='$get_payment',status='7',order_closed_by='$order_closed_by',order_status='Closed' where order_id='$order_id' and admin_id='$admin_id' AND status='6' ");
    }
    function BLE_brodcast_for_restaurants($data)
    {   
      $admin_id =$data ->admin_id;
      $BLE_id=$data->BLE_id;
      $this->db->query("UPDATE spots SET BLE_id='$BLE_id' where  admin_id='$admin_id' ");
    }

    function update_order_waiter_id($alphanumerric,$result)
    {   
      $this->db->query("UPDATE tbl_order_detail_for_restaurant SET order_id='$alphanumerric' where id='$result'");
    }


    function remove_staff($mobile_no,$user_type)
    {   
      date_default_timezone_set('Asia/kolkata'); 
      $now = date('Y-m-d H:i:s');    
      $this->db->query("delete from tbl_restaurant_staff_registration where mobile_no='$mobile_no' and user_type='$user_type'");
      $this->db->query("delete from tbl_manage_login_user where mobile_no='$mobile_no' and user_type='$user_type'");
    }
    function remove_menu_item_staff($menu_id,$admin_id)
    {   
      date_default_timezone_set('Asia/kolkata'); 
      $now = date('Y-m-d H:i:s');
      $this->db->query("UPDATE tbl_restaurant_menu_item_list SET status='0',modified_date='$now' where menu_id='$menu_id' and admin_id='$admin_id'");
    }

    function confirm_order_by_waiter($data)
    {   
      date_default_timezone_set('Asia/kolkata');
      $json_decode =json_decode(TABLES); 
      $now = date('Y-m-d H:i:s');
      $waiter_mobile_no =$data ->waiter_mobile_no;
      $order_status=$data->order_status;
      $admin_id=$data->admin_id;
      $order_id=$data->order_id;
      $this->db->query("UPDATE $json_decode->table17 SET status='2',modified_date='$now',confirm_order_by='$waiter_mobile_no',order_status='$order_status' where order_id='$order_id' and  admin_id='$admin_id'");
    }

    function update_restaurant_data($data)
    {   
      date_default_timezone_set('Asia/kolkata'); 
      $now = date('Y-m-d H:i:s');
      $city =$data ->city;
      $admin_id=$data->admin_id;
      $name=$data->name;
      $image=$data->image;
      $gst_no =$data ->gst_no;
      $pan_no=$data->pan_no;
      $lat=$data->lat;
      $lng=$data->lng;
      $location =$data ->location;
      $cuisines=$data->cuisines;
      $cost=$data->cost;
      $openingTime=$data->openingTime;
      $closingTime =$data ->closingTime;
      $phone=$data->phone;
      $address=$data->address;
      $amenities=$data->amenities;
      $update_by=$data->update_by;
      $food_code=$data->food_code;
        
      $this->db->query("UPDATE spots SET city='$city',name='$name',image='$image',gst_no='$gst_no',pan_no='$pan_no',lat='$lat',lng='$lng',location='$location',cuisines='$cuisines',cost='$cost',openingTime='$openingTime',closingTime='$closingTime',phone='$phone',address='$address',amenities='$amenities',update_by='$update_by',modified_date='$now',food_code='$food_code' where admin_id='$admin_id'");

      $this->db->query("UPDATE tbl_admin SET restaurant_name='$name' where admin_id='$admin_id'");
    }

     function create_slip($data)
    {   
      date_default_timezone_set('Asia/kolkata'); 
      $now = date('Y-m-d H:i:s');
      $json_decode =json_decode(TABLES);
      $admin_id=$data->admin_id;
      $order_id=$data->order_id;
      $mobile_no=$data->mobile_no;  
      $this->db->query("UPDATE $json_decode->table17 SET slip_status='1',modified_date='$now',create_slip_by='$mobile_no',order_status='Prepare',status='3' where order_id='$order_id' and  admin_id='$admin_id'");
    }
    function get_order_detail_for_staff($order_id)
     {
       
        $q=$this->db->query("SELECT * from tbl_order_detail_for_restaurant where order_id='".$order_id."'");
          return($q->row());
        $row = $q->num_rows();
     }

    function order_update_for_customer_by_staff($data)
    { 
      $order_id=$data->order_id;  
      $admin_id =$data->admin_id;
      $table_no =$data ->table_no;
      $menu_item_name=$data->menu_item_name;
      $quantity=$data->quantity;
      $menu_price=$data->menu_price;
      $total_item=$data->total_item;
      $total_price=$data->total_price;
      $gst_amount =$data->gst_amount;
      $order_status =$data ->order_status;
      $order_change_by=$data->order_change_by;
      $slip_status=$data->slip_status;
      date_default_timezone_set('Asia/kolkata'); 
      $now = date('Y-m-d H:i:s');
      $this->db->query("UPDATE tbl_order_detail_for_restaurant SET admin_id='$admin_id',table_no='$table_no',menu_item_name='$menu_item_name',quantity='$quantity',menu_price='$menu_price',total_item='$total_item',total_price='$total_price',gst_amount='$gst_amount',order_status='$order_status',order_change_by='$order_change_by',slip_status='$slip_status',modified_date='$now' where order_id='$order_id'");

    }

    function delete_order($data)
    {   
      date_default_timezone_set('Asia/kolkata');
      $json_decode =json_decode(TABLES); 
      $now = date('Y-m-d H:i:s');
      $admin_id=$data->admin_id;
      $order_id=$data->order_id;
      $mobile_no=$data->mobile_no;  
      $result=$this->db->query("UPDATE $json_decode->table17 SET status='0',modified_date='$now',order_delete_by='$mobile_no',order_status='Rejected' where order_id='$order_id' and  admin_id='$admin_id'");
      return $result?TRUE:FALSE;

    }

    function complete_order_by_supervisor($data)
    {   
      date_default_timezone_set('Asia/kolkata'); 
      $now = date('Y-m-d H:i:s');
      $admin_id=$data->admin_id;
      $order_id=$data->order_id;
      $supervisor_mobile_no=$data->supervisor_mobile_no;  
      $this->db->query("UPDATE tbl_order_detail_for_restaurant SET status='4',modified_date='$now',order_complete_by='$supervisor_mobile_no',order_status='Complete' where order_id='$order_id' and  admin_id='$admin_id'");
    }

    function complete_order_by_chef($data)
    {   
      date_default_timezone_set('Asia/kolkata'); 
      $now = date('Y-m-d H:i:s');
      $admin_id=$data->admin_id;
      $order_id=$data->order_id;
      $chef_mobile_no=$data->chef_mobile_no;  
      $this->db->query("UPDATE tbl_order_detail_for_restaurant SET status='5',modified_date='$now',order_ready_to_serve_by='$chef_mobile_no',order_status='Ready to Serve' where order_id='$order_id' and  admin_id='$admin_id'");
    }


    function staff_registration($data)
    {
        $json_decode =json_decode(TABLES);
        $insert = $this->db->insert($json_decode->table25, $data);
        return $insert?$this->db->insert_id():false;      
    }
    function add_order_detail_for_waiter($data)
    {
        $json_decode =json_decode(TABLES);
        $insert = $this->db->insert($json_decode->table17, $data);
        return $insert?$this->db->insert_id():false;
      
    }

    function add_restaurant($data)
    {
        $json_decode =json_decode(TABLES);
        $insert = $this->db->insert($json_decode->table3, $data);
        return $insert?$this->db->insert_id():false;      
    }

    function add_menu_item_restaurant($data)
    {
        $json_decode =json_decode(TABLES);
        $insert = $this->db->insert($json_decode->table24, $data);
        return $insert?$this->db->insert_id():false;      
    }
     public function check_total_count($admin_id,$order_status)
     {
        date_default_timezone_set('Asia/kolkata'); 
        $now = date('Y-m-d');
        $q=$this->db->query("SELECT order_id from tbl_order_detail_for_restaurant where admin_id='$admin_id' and order_status='$order_status'");
        return($q->num_rows());
     }

    function get_user_type()
    {  
      $q=$this->db->query("SELECT * from tbl_user_type where status='1'");
       return($q->result_array());
    }
    function get_notification_data($staff_mobile_no,$user_type)
    {  
       date_default_timezone_set('Asia/kolkata'); 
        $now = date('Y-m-d');
      $q=$this->db->query("SELECT  `order_id`, `admin_id`, `table_no`, `customer_mobile_no`, `title`, `message`, `date_time`, `modified_date`, `status`, `count_status` from tbl_notification_by_staff where staff_mobile_no='$staff_mobile_no'and user_type='$user_type' and date_time LIKE '%$now%'order by date_time DESC");
       // print_r($this->db->last_query());exit;
       return($q->result_array());
    }
    function get_notification_data3($staff_mobile_no,$user_type)
    {  
       date_default_timezone_set('Asia/kolkata'); 
        $now = date('Y-m-d');
      $q=$this->db->query("SELECT  `order_id`, `admin_id`, `table_no`, `customer_mobile_no`, `title`, `message`, `date_time`, `modified_date`, `status`, `count_status` from tbl_notification_by_customer where mobile_no='$staff_mobile_no'and user_type='$user_type' and date_time LIKE '%$now%'order by date_time DESC");

       return($q->result_array());
    }
 function get_notification_data2($staff_mobile_no,$user_type)
    {  
       date_default_timezone_set('Asia/kolkata'); 
        $now = date('Y-m-d');
      $q=$this->db->query("SELECT  `order_id`, `admin_id`, `table_no`, `customer_mobile_no`, `mobile_no`, `title`, `message`, `date_time`, `modified_date`, `status`, `count_status` from tbl_notification_by_customer where mobile_no='$staff_mobile_no'and user_type='$user_type' and date_time LIKE '%$now%' order by date_time DESC");
       return($q->result_array());
    }

    function get_order_data($start_date,$end_date,$admin_id)
    {  
      
      $q=$this->db->query("SELECT * FROM tbl_order_detail_for_restaurant where date 
                          between '$start_date' and '$end_date'  and order_status ='Complete' and admin_id='$admin_id' order by date DESC ");
      
       return($q->result_array());
    }
    function getGroupData($admin_id,$order_status)
    {
      $json_decode =json_decode(TABLES);
      $this->db->select('*');
      $this->db->from($json_decode->table17);
      $this->db->where('admin_id',$admin_id);
      $this->db->where('status !=','7');
      $this->db->group_by('order_id');
      $this->db->order_by('create_date',DESC);
      $query=$this->db->get();
      // print_r($this->db->last_query());exit;
      $result=$query->result_array();
      return $result;
     
    }
     function getGroupData_for_date($admin_id,$start_date,$end_date)
    {
      
        $data=$this->db->query("SELECT * from tbl_order_detail_for_restaurant where date 
                          between '$end_date' and '$start_date' and admin_id='$admin_id' group by order_id ORDER BY date DESC");
            
        return($data->result_array());
    }

    function getGroupDatas($order_id)
    {
      
         $data = $this->db->order_by('create_date', 'DESC')->group_by("order_id")->select('order_id')->get_where("tbl_order_detail_for_restaurant",["order_id"=>$order_id])->result_array();
       
       return $data;
    }
    function getDataOrderWise($orderid,$admin_id)
    {
      $result = $this->db->get_where("tbl_order_detail_for_restaurant",['order_id'=>$orderid])->result_array();
        return $result;

    }
    function getDataOrderWisedate($orderid)
    {
      $result = $this->db->get_where("tbl_order_detail_for_restaurant",['order_id'=>$orderid,'order_status'=>'Complete'])->result_array();
	//print_r($this->db->last_query());exit;

        return $result;

    }
    function getDataOrderWises($orderid)
    {
      $result = $this->db->get_where("tbl_order_detail_for_restaurant",['order_id'=>$orderid])->result_array();
        return $result;

    }
    function order_detail_for_restaurant_customer($admin_id,$order_status)
    { 
     
      if($order_status=='')
      {
         $q=$this->db->query("SELECT * from tbl_order_detail_for_restaurant where admin_id='$admin_id' and order_status!='Pending' and order_status!='Rejected' and order_status!='Complete' ORDER BY create_date DESC");
       return($q->result_array());
      }
      else
      {
         $q=$this->db->query("SELECT * from tbl_order_detail_for_restaurant where admin_id='$admin_id' and order_status='$order_status' ORDER BY create_date DESC");
       return($q->result_array());
      }
       
     
    }
    function update_menu_profile($data)
    { 
      $menu_id=$data->menu_id;  
      $admin_id =$data->admin_id;
      $menu_food_type =$data->menu_food_type;
      $menu_name =$data ->menu_name;
      $menu_image=$data->menu_image;
      $menu_detail=$data->menu_detail;
      $menu_half_price=$data->menu_half_price;
      $menu_full_price=$data->menu_full_price;
      $menu_fix_price=$data->menu_fix_price;
      $nutrient_counts =$data->nutrient_counts;
      $menu_category_id =$data->menu_category_id;
      $cat_id =$data->cat_id;
      $sub_cat_id =$data->sub_cat_id;

      date_default_timezone_set('Asia/kolkata'); 
      $now = date('Y-m-d H:i:s');

      $this->db->query("UPDATE tbl_restaurant_menu_item_list SET menu_food_type='$menu_food_type', menu_name='$menu_name',menu_image='$menu_image',menu_detail='$menu_detail',menu_half_price='$menu_half_price',menu_full_price='$menu_full_price',menu_fix_price='$menu_fix_price',nutrient_counts='$nutrient_counts',modified_date='$now',menu_category_id='$menu_category_id',cat_id='$cat_id',sub_cat_id='$sub_cat_id' where menu_id='$menu_id'");

    }

    function update_staff_profile($data)
    { 

      $admin_id=$data['admin_id'];  
      $name =$data['name'];
      $username =$data['username'];
      $mobile_no =$data ['mobile_no'];
      $email=$data['email'];
      $password=$data['password'];
      $date_of_birth=$data['date_of_birth'];
      $aadhar_no=$data['aadhar_no'];
      $pan_number=$data['pan_number'];
      $desingination =$data['desingination'];
      $gender =$data['gender'];
      $permanent_address =$data['permanent_address'];
      $current_address =$data['current_address'];
      $user_type =$data['user_type'];
      $id =$data['id'];
      date_default_timezone_set('Asia/kolkata'); 
      $now = date('Y-m-d H:i:s');
      $result=$this->getAdminMobileNo($admin_id);
      if($result[0]['mobile_no']!=$mobile_no){
         $this->db->query("delete from tbl_manage_login_user where mobile_no=".$result[0]['mobile_no']." and user_type='$user_type'");
      }
      $staffData=$this->getStaffData($user_type,$admin_id);
      if($staffData[0]['mobile_no']!=$mobile_no){
         $this->db->query("delete from tbl_manage_login_user where mobile_no=".$staffData[0]['mobile_no']." and user_type='$user_type'");
      }
      $this->db->query("UPDATE tbl_restaurant_staff_registration SET name='$name', username='$username',mobile_no='$mobile_no',email='$email',password='$password',date_of_birth='$date_of_birth',aadhar_no='$aadhar_no',pan_number='$pan_number',desingination='$desingination',gender='$gender',permanent_address='$permanent_address',current_address='$current_address',user_type='$user_type',modified_date='$now' where id='$id'");

    }
   
    function get_menu_list_data($admin_id)
    {  
      $q=$this->db->query("SELECT * from tbl_restaurant_menu_item_list where  admin_id='$admin_id' and  status='1'");
       return($q->result_array());
    }
    function get_food_type()
    {  
      $q=$this->db->query("SELECT * from tbl_food_type where status='1'");
       return($q->result_array());
    }
    function get_amenities_type()
    {  
      $q=$this->db->query("SELECT * from tbl_amenities_type where status='1'");
       return($q->result_array());
    }
    function get_staff_data($admin_id)
    {  
      $q=$this->db->query("SELECT * from tbl_restaurant_staff_registration where admin_id='$admin_id' and  status='1'");
       return($q->result_array());
    }

    
    function manage_login_data($where)
    {
        $name =$where ->name;
        $user_type=$where->user_type;
        $mobile_no=$where->mobile_no;
        $device_id=$where->device_id;
        $notification_id=$where->notification_id;
        $login_time=$where->login_time;
        $admin_id=$where->admin_id;
        // print_r($where);exit;
        $RolesForLogin=$this->getMultipleRolesForLogin($admin_id,$mobile_no);
        $array_admin=array(array('user_type'=>'Admin','name'=>$name,'mobile_no'=>$mobile_no,'device_id'=>$device_id,'notification_id'=>$notification_id,'login_time'=>$login_time,'active_status'=>1,'status'=>1));
        if(!empty($RolesForLogin)&&count($RolesForLogin) >1 && count($RolesForLogin)<6){
            $status=$this->checkLoginStatus($mobile_no,'');
            if(empty($status)){
              $this->db->query("delete from tbl_manage_login_user where mobile_no='$mobile_no'");
              $result=array_merge_recursive($RolesForLogin,$array_admin);
            }else{
              $this->db->query("delete from tbl_manage_login_user where mobile_no='$mobile_no'");
              $result=array_merge_recursive($RolesForLogin);
            }
              foreach($result AS $value){
                  $array=array(
                          'name'=>$name,
                          'mobile_no'=>$mobile_no,
                          'device_id'=>$device_id,
                          'notification_id'=>$notification_id,
                          'user_type'=>$value['user_type'],
                          'login_time'=>$login_time,
                          'active_status'=>1,
                          'status'=>1
                  );
                   $this->db->insert('tbl_manage_login_user',$array);
              }
              $this->db->query("UPDATE tbl_manage_login_user SET login_time='$login_time',notification_id='$notification_id',active_status=1,device_id='$device_id' where mobile_no='$mobile_no' and user_type='admin'");
        }else{
          $query=$this->db->get_where(json_decode(TABLES)->table13,['mobile_no'=>$where->mobile_no,'device_id'=>$device_id])->num_rows();
        $query1=$this->db->get_where(json_decode(TABLES)->table13,['mobile_no'=>$where->mobile_no])->num_rows();
        if($query>0){
             $this->db->query("UPDATE tbl_manage_login_user SET login_time='$login_time',notification_id='$notification_id',active_status=1 where mobile_no='$mobile_no' and device_id='$device_id'");
        }else if($query1>0){
            $this->db->query("UPDATE tbl_manage_login_user SET login_time='$login_time',notification_id='$notification_id',device_id='$device_id',active_status=1 where mobile_no='$mobile_no'");
        }else{
             $query="insert into tbl_manage_login_user(name,mobile_no,device_id,notification_id,user_type,login_time,active_status,status) values('$name','$mobile_no','$device_id','$notification_id','$user_type','$login_time','1','1')";
            $this->db->query($query);
          }
        }
     }
     function update_login_data($data)
     {
       $device_id=$data->device_id;
        $notification_id=$data->notification_id;
        $login_time=$data->login_time;
        $mobile_no = $data->mobile_no;

         $this->db->where('mobile_no', $data->mobile_no);  
             $this->db->update(json_decode(TABLES)->table13, ['login_time'=>$login_time,'notification_id'=>$notification_id,'device_id'=>$device_id,'active_status'=>'1']);

     }

    
      function get_restaurant_data($admin_id)
     {
       
        $q=$this->db->query("SELECT restaurant_name from tbl_admin where admin_id='".$admin_id."'");
          return($q->row());
        $row = $q->num_rows();
     }
     function check_data_for_restaurant($admin_id)
     {
       
        $q=$this->db->query("SELECT * from spots where admin_id='".$admin_id."'");
          return($q->row());
        $row = $q->num_rows();
     }
     function get_detail_for_restaurant($admin_id)
     {
       
        $q=$this->db->query("SELECT * from spots where admin_id='".$admin_id."'");
          return($q->row());
        $row = $q->num_rows();
     }
     function get_staff_detail($admin_id,$mobile_no,$user_type)
     {
        $q=$this->db->query("SELECT * from tbl_restaurant_staff_registration where admin_id='$admin_id' and mobile_no='$mobile_no'and user_type='$user_type'");
          return($q->row());
        $row = $q->num_rows();
     }
    
     function logout_staff_data($data)
    {
        $active_status='0';
        $mobile_no = $data->mobile_no;
        $device_id = $data->device_id;
        $now = $data->logout_time;
         $this->db->query("UPDATE tbl_manage_login_user SET active_status='$active_status',logout_time='$now',notification_id='NULL' where mobile_no='$mobile_no' and device_id='$device_id'");
    }


     function send_otp($mobile_no,$otpValue)
    {
        // Set POST variables
        //$mobile_no=9799572870;
        $url='https://2factor.in/API/V1/c43867a9-ba7e-11e9-ade6-0200cd936042/SMS/'.$mobile_no.'/'.$otpValue.'/OYLY%20OTP';
        // $url = 'https://2factor.in/API/V1/c43867a9-ba7e-11e9-ade6-0200cd936042/SMS/'.$mobile_no.'/'.$otpValue.'/'.'OTP'.'';
        // Open connection
        $ch = curl_init();
        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($url));
        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        // Close connection
        curl_close($ch);
        return $result;
    }
     function otpgetdata($data) 
     {
        $mobile_no=$data->mobile_no;
        $device_id=$data->device_id;
        $notification_id=$data->notification_id;
        $otp=$data->otp;
        date_default_timezone_set('Asia/kolkata'); 
        $now = date('Y-m-d H:i:s');
        $query=$this->db->get_where(json_decode(TABLES)->table19,['mobile_no'=>$data->mobile_no,'device_id'=>$device_id])->num_rows();
        if($query>0)
        {
            $this->db->query("UPDATE tbl_otp_admin SET create_date='$now',notification_id='$notification_id',mobile_no='$mobile_no',otp='$otp',modified_date='$now' where mobile_no='$mobile_no' and device_id='$device_id'");
        }
        else
        {
        $query="insert into tbl_otp_admin(otp,mobile_no,device_id,notification_id,create_date,status,modified_date) values('$otp','$mobile_no','$device_id','$notification_id','$now','1','$now')";
        $this->db->query($query);
        }
      }

      function verification_otp($data)
     { 
        $device_id=$data->device_id;
        $mobile_no=$data->mobile_no;
        $otp=$data->otp;
        $q=$this->db->query("SELECT * from tbl_otp_admin where otp='".$otp."' and  device_id='".$device_id."' and mobile_no='".$mobile_no."'");
          return($q->row());
        $row = $q->num_rows();

     }
      function resend_otp($data) 
     {
        $mobile_no=$data->mobile_no;
        $device_id=$data->device_id;
        $otp=$data->otp;
        date_default_timezone_set('Asia/kolkata'); 
        $now = date('Y-m-d H:i:s');
        $query=$this->db->get_where(json_decode(TABLES)->table19,['mobile_no'=>$data->mobile_no,'device_id'=>$device_id])->num_rows();
        if($query>0)
        {  
             $this->db->query("UPDATE tbl_otp_admin SET otp='$otp',modified_date='$now' where mobile_no='$mobile_no' and device_id='$device_id'");
        }
        
      }
       function remove_otp($data)
     {
        $mobile_no=$data->mobile_no;
        $device_id=$data->device_id;
         date_default_timezone_set('Asia/kolkata'); 
        $now = date('Y-m-d H:i:s');
        $query=$this->db->get_where(json_decode(TABLES)->table19,['mobile_no'=>$data->mobile_no,'device_id'=>$device_id])->num_rows();
        if($query>0)
        {
            $this->db->query("UPDATE tbl_otp_admin SET notification_id='' where device_id='$device_id' and mobile_no='$mobile_no'");
        }
      }
     public function check_total_count_notifications($staff_mobile_no,$user_type)
     {
        date_default_timezone_set('Asia/kolkata'); 
        $now = date('Y-m-d');
        $q=$this->db->query("SELECT order_id from tbl_notification_by_staff where staff_mobile_no='".$staff_mobile_no."' and count_status='1' and user_type='$user_type' and date_time LIKE '%$now%'");
        return($q->num_rows());
     }
   public function check_total_count_notifications2($staff_mobile_no,$user_type)
     {
        date_default_timezone_set('Asia/kolkata'); 
        $now = date('Y-m-d');
        $q=$this->db->query("SELECT order_id from tbl_notification_by_customer where mobile_no='".$staff_mobile_no."' and count_status='1' and user_type='$user_type' and date_time LIKE '%$now%'");
        return($q->num_rows());
     }
     function check_status_for_notifications($check_status,$staff_mobile_no,$user_type)
    {   
      $this->db->query("UPDATE tbl_notification_by_staff SET count_status='0' where staff_mobile_no='$staff_mobile_no'and user_type='$user_type'");
    }
   function check_status_for_notifications2($check_status,$staff_mobile_no,$user_type)
    {   

      $this->db->query("UPDATE tbl_notification_by_customer SET count_status='0' where mobile_no='$staff_mobile_no' and user_type='$user_type'");
    }

     function get_gst_amount()
     { 
        $q=$this->db->query("SELECT gst_amount from tbl_gst_amount_detail");
          return($q->row());
        $row = $q->num_rows();

     }
     
    public function getAmenitiesType()
     {
      $json_decode =json_decode(TABLES);
      $this->db->select('amenities_type');
      $this->db->from($json_decode->table5);
      $this->db->where('status',1);
      $query=$this->db->get();
      $result=$query->result_array();
      return $result;
     }
     public function getFoodType()
     {
      $json_decode =json_decode(TABLES);
      $this->db->select('food_type');
      $this->db->from($json_decode->table8);
      $this->db->where('status',1);
      $query=$this->db->get();
      $result=$query->result_array();
      return $result;
     }
 public function getFileName($admin_id)
     {
        $json_decode =json_decode(TABLES);
        $this->db->select('image');
        $this->db->from($json_decode->table3);
        $this->db->where('admin_id',$admin_id);
        $query=$this->db->get();
        $result=$query->result_array();
        return $result[0]['image'];
     }
public function getMenuImage($menu_id,$admin_id)
     {
        $json_decode =json_decode(TABLES);
        $this->db->select('menu_image');
        $this->db->from($json_decode->table24);
        $this->db->where('admin_id',$admin_id);
        $this->db->where('menu_id',$menu_id);
        $query=$this->db->get();
        $result=$query->result_array();
        return $result[0]['menu_image'];
     }
  public function getMax($order_id,$admin_id)
  {

    $json_decode =json_decode(TABLES);
    $this->db->select('*');
    $this->db->from($json_decode->table30);
    $this->db->where('order_id',$order_id);
    $this->db->where('admin_id',$admin_id);
    $query=$this->db->get();
    // print_r($this->db->last_query());exit;
    $result=$query->num_rows();
    // print_r($result);exit;
    return $result;
  }
  public function getSubOrder($order_id,$admin_id)
  {
    $json_decode =json_decode(TABLES);
    $this->db->select('*');
    $this->db->from($json_decode->table30);
    $this->db->where('order_id',$order_id);
    $this->db->where('admin_id',$admin_id);
    $query=$this->db->get();
    // print_r($this->db->last_query());exit;
    $result=$query->result_array();
    // print_r($result);exit;
    return $result;
  }
  public function getCustId($order_id,$admin_id,$customer_mobile_no)
{

    $this->db->select('cus_id');
    $this->db->from(json_decode(TABLES)->table17);
    $this->db->where('order_id',$order_id);
    $this->db->where('admin_id',$admin_id);
    $this->db->where('customer_mobile_no',$customer_mobile_no);
    $query=$this->db->get();
    // print_r($this->db->last_query());exit;
    $result=$query->result_array();
    // print_r($result);exit;
    return $result[0]['cus_id'];
}
  public function updateSubOrderByWaiter($order_array,$order_id,$admin_id,$sub_order_id)
  {
    $this->db->where('order_id',$order_id);
    $this->db->where('sub_order_id',$sub_order_id);
    $this->db->where('admin_id',$admin_id);
    $this->db->update(json_decode(TABLES)->table30,$order_array);
    $result=$this->db->affected_rows(); 
    return $result ;
  }
  public function subOrderCreateSlip($arr,$order_id,$admin_id,$sub_order_id)
  {
    $this->db->where('order_id',$order_id);
    $this->db->where('sub_order_id',$sub_order_id);
    $this->db->where('admin_id',$admin_id);
    $this->db->update(json_decode(TABLES)->table30,$arr);
    $result=$this->db->affected_rows(); 
    return $result ;
  }
  function getGroupDataFromDateWise($admin_id,$start_date,$end_date)
    {
      $json_decode =json_decode(TABLES);
      $this->db->select('*');
      $this->db->from($json_decode->table17);
      $this->db->where('admin_id',$admin_id);
      $this->db->where('date BETWEEN "'.$start_date.'" AND "'.$end_date.'"');
      $this->db->group_by('order_id');
      $this->db->order_by('create_date',DESC);
      $query=$this->db->get();
      //print_r($this->db->last_query());exit;
      $result=$query->result_array();
      return $result;
    }
 function getClosedOrder($admin_id)
    {
      $json_decode =json_decode(TABLES);
      $this->db->select('*');
      $this->db->from($json_decode->table17);
      $this->db->where('admin_id',$admin_id);
      $this->db->where('status','7');
      // $this->db->where('date BETWEEN "'.$start_date.'" AND "'.$end_date.'"');
      $this->db->group_by('order_id');
      $this->db->order_by('create_date',DESC);
      $query=$this->db->get();
      //print_r($this->db->last_query());exit;
      $result=$query->result_array();
      return $result;
    }
     function getClosedOrderForWaiter($admin_id,$mobile_no)
    {
      $json_decode =json_decode(TABLES);
      $this->db->select('*');
      $this->db->from($json_decode->table17);
      $this->db->where('admin_id',$admin_id);
      $this->db->where('confirm_order_by',$mobile_no);
      $this->db->where('status','7');
      $this->db->group_by('order_id');
      $this->db->order_by('create_date',DESC);
      $query=$this->db->get();
      //print_r($this->db->last_query());exit;
      $result=$query->result_array();
      return $result;
    }
    public function complete_sub_order_by_chef($array,$admin_id,$sub_order_id,$order_id)
    {
      $this->db->where('admin_id',$admin_id);
      $this->db->where('sub_order_id',$sub_order_id);
      $this->db->where('order_id',$order_id);
      $this->db->update(json_decode(TABLES)->table30,$array);
     // print_r($this->db->last_query());exit;
      $result=$this->db->affected_rows(); 
      return $result;
    }
    public function getOrderStatusByWaiter($order_id,$admin_id)
    {
      $this->db->select('status');
      $this->db->from(json_decode(TABLES)->table17);
      $this->db->where('order_id',$order_id);
      $this->db->where('admin_id',$admin_id);
      $query=$this->db->get();
      // print_r($this->db->last_query());exit;
      $result=$query->result_array();
      return $result[0]['status'];
    }
    public function deletedSubOrder($order_id,$admin_id)
    {
      $array=array(
                  'status'=>'0',
                  'order_status'=>'Rejected',
                  'modified_date'=>date('Y-m-d H:i:s')
                    );
      $this->db->where('admin_id',$admin_id);
      $this->db->where('order_id',$order_id);
      $this->db->update(json_decode(TABLES)->table30,$array);
      $result=$this->db->affected_rows(); 
      return $result;
    }
  public function getSubOrderDetails($order_id,$admin_id)
  {
    $this->db->select('*');
    $this->db->from(json_decode(TABLES)->table30);
    $this->db->where('order_id',$order_id);
    $this->db->where('admin_id',$admin_id);
    $this->db->where('order_status','Pending');
    $query=$this->db->get();
    $result=$query->result_array();
    return $result; 

  }
  public function confirmSubOrderBywaiter($order_id,$admin_id)
  {
    $array=array(
                  'status'=>'2',
                  'order_status'=>'Confirm',
                  'modified_date'=>date('Y-m-d H:i:s')
                    );
      $this->db->where('admin_id',$admin_id);
      $this->db->where('order_id',$order_id);
      $this->db->where('order_status','Pending');
      $this->db->update(json_decode(TABLES)->table30,$array);
      $result=$this->db->affected_rows(); 
      return $result;
  }
  public function getOrderByChef($order_id,$admin_id)
  {
    $this->db->select('*');
    $this->db->from(json_decode(TABLES)->table30);
    $this->db->where('order_id',$order_id);
    $this->db->where('admin_id',$admin_id);
    $this->db->where('order_status','Prepare');
    $query=$this->db->get();
    //print_r($this->db->last_query());exit;
    $result=$query->result_array();
    return $result;
  }
    public function getSubOrderChef($order_id,$admin_id,$order_status)
  {
    $this->db->select('*');
    $this->db->from(json_decode(TABLES)->table30);
    $this->db->where('order_id',$order_id);
    $this->db->where('admin_id',$admin_id);
    $this->db->where('order_status',$order_status);
    $query=$this->db->get();
    $result=$query->result_array();
    return $result;
  }
  public function readyToserveOrder($order_id,$admin_id)
  {
     $array=array(
                  'status'=>'5',
                  'order_status'=>'Ready to Serve',
                  'modified_date'=>date('Y-m-d H:i:s')
                    );
      $this->db->where('admin_id',$admin_id);
      $this->db->where('order_id',$order_id);
      $this->db->where('order_status','Prepare');
      $this->db->update(json_decode(TABLES)->table30,$array);
      $result=$this->db->affected_rows(); 
      return $result;
  }
  public function  prepareAllOrder($order_id,$admin_id,$order_status)
  {
     $array=array(
                  'status'=>'3',
                  'order_status'=>'Prepare',
                  'slip_status'=>'1',
                  'modified_date'=>date('Y-m-d H:i:s')
                    );
      $this->db->where('admin_id',$admin_id);
      $this->db->where('order_id',$order_id);
      $this->db->where('order_status',$order_status);
      $this->db->update(json_decode(TABLES)->table30,$array);
      $result=$this->db->affected_rows(); 
      return $result;
  }
public function completAllSubOrder($order_id,$admin_id,$order_status,$waiter_mobile_no)
{
      $array=array(
                  'status'=>'4',
                  'order_status'=>'Complete',
                  'order_complete_by'=>$waiter_mobile_no,
                  'modified_date'=>date('Y-m-d H:i:s')
                    );
      $this->db->where('admin_id',$admin_id);
      $this->db->where('order_id',$order_id);
      $this->db->where('order_status !=',$order_status);
      $this->db->update(json_decode(TABLES)->table30,$array);

      $result=$this->db->affected_rows(); 
      return $result;
}
public function completAllOrder($order_id,$admin_id,$order_status,$waiter_mobile_no)
{
      $array=array(
                  'status'=>'4',
                  'order_status'=>'Complete',
                  'order_complete_by'=>$waiter_mobile_no,
                  'modified_date'=>date('Y-m-d H:i:s')
                    );
      $this->db->where('admin_id',$admin_id);
      $this->db->where('order_id',$order_id);
      $this->db->where('order_status !=',$order_status);
      $this->db->update(json_decode(TABLES)->table17,$array);
      $result=$this->db->affected_rows(); 
      return $result;
}
public function getCompletOrder($order_id,$admin_id)
{
    $this->db->select('*');
    $this->db->from(json_decode(TABLES)->table30);
    $this->db->where('order_id',$order_id);
    $this->db->where('admin_id',$admin_id);
    $this->db->where('status IN (1,2,3)');
    $query=$this->db->get();
    $result=$query->result_array();
    return $result;
}
public function getOrderForCashier($order_id,$admin_id)
{
      $json_decode=json_decode(TABLES);
      $this->db->select('*');
      $this->db->from($json_decode->table17);
      $this->db->where('order_id',$order_id);
      $this->db->where('admin_id',$admin_id);
      $this->db->where('status','6');
      $query=$this->db->get();
      // print_r($this->db->last_query());exit;
      $result=$query->result_array();
      return $result;
}
public function getSubOrderForCashier($order_id)
{
      $json_decode=json_decode(TABLES);
      $this->db->select('*');
      $this->db->from($json_decode->table30);
      $this->db->where('order_id',$order_id);
      $this->db->where('status','6');
      $query=$this->db->get();
      // print_r($this->db->last_query());exit;
      $result=$query->result_array();
      return $result;
}
public function invoiceCreatedForOrders($order_id,$admin_id,$mobile_no)
{
  $array=array(
    'status'=>'6',
    'order_status'=>'INV CREATED',
    'modified_date'=>date('Y-m-d H:i:s'),
    'inv_created_by'=>$mobile_no
  );
  $this->db->where('order_id',$order_id);
  $this->db->where('admin_id',$admin_id);
  $this->db->update(json_decode(TABLES)->table17,$array);
  $result=$this->db->affected_rows(); 
  return $result;

}
public function invoiceCreatedForSubOrders($order_id,$admin_id,$mobile_no)
{
  $array=array('status'=>'6','order_status'=>'INV CREATED','modified_date'=>date('Y-m-d H:i:s'),'inv_created_by'=>$mobile_no);
  $this->db->where('order_id',$order_id);
  $this->db->where('admin_id',$admin_id);
  $this->db->where('status !=','0');
  $this->db->update(json_decode(TABLES)->table30,$array);
  $result=$this->db->affected_rows(); 
  return $result;

}
public function insertBatchOrder($insert_array)
{
  $this->db->insert_batch(json_decode(TABLES)->table31,$insert_array);
  $this->db->insert_id();
}
public function insertBatchSubOrder($insert_array)
{
  $this->db->insert_batch(json_decode(TABLES)->table32,$insert_array);
  $this->db->insert_id();
}
public function getMenuItemForOrder($order_id,$admin_id)
{

      $json_decode =json_decode(TABLES);
      $this->db->select('*');
      $this->db->from($json_decode->table31);
      $this->db->where('admin_id',$admin_id);
      $this->db->where('order_id',$order_id);
      // $this->db->where('status','1');
      $query=$this->db->get();
      // print_r($this->db->last_query());exit;
      $result=$query->result_array();
      return $result;
}
public function getMenuItemForSubOrder($order_id,$admin_id,$sub_order_id)
{

      $json_decode =json_decode(TABLES);
      $this->db->select('*');
      $this->db->from($json_decode->table32);
      $this->db->where('admin_id',$admin_id);
      $this->db->where('order_id',$order_id);
      $this->db->where('sub_order_id',$sub_order_id);
      // $this->db->where('status','1');
      $query=$this->db->get();
      //print_r($this->db->last_query());exit;
      $result=$query->result_array();
      return $result;
}
public function getMenuItemForOrderInvoice($order_id,$admin_id)
{

      $json_decode =json_decode(TABLES);
      $this->db->select('*');
      $this->db->from($json_decode->table31);
      $this->db->where('admin_id',$admin_id);
      $this->db->where('order_id',$order_id);
      $this->db->where('status','1');
      $query=$this->db->get();
      //print_r($this->db->last_query());exit;
      $result=$query->result_array();
      return $result;
}
public function getMenuItemForSubOrderInvoice($order_id,$admin_id,$sub_order_id)
{

      $json_decode =json_decode(TABLES);
      $this->db->select('*');
      $this->db->from($json_decode->table32);
      $this->db->where('admin_id',$admin_id);
      $this->db->where('order_id',$order_id);
      $this->db->where('sub_order_id',$sub_order_id);
      $this->db->where('status','1');
      $query=$this->db->get();
      //print_r($this->db->last_query());exit;
      $result=$query->result_array();
      return $result;
}
public function deletedSubOrderWithMenu($order_id,$admin_id)
{
  $array=array('status'=>'0');
  $this->db->where('order_id',$order_id);
  $this->db->where('admin_id',$admin_id);
  $this->db->update(json_decode(TABLES)->table32,$array);
  $result=$this->db->affected_rows(); 
  return $result;
}
public function deletedOrderWithMenu($order_id,$admin_id)
{
  $array=array('status'=>'0','creation_date'=>date('Y-m-d H:i:s'));
  $this->db->where('order_id',$order_id);
  $this->db->where('admin_id',$admin_id);
  $this->db->update(json_decode(TABLES)->table31,$array);
  $result=$this->db->affected_rows(); 
  return $result;
}
public function deletedSubOrderWithMenu2($order_id,$admin_id,$sub_order_id)
{
  $array=array('status'=>'0','creation_date'=>date('Y-m-d H:i:s'));
  $this->db->where('order_id',$order_id);
  $this->db->where('admin_id',$admin_id);
  $this->db->where('sub_order_id',$sub_order_id);
  $this->db->update(json_decode(TABLES)->table32,$array);
  $result=$this->db->affected_rows(); 
  return $result;
}
public function cancelOrderItem($order_id,$admin_id,$item_name,$id)
{
  $array=array('status'=>'0','creation_date'=>date('Y-m-d H:i:s'));
  $this->db->where('order_id',$order_id);
  $this->db->where('admin_id',$admin_id);
  $this->db->where('id',$id);
  $this->db->update(json_decode(TABLES)->table31,$array);
  $result=$this->db->affected_rows(); 
  return $result;
}
public function cancelSubOrderItem($order_id,$admin_id,$item_name,$sub_order_id,$id)
{
  $array=array('status'=>'0','creation_date'=>date('Y-m-d H:i:s'));
  $this->db->where('order_id',$order_id);
  $this->db->where('sub_order_id',$sub_order_id);
  $this->db->where('admin_id',$admin_id);
  $this->db->where('id',$id);
  $this->db->update(json_decode(TABLES)->table32,$array);
  $result=$this->db->affected_rows(); 
  return $result;
}
public function getOrderPrice($order_id,$admin_id)
{
      $this->db->select('total_item,total_price,net_pay_amount,gst_amount,gst_amount_price');
      $this->db->from(json_decode(TABLES)->table17);
      $this->db->where('admin_id',$admin_id);
      $this->db->where('order_id',$order_id);
      $query=$this->db->get();
      //print_r($this->db->last_query());exit;
      $result=$query->result_array();
      return $result;
}
public function getOrderItemPrice($order_id,$admin_id,$id)
{
      $this->db->select('menu_price,quantity');
      $this->db->from(json_decode(TABLES)->table31);
      $this->db->where('admin_id',$admin_id);
      $this->db->where('order_id',$order_id);
      $this->db->where('id',$id);
      $this->db->where('status','0');
      $query=$this->db->get();
      //print_r($this->db->last_query());exit;
      $result=$query->result_array();
      return $result;
}
public function getItemMenuOrderDetails($order_id,$admin_id)
{
      $this->db->select('*');
      $this->db->from(json_decode(TABLES)->table31);
      $this->db->where('admin_id',$admin_id);
      $this->db->where('order_id',$order_id);
      $this->db->where('status','1');
      $query=$this->db->get();
      //print_r($this->db->last_query());exit;
      $result=$query->result_array();
      return $result;
}
public function updateMenuListDetails($updateArray,$order_id,$admin_id)
{
  $this->db->where('order_id',$order_id);
  $this->db->where('admin_id',$admin_id);
  $this->db->update(json_decode(TABLES)->table17,$updateArray);
  $result=$this->db->affected_rows(); 
  return $result;
}
public function getItemMenuSubOrderDetails($order_id,$admin_id,$sub_order_id)
{
      $this->db->select('*');
      $this->db->from(json_decode(TABLES)->table30);
      $this->db->where('admin_id',$admin_id);
      $this->db->where('order_id',$order_id);
      $this->db->where('sub_order_id',$sub_order_id);
      $this->db->where('status','3');
      $query=$this->db->get();
      // print_r($this->db->last_query());exit;
      $result=$query->result_array();
      return $result;
}
public function getItemMenuSubOrderDetails2($order_id,$admin_id,$sub_order_id)
{
      $this->db->select('*');
      $this->db->from(json_decode(TABLES)->table30);
      $this->db->where('admin_id',$admin_id);
      $this->db->where('order_id',$order_id);
      $this->db->where('sub_order_id',$sub_order_id);
      $this->db->where('status','5');
      $query=$this->db->get();
      // print_r($this->db->last_query());exit;
      $result=$query->result_array();
      return $result;
}
public function getSubOrderPrice($order_id,$admin_id,$sub_order_id)
{
      $this->db->select('total_item,total_price,net_pay_amount,gst_amount,gst_amount_price');
      $this->db->from(json_decode(TABLES)->table30);
      $this->db->where('admin_id',$admin_id);
      $this->db->where('order_id',$order_id);
      $this->db->where('sub_order_id',$sub_order_id);
      $query=$this->db->get();
      //print_r($this->db->last_query());exit;
      $result=$query->result_array();
      return $result;
}
public function getSubOrderItemPrice($order_id,$admin_id,$id,$sub_order_id)
{
      $this->db->select('menu_price,quantity');
      $this->db->from(json_decode(TABLES)->table32);
      $this->db->where('admin_id',$admin_id);
      $this->db->where('order_id',$order_id);
      $this->db->where('sub_order_id',$sub_order_id);
      $this->db->where('id',$id);
      $this->db->where('status','0');
      $query=$this->db->get();
      //print_r($this->db->last_query());exit;
      $result=$query->result_array();
      return $result;
}
public function updateMenuSubListDetails($updateArray,$order_id,$admin_id,$sub_order_id)
{
  $this->db->where('order_id',$order_id);
  $this->db->where('admin_id',$admin_id);
  $this->db->where('sub_order_id',$sub_order_id);
  $this->db->update(json_decode(TABLES)->table30,$updateArray);
  $result=$this->db->affected_rows(); 
  return $result;
}
public function getSubOrderTotalItemPrice($order_id,$admin_id,$sub_order_id)
{
      $this->db->select('SUM(menu_price) AS menu_price,SUM(quantity) AS quantity');
      $this->db->from(json_decode(TABLES)->table32);
      $this->db->where('admin_id',$admin_id);
      $this->db->where('order_id',$order_id);
      $this->db->where('status','0');
      $this->db->where('sub_order_id',$sub_order_id);
      $query=$this->db->get();
      //print_r($this->db->last_query());exit;
      $result=$query->result_array();
      return $result;
}
public function updateSubOrderAmount($subOrserArray,$order_id,$admin_id,$sub_order_id)
{
  $this->db->where('order_id',$order_id);
  $this->db->where('admin_id',$admin_id);
  $this->db->where('sub_order_id',$sub_order_id);
  $this->db->update(json_decode(TABLES)->table30,$subOrserArray);
  $result=$this->db->affected_rows(); 
  return $result;
}
public function getEmpRole($mobile_no,$admin_id)
{
      $this->db->select('desingination');
      $this->db->from(json_decode(TABLES)->table25);
      $this->db->where('mobile_no',$mobile_no);
      $this->db->where('admin_id',$admin_id);
      $this->db->where('status','1');
      $query=$this->db->get();
      // print_r($this->db->last_query());exit;
      $result=$query->result_array();
      return $result[0]['desingination'];
}
function getGroupData2($admin_id,$order_status,$table_no)
{

  $json_decode =json_decode(TABLES);
  $this->db->select('*');
  $this->db->from($json_decode->table17);
  $this->db->where('admin_id',$admin_id);
  $this->db->where('order_status','Pending');
  if(!empty($table_no))
  {
     $this->db->where('table_no IN ('.$table_no.')');
  }else
  { 
     $this->db->where('table_no IN (99999999999)');
  }

  $this->db->order_by('create_date',DESC);
  $query=$this->db->get();
  // print_r($this->db->last_query());exit;
  $result=$query->result_array();
  return $result;
 
}
public function insertTableWaiterMapping($admin_id,$table_no,$mobile_no)
{
  $date=date('Y-m-d');
  $data=array('admin_id'=>$admin_id,'table_no'=>$table_no,'mobile_no'=>$mobile_no,'status'=>'1','creation_date'=>$date);
  $insert = $this->db->insert('table_waiter_mapping', $data);
  return $insert?$this->db->insert_id():false;

}
public function getWaiterMappedData($admin_id,$mobile_no)
{
  $this->db->select('*');
  $this->db->from('table_waiter_mapping');
  $this->db->where('admin_id',$admin_id);
  $this->db->where('mobile_no',$mobile_no);
  $this->db->where('CURDATE()=STR_TO_DATE(creation_date,"%Y-%m-%d")');
  $query=$this->db->get();
  // print_r($this->db->last_query());exit;
  $result=$query->result_array();
  return $result;
}
public function getWaiterResult($admin_id,$table_no,$mobile_no)
{
  $this->db->select('*');
  $this->db->from('table_waiter_mapping');
  $this->db->where('admin_id',$admin_id);
  $this->db->where('mobile_no',$mobile_no);
  $this->db->where('table_no',$table_no);
  $this->db->where('CURDATE()=STR_TO_DATE(creation_date,"%Y-%m-%d")');
  $query=$this->db->get();
  // print_r($this->db->last_query());exit;
  $result=$query->result_array();
  return $result;
}
public function getWaiterResultBytable($admin_id,$table_no)
{
  $this->db->select('*');
  $this->db->from('table_waiter_mapping');
  $this->db->where('admin_id',$admin_id);
  $this->db->where('table_no',$table_no);
  $this->db->where('CURDATE()=STR_TO_DATE(creation_date,"%Y-%m-%d")');
  $query=$this->db->get();
  // print_r($this->db->last_query());exit;
  $result=$query->result_array();
  return $result;
}
public function getGroupData3($admin_id,$order_status,$mobile_no)
{
  $json_decode =json_decode(TABLES);
  $this->db->select('*');
  $this->db->from($json_decode->table17);
  $this->db->where('admin_id',$admin_id);
  $this->db->where('confirm_order_by',$mobile_no);
  $this->db->where("(status !=7 OR order_delete_by='$mobile_no')");
  //$this->db->or_where('order_delete_by',$mobile_no);
  $this->db->order_by('create_date',DESC);
  $query=$this->db->get();
  // print_r($this->db->last_query());exit;
  $result=$query->result_array();
  return $result;
}
public function getpendingOrders($admin_id,$table_no)
{
  $json_decode =json_decode(TABLES);
  $this->db->select('*');
  $this->db->from($json_decode->table17);
  $this->db->where('admin_id',$admin_id);
  $this->db->where('table_no',$table_no);
  $this->db->where('status NOT IN(7,0)');
  $query=$this->db->get();
  // print_r($this->db->last_query());exit;
  $result=$query->result_array();
  return $result;
}
public function offerDiscount($order_id,$admin_id,$array)
{
  $this->db->where('order_id',$order_id);
  $this->db->where('admin_id',$admin_id);
  $this->db->update(json_decode(TABLES)->table17,$array);
  $result=$this->db->affected_rows(); 
  return $result;
}
public function checkSubOrderItem($order_id,$admin_id,$sub_order_id)
{

  $json_decode =json_decode(TABLES);
  $this->db->select('*');
  $this->db->from($json_decode->table32);
  $this->db->where('admin_id',$admin_id);
  $this->db->where('order_id',$order_id);
  $this->db->where('sub_order_id',$sub_order_id);
  $this->db->where('status','1');
  $query=$this->db->get();
  // print_r($this->db->last_query());exit;
  $result=$query->result_array();
  return $result;
}
public function RejectSubOrder($order_id,$admin_id,$sub_order_id,$mobile_no,$id)
{
  $array=array('order_status'=>'Rejected','order_delete_by'=>$mobile_no,'modified_date'=>date('Y-m-d'),'status'=>'0');
  $this->db->where('order_id',$order_id);
  $this->db->where('sub_order_id',$sub_order_id);
  $this->db->where('admin_id',$admin_id);
  //$this->db->where('id',$id);
  $this->db->update(json_decode(TABLES)->table30,$array);
  $result=$this->db->affected_rows(); 
  return $result;
}
public function checkLogedinUser($mobile_no,$admin_id)
{
  $json_decode =json_decode(TABLES);
  $this->db->select('*');
  $this->db->from($json_decode->table13);
  // $this->db->where('admin_id',$admin_id);
  $this->db->where('mobile_no',$mobile_no);
  $this->db->where('active_status','1');
  $query=$this->db->get();
  // print_r($this->db->last_query());exit;
  $result=$query->result_array();
  return $result;
}
public function getGst($id,$admin_id)
{
  $this->db->select('gst');
  $this->db->from('master_item_category');
  $this->db->where('id',$id);
  $this->db->where('admin_id',$admin_id);
  $this->db->where('status','1');
  $query=$this->db->get();
  $result=$query->result_array();
  return $result[0]['gst'];
}
public function getMasterCategory($admin_id)
{
  $this->db->select('*');
  $this->db->from('master_item_category');
  $this->db->where('admin_id',$admin_id);
  $this->db->where('status','1');
  $query=$this->db->get();
  $result=$query->result_array();
  return $result;
}
public function getGstInforForOrder($order_id,$admin_id)
{
     $this->db->select('sum(menu_price) AS menu_price,gst,round((menu_price*gst/100),2) AS gst_amount');
     $this->db->from(json_decode(TABLES)->table31);
     $this->db->where('order_id',$order_id);
     $this->db->where('admin_id',$admin_id);
     $this->db->where('status',1);
     $this->db->group_by('gst');
     $query=$this->db->get();
     //print_r($this->db->last_query());exit;
     $result=$query->result_array();
     return $result;
}
  public function getGstInforForSubOrder($order_id,$admin_id)
  {
       $this->db->select('sum(menu_price) AS menu_price,gst,round((menu_price*gst/100),2) AS gst_amount');
       $this->db->from(json_decode(TABLES)->table32);
       $this->db->where('order_id',$order_id);
       $this->db->where('admin_id',$admin_id);
       $this->db->where('status',1);
       $this->db->group_by('gst');
       $query=$this->db->get();
       //print_r($this->db->last_query());exit;
       $result=$query->result_array();
       return $result;
  }
    public function getMasterGst($admin_id)
  {
       $this->db->select('gst');
       $this->db->from('master_item_category');
       $this->db->where('status',1);
       $this->db->where('admin_id',$admin_id);
       $query=$this->db->get();
       //print_r($this->db->last_query());exit;
       $result=$query->result_array();
       return $result;
  }
  public function getRoleInfo($admin_id,$desingination)
  {
       $this->db->select('*');
       $this->db->from(json_decode(TABLES)->table25);
       $this->db->where('user_type',$desingination);
       $this->db->where('admin_id',$admin_id);
       $query=$this->db->get();
       //print_r($this->db->last_query());exit;
       $result=$query->result_array();
       return $result;
  }
  public function addItemCategory($array)
  {
    $insert=$this->db->insert('master_item_category',$array);
    return $insert?$this->db->insert_id():false;
  }
  public function updateStatus($admin_id,$id,$status)
  {
  $array=array('status'=>$status);
  $this->db->where('id',$id);
  $this->db->where('admin_id',$admin_id);
  $this->db->update('master_item_category',$array);
  $result=$this->db->affected_rows(); 
  return $result;
  }
  public function insertGstDetails($array)
  {
   $insert=$this->db->insert_batch('master_item_category',$array);
    return $insert?$this->db->insert_id():false; 
  }
  public function checkLoginStatus($mobile_no,$device_id)
  {
       $this->db->select('*');
       $this->db->from('tbl_manage_login_user');
       $this->db->where('mobile_no',$mobile_no);
       if(!empty($device_id)){
       $this->db->where('device_id',$device_id);
       }
       $this->db->where('active_status',1);
       $query=$this->db->get();
       //print_r($this->db->last_query());exit;
       $result=$query->result_array();
       return $result;
  }
    public function checkLoginStatusForStoff($mobile_no,$admin_id)
  {
       $this->db->select('*');
       $this->db->from('tbl_restaurant_staff_registration');
       $this->db->where('mobile_no',$mobile_no);
       $this->db->where('admin_id',$admin_id);
       $query=$this->db->get();
       //print_r($this->db->last_query());exit;
       $result=$query->result_array();
       return $result;
  }
    public function checkLoginStatusForEmp($mobile_no,$admin_id)
  {
       $this->db->select('*');
       $this->db->from('tbl_admin');
       $this->db->where('mobile_no',$mobile_no);
       $this->db->where('admin_id',$admin_id);
       $this->db->where('user_active',1);
       $query=$this->db->get();
       //print_r($this->db->last_query());exit;
       $result=$query->result_array();
       return $result;
  }
  public function getRestaurant($admin_id)
  {
      $this->db->select('*');
       $this->db->from('spots');
       $this->db->where('admin_id',$admin_id);
       $this->db->where('status',1);
       $query=$this->db->get();
       // print_r($this->db->last_query());exit;
       $result=$query->result_array();
       return $result;
  }
  public function getMaxCatId()
  {
        $this->db->SELECT('MAX(CAST(`cat_id` AS UNSIGNED)+1) AS `cat_id`');
        $this->db->FROM('misc_category');
        $query=$this->db->get();
        // print_r($this->db->last_query());exit;
        $result=$query->result_array();
        return $result[0]['cat_id'];
  }
  public function getMaxSubCatId()
  {
        $this->db->SELECT('MAX(CAST(`sub_cat_id` AS UNSIGNED)+1) AS `sub_cat_id`');
        $this->db->FROM('misc_sub_category');
	     $this->db->where('sub_cat_id !=','NA');
        $query=$this->db->get();
        // print_r($this->db->last_query());exit;
        $result=$query->result_array();
        return $result[0]['sub_cat_id'];
  }
  public function addCategory($array)
  {
    
    $insert=$this->db->insert('misc_category',$array);
    //print_r($this->db->last_query());exit;
    return $insert?$this->db->insert_id():false;
  }
  public function addSubCategory($array)
  {
  
    $insert=$this->db->insert('misc_sub_category',$array);
    return $insert?$this->db->insert_id():false;
  }
  public function getRestaurantCategory($admin_id,$rtrim)
  {
       $this->db->select('*');
       $this->db->from('misc_category');
       if(!empty($rtrim))
       {
        $this->db->where('cat_id IN ('.$rtrim.')');
       }
       $this->db->where('admin_id',$admin_id);
       $this->db->where('status',1);
       $query=$this->db->get();
       //print_r($this->db->last_query());exit;
       $result=$query->result_array();
       return $result;
  }
    
    function getCatIds($admin_id)
  {
    $this->db->select('DISTINCT(cat_id)');
    $this->db->from('tbl_restaurant_menu_item_list');
    $this->db->where('admin_id',$admin_id);
    $this->db->where('status',1);
    $query=$this->db->get();
    // print_r($this->db->last_query());
    $result=$query->result_array();
    return $result;
  }
  public function getRestaurantSubCategory($admin_id,$cat_id)
  {
       $this->db->select('*');
       $this->db->from('misc_sub_category');
       $this->db->where('admin_id',$admin_id);
       $this->db->where('cat_id',$cat_id);
       $this->db->where('status',1);
       $query=$this->db->get();
       //print_r($this->db->last_query());exit;
       $result=$query->result_array();
       return $result;
  }
  public function getCatName($cat_id,$admin_id)
  {
       $this->db->select('cat_name');
       $this->db->from('misc_category');
       $this->db->where('admin_id',$admin_id);
       $this->db->where('cat_id',$cat_id);
       $this->db->where('status',1);
       $query=$this->db->get();
       //print_r($this->db->last_query());exit;
       $result=$query->result_array();
       return $result[0]['cat_name'];
  }
    public function getSubCatName($sub_cat_id,$admin_id)
  {
       $this->db->select('sub_cat_name');
       $this->db->from('misc_sub_category');
       $this->db->where('admin_id',$admin_id);
       $this->db->where('sub_cat_id',$sub_cat_id);
       $this->db->where('status',1);
       $query=$this->db->get();
       //print_r($this->db->last_query());exit;
       $result=$query->result_array();
       return $result[0]['sub_cat_name'];
  }
         public function getSubcatId($admin_id,$cat_id)
      {
        $this->db->select('sub_cat_id,sub_cat_name');
        $this->db->from('misc_sub_category');
        $this->db->where('cat_id',$cat_id);
        $this->db->where('admin_id',$admin_id);
        $query=$this->db->get();
        // print_r($this->db->last_query());
        return $query->result_array();
      }
      public function getSubCatMenuItems($sub_cat_id,$cat_id,$admin_id)
      {
        $this->db->select('*');
        $this->db->from('tbl_restaurant_menu_item_list');
        $this->db->where('cat_id',$cat_id);
        $this->db->where('admin_id',$admin_id);
        $this->db->where('sub_cat_id',$sub_cat_id);
        $this->db->where('status',1);
        $query=$this->db->get();
        // print_r($this->db->last_query());
        return $query->result_array();
      }
  public function getNaSubCatMenuItems($cat_id,$admin_id)
  {
        $this->db->select('*');
        $this->db->from('tbl_restaurant_menu_item_list');
        $this->db->where('cat_id',$cat_id);
        $this->db->where('admin_id',$admin_id);
        $this->db->where('sub_cat_id','NA');
        $this->db->where('status',1);
        $query=$this->db->get();
        // print_r($this->db->last_query());
        return $query->result_array();
   }
   function getPrvOrderDate($admin_id){
    $this->db->select('date');
    $this->db->from('tbl_order_detail_for_restaurant');
    $this->db->where('admin_id',$admin_id);
    $this->db->order_by('date',DESC);
    $this->db->LIMIT(1);
    $query=$this->db->get();
    // print_r($this->db->last_query());exit;
    $result=$query->result_array();
    return $result;
   }
  public function getMaxOrderId($admin_id,$date)
  {
    date_default_timezone_set('Asia/kolkata'); 
    $order_id=$admin_id.'-'.substr(str_replace('-', '',$date),2);
    // echo $order_id;exit;
    $this->db->select("MAX(CAST(REPLACE(order_id,'$order_id','') AS UNSIGNED)+1) AS `order_id`");
    $this->db->from('tbl_order_detail_for_restaurant');
    $this->db->where('admin_id',$admin_id);
    $this->db->where('date',date('Y-m-d'));
    $query=$this->db->get();
    // print_r($this->db->last_query());exit;
    $result=$query->result_array();
    return $result[0]['order_id'];
  }
  public function getCustomerData($waiter_mobile_no,$admin_id,$order_id)
  {
    $this->db->select('lc.notification_id');
    $this->db->from('tbl_order_detail_for_restaurant AS odr');
    $this->db->JOIN('tbl_login_customer AS lc','lc.mobile_no=odr.customer_mobile_no','INNER');
    $this->db->where('odr.order_id',$order_id);
    $this->db->where('odr.admin_id',$admin_id);
    $this->db->where('odr.confirm_order_by',$waiter_mobile_no);
    $this->db->where('lc.active_status',1);
    $query=$this->db->get();
    $result=$query->result_array();
    return $result[0]['notification_id'];
  }
  public function getStaffNotification($admin_id,$desingination)
  {
    $this->db->distinct();
    $this->db->select('mlu.notification_id,mlu.mobile_no,rsr.user_type,rsr.name');
    $this->db->from('tbl_restaurant_staff_registration AS rsr');
    $this->db->JOIN('tbl_manage_login_user AS mlu','mlu.mobile_no=rsr.mobile_no','INNER');
    $this->db->where('rsr.admin_id',$admin_id);
    $this->db->where('rsr.user_type',$desingination);
    $this->db->where('mlu.active_status',1);
    $this->db->group_by('mlu.user_type');
    $query=$this->db->get();
    // print_r($this->db->last_query());exit;
    $result=$query->result_array();
    return $result;
  }
   public function insertNotification($array)
  {
    $this->db->insert('tbl_notification_by_staff',$array);
    return $insert?$this->db->insert_id():false;
  }
  public function getWaiterMobileNo($order_id,$admin_id)
  {
        $this->db->select('confirm_order_by');
        $this->db->from('tbl_order_detail_for_restaurant');
        $this->db->where('order_id',$order_id);
        $this->db->where('admin_id',$admin_id);
        // $this->db->where('status',1);
        $query=$this->db->get();
        // print_r($this->db->last_query());exit;
        $result=$query->result_array();
        return $result[0]['confirm_order_by'];
  }
public function getWaiterNotification($waiter_mobile_no)
{
    $this->db->select('notification_id,user_type,mobile_no');
    $this->db->from('tbl_manage_login_user');
    $this->db->where('mobile_no',$waiter_mobile_no);
    $this->db->where('active_status',1);
    $query=$this->db->get();
    //print_r($this->db->last_query());exit;
    $result=$query->result_array();
    return $result;
}
public function getCusmerData($order_id,$admin_id)
{
  $this->db->select('lc.notification_id');
  $this->db->from('tbl_order_detail_for_restaurant AS odr');
  $this->db->JOIN('tbl_registration_customer as rc','rc.mobile_no=odr.customer_mobile_no','INNER');
  $this->db->JOIN('tbl_login_customer as lc','lc.cus_id=rc.cus_id','INNER');
  $this->db->where('odr.admin_id',$admin_id);
  $this->db->where('odr.order_id',$order_id);
  $this->db->where('lc.active_status',1);
  $query=$this->db->get();
  $result=$query->result_array();
  return $result[0]['notification_id'];
}
public function checkOrderStatus($order_id,$admin_id)
{
        $this->db->select('*');
        $this->db->from('tbl_order_detail_for_restaurant');
        $this->db->where('order_id',$order_id);
        $this->db->where('admin_id',$admin_id);
        $this->db->where('status',1);
        $query=$this->db->get();
        // print_r($this->db->last_query());exit;
        $result=$query->result_array();
        return $result;
}
public function getCustmoerData($order_id,$admin_id)
{
        $this->db->select('*');
        $this->db->from('tbl_order_detail_for_restaurant');
        $this->db->where('order_id',$order_id);
        $this->db->where('admin_id',$admin_id);
        // $this->db->where('status',1);
        $query=$this->db->get();
        // print_r($this->db->last_query());
        $result=$query->result_array();
        return $result;
}
public function getOrderDate($order_id)
{
        $this->db->select('date');
        $this->db->from('tbl_order_detail_for_restaurant');
        $this->db->where('order_id',$order_id);
        $query=$this->db->get();
        // print_r($this->db->last_query());
        $result=$query->result_array();
        return $result[0]['date'];
}
public function getInvoiceData($admin_id,$order_id)
{
        $this->db->select('*');
        $this->db->from('order_invoice');
        $this->db->where('order_id',$order_id);
        $this->db->where('admin_id',$admin_id);
        $query=$this->db->get();
        // print_r($this->db->last_query());
        $result=$query->result_array();
        return $result;
}
public function getMaxInvNumber()
{
        $this->db->select("MAX(CAST(REPLACE(inv_no,'inv','') AS UNSIGNED)) AS inv");
        $this->db->from('order_invoice');
        $query=$this->db->get();
        // print_r($this->db->last_query());exit;
        $result=$query->result_array();
        return $result[0]['inv'];
}
public function insertInvoiceDetails($inv_array)
{
  $insert=$this->db->insert('order_invoice',$inv_array);
  // print_r($this->db->last_query());exit;
   return $insert?$this->db->insert_id():false;

}

function getOrderItems($order_id,$admin_id){
  $this->db->select('odr.order_id,mi.menu_item_name,mi.menu_id,mi.quantity,mi.half_and_full_status,mi.menu_price');
  $this->db->from('tbl_order_detail_for_restaurant AS odr');
  $this->db->join('master_item AS mi','mi.order_id=odr.order_id');
  $this->db->where('odr.order_id',$order_id);
  $this->db->where('odr.admin_id',$admin_id);
  $query=$this->db->get();
  // print_r($this->db->last_query());exit;
  $result=$query->result_array();
  return $result;
}
function getSubOrderItems($order_id,$admin_id){
  $this->db->select('sodr.order_id,smi.menu_item_name,smi.menu_id,smi.quantity,smi.half_and_full_status,smi.menu_price');
  $this->db->from('tbl_sub_order_detail_for_restaurant AS sodr');
  $this->db->join('sub_master_item AS smi','smi.order_id=sodr.order_id');
  $this->db->where('smi.sub_order_id=sodr.sub_order_id');
  $this->db->where('sodr.order_id',$order_id);
  $this->db->where('sodr.admin_id',$admin_id);
  $query=$this->db->get();
  // print_r($this->db->last_query());exit;
  $result=$query->result_array();
  return $result;
}
function getRestaurantTypes(){
  $this->db->select('*');
  $this->db->from('master_restaurant_types');
  $this->db->where('status',1);
  $query=$this->db->get();
  // print_r($this->db->last_query());exit;
  $result=$query->result_array();
  return $result;
}
function getStaffData($user_type,$admin_id){
  $this->db->select('*');
  $this->db->from('tbl_restaurant_staff_registration');
  $this->db->where('status',1);
  $this->db->where('admin_id',$admin_id);
  $this->db->where('user_type',$user_type);
  $query=$this->db->get();
  // print_r($this->db->last_query());exit;
  $result=$query->result_array();
  return $result;
}
function getwaiterData($waiter_mobile_no,$user_type){
  $this->db->select('*');
  $this->db->from('tbl_restaurant_staff_registration');
  $this->db->where('status',1);
  $this->db->where('mobile_no',$waiter_mobile_no);
  if(!empty($user_type)){
    $this->db->where('user_type',$user_type);
  }
  $query=$this->db->get();
  // print_r($this->db->last_query());exit; 
  $result=$query->result_array();
  return $result;
}
function getCustData($mobile_no){
  $this->db->select('notification_id,mobile_no,user_type');
  $this->db->from('tbl_login_customer');
  $this->db->where('status',1);
  $this->db->where('mobile_no',$mobile_no);
  $query=$this->db->get();
  //print_r($this->db->last_query());exit;
  $result=$query->result_array();
  return $result;
}
function getAdminData($admin_id){

  $this->db->distinct();
  $this->db->select('mlu.notification_id,mlu.mobile_no,a.user_type');
  $this->db->from('tbl_manage_login_user AS mlu');
  $this->db->JOIN('tbl_admin AS a','a.mobile_no=mlu.mobile_no','INNER');
  $this->db->where('a.admin_id',$admin_id);
  $this->db->where('mlu.status',1);
  $query=$this->db->get();
  // print_r($this->db->last_query());exit;
  $result=$query->result_array();
  return $result;
}
public function getRestaurantStaffNotification($admin_id,$mobile_no)
  {
    $this->db->distinct();
    $this->db->select('mlu.notification_id,rsr.mobile_no,rsr.user_type,rsr.name');
    $this->db->from('tbl_manage_login_user AS mlu');
    $this->db->JOIN('tbl_restaurant_staff_registration AS rsr','rsr.mobile_no=mlu.mobile_no','INNER');
    $this->db->where('rsr.status',1);
    $this->db->where('rsr.admin_id',$admin_id);
    $this->db->where('mlu.active_status',1);
    $this->db->where('mlu.mobile_no',$mobile_no);
    $this->db->where('rsr.user_type','Waiter');
    $query=$this->db->get();
    // print_r($this->db->last_query());exit;
    $result=$query->result_array();
    return $result;
  }
  function getSupervisordata($admin_id){
    $this->db->distinct();
    $this->db->select('mlu.notification_id,rsr.mobile_no,rsr.user_type');
    $this->db->from('tbl_manage_login_user AS mlu');
    $this->db->JOIN('tbl_restaurant_staff_registration AS rsr','rsr.mobile_no=mlu.mobile_no','INNER');
    $this->db->where('rsr.status',1);
    $this->db->where('rsr.admin_id',$admin_id);
    $this->db->where('mlu.active_status',1);
    $this->db->where('rsr.user_type','Supervisor');
    $this->db->group_by('mlu.user_type');
    $query=$this->db->get();
    // print_r($this->db->last_query());exit;
    $result=$query->result_array();
    return $result;
  }
   public function getAdmin($order_id)
  {

    $this->db->select('admin_id');
    $this->db->from('tbl_order_detail_for_restaurant');
    $this->db->where('order_id',$order_id);
    $query=$this->db->get();
    // print_r($this->db->last_query());
    $result=$query->result_array();
    return $result[0]['admin_id'];
  }
  function getPrefix($city){
    $this->db->select('mc.city_prefix,ms.state_prefix');
    $this->db->FROM('master_city AS mc');
    $this->db->JOIN('master_state AS ms','ms.state_code=mc.state_code','INNER');
    $this->db->JOIN('master_country AS mcc','mcc.country_code=ms.country_code','INNER');
    $this->db->where('mc.city_name',strtoupper($city));
    $query=$this->db->get();
    // print_r($this->db->last_query());exit;
    $result=$query->result_array();
    return $result;
  }
  function getMaxAdminData($city_prefix,$state_prefix){
    $prefix=$state_prefix.$city_prefix;
    $this->db->select("MAX(CAST(REPLACE(admin_id,'$prefix','') AS UNSIGNED)) AS `admin_id`");
    $this->db->from('tbl_admin');
    $query=$this->db->get();
    // print_r($this->db->last_query());exit;
    $result=$query->result_array();
    return $result;
  }
  public function getCategoryRestaurant($admin_id)
  {
       $this->db->select('*');
       $this->db->from('misc_category');
       $this->db->where('admin_id',$admin_id);
       $this->db->where('status',1);
       $query=$this->db->get();
       //print_r($this->db->last_query());exit;
       $result=$query->result_array();
       return $result;
  }
  function getUserTypeLable($user_type){
    $this->db->select('*');
    $this->db->from('tbl_user_type');
    $this->db->where('user_value',$user_type);
    $query=$this->db->get();
   //print_r($this->db->last_query());
   $result=$query->result_array();
   return $result;
  }
  function getFoodCategory(){
    $this->db->select('*');
    $this->db->from('food_category');
    $this->db->where('status',1);
    $query=$this->db->get();
   //print_r($this->db->last_query());exit;
   $result=$query->result_array();
   return $result;
  }
  function getRestaurantFoodType($food_code){
    $this->db->select('food_type');
    $this->db->from('food_category');
    $this->db->where('status',1);
    $this->db->where('food_code',$food_code);
    $query=$this->db->get();
   //print_r($this->db->last_query());exit;
   $result=$query->result_array();
   return $result;
  }
  function getAdminInfo($mobile_num){
    $this->db->select('user_fullname,user_email,mobile_no');
    $this->db->from('tbl_admin');
    $this->db->where('mobile_no',$mobile_num);
    $this->db->where('status',1);
    $query=$this->db->get();
    // print_r($this->db->last_query());exit;
    $result=$query->result_array();
    return $result;
  }
  function getMasterInfo($mobile_num){
    $this->db->select('user_fullname,user_email,mobile_no');
    $this->db->from('master_user');
    $this->db->where('mobile_no',$mobile_num);
    $this->db->where('status',1);
    $query=$this->db->get();
    // print_r($this->db->last_query());exit;
    $result=$query->result_array();
    return $result;
  }
  function getStaffInfo($mobile_num){
    $this->db->select('name as user_fullname,email as user_email,mobile_no');
    $this->db->from('tbl_restaurant_staff_registration');
    $this->db->where('mobile_no',$mobile_num);
    $this->db->where('status',1);
    $query=$this->db->get();
    // print_r($this->db->last_query());exit;
    $result=$query->result_array();
    return $result;
  }
  function getTermsAndCondition(){
    $this->db->select('term_condtion');
    $this->db->from('company_policy');
    $this->db->where('status',1);
    $query=$this->db->get();
    // print_r($this->db->last_query());exit;
    $result=$query->result_array();
    return $result;
  }
  function getMaxDocId(){
    $this->db->SELECT('MAX(CAST(`doc_id` AS UNSIGNED)) AS `docId`');
    $this->db->FROM('master_document');
    $query=$this->db->get();
    // print_r($this->db->last_query());exit;
    $result=$query->result_array();
    $docId = !empty($result[0]['docId']) ?$result[0]['docId']+1 : '1';
    return $docId;
    }
    function addKycInfo($data)
    {
        $json_decode =json_decode(TABLES);
        $insert = $this->db->insert($json_decode->table33, $data);
        return $insert?$this->db->insert_id():false;      
    }
    function updateSpots($docId,$adminId)
    {   
      $json_decode =json_decode(TABLES);
      $array=array('doc_id'=>$docId);
      $this->db->where('admin_id',$adminId);
      $this->db->update($json_decode->table3,$array);
      $result=$this->db->affected_rows(); 
      return $result;
    }
    function getMultipleRoles($admin_id,$mobile_no,$user_type){
    // $this->db->distinct();
    $this->db->select('user_type,name');
    $this->db->from('tbl_restaurant_staff_registration');
    $this->db->where('status',1);
    $this->db->where('admin_id',$admin_id);
    $this->db->where('mobile_no',$mobile_no);
    if(!empty($user_type)){
       $this->db->where('user_type',$user_type);
    }
   
    $query=$this->db->get();
    // print_r($this->db->last_query());exit;
    $result=$query->result_array();
    return $result;
  }
  function checkStaffData($admin_id,$mobile_no){
    $this->db->select('user_type');
    $this->db->from('tbl_restaurant_staff_registration');
    $this->db->where('admin_id',$admin_id);
    $this->db->where('mobile_no',$mobile_no);
    $query=$this->db->get();
    // print_r($this->db->last_query());exit;
    $result=$query->result_array();
    return $result;
  }
  function checkStaffDataRole($admin_id,$role)
  {
    $this->db->select('user_type');
    $this->db->from('tbl_restaurant_staff_registration');
    $this->db->where('admin_id',$admin_id);
    // $this->db->where('mobile_no',$mobile_no);
    $this->db->where('user_type',$role);
    $query=$this->db->get();
    // print_r($this->db->last_query());exit;
    $result=$query->result_array();
    return $result;
  }
  function getsalesReport($admin_id,$from_date,$to_date){
    $this->db->select('oi.admin_id,oi.order_id,oi.discount_amount,oi.total_order_amount,oi.total_gst,oi.creation_date,od.get_payment,pt.payment_mode,od.net_pay_amount,od.discount');
    $this->db->from('order_invoice as oi');
    $this->db->JOIN('tbl_order_detail_for_restaurant AS od','oi.order_id=od.order_id','LEFT');
    $this->db->JOIN('payment_txns AS pt','pt.order_id=oi.order_id','LEFT');
    $this->db->where('oi.admin_id',$admin_id);
    $this->db->where('oi.status',1);
    $this->db->where('oi.creation_date >=',$from_date.' 00:00:01');
    $this->db->where('oi.creation_date <=',$to_date.' 23:59:59');
    $query=$this->db->get();
    //print_r($this->db->last_query());exit;
    $result=$query->result_array();
    return $result;
  }
   function getRolesStaff($mobile_no,$admin_id){
    $this->db->select('user_type,name');
    $this->db->from('tbl_restaurant_staff_registration');
    $this->db->where('mobile_no',$mobile_no);
    $this->db->where('admin_id',$admin_id);
    $this->db->where('status',1);
    $query=$this->db->get();
    //print_r($this->db->last_query());exit;
    $result=$query->result_array();
    return $result;
  }
  function getRolesAdmin($mobile_no,$admin_id){
    $this->db->select('user_type,user_fullname as name');
    $this->db->from('tbl_admin');
    $this->db->where('mobile_no',$mobile_no);
    $this->db->where('admin_id',$admin_id);
    $this->db->where('status',1);
    $query=$this->db->get();
    //print_r($this->db->last_query());exit;
    $result=$query->result_array();
    return $result;
  }
  function getRoleImages($user_value){
    $this->db->select('image');
    $this->db->from('tbl_user_type');
    $this->db->where('user_value',$user_value);
    $query=$this->db->get();
    //print_r($this->db->last_query());exit;
    $result=$query->result_array();
    return $result;
  }
  function getNotificationIdForUserRole($mobile_no){
    $this->db->select('notification_id,device_id');
    $this->db->from('tbl_manage_login_user');
    $this->db->where('mobile_no',$mobile_no);
    $this->db->where('user_type','admin');
    $query=$this->db->get();
    //print_r($this->db->last_query());exit;
    $result=$query->result_array();
    return $result;
  }
  function getAdminMobileNo($admin_id){
    $this->db->select('mobile_no');
    $this->db->from('tbl_admin');
    $this->db->where('admin_id',$admin_id);
    $query=$this->db->get();
    //print_r($this->db->last_query());exit; 
    $result=$query->result_array();
    return $result;
  }
  function addUserRole($user_array){

    $this->db->query("delete from tbl_manage_login_user where mobile_no='".$user_array['mobile_no']."' and user_type='".$user_array['user_type']."'");
    $query=$this->db->insert('tbl_manage_login_user',$user_array);
    return $query?$this->db->insert_id():false; 
  }
  function getStaffDataForUpdate($id){
  $this->db->select('*');
  $this->db->from('tbl_restaurant_staff_registration');
  $this->db->where('id',$id);
  $query=$this->db->get();
  // print_r($this->db->last_query());exit;
  $result=$query->result_array();
  return $result;
  }
  function getMultipleRolesForLogin($admin_id,$mobile_no){
    $this->db->distinct();
    $this->db->select('user_type');
    $this->db->from('tbl_restaurant_staff_registration');
    $this->db->where('status',1);
    $this->db->where('admin_id',$admin_id);
    $this->db->where('mobile_no',$mobile_no);
    $query=$this->db->get();
    // print_r($this->db->last_query());exit;
    $result=$query->result_array();
    return $result;
  }
  function staffDataForWaiter($mobile_no,$user_type){
    $this->db->select('id');
    $this->db->from('tbl_restaurant_staff_registration');
    $this->db->where('status',1);
    $this->db->where('user_type',$user_type);
    $this->db->where('mobile_no',$mobile_no);
    $query=$this->db->get();
    // print_r($this->db->last_query());exit;
    $result=$query->result_array();
    return $result;
  }
  function checkKycExist($admin_id){
    $this->db->select('id');
    $this->db->from('master_document');
    $this->db->where('admin_id',$admin_id);
    $query=$this->db->get();
    // print_r($this->db->last_query());exit;
    $result=$query->result_array();
    return $result;
  }
}
?>