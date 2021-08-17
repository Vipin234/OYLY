<?php

class RestaurantKyc_model extends CI_Model
{
	
	var $table ='master_document AS md';
    var $column_order = array('md.admin_id','md.registration_doc','md.registration_no','md.licence_doc','md.licence_no','md.shop_act_licenece_doc','md.shop_act_licenece_no','md.uaid_doc','md.uaid_no','md.pan_doc','md.pan_no','md.acc_no','md.ifsc','md.status'); 
    var $column_search =  array('s.name','md.registration_no','md.licence_no','md.shop_act_licenece_no','md.uaid_no','md.pan_no','md.acc_no','md.ifsc'); 
    var $order = array('md.doc_id' => 'asc');
	public function __construct() 
    {
        parent::__construct();
		$this->load->database();
		$this->load->library('session');
    }
   private function _get_datatables_query()
    {

            $select="md.admin_id,md.registration_doc,md.registration_no,md.licence_doc,md.licence_no,md.shop_act_licenece_doc,md.shop_act_licenece_no,md.uaid_doc,md.uaid_no,md.pan_doc,md.pan_no,md.acc_no,md.ifsc,md.status,s.name";
          //  $select="*";
	        $this->db->select($select);
	        $this->db->from('master_document AS md');
            // print_r($_SESSION['user_role']);exit;
            $this->db->JOIN('spots AS s','md.admin_id=s.admin_id','INNER');
           // $this->db->JOIN('master_document AS md','md.doc_id=s.doc_id','INNER');

            if(!empty($_POST['searchByAdminId']))
            {
                $this->db->where('md.admin_id',$_POST['searchByAdminId']);
            }
            if(!empty($_POST['searchByFromDate']))
            {
                //where('payment.paymentdate =<', $month_end)
                $this->db->where('md.creation_date >=',$_POST['searchByFromDate']);
            }
            if(!empty($_POST['searchByToDate']))
            {
                $this->db->where('md.creation_date <=',$_POST['searchByToDate']);
               // $this->db->where('a.mobile_no',$_SESSION['mobile_no']);
            }
	        $this->db->order_by('md.doc_id','DESC');

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

public function changeStatus($admin_id,$change)
{
  // print_r($this->db->last_query());exit;
   $this->db->where('admin_id',$admin_id);
   $this->db->update('master_document',array('status'=>$change));
  // print_r($this->db->last_query());exit;
   return $this->db->affected_rows();
}
  function getKycList()
    {
        $select="md.admin_id,s.name";
        $this->db->select($select);
        $this->db->from('master_document AS md');
        $this->db->JOIN('spots AS s','md.admin_id=s.admin_id','INNER');
        $this->db->order_by('md.doc_id','DESC');
        $query=$this->db->get();
        $result=$query->result_array();
        return $result;
    
    }
 }
