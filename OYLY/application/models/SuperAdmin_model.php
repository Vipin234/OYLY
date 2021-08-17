<?php

class SuperAdmin_model extends CI_Model
{
	
	var $table ='master_user';
    var $column_order = array('id','user_fullname','user_email','mobile_no','user_password','user_createdate','user_role'); 
    var $column_search =  array('id','user_fullname','user_email','mobile_no','user_password','user_createdate','user_role'); 
    var $order = array('id' => 'asc');
	public function __construct() 
    {
        parent::__construct();
		$this->load->database();
		$this->load->library('session');
    }
   private function _get_datatables_query()
    {

            $select="*";
	        $this->db->select($select);
	        $this->db->from('master_user');
	        $this->db->order_by('id','DESC');

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
  public function change($user_id,$status)
 {
    $this->db->where('user_id',$user_id);
    $this->db->update('master_user',array('status'=>$status));
    // print_r($this->db->last_query());exit;
    return $this->db->affected_rows();
 }
  public function getAdmin($user_id)
 {
    $this->db->select('*');
    $this->db->from('master_user');
    $this->db->where('user_id',$user_id);
    $this->db->where('status',1);
    $query = $this->db->get();
    $result=$query->result_array();
    // print_r($this->db->last_query());exit;
    return $result;

 }
 public function updateAdmin($admin_id,$admin_array)
{
    $this->db->where('user_id',$admin_id);
    $this->db->update('master_user',$admin_array);
    // print_r($this->db->last_query());exit;
    return $this->db->affected_rows();
}
 }
