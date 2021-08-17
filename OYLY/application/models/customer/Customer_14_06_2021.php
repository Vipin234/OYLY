<?php

class Customer extends CI_Model
{    
  
    
    function update_customer_id($alphanumerric,$result)    
    {   
      $this->db->query("UPDATE tbl_registration_customer SET cus_id='$alphanumerric' where id='$result'");
    }

    function average_rating_for_restaurant($arraydata,$admin_id)    
    {   
      $this->db->query("UPDATE spots SET rating='$arraydata' where admin_id='$admin_id'");    
    }

    function update_order_id($alphanumerric,$result)
    {   
      $this->db->query("UPDATE tbl_order_detail_for_restaurant SET order_id='$alphanumerric' where id='$result'");
    }

    function get_menu_list_data($admin_id)
    {  
      $json_decode =json_decode(TABLES);
      $q=$this->db->query("SELECT * from $json_decode->table24 where  admin_id='$admin_id' and  status='1'");
       return($q->result_array());
    }

    function customer_add($data)
    {   
       $insert = $this->db->insert(json_decode(TABLES)->table22, $data);
       return $insert?$this->db->insert_id():false;
    }
    function add_contact_detail($data)
    {   
       $insert = $this->db->insert(json_decode(TABLES)->table6, $data);
       return $insert?$this->db->insert_id():false;
    }
    function add_order_detail_restaurant($data)
    {
        $json_decode =json_decode(TABLES);
        $insert = $this->db->insert($json_decode->table17, $data);
        return $insert?$this->db->insert_id():false;
      
    }

    function get_notification_id_for_staff_data()
     { 
         $q=$this->db->query("SELECT notification_id FROM tbl_manage_login_user where user_type!='KOT'");

         return($q->result_array());
     }
     function get_restaurant_list_data($city)
     { 
      if($city=='')
      {
       
         $q=$this->db->query("SELECT * FROM spots");
      }
      else
      {
  
        $q=$this->db->query("SELECT * FROM spots where city='$city'");
      }
         return($q->result_array());

     }
     function get_city_list_data()
     { 
         $q=$this->db->query("SELECT DISTINCT city FROM spots where status=1");
         return($q->result_array());
     }


    function feedback_data($data)
    {   
       $insert = $this->db->insert(json_decode(TABLES)->table7, $data);
       return $insert?$this->db->insert_id():false;
    }

     function update_payment_status($data)
    {   
      $order_id =$data->order_id;
      $admin_id =$data ->admin_id;
      $payment_status=$data->payment_status;
      $this->db->query("UPDATE tbl_order_detail_for_restaurant SET payment_status='$payment_status' where order_id='$order_id' and admin_id='$admin_id' ");
    }
    function getGroupData($customer_mobile_no,$customer_mobile_no1)
    {
     

         $data = $this->db->query("SELECT tbl_order_detail_for_restaurant.*,`spots`.`name` as RestaurentName,`spots`.`address` as `address`,`spots`.`phone` as `phone` from tbl_order_detail_for_restaurant INNER JOIN spots ON tbl_order_detail_for_restaurant.admin_id = spots.admin_id where customer_mobile_no=$customer_mobile_no group by order_id  ORDER BY id DESC");
        //print_r($this->db->last_query());exit;
	return($data->result_array());
    }
    function getDataOrderWise($orderid)
    {
      $result = $this->db->select('tbl_order_detail_for_restaurant.*,spots.name as RestaurentName')->from("tbl_order_detail_for_restaurant")->join("spots","spots.admin_id=tbl_order_detail_for_restaurant.admin_id")->where(['order_id'=>$orderid])->get()->result_array();
        return $result;

    }
    function getGroupDatas($order_id)
    {

       $data = $this->db->query("SELECT tbl_order_detail_for_restaurant.*,`spots`.`name` as RestaurentName from tbl_order_detail_for_restaurant INNER JOIN spots ON tbl_order_detail_for_restaurant.admin_id = spots.admin_id where order_id='$order_id'  group by order_id  ORDER BY id DESC");     
        return($data->result_array());
    }
    function getDataOrderWises($orderid,$admin_id)
    {
     
     $this->db->select('*');
     $this->db->from(json_decode(TABLES)->table31);
     $this->db->where('order_id',$orderid);
     $this->db->where('admin_id',$admin_id);
     $query=$this->db->get();
     // print_r($this->db->last_query());exit;
     $result=$query->result_array();
     return $result;

    }


