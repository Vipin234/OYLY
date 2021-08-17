<?php

class SubCat_model extends CI_Model
{
	
	var $table ='misc_category as mc ';
    var $column_order = array('','mc.cat_name','msc.sub_cat_name','s.name','mc.creation_date',''); 
    var $column_search =  array('mc.id','mc.cat_id','msc.sub_cat_name','mc.creation_date','mc.status'); 
    var $order = array('mc.id' => 'desc');
	public function __construct() 
    {
        parent::__construct();
		$this->load->database();
		$this->load->library('session');
    }
   private function _get_datatables_query()
    {

            $select="mc.*,s.name,msc.sub_cat_name,msc.sub_cat_id,msc.status as subCatStatus";
	        $this->db->select($select);
	        $this->db->from($this->table);
            $this->db->join('spots as s','s.admin_id=mc.admin_id','INNER');
            $this->db->join('misc_sub_category as msc','msc.cat_id=mc.cat_id','INNER');
           
            if($_SESSION['user_role']==2)
            {
                $this->db->where('mc.admin_id',$_SESSION['admin_id']);
            }
            
	       // $this->db->order_by('mc.id','DESC');

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
        //print_r($this->db->last_query());exit;
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
public function change($status,$admin_id,$sub_cat_id)
 {
    $this->db->where('sub_cat_id',$sub_cat_id);
    $this->db->where('admin_id',$admin_id);
    $this->db->update('misc_sub_category',array('status'=>$status));
    // print_r($this->db->last_query());exit;
    return $this->db->affected_rows();
 }
 public function getSubCategory($cat_id,$admin_id, $sub_cat_id)
 {
    $this->db->select('*');
    $this->db->where('cat_id',$cat_id);
    $this->db->where('admin_id',$admin_id);
    $this->db->where('sub_cat_id',$sub_cat_id);
    $this->db->where('status',1);
    $this->db->from('misc_sub_category');
    $query = $this->db->get();
        // print_r($this->db->last_query());exit;
    return $query->result();
 }
 function updateSubCategory($cat_id,$sub_cat_id,$admin_id,$sub_cat_name)
 {

    $this->db->where('admin_id',$admin_id);
    $this->db->where('sub_cat_id',$sub_cat_id);
    $this->db->update('misc_sub_category',array('sub_cat_name'=>$sub_cat_name,'cat_id'=>$cat_id));
    // print_r($this->db->last_query());exit;
    return $this->db->affected_rows();
 }
}