<?php

defined('BASEPATH') or exit('No direct script access allowed');

class RestaurantKyc extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->library('encrypt');
        $this->load->model('Login_model');
        $this->load->model('restaurantKyc_model');
        $this->load->helper('main_helper');
        if (empty($_SESSION['mobile_no'])) {
            redirect(base_url());
        }

    }

    public function restaurantKyc()
    {
        $session_data = $this->session->all_userdata();
        $result['kyc_list'] = $this->restaurantKyc_model->getKycList();
        // print_r($result);
        // die;
        $this->load->view('header', $session_data);
        $this->load->view('leftSidebar', $session_data);
        $this->load->view('restaurantKyc', $result);
        $this->load->view('footer');
    }
    public function logout()
    {
        // print_r('expression');exit;
        $session_data = $this->session->all_userdata();
        $this->session->sess_destroy();
        redirect(base_url());
    }
    public function KycList()
    {
        try
        {
            $KycList = $this->restaurantKyc_model->get_datatables();
            //   echo "<pre>";
            // print_r($KycList);exit;
            $data = array();
            $no = $_POST['start'];
            foreach ($KycList as $list) {
                $no++;
                $row = array();
                $row[] = $no;
                $row[] = $list->name;
                $row[] = $list->registration_no;
                $row[] = !empty($list->registration_doc) ? '<a href="' . base_url() . $list->registration_doc . '"target="_blank">view</a>' : '';
                // $row[]     =$list->registration_doc;
                $row[] = $list->licence_no;
                $row[] = !empty($list->licence_doc) ? '<a href="' . base_url() . $list->licence_doc . '"target="_blank">view</a>' : '';
                $row[] = $list->shop_act_licenece_no;
                $row[] = !empty($list->shop_act_licenece_doc) ? '<a href="' . base_url() . $list->shop_act_licenece_doc . '"target="_blank">view</a>' : '';
                $row[] = $list->uaid_no;
                $row[] = !empty($list->uaid_doc) ? '<a href="' . base_url() . $list->uaid_doc . '"target="_blank">view</a>' : '';
                $row[] = $list->pan_no;
                $row[] = !empty($list->pan_doc) ? '<a href="' . base_url() . $list->pan_doc . '"target="_blank">view</a>' : '';
                $row[] = $list->acc_no;
                $row[] = $list->ifsc;
                $action = '';
                
                $button =$list->status=='0'?'Verify':'Reject';
                $button_class=$list->status=='0'?'btn-sm btn-success':'btn-sm btn-danger';
                $status =$list->status=='0'?'1':'0';
                $action .= '<div class="row text-center" style="width: max-content;margin-right: auto; margin-left: auto;"><a class="btn 
            '.$button_class.' btn-xs" href="javascript:void(0)" title="Verify" onclick="verify_kyc(' . "'" . $list->admin_id . "'," . "'" . $status . "'" .')"style="height: 33px">'.$button.'</a></div>';
                $row[] = $action;
                $data[] = $row;
            }
            $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->restaurantKyc_model->count_all(),
                "recordsFiltered" => $this->restaurantKyc_model->count_filtered(),
                "data" => $data,
            );
            echo json_encode($output);

        } catch (Exception $e) {
            echo $e->getMessage();
            $error = array('status' => '0', "data" => "Internal Server Error - Please try Later.", "StatusCode" => "HTTP405");
            $this->response($error, 200);
        }
    }
    public function verifyKyc()
    {
        // print_r('expression');exit;
        $result = $this->restaurantKyc_model->changeStatus($_POST['admin_id'],$_POST['status']);
       // print_r($result);exit;
        if($result)
        {
          if($_POST['status']=='1')
          {
            echo json_encode(array('status'=>1,'message'=>'Kyc Verification has been done'));exit;
          }
          else{
            echo json_encode(array('status'=>1,'message'=>'Kyc Verification has been rejected'));exit;
          }
        }else
        {
           echo json_encode(array('status'=>0,'result'=>'Not updated'));exit;
        }
    }
}