    function order_detail_for_customer($customer_mobile_no,$customer_mobile_no1)
    {  
       
      $q=$this->db->query("SELECT * from tbl_order_detail_for_restaurant where customer_mobile_no IN ($customer_mobile_no,$customer_mobile_no1)  ORDER BY id DESC");
       return($q->result_array());
    }

    function order_detail_particular_for_customer($order_id)
    {  
       
      $q=$this->db->query("SELECT * from tbl_order_detail_for_restaurant where order_id='$order_id' ORDER BY id DESC");
       return($q->result_array());
    }

    function update_customer_profile($data)
    { 


      $cus_id=$data->cus_id;  
      $name =$data->name;
      $state =$data ->state;
      $city=$data->city;
      $address=$data->address;
      $gender=$data->gender;
      $date_of_birth=$data->date_of_birth;
      $area=$data->area;
      $pincode =$data->pincode;
      $mobile_no =$data ->mobile_no;
      $email_id=$data->email;
      $cus_image =$data ->cus_image;
      $active_status='1';
      $profile_update_status=1;
      
      date_default_timezone_set('Asia/kolkata'); 

      $now = date('Y-m-d H:i:s');

      $string="UPDATE tbl_registration_customer SET ";

      if(isset($name)&&!empty($name))
      {
        $string.='name="'.$name.'"'.',';
      }
      if(isset($state)&&!empty($state))
      {
        $string.='state="'.$state.'"'.',';
      }
      if(isset($city)&&!empty($city))
      {
        $string.='city="'.$city.'"'.',';
      }
      if(isset($address)&&!empty($address))
      {
        $string.='address="'.$address.'"'.',';
      }
      if(isset($area)&&!empty($area))
      {
        $string.='area="'.$area.'"'.',';
      }
      if(isset($gender)&&!empty($gender))
      {
        $string.='gender="'.$gender.'"'.',';
      }
      if(isset($date_of_birth)&&!empty($date_of_birth))
      {
        $string.='date_of_birth="'.$date_of_birth.'"'.',';
      }
      if(isset($pincode)&&!empty($pincode))
      {
        $string.='pincode="'.$pincode.'"'.',';
      }
      if(isset($mobile_no)&&!empty($mobile_no))
      {
        $string.='mobile_no="'.$mobile_no.'"'.',';
      }
      if(isset($email_id)&&!empty($email_id))
      {
        $string.='email_id="'.$email_id.'"'.',';
      }
      if(isset($cus_image)&&!empty($cus_image))
      {
        $string.='cus_image="'.$cus_image.'"'.',';
      }
      if(isset($active_status)&&!empty($active_status))
      {
        $string.='active_status="'.$active_status.'"'.',';
      }
        $string.='modified_date="'.$now.'"'.',';

        $string.='profile_update_status="'.$profile_update_status.'"';

        $string.='where cus_id="'.$cus_id.'" and mobile_no="'.$mobile_no.'"';

        // print_r($string);exit;

        $this->db->query($string);

    }




    function Validate_custumer($mobile_no,$password)
    {
         $q=$this->db->where(['mobile_no'=>$mobile_no,'password'=>$password])
          ->get('registration_customer');
           return($q->row());
    }

     function customer_data($mobile_no)
    {
         $q=$this->db->where(['mobile_no'=>$mobile_no])
          ->get('registration_customer');
           return($q->row());
    }

    function get_custumer_data($mobile_no,$password,$id)
    {
         $q=$this->db->query("SELECT * from registration_customer where id='".$id."'");
         $row = $que->num_rows();
         if($row>0)
         {
            return true;
         }
         else
         {
            return false;
         }
     }

