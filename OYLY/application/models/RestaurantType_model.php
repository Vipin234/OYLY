<?php

class RestaurantType_model extends CI_Model
{

    var $table ='master_restaurant_types';
    var $column_order = array('id','name','creation_date','status'); 
    var $column_search = array('id','name','creation_date','status'); 
    var $order = array('id' => 'asc');
	public function __construct() 
    {
        parent::__construct();
		$this->load->database();
		$this->load->library('session');
       date_default_timezone_set('Asia/kolkata'); 
    }
   private function _get_datatables_query()
    {

            $select="*";
            $this->db->select($select);
            $this->db->from($this->table);
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
    function addTypes($array){
        $this->db->insert($this->table,$array);
        // print_r($this->db->last_query());exit;
        return $this->db->insert_id();
    }
public function changeStatus($id,$change)
{
   $this->db->where('id',$id);
   $this->db->update($this->table,array('status'=>$change)); 
   // print_r($this->db->last_query());exit;
   return $this->db->affected_rows();
}
function getTypes($id){
    $this->db->select('*');
    $this->db->from($this->table);
    $this->db->where('status',1);
    $this->db->where('id',$id);
    $query=$this->db->get();
    $result=$query->result_array();
    return $result;
}
function updateTypes($id,$array){
   $this->db->where('id',$id);
   $this->db->update($this->table,$array); 
   // print_r($this->db->last_query());exit;
   return $this->db->affected_rows();
}
function getrestGst(){
    $this->db->select('*');
    $this->db->from('master_gst');
    $this->db->where('status',1);
    $query=$this->db->get();
    $result=$query->result_array();
    return $result;
}
}