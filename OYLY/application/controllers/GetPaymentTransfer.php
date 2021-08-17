<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class GetPaymentTransfer extends CI_Controller
{
	
	function __construct() 
    {   
       parent::__construct();
       $this->load->library('session');
       $this->load->library('form_validation');    
       $this->load->library('encrypt');     
       $this->load->model('SpreesdSheetModel');
       $this->load->model('PaymentTransferModel');
       $this->load->helper('main_helper'); 
       date_default_timezone_set('Asia/kolkata'); 
       $this->load->library('Excel');
       if(empty($_SESSION['mobile_no']))
       {
       	redirect(base_url());
       }
          
    }
    function PaymentTransfer()
    {
      
      $session_data=$this->session->all_userdata();
      $this->load->view('header',$session_data);
      $this->load->view('leftSidebar',$session_data);
      $this->load->view('GetPaymentTransfer');
      $this->load->view('footer');
    }
function GetPaymnetTransferHistory()
{
      try
        { 
        $restaurantList =$this->PaymentTransferModel->get_datatables();
        // echo '<pre>';print_r($restaurantList);exit;
        $data = array();
        $no = $_POST['start'];
        foreach ($restaurantList as $list) {
          $no++;
          $row = array();
          $row[]  =$no;
          $refund=$list->total_order_amount-$list->discount_amount+$list->total_gst-$list->service_charge;
          $row[]  =$list->inv_no;
          $row[]  =$list->order_id;
          $row[]  =$list->total_order_amount;
          $row[]  =$list->discount_amount;
          $row[]  =$list->total_gst;
          $row[]  =($list->total_order_amount-$list->discount_amount);
          $row[]  =($list->total_order_amount-$list->discount_amount)+$list->total_gst;
          // $row[]  =$list->service_charge;
          // $row[]  =$list->total_order_amount-$list->discount_amount+$list->total_gst-$list->service_charge;



          // $convienceCharge=($refund-$list->total_gst)*5/100;
          // $gstCharge      =$convienceCharge*18/100;

          // $row[]  =$refund-($convienceCharge+$gstCharge);
          // $row[]  =$convienceCharge+$gstCharge;
          // $row[]  =$list->modified_date=='0000-00-00 00:00:00'?'':$list->modified_date;
          // $row[]  =$list->status==0?'<span class="text-success">Paid</span>':'<span class="text-danger">Unpaid</span>';
          $data[] =$row;
          $refund=0;
          $gstCharge=0;
          $convienceCharge=0;
        }
        $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->PaymentTransferModel->count_all(),
                "recordsFiltered" => $this->PaymentTransferModel->count_filtered(),
                "data" => $data,
            );
      echo json_encode($output);
                
        }catch(Eception $e)
        {
            echo $e->getMessage();
            $error = array('status' =>'0', "data" => "Internal Server Error - Please try Later.","StatusCode"=> "HTTP405");
            echo json_encode($error, 200);exit;
        }
}


}