     ////////Add Registartion Custumer data //////////
    function Add_registration_custumer_data($data)
    {
        $CusID =$data->cus_id;
        $name =$data ->name;
        $mobile_no=$data->mobile_no;
        $device_id=$data->device_id;
        $notification_id=$data->notification_id;
        $login_time=$data->login_time;
        $emailID=$data->email_id;
        $address=$data->address;
        $query=$this->db->get_where(json_decode(TABLES)->table12,['cus_id'=>$CusID,'device_id'=>$device_id])->num_rows();
        $query1=$this->db->get_where(json_decode(TABLES)->table12,['cus_id'=>$CusID])->num_rows();

        if($query>0)
        {
            date_default_timezone_set('Asia/kolkata'); 
            $now = date('Y-m-d H:i:s');
            $this->db->query("UPDATE tbl_login_customer SET login_time='$login_time',notification_id='$notification_id' where mobile_no='$mobile_no' and device_id='$device_id'");
        }
         else if($query1>0)
        {
            $this->db->query("UPDATE tbl_login_customer SET login_time='$login_time',notification_id='$notification_id',device_id='$device_id' where mobile_no='$mobile_no'");
        }
        else
        {
              $query="insert into tbl_login_customer(cus_id,name,mobile_no,email_id,address,device_id,notification_id,login_time,status,active_status) values('$CusID','$name','$mobile_no','$emailID','$address','$device_id','$notification_id','$login_time','1','1')";
        
             $this->db->query($query);
        }
     }


     function check_profile_update_status($cus_id,$mobile_no)
     {
        
        $q=$this->db->query("SELECT profile_update_status from tbl_registration_customer where cus_id='$cus_id' and mobile_no='$mobile_no'");
         return($q->row());
        $row = $q->num_rows();
     }


      function get_city_data($city)
     {  
        $q=$this->db->query("SELECT city from city_state_code where city='$city' and active_status='1'");
          return($q->row());
     }


      function customer_profile($cus_id,$mobile_no)
     { 
         $q=$this->db->query("SELECT * from tbl_registration_customer where cus_id='$cus_id' and mobile_no='$mobile_no' and active_status='1'");
           return($q->row());
     }


     function get_restaurant_name_data($adminID)
     { 
         $q=$this->db->query("SELECT name from spots where admin_id='$adminID'");
           return($q->row());
     }

      function logout_customer_data($data)
    {
        $active_status='0';
        $cus_id = $data->cus_id;
        $device_id = $data->device_id;
        $now = $data->logout_time;
         $this->db->query("UPDATE tbl_login_customer SET active_status='$active_status',logout_time='$now',notification_id='NULL' where cus_id='$cus_id' and device_id='$device_id'");
    }


      function send_otp($mobile_no,$otpValue)
    {
        // Set POST variables
        $url='https://2factor.in/API/V1/c43867a9-ba7e-11e9-ade6-0200cd936042/SMS/'.$mobile_no.'/'.$otpValue.'/OYLY%20OTP';
        // $url = 'https://2factor.in/API/V1/c43867a9-ba7e-11e9-ade6-0200cd936042/SMS/'.$mobile_no.'/'.$otpValue.'/'.'OTP'.'';
        // Open connection
        $ch = curl_init();\
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
        $cus_id=$data->cus_id;
        $otp=$data->otp;
        date_default_timezone_set('Asia/kolkata'); 
        $now = date('Y-m-d H:i:s');
        $query=$this->db->get_where(json_decode(TABLES)->table20,['cus_id'=>$data->cus_id,'device_id'=>$device_id])->num_rows();
        if($query>0)
        {

            $this->db->query("UPDATE tbl_otp_customer SET create_date='$now',notification_id='$notification_id', mobile_no='$mobile_no',otp='$otp',modified_date='$now' where cus_id='$cus_id' and device_id='$device_id'");
        }
        else
        {
        $query="insert into tbl_otp_customer(cus_id,mobile_no,otp,device_id,notification_id,create_date,status,modified_date) values('$cus_id','$mobile_no','$otp','$device_id','$notification_id','$now','1','$now')";
        $this->db->query($query);
        }
      }
       function verification_otp($data)
     { 
        $device_id=$data->device_id;
        $mobile_no=$data->mobile_no;
        $otp=$data->otp;
        $q=$this->db->query("SELECT * from tbl_otp_customer where otp='".$otp."' and  device_id='".$device_id."' and mobile_no='".$mobile_no."'");
          return($q->row());
        $row = $q->num_rows();

     }

