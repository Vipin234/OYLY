<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class PaymentTxns extends CI_Controller
{
	
	function __construct() 
    {   
       parent::__construct();
       $this->load->library('session');
       $this->load->library('form_validation');    
       $this->load->library('encrypt');     
       $this->load->model('SpreesdSheetModel');
       $this->load->model('PaymentTransferModel');
       $this->load->model('PaymentTxnsModel');
       $this->load->helper('main_helper'); 
       date_default_timezone_set('Asia/kolkata'); 
       $this->load->library('Excel');
       if(empty($_SESSION['mobile_no']))
       {
       	redirect(base_url());
       }
          
    }
    function Payment()
    {
      
      $session_data=$this->session->all_userdata();
      $this->load->view('header',$session_data);
      $this->load->view('leftSidebar',$session_data);
      $result['results']=$this->PaymentTxnsModel->getRestaurant();
      $this->load->view('PaymentOrders',$result);
      $this->load->view('footer');
    }
function GetPaymentTxns()
{
      try
        { 
        $restaurantList =$this->PaymentTxnsModel->get_datatables();
        // echo '<pre>';print_r($restaurantList);exit;
        $data = array();
        $no = $_POST['start'];
        foreach ($restaurantList as $list) {

          $no++;
          $row = array();
          $row[]  ="<input type='checkbox' name='chechall'class='chechall'value='".$list->order_id."'>";
          $row[]  =$no;
          $row[]  =$list->order_id;
          $row[]  =$list->mobile_no;
          $row[]  =$list->amount;
          $row[]  =$list->service_charge;
          $row[]  =$list->settled_amount;
          $row[]  =$list->total_gst;
          $amount_without_gst=$list->settled_amount-$list->total_gst;
          $convenience=$amount_without_gst*($this->PaymentTxnsModel->getConvience($list->convenience_code))/100;
          $gstRest=$convenience*($this->PaymentTxnsModel->getGstValue($list->gst_code))/100;
          $row[]  =number_format($amount_without_gst-($convenience+$gstRest)+$list->total_gst,2);
          $row[]  =number_format($convenience+$gstRest,2);
          $row[]  =$list->creation_date;
          $row[]  =$list->refund;
          $row[]  =$list->status==1?'<div class="alert alert-danger">
                    <strong>UnPaid</strong></div>':'<div class="alert alert-success">
                    <strong>Paid</strong></div>';
          $data[] =$row;

          $convenience=0;
          $amount_without_gst=0;
          $gstRest=0;
         
        }
        // echo '<pre>';print_r($data);exit;
        $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->PaymentTxnsModel->count_all(),
                "recordsFiltered" => $this->PaymentTxnsModel->count_filtered(),
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
function getPaymentsAmount()
{
  try{

    $result=$this->PaymentTxnsModel->getPaymentsAmount();
    // echo '<pre>';print_r($result);exit;
    $Paid_settled_amount=$result[0]['Paid_settled_amount']-$result[0]['UnPaid_service_charge'];

    $Paid_total_gst=$result[0]['Paid_total_gst'];

    $amount_without_gst=$Paid_settled_amount-$Paid_total_gst;

    $convenience=$amount_without_gst*10/100;

    $gstRest=$convenience*18/100;

    $resto_amount=$Paid_settled_amount-($convenience+$gstRest);

    $amount_array=array(
                'Total_amount'=>number_format($result[0]['Total_amount'],2),
                'settled_amount'=>number_format($Paid_settled_amount >0?$Paid_settled_amount:0.00,2),
                'total_gst'=>number_format($result[0]['Paid_total_gst'],2),
                'service_charge'=>number_format($result[0]['Paid_service_charge']+$result[0]['UnPaid_service_charge'],2),
                'Refund_amount'=>number_format($result[0]['Refund_amount'],2),
                'total_resto_amount'=>number_format($resto_amount >0?$resto_amount:0.00,2),
                'convenience'=>number_format($convenience+$gstRest > 0?$convenience+$gstRest:0.00,2)
    );

    if(!empty($amount_array))
    {
      $array=array('status'=>1,'result'=>$amount_array);
      echo json_encode($array, 200);exit;
    }else
    {
      $array=array('status'=>0,'result'=>$amount_array);
      echo json_encode($array, 200);exit;
    }
  }catch(Eception $e){
    $e->getMessage();
    $error = array('status' =>'0', "data" => "Internal Server Error - Please try Later.","StatusCode"=> "HTTP405");
    echo json_encode($error, 200);exit;

  }
}
function UpdatePayment()
{

  $array=$_POST['order_idString'];
  $string='';
      for($i=0;$i<count($array);$i++)
      {
        $string .= "'".$array[$i]."'".',';
      }

      $result=$this->PaymentTxnsModel->UpdatePayment(rtrim($string,','));
      if($result)
      {
        $array=array('status'=>1,'result'=>'Pament updated successfull.');
          echo json_encode($array, 200);exit;
      }else
      {
        $array=array('status'=>0,'result'=>'Pament not updated.');
          echo json_encode($array, 200);exit;
      }
}

}