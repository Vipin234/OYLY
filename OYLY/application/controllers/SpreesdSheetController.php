<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class SpreesdSheetController extends CI_Controller
{
  
  function __construct() 
    {   
       parent::__construct();
       $this->load->library('session');
       $this->load->library('form_validation');    
       $this->load->library('encrypt');     
       $this->load->model('SpreesdSheetModel');
       $this->load->helper('main_helper'); 
       date_default_timezone_set('Asia/kolkata'); 
       $this->load->library('Excel');
       if(empty($_SESSION['mobile_no']))
       {
        redirect(base_url());
       }
          
    }
    function SpreedSheet()
    {
      $errors['error']=array();
      $error=array();
      $session_data=$this->session->all_userdata();
      $this->load->view('header',$session_data);
      $this->load->view('leftSidebar',$session_data);
      $this->load->view('SpreadSheet',$errors);
      $this->load->view('footer');
    }
    function uploadEcxelFile()
    {
      $array=array();
      $error=array();
      if(isset($_FILES['excelFile']['name']) && ($_FILES['excelFile']['size'] > 0))
      {
          
          $path=$_FILES['excelFile']['tmp_name'];
          $objPHPExcel = PHPExcel_IOFactory::load($path);
          $objWorksheet=$objPHPExcel->setActiveSheetIndex(0);
          $xls_column = array();
          foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
              foreach ($worksheet->getRowIterator() as $row) {
                  foreach ($row->getCellIterator() as $key => $cell) {
                      $xls_column[] = $cell->getCalculatedValue();
                  }
                  break;
              }
          }
          if (!empty(json_decode(XLS_COLUMN, true))) {
              $xls_match = json_decode(XLS_COLUMN, true);
          }
          $err = 0;
          if (!empty($xls_match) && !empty($xls_column)) {
              $result = array_diff($xls_column, $xls_match);
              if (count($result) > 0) {
                  $err = '1';
              }
          } else {
              $err = '1';
          }
          if ($err == '1') {
              echo "<script>
                      alert('Invalid Format xls');
                      window.location.href='SpreedSheet';
                      </script>";
              exit;
          }
          $destFile = "TxnsExcel/".date('Y-m-d').'-'.time().'.csv';
          move_uploaded_file($_FILES['excelFile']['tmp_name'], $destFile);
          foreach ($objPHPExcel->getWorksheetIterator() as $worksheet){
            $highestRow         =$worksheet->getHighestRow();
            $highestColumn      =$worksheet->getHighestColumn();

            for($i=2;$i<=$highestRow;$i++){

                $order_id      =trim($objWorksheet->getCellByColumnAndRow(0,$i)->getValue());
                $data          =$this->SpreesdSheetModel->getOrderData($order_id);
                $gstdata       =$this->SpreesdSheetModel->getData($order_id);
                if(empty($gstdata)){
                 echo "Invoice not generate for order no ".$order_id;exit;
                }
                $ref_id        =trim($objWorksheet->getCellByColumnAndRow(1,$i)->getValue());
                $mobile_no     =trim($objWorksheet->getCellByColumnAndRow(3,$i)->getValue());
                $amount        =trim($objWorksheet->getCellByColumnAndRow(7,$i)->getValue());
                $serviceCharge =trim($objWorksheet->getCellByColumnAndRow(9,$i)->getValue());
                $settled_amount=trim($objWorksheet->getCellByColumnAndRow(11,$i)->getValue());
                $txn_date      =trim($objWorksheet->getCellByColumnAndRow(17,$i)->getValue());
                $txn_status    =trim($objWorksheet->getCellByColumnAndRow(18,$i)->getValue());;
                $utr           =trim($objWorksheet->getCellByColumnAndRow(20,$i)->getValue());
                $settled_on    =trim($objWorksheet->getCellByColumnAndRow(21,$i)->getValue());
                $refund_status =trim($objWorksheet->getCellByColumnAndRow(22,$i)->getValue());

                $result=$this->SpreesdSheetModel->getOrderRecord($order_id);
                if(!empty($result))
                {
                 $error[]=array('error'=>$order_id);
                }else
                {
                    $array[]=array(

                              'admin_id'=>$data[0]['admin_id'],
                              'total_gst'=>$gstdata[0]['total_gst'],
                              'order_id'=>$order_id,
                              'mobile_no'=>$mobile_no,
                              'reference_id'=>$ref_id,
                              'amount'=>$amount,
                              'service_charge'=>$serviceCharge,
                              'settled_amount'=>$settled_amount,
                              'txn_status'=>$txn_status,
                              'utr_no'=>$utr,
                              'settled_on'=>date('Y-m-d',strtotime(str_replace('/', '-', $settled_on))),
                              'refund'=>$refund_status,
                              'creation_date'=>date('Y-m-d H:i:s'),
                              'txn_date'=>date('Y-m-d',strtotime(str_replace('/', '-',$txn_date))),
                              'status'=>1
                    ); 
                }               
            }
             if(!empty($array))
             {
              $this->SpreesdSheetModel->insertPaymentRecords($array);
             }
             
          }
      // print_r($status);exit;
      $errors['error']=$error;
      // print_r($errors);exit;
      $session_data=$this->session->all_userdata();
      $this->load->view('header',$session_data);
      $this->load->view('leftSidebar',$session_data);
      $this->load->view('SpreadSheet',$errors);
      $this->load->view('footer');

      }else
      {
         $errors['error']=$error;
        $session_data=$this->session->all_userdata();
        $this->load->view('header',$session_data);
        $this->load->view('leftSidebar',$session_data);
        $this->load->view('SpreadSheet',$errors);
        $this->load->view('footer');
      }
  }


}