     function get_gst_amount()
     { 
        $q=$this->db->query("SELECT gst_amount from tbl_gst_amount_detail");
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
        $query=$this->db->get_where(json_decode(TABLES)->table20,['mobile_no'=>$data->mobile_no,'device_id'=>$device_id])->num_rows();
        if($query>0)
        {
           
            $this->db->query("UPDATE tbl_otp_customer SET otp='$otp',modified_date='$now' where mobile_no='$mobile_no' and device_id='$device_id'");
        }
        
      }

       function remove_otp($data)
     {
        $mobile_no=$data->mobile_no;
        $device_id=$data->device_id;
        $query=$this->db->get_where(json_decode(TABLES)->table20,['mobile_no'=>$data->mobile_no,'device_id'=>$device_id])->num_rows();
        if($query>0)
        {
        
            $this->db->query("UPDATE tbl_otp_customer SET notification_id='' where mobile_no='$mobile_no' and device_id='$device_id'");
        }
        
      }


     function get_detail_for_restaurant_by_BLE_brodcast($BLE_id)
     {
       
        $q=$this->db->query("SELECT * from spots where BLE_id='".$BLE_id."'");
          return($q->row());
        $row = $q->num_rows();
     }
     function get_order_detail_for_customer($order_id)
     {
       
        $q=$this->db->query("SELECT * from tbl_order_detail_for_restaurant where order_id='".$order_id."' ");
          return($q->row());
        $row = $q->num_rows();
     }
     
    function rating_for_restaurant_customer($data)
       {
        $admin_id=$data->admin_id;
        // $res_id=$data->res_id;
        $cus_id=$data->cus_id;
        $customer_mobile_no=$data->customer_mobile_no;
        $rating_point=$data->rating_point;
        $detail=$data->detail;
        $create_date=$data->create_date;
        $status=$data->status;
        date_default_timezone_set('Asia/kolkata'); 
        $now = date('Y-m-d H:i:s');
        $query=$this->db->get_where(json_decode(TABLES)->table21,['admin_id'=>$admin_id,'customer_mobile_no'=>$customer_mobile_no])->num_rows();
        if($query>0)
        {
            $this->db->query("UPDATE tbl_rating_for_customer SET rating_point='$rating_point', modified_date='$now',detail='$detail' where admin_id='$admin_id' and customer_mobile_no='$customer_mobile_no'");
        }
        else
        {
              $query="insert into tbl_rating_for_customer(admin_id,cus_id,customer_mobile_no,rating_point,detail,create_date,status) values('$admin_id','$cus_id','$customer_mobile_no','$rating_point','$detail','$now',$status)";
        
             $this->db->query($query);
        }
      }
    public function check_total_count_notification($customer_mobile_no)
     {
        date_default_timezone_set('Asia/kolkata'); 
        $now = date('Y-m-d');
        $q=$this->db->query("SELECT order_id from tbl_notification_by_staff where customer_mobile_no='".$customer_mobile_no."' and status='1'");
        return($q->num_rows());
     }
      function get_notification_data($customer_mobile_no)
    {  
      $q=$this->db->query("SELECT * from tbl_notification_by_staff where customer_mobile_no='$customer_mobile_no'order by date_time desc");
       return($q->result_array());
    }
     function check_status_for_notification($check_status,$customer_mobile_no)     
    {   
      $this->db->query("UPDATE tbl_notification_by_staff SET status='0' where customer_mobile_no='$customer_mobile_no'");
     return  $this->db->affected_rows();
    }
    function get_food_type()
    {  
      $q=$this->db->query("SELECT * from tbl_food_type where status='1'");
       return($q->result_array());
    }

