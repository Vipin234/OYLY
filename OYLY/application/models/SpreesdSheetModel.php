<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class SpreesdSheetModel extends CI_Model {
 
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        date_default_timezone_set('Asia/kolkata'); 
    }
    public function updateServiceCharge($order_id,$service_charge)
    {
    	$this->db->where('order_id',$order_id);
    	$this->db->update('order_invoice',array('service_charge'=>$service_charge,'modified_date'=>date('Y-m-d H:i:s'),'status'=>0));
    	// print_r($this->db->last_query());exit;
    	return $this->db->affected_rows();
    }
    function getOrderData($order_id)
    {
        $this->db->select('*');
        $this->db->from('tbl_order_detail_for_restaurant');
        $this->db->where('order_id',$order_id);
        $query=$this->db->get();
        $result=$query->result_array();
        return $result;
    }
    function insertPaymentRecords($array)
    {
        $this->db->insert_batch('payment_record',$array);
         return $this->db->insert_id();
    }
    function getOrderRecord($order_id)
    {
        $this->db->select('*');
        $this->db->from('payment_record');
        $this->db->where('order_id',$order_id);
        $query=$this->db->get();
        $result=$query->result_array();
        return $result; 
    }
     function getData($order_id)
    {
        $this->db->select('*');
        $this->db->from('order_invoice');
        $this->db->where('order_id',$order_id);
        $query=$this->db->get();
        $result=$query->result_array();
        return $result; 
    }
  }