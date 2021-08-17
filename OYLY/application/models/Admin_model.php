<?php

class Admin_model extends CI_Model
{
	
	var $table ='spots AS s';
    var $column_order = array('','s.name','s.rating','s.address','s.create_date'); 
    var $column_search =  array('s.admin_id','s.name','s.phone','s.create_date','s.address'); 
    var $order = array('s.spotId' => 'DESC');
	public function __construct() 
    {
        parent::__construct();
		$this->load->database();
		$this->load->library('session');
    }
   private function _get_datatables_query()
    {

            $select="s.rating,a.restaurant_name,a.user_email,a.mobile_no,s.gst_no,s.pan_no,s.address,a.user_role,s.create_date,a.admin_id,s.status,md.status as kyc_status";
	        $this->db->select($select);
	        $this->db->from('spots AS s');
            // print_r($_SESSION['user_role']);exit;
            $this->db->JOIN('tbl_admin AS a','a.admin_id=s.admin_id','LEFT');
            $this->db->JOIN('master_document AS md','md.doc_id=s.doc_id','LEFT');
            if($_SESSION['user_role']==2)
            {
                $this->db->where('a.mobile_no',$_SESSION['mobile_no']);
            }
            if(!empty($_POST['searchBySpotId']))
            {
                $this->db->where('s.spotId',$_POST['searchBySpotId']);
            }
            if(!empty($_POST['searchByFromDate']))
            {
                //where('payment.paymentdate =<', $month_end)
                $this->db->where('s.create_date >=',$_POST['searchByFromDate'].' 00:00:01');
            }
            if(!empty($_POST['searchByToDate']))
            {
                $this->db->where('s.create_date <=',$_POST['searchByToDate'].' 23:59:59');
               // $this->db->where('a.mobile_no',$_SESSION['mobile_no']);
            }
	       // $this->db->order_by('s.spotId','DESC');

        $i = 0;
        foreach ($this->column_search as $item) 
        {
            if($_POST['search']['value']) 
            {
                 
                if($i===0) // first loop
                {
                    $this->db->group_start(); 
                    $this->db->like($item, $_POST['search']['value']);
                }
                else
                {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
 
                if(count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }
         
        if(isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
            
        } 
        else if(isset($this->order))
        {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    public function get_datatables()
    {


        $this->_get_datatables_query();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        // print_r($this->db->last_query());exit;
        return $query->result();
    }
    public function count_all()
    {
        $this->_get_datatables_query();
        return $this->db->count_all_results();
    }
public function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }
public function getAmenities()
{
    $this->db->select('*');
    $this->db->from('tbl_amenities_type');
    $this->db->where('status',1);
    $query=$this->db->get();
    $result=$query->result_array();
    return $result;
}
public function getFoodType()
{
    $this->db->select('*');
    $this->db->from('tbl_food_type');
    $this->db->where('status',1);
    $query=$this->db->get();
    $result=$query->result_array();
   // print_r($this->db->last_query());exit;
    return $result;
}
public function checkAdmin($mobile_no)
{
    $this->db->select('*');
    $this->db->from('tbl_admin');
    $this->db->where('mobile_no',$mobile_no);
    $query=$this->db->get();
    $result=$query->result_array();
    return $result;
}
public function max()
{
    $this->db->select('max(user_id) AS user_id');
    $this->db->from('tbl_admin');
    $query=$this->db->get();
    $result=$query->result_array();
    return $result[0]['user_id']; 
}
public function addAdmin($admin_array)
{
    $this->db->insert('tbl_admin',$admin_array);
    return $this->db->insert_id();;
}
public function addRestaurant($restau_array)
{
    $this->db->insert('spots',$restau_array);
    return $this->db->insert_id();;
}
public function getRestaurant($admin_id)
{
    $this->db->select('s.name,a.restaurant_name,a.mobile_no,a.user_email,a.user_password,a.salt,s.gst_no,s.pan_no,s.address,s.city,s.openingTime,s.closingTime,s.amenities,s.cuisines,a.user_fullname,a.admin_id');
    $this->db->from('spots AS s');
    $this->db->JOIN('tbl_admin AS a','a.admin_id=s.admin_id');
    $this->db->where('s.status',1);
    $this->db->where('s.admin_id',$admin_id);
    $query=$this->db->get();
     // print_r($this->db->last_query());exit;
    $result=$query->result_array();
    return $result;
}

public function updateRestaurant($admin_id,$restau_array,$admin_array)
{
    $this->db->where('admin_id',$admin_id);
    $this->db->update('tbl_admin',$admin_array);

    $this->db->where('admin_id',$admin_id);
    $this->db->update('spots',$restau_array);
    return $this->db->affected_rows();
}
public function changeStatus($admin_id,$change,$convenience_code,$gst_code)
{
  
  // print_r($this->db->last_query());exit;
   $this->db->where('admin_id',$admin_id);
   $this->db->update('tbl_admin',array('status'=>$change));


   $this->db->where('admin_id',$admin_id);
   $this->db->update('spots',array('status'=>$change,'gst_code'=>$gst_code,'convenience_code'=>$convenience_code));  

   return $this->db->affected_rows();
}
  public function insertGstDetails($array)
  {
   $insert=$this->db->insert_batch('master_item_category',$array);
    return $insert?$this->db->insert_id():false; 
  }
  function getPrefix($city){
    $this->db->select('mc.city_prefix,ms.state_prefix');
    $this->db->FROM('master_city AS mc');
    $this->db->JOIN('master_state AS ms','ms.state_code=mc.state_code','INNER');
    $this->db->JOIN('master_country AS mcc','mcc.country_code=ms.country_code','INNER');
    $this->db->where('mc.city_name',strtoupper($city));
    $query=$this->db->get();
    //print_r($this->db->last_query());exit;
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
  function getRestaurantList()
  {
      $select="s.rating,s.spotId,a.restaurant_name,a.user_email,a.mobile_no,a.admin_id,s.status";
      $this->db->select($select);
      $this->db->from('spots AS s');
      // print_r($_SESSION['user_role']);exit;
      $this->db->JOIN('tbl_admin AS a','a.admin_id=s.admin_id','LEFT');
      $this->db->JOIN('master_document AS md','md.doc_id=s.doc_id','LEFT');
      if($_SESSION['user_role']==2)
      {
          $this->db->where('a.mobile_no',$_SESSION['mobile_no']);
      }
      $this->db->order_by('s.spotId','DESC');
      $query=$this->db->get();
      $result=$query->result_array();
      return $result;
  
  }
  function checkKyc($admin_id){
    $select="s.doc_id,s.admin_id,md.status as kyc_status";
    $this->db->select($select);
    $this->db->from('spots AS s');
    $this->db->JOIN('master_document AS md','md.doc_id=s.doc_id','LEFT');
    $this->db->where('s.admin_id',$admin_id);
    $query=$this->db->get();
    // print_r($this->db->last_query());exit;
    $result=$query->result_array();
    return $result[0]['kyc_status'];
  }
 }