     function Validate_version_data($data)
     {
       
        $version_name = $data->version_name;
        $version_code = $data->version_code;
         $q=$this->db->query("SELECT * from check_version_for_customer where version_name='".$version_name."' and version_code='".$version_code."'");
          return($q->row());
        $row = $q->num_rows();
     }
      function get_restaurantName($admin_id)
     {
       
       
         $q=$this->db->query("SELECT name from spots where admin_id='".$admin_id."'");
          return($q->row());
        $row = $q->num_rows();
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
    function add_sub_order_detail_restaurant($data)
    {
        $json_decode =json_decode(TABLES);
        $insert = $this->db->insert($json_decode->table30, $data);
        return $insert?$this->db->insert_id():false;
      
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
        public function getMenuItemForSubOrder($order_id,$admin_id,$sub_order_id)
      {

            $json_decode =json_decode(TABLES);
            $this->db->select('*');
            $this->db->from($json_decode->table32);
            $this->db->where('admin_id',$admin_id);
            $this->db->where('order_id',$order_id);
            $this->db->where('sub_order_id',$sub_order_id);
            //$this->db->where('status','1');
            $query=$this->db->get();
            //print_r($this->db->last_query());exit;
            $result=$query->result_array();
            return $result;
      }
      public function getOrderCalculation($orderid,$admin_id)
      {
        $this->db->select('quantity,menu_price,gst');
        $this->db->from(json_decode(TABLES)->table31);
        $this->db->where('order_id',$orderid);
        $this->db->where('admin_id',$admin_id);
        $this->db->where('status','1');
        $query=$this->db->get();
        $result=$query->result_array();
        return $result;

      }
      public function getSubOrderCalculation($orderid,$admin_id,$sub_order_id)
      {
        $this->db->select('quantity,menu_price,gst');
        $this->db->from(json_decode(TABLES)->table32);
        $this->db->where('order_id',$orderid);
        //$this->db->where('sub_order_id',$sub_order_id);
        $this->db->where('admin_id',$admin_id);
        $this->db->where('status','1');
        $query=$this->db->get();
        //print_r($this->db->last_query());exit;
        $result=$query->result_array();
        return $result;

      }
      public function getSubOrderMenuItems($orderid,$sub_order_id,$admin_id)
      {
           $this->db->select('*');
           $this->db->from(json_decode(TABLES)->table32);
           $this->db->where('order_id',$order_id);
           $this->db->where('sub_order_id',$sub_order_id);
           $this->db->where('admin_id',$admin_id);
           $query=$this->db->get();
           $result=$query->result_array();
           return $result;
      }
      public function getGst($id,$admin_id)
      {
        $this->db->select('gst');
        $this->db->from('master_item_category');
        $this->db->where('id',$id);
        $this->db->where('status','1');
        $this->db->where('admin_id',$admin_id);
        $query=$this->db->get();
        $result=$query->result_array();
        return $result[0]['gst'];
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
       public function getSubcatId($admin_id,$cat_id)
      {
        $this->db->select('sub_cat_id,sub_cat_name');
        $this->db->from('misc_sub_category');
        $this->db->where('cat_id',$cat_id);
        $this->db->where('admin_id',$admin_id);
	$this->db->where('status',1);
        $query=$this->db->get();
        // print_r($this->db->last_query());exit;
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
      public function getCatName($cat_id,$admin_id)
  {
       $this->db->select('cat_name');
       $this->db->from('misc_category');
       $this->db->where('admin_id',$admin_id);
       $this->db->where('cat_id',$cat_id);
       $this->db->where('status',1);
       $query=$this->db->get();
       // print_r($this->db->last_query());exit;
       $result=$query->result_array();
       return $result[0]['cat_name'];
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
  public function txnRecord($data)
  {
        $insert = $this->db->insert('payment_txns', $data);
        return $insert?$this->db->insert_id():false;
  }
  public function getMaxOrderId($admin_id)
  {
    $new_admin=str_replace("_","","$admin_id").'-';
    $this->db->select("MAX(CAST(REPLACE(order_id,'$new_admin','') AS UNSIGNED)+1) AS `order_id`");
    $this->db->from('tbl_order_detail_for_restaurant');
    $this->db->where('admin_id',$admin_id);
    $query=$this->db->get();
    // print_r($this->db->last_query());exit;
    $result=$query->result_array();
    return $result[0]['order_id'];
  }
  public function getVendors($admin_id)
  {
        $this->db->select('vendor_code as vendorId,commission as commission');
        $this->db->from('master_vendor');
        $this->db->where('admin_id',$admin_id);
        $this->db->where('status','1');
        $query=$this->db->get();
        // print_r($this->db->last_query());
        return $query->result_array();
  }
  public function getCustInfo($mobile_no)
  {
        $this->db->select('name,mobile_no,email_id');
        $this->db->from('tbl_registration_customer');
        $this->db->where('mobile_no',$mobile_no);
        $this->db->where('active_status','1');
        $query=$this->db->get();
        // print_r($this->db->last_query());
        return $query->result_array();
  }
  public function getCompanyPolicy()
  {
        $this->db->select('content');
        $this->db->from('company_policy');
        $this->db->where('status','1');
        $query=$this->db->get();
        // print_r($this->db->last_query());
        $result=$query->result_array();
        return $result[0]['content'];
  }
  public function getRestaurantStaffNotification($admin_id)
  {
    $this->db->select('mlu.notification_id,rsr.mobile_no');
    $this->db->from('tbl_manage_login_user AS mlu');
    $this->db->JOIN('tbl_restaurant_staff_registration AS rsr','rsr.mobile_no=mlu.mobile_no','INNER');
    $this->db->where('rsr.status',1);
    $this->db->where('rsr.admin_id',$admin_id);
    $this->db->where('mlu.active_status',1);
    $this->db->where('rsr.user_type','Waiter');
    $query=$this->db->get();
    // print_r($this->db->last_query());
    $result=$query->result_array();
    return $result;
  }
  public function insertNotification($array)
  {
    $this->db->insert_batch('tbl_notification_by_customer',$array);
    return $insert?$this->db->insert_id():false;
  }
  public function checkOrderStatus($admin_id,$order_id)
  {
    $this->db->select('*');
    $this->db->from('tbl_order_detail_for_restaurant');
    $this->db->where('admin_id',$admin_id);
    $this->db->where('order_id',$order_id);
    $this->db->where('status',1);
    $query=$this->db->get();
    $result=$query->result_array();
    return $result;

  }
  public function getRestaurantWaiterNotification($admin_id,$order_id)
  {
    $this->db->select('mlu.notification_id,odr.confirm_order_by as mobile_no');
    $this->db->from('tbl_manage_login_user AS mlu');
    $this->db->JOIN('tbl_order_detail_for_restaurant AS odr','odr.confirm_order_by=mlu.mobile_no','INNER');
    $this->db->where('odr.admin_id',$admin_id);
    $this->db->where('odr.order_id',$order_id);
    $query=$this->db->get();
    // print_r($this->db->last_query());
    $result=$query->result_array();
    return $result;
  }
 public function getNearBySpots($city)
  {
    $this->db->select('*');
    $this->db->from('spots');
    if($city !='All Cities' && !empty($city))
    {
      $this->db->where('city',$city);
    }
    $this->db->where('status',1);
    $query=$this->db->get(); 
    //print_r($this->db->last_query());
    $result=$query->result_array();
    return $result;
  }
  public function getRestaurantStaffNotificationForPayment($admin_id)
  {
    $this->db->select('mlu.notification_id,rsr.mobile_no');
    $this->db->from('tbl_manage_login_user AS mlu');
    $this->db->JOIN('tbl_restaurant_staff_registration AS rsr','rsr.mobile_no=mlu.mobile_no','INNER');
    $this->db->where('rsr.status',1);
    $this->db->where('rsr.admin_id',$admin_id);
    $this->db->where('mlu.active_status',1);
    $this->db->where('rsr.user_type IN ("Waiter","Cashier","Supervisor")');
    $query=$this->db->get();
    // print_r($this->db->last_query());
    $result=$query->result_array();
    return $result;
  }
  public function getMenuIdsForOrder($order_id)
  {
    $this->db->select('menu_id');
    $this->db->from('master_item');
    $this->db->where('status',1);
    $this->db->where('order_id',$order_id);
    $this->db->group_by('menu_id');
    $query=$this->db->get();
    // print_r($this->db->last_query());
    $result=$query->result_array();
    return $result;
  }

 public function getMenuIdsForrSubOrder($order_id)
  {
    $this->db->select('menu_id');
    $this->db->from('sub_master_item');
    $this->db->where('status',1);
    $this->db->where('order_id',$order_id);
    $this->db->group_by('menu_id');
    $query=$this->db->get();
    // print_r($this->db->last_query());
    $result=$query->result_array();
    return $result;
  }
  public function getMenuItemsForFeedback($string)
  {
    $this->db->select('*');
    $this->db->from('tbl_restaurant_menu_item_list');
    $this->db->where('status',1);
    $this->db->where('menu_id IN ('.$string.')');
    $query=$this->db->get();
    // print_r($this->db->last_query());
    $result=$query->result_array();
    return $result;
  }
  public function customerRating($array)
  {
    $insert=$this->db->insert('cust_feedback',$array);
    return $insert?TRUe:FALSE;
  }
  public function updateRating($admin_id,$menu_id,$update,$order_id)
  {
    $this->db->where('admin_id',$admin_id);
    $this->db->where('menu_id',$menu_id);
    $this->db->update('tbl_restaurant_menu_item_list',$update);

    $this->db->where('order_id',$order_id);
    $this->db->where('menu_id',$menu_id);
    $this->db->update('sub_master_item',array('is_reviewed'=>1));

    $this->db->where('order_id',$order_id);
    $this->db->where('menu_id',$menu_id);
    $this->db->update('master_item',array('is_reviewed'=>1));

    // print_r($this->db->last_query());exit;
  }
  public function getMenuRating($menu_id)
  {
    $this->db->select('AVG(rating) AS rating');
    $this->db->from('cust_feedback');
    $this->db->where('status',1);
    $this->db->where('menu_id',$menu_id);
    $query=$this->db->get();
    // print_r($this->db->last_query());
    $result=$query->result_array();
    return $result[0]['rating'];
  }
  public function getCustomerFeedbackMenuIds($order_id)
  {
    $this->db->select('menu_id');
    $this->db->from('cust_feedback');
    $this->db->where('status',1);
    $this->db->where('order_id',$order_id);
    $query=$this->db->get();
    // print_r($this->db->last_query());
    $result=$query->result_array();
    return $result;
  }
  public function customerNotReview($order_id,$rtrim)
  {
    $this->db->where('order_id',$order_id);
    $this->db->where('menu_id IN ('.$rtrim.')');
    $this->db->update('master_item',array('is_reviewed'=>0));
    // print_r($this->db->last_query());exit;
    $this->db->where('order_id',$order_id);
    $this->db->where('menu_id IN ('.$rtrim.')');
    $this->db->update('sub_master_item',array('is_reviewed'=>0));
  }
  public function updateOrderOnCustomerFeedback($admin_id,$order_id)
  {
    $this->db->where('order_id',$order_id);
    $this->db->where('admin_id',$admin_id);
    $this->db->update('tbl_order_detail_for_restaurant',array('is_reviewed'=>1));
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
function getCustData($mobile_no){
  $this->db->select('notification_id,mobile_no');
  $this->db->from('tbl_login_customer');
  $this->db->where('status',1);
  $this->db->where('mobile_no',$mobile_no);
  $query=$this->db->get();
  // print_r($this->db->last_query());exit;
  $result=$query->result_array();
  return $result;
}
function getAdminData($admin_id){

  $this->db->select('mlu.notification_id,mlu.mobile_no');
  $this->db->from('tbl_manage_login_user AS mlu');
  $this->db->JOIN('tbl_admin AS a','a.mobile_no=mlu.mobile_no','INNER');
  $this->db->where('a.admin_id',$admin_id);
  $this->db->where('mlu.status',1);
  $query=$this->db->get();
  // print_r($this->db->last_query());exit;
  $result=$query->result_array();
  return $result;
}
}
?>