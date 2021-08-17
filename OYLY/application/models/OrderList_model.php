<?php

class OrderList_model extends CI_Model
{
	
	var $table ='tbl_order_detail_for_restaurant AS od ';
    var $subtable ='tbl_sub_order_detail_for_restaurant as sod';
    // var $column_order = array();
    // var $column_search=array();
   var $column_order = array('','od.order_id','od.table_no','',''); 
    var $column_search =  array('od.order_id','od.table_no'); 
    var $order = array('od.id' => 'desc');
	public function __construct() 
    {
        parent::__construct();
		$this->load->database();
		$this->load->library('session');
    }
   private function _get_datatables_query()
    {

            //$select="od.admin_id,od.order_id,od.table_no,od.menu_item_name,od.quantity,od.half_and_full_status,sod.menu_item_name as sbmenu_item_name,sod.quantity as sub_quantity ,sod.half_and_full_status as sub_half_full_status";
           $select="od.admin_id,od.order_id,od.table_no,od.menu_item_name,od.quantity,od.half_and_full_status";
	        $this->db->select($select);
	        $this->db->from($this->table);
           // $this->db->JOIN('tbl_sub_order_detail_for_restaurant as sod','od.order_id=sod.order_id','LEFT');
            $this->db->where('od.admin_id',$_SESSION['admin_id']);
           // $this->db->where('od.order_id','HRGR00005-2107200001');
            $this->db->where('od.order_status','Confirm');
            //$this->db->where('od.status',2);

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
  public function change_status($order_id)
 {
    $this->db->where('order_id',$order_id);
    $this->db->where('status',2);
    $update= $this->db->update('tbl_order_detail_for_restaurant',array('order_status'=>'Prepare','status'=>3));
    //return $this->db->affected_rows();
    $this->db->where('order_id',$order_id);
    $this->db->where('status',2);
    $this->db->update('tbl_sub_order_detail_for_restaurant',array('order_status'=>'Prepare','status'=>3));
    // print_r($this->db->last_query());exit;
    return $update;
 }
 public function get_print_data($order_id)
 {

         $select="od.admin_id,od.order_id,od.table_no,od.menu_item_name,od.quantity,od.half_and_full_status";
        // $select="od.*";
         $this->db->select($select);
         $this->db->from($this->table);
         $this->db->where('od.order_id',$order_id);
         $this->db->where('od.order_status','Prepare');
         $this->db->where('status',3);
         $query = $this->db->get();
        // print_r($this->db->last_query());exit;
        return $query->result();
 }       
 public function get_suborder_data($order_id)
 {

         $select="sod.menu_item_name as sbmenu_item_name,sod.quantity as sub_quantity ,sod.half_and_full_status as sub_half_full_status";
        // $select="od.*";
         $this->db->select($select);
         $this->db->from($this->subtable);
         $this->db->where('sod.order_id',$order_id);
         $this->db->where('sod.order_status','Prepare');
         $this->db->where('sod.status',3);
         
         $query = $this->db->get();
        // print_r($this->db->last_query());exit;
        return $query->result();
 }
}
