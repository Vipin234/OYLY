<?php

class StaffList_model extends CI_Model
{
	
	var $table ='tbl_restaurant_staff_registration AS rsr ';
    var $column_order = array('','rsr.name','','','rsr.permanent_address','s.name','rsr.create_date','',''); 
    var $column_search =  array('rsr.id','rsr.name','rsr.email','rsr.mobile_no','rsr.aadhar_no','rsr.pan_number','rsr.permanent_address','rsr.user_type','rsr.create_date','rsr.status');
    var $order = array('rsr.id' => 'desc');
	public function __construct() 
    {
        parent::__construct();
		$this->load->database();
		$this->load->library('session');
    }
   private function _get_datatables_query()
    {

            $select="rsr.*,s.name as restaurant_name";
	        $this->db->select($select);
	        $this->db->from($this->table);
            $this->db->JOIN('spots as s','s.admin_id=rsr.admin_id','INNER');
            if($_SESSION['user_role']==2)
            {
                $this->db->where('rsr.mobile_no',$_SESSION['mobile_no']);
            }
	        //$this->db->order_by('rsr.id','DESC');

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
 
 public function delete($user_id)
 {
    $this->db->where('user_id',$user_id);
    $this->db->delete('master_user');
    return $this->db->affected_rows();
 }
  public function change($id,$status,$admin_id)
 {
    $this->db->where('id',$id);
    $this->db->where('admin_id',$admin_id);
    $this->db->update('tbl_restaurant_staff_registration',array('status'=>$status));
    // print_r($this->db->last_query());exit;
    return $this->db->affected_rows();
 }
  public function getStaff($id)
 {
    $this->db->select('*');
    $this->db->from('tbl_restaurant_staff_registration');
    $this->db->where('status',1);
    $this->db->where('id',$id);
    $query = $this->db->get();
    $result=$query->result_array();
    // print_r($this->db->last_query());exit;
    return $result;

 }
 public function updateStaff($array,$mobile_no,$id)
{
    $this->db->where('id',$id);
    $this->db->update('tbl_restaurant_staff_registration',$array);
    //print_r($this->db->last_query());exit;
    return $this->db->affected_rows();
}
public function getAllAdmin()
{
    $this->db->select('restaurant_name,admin_id');
    $this->db->from('tbl_admin');
    $this->db->where('status',1);
    $query = $this->db->get();
    $result=$query->result_array();
    // print_r($this->db->last_query());exit;
    return $result; 
 }
 public function checkStaff($mobile_no)
 {
    $this->db->select('*');
    $this->db->from('tbl_restaurant_staff_registration');
    $this->db->where('mobile_no',$mobile_no);
    $query = $this->db->get();
    // print_r($this->db->last_query());exit;
    $result=$query->result_array();
    return $result;
 }
 public function addStaff($array)
 {
    $this->db->insert('tbl_restaurant_staff_registration',$array);
    return $this->db->insert_id();
 }
 public function checkSuperAdmin($mobile_no)
 {
    $this->db->select('*');
    $this->db->from('master_user');
    $this->db->where('mobile_no',$mobile_no);
    $query = $this->db->get();
    $result=$query->result_array();
    // print_r($this->db->last_query());exit;
    return $result; 
 }
 public function getRestaurant2($admin_id)
{
    $this->db->select('s.name,a.restaurant_name,a.mobile_no,a.user_email,a.user_password,a.salt,s.gst_no,s.pan_no,s.address,s.city,s.openingTime,s.closingTime,s.amenities,s.cuisines,a.user_fullname,a.admin_id,s.status');
    $this->db->from('spots AS s');
    $this->db->JOIN('tbl_admin AS a','a.admin_id=s.admin_id');
    $this->db->where('s.admin_id',$admin_id);
    $query=$this->db->get();
    $result=$query->result_array();
    return $result;
}
 public function getRestaurant($admin_id)
 {
    $this->db->select('*');
    $this->db->from('spots');
    $this->db->where('admin_id',$admin_id);
    $this->db->where('status',1);
    $query = $this->db->get();
    $result=$query->result_array();
    // print_r($this->db->last_query());exit;
    return $result;
 }
}
