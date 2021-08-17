<?php

class Menu_model extends CI_Model
{
	
	var $table ='tbl_restaurant_menu_item_list ';
    var $column_order = array('id','menu_name','menu_food_type','menu_category_id','cat_id','sub_cat_id','menu_image','menu_detail','rating','menu_half_price','menu_full_price','menu_fix_price','create_date','status'); 
    var $column_search =  array('id','menu_name','menu_food_type','menu_category_id','cat_id','sub_cat_id','menu_image','menu_detail','rating','menu_half_price','menu_full_price','menu_fix_price','create_date','status');
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
	        $this->db->from($this->table);
            if($_SESSION['user_role']==2)
            {
                $this->db->where('admin_id',$_SESSION['admin_id']);
            }
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
  public function change($menu_id,$status)
 {
    $this->db->where('menu_id',$menu_id);
    $this->db->update('tbl_restaurant_menu_item_list',array('status'=>$status));
    // print_r($this->db->last_query());exit;
    return $this->db->affected_rows();
 }
public function getMenu($menu_id)
{
     $this->db->select('*');
     $this->db->from('tbl_restaurant_menu_item_list');
     $this->db->where('menu_id',$menu_id);
     $this->db->where('status',1);
     $query = $this->db->get();
    // print_r($this->db->last_query());exit;
     $result=$query->result_array();
     return $result;
}
 public function updateMenu($array,$menu_id)
{
    $this->db->where('menu_id',$menu_id);
    $this->db->update('tbl_restaurant_menu_item_list',$array);
    // print_r($this->db->last_query());exit;
    return $this->db->affected_rows();
}

 public function addMenu($array)
 {
    $this->db->insert('tbl_restaurant_menu_item_list',$array);
    return $this->db->insert_id();
 }
 public function getGst($menu_category_id)
 {
    $this->db->select('*');
    $this->db->from('master_item_category');
    $this->db->where('id',$menu_category_id);
    $query = $this->db->get();
    // print_r($this->db->last_query());exit;
    $result=$query->result_array();
    return $result;
 }
 public function getCat($cat_id)
 {
    $this->db->select('*');
    $this->db->from('misc_category');
    $this->db->where('cat_id',$cat_id);
    $query = $this->db->get();
    // print_r($this->db->last_query());exit;
    $result=$query->result_array();
    return $result;
 }
 public function getSubCat($sub_cat_id,$cat_id)
 {
    $this->db->select('*');
    $this->db->from('misc_sub_category');
    $this->db->where('cat_id',$cat_id);
    $this->db->where('sub_cat_id',$sub_cat_id);
    $query = $this->db->get();
    // print_r($this->db->last_query());exit;
    $result=$query->result_array();
    return $result;
 }
 public function getMenuCategory($admin_id) 
 {
    $this->db->select('*');
    $this->db->from('misc_category');
    $this->db->where('status',1);
    $this->db->where('admin_id',$admin_id);
    $query = $this->db->get();
    // print_r($this->db->last_query());exit;
    $result=$query->result_array();
    return $result;
 }
  public function getMenuSubCategory($cat_id,$admin_id)
 {
    $this->db->select('*');
    $this->db->from('misc_sub_category');
    $this->db->where('status',1);
    $this->db->where('cat_id',$cat_id);
    $this->db->where('admin_id',$admin_id);
    $query = $this->db->get();
    // print_r($this->db->last_query());exit;
    $result=$query->result_array();
    return $result;
 }
 public function getMenuGST($admin_id)
 {
    $this->db->select('*');
    $this->db->from('master_item_category');
    $this->db->where('status',1);
    $this->db->where('admin_id',$admin_id);
    $query = $this->db->get();
    // print_r($this->db->last_query());exit;
    $result=$query->result_array();
    return $result;
 }
 public function getMenuMax()
 {
    $this->db->select('max(id)+1 as id');
    $this->db->from('tbl_restaurant_menu_item_list');
    $query=$this->db->get();
    // print_r($this->db->last_query());exit;
    $result=$query->result_array();
    return $result[0]['id'];
 }
}
