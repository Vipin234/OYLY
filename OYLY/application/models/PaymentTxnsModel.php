<?php

class PaymentTxnsModel extends CI_Model
{
	
	var $table ='payment_record';
    var $column_order = array('','','order_id','mobile_no','amount','service_charge','settled_amount','total_gst','','','creation_date','refund',''); 
    var $column_search =array('id','order_id','mobile_no','amount','service_charge','settled_amount','total_gst','refund'); 
    var $order = array('id' => 'desc');
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
            if($_SESSION['user_role']==2)
            {
                $this->db->where('admin_id',$_SESSION['admin_id']);
            }
            if(isset($_POST['from_date'])&& !empty($_POST['to_date'])){
                    $this->db->where("creation_date BETWEEN '".$_POST['from_date']."'AND '".$_POST['to_date']."'");
                }
                if(isset($_POST['admin_id']) && !empty($_POST['admin_id'])){
                    $this->db->where('admin_id',$_POST['admin_id']);
                }
            // echo "<pre>";print_r($_POST);exit;
	        //$this->db->order_by('id','DESC');

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
function getGstValues($order_id)
{
    $this->db->select('total_gst');
    $this->db->from('order_invoice');
    $this->db->where('order_id',$order_id);
    $query=$this->db->get();
    $result=$query->result_array();
    return $result;

}

function getPaymentsAmount(){

    $admin_id=$_POST['admin_id'];
    $from_date=$_POST['from_date'];
    $to_date=$_POST['to_date'];

    $this->db->select(  'SUM(amount)AS Total_amount,
                        SUM(CASE WHEN `refund` = "No" THEN `service_charge` END) AS Paid_service_charge,
                        SUM(CASE WHEN `refund` = "Yes" THEN `service_charge` END) AS UnPaid_service_charge,
                        SUM(CASE WHEN `refund` = "Yes" THEN `amount` END) AS Refund_amount,
                        SUM(CASE WHEN `refund` = "No" THEN `settled_amount` END) AS Paid_settled_amount,
                        SUM(CASE WHEN `refund` = "No" THEN `total_gst` END) AS Paid_total_gst,
                        ');
    $this->db->from('payment_record');
    if(isset($from_date)&& !empty($to_date)){
        $this->db->where("creation_date BETWEEN '".$from_date."'AND '".$to_date."'");
    }
    if(isset($admin_id) && !empty($admin_id)){
        $this->db->where('admin_id',$admin_id);
    }
    $this->db->where('status',1);
    $query=$this->db->get();
    // print_r($this->db->last_query());exit;
    $result=$query->result_array();
    return $result;

}
function getRestaurant()
{
    $this->db->select('*');
    $this->db->from('spots');
    $this->db->where('status',1);
    $this->db->order_by('name','ASC');
    $query=$this->db->get();
    $result=$query->result_array();
    return $result;
}

function UpdatePayment($string){
    $this->db->where("order_id IN (".$string.")");
    $this->db->where('refund','No');
    $this->db->update('payment_record',array('status'=>0,'modified_on'=>date('Y-m-d H:i:s')));
    // print_r($this->db->last_query());exit;
    return $this->db->affected_rows();
}
function getConvience($convenience_code){
    $this->db->select('convenience');
    $this->db->from('master_convenience');
    $this->db->where('id',$convenience_code);
    $query=$this->db->get();
    $result=$query->result_array();
    return $result[0]['convenience'];
}
function getGstValue($gst_code){
    $this->db->select('gst');
    $this->db->from('master_gst');
    $this->db->where('id',$gst_code);
    $query=$this->db->get();
    $result=$query->result_array();
    return $result[0]['gst'];
}
}