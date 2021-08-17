<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cat extends CI_Controller
{
	
	function __construct() 
    {   
       parent::__construct();
       $this->load->library('session');
       $this->load->library('form_validation'); 
       $this->load->library('encrypt');     
       $this->load->model('Login_model'); 
       $this->load->model('Admin_model');
       $this->load->model('Menu_model');
       $this->load->model('Cat_model');
       $this->load->helper('main_helper');     
       if(empty($_SESSION['mobile_no']))
       {
       	redirect(base_url());
       }

    }
    function category()
    {
      $session_data=$this->session->all_userdata();
      $this->load->view('header',$session_data);
      $this->load->view('leftSidebar',$session_data);
      $this->load->view('Cat');
      $this->load->view('footer');
    }
    function getCategoryList()
    {
             try
        { 
        $restaurantList =$this->Cat_model->get_datatables();
        // echo '<pre>';print_r($restaurantList);exit;
        $data = array();
        $no = $_POST['start'];
        foreach ($restaurantList as $list) {
          $no++;
          $row = array();
          $row[]  =$no;
          $row[]  =$list->cat_name;
          $row[]  =$list->name;
          $row[]  =$list->creation_date;
          $button =$list->status==1?'Active':'Deactive';
          $row[]  ='<div class="row text-center" style="width: max-content;margin-right: auto; margin-left: auto;"><a class="btn btn-sm btn-primary btn-xs" href="javascript:void(0)" title="Edit" onclick="edit('."'".$list->cat_id."'".','."'".$list->status."'".','."'".$list->admin_id."'".')"style="height: 33px"><i class="glyphicon glyphicon-eye-open"></i>Edit</a>
                          <a class="btn btn-info btn-xs" href="javascript:void(0)" title="'.$button.'" onclick="change('."'".$list->cat_id."'".','."'".$list->status."'".','."'".$list->admin_id."'".')"style="height: 33px;"><i class="glyphicon glyphicon-saved"></i>'.$button.'</a>
                          <a class="btn btn-danger btn-xs" href="javascript:void(0)" title="Delete" onclick="remove('."'".$list->cat_id."'".','."'".$list->status."'".','."'".$list->admin_id."'".')"style="height: 33px;display:none;"><i class="glyphicon glyphicon-deleted"></i>Delete</a></div>';
          $data[] =$row;
        }
        $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->Cat_model->count_all(),
                "recordsFiltered" => $this->Cat_model->count_filtered(),
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
public function change()
{
  try
  {
    $cat_id =$_POST['cat_id'];
    $status  =$_POST['change'];
    $admin_id  =$_POST['admin_id'];
    if(!empty($cat_id))
    {

      $result=$this->Cat_model->change($cat_id,($status==1?'0':'1'),$admin_id);
      if($result)
      {
        $arry=array('status'=>'1','message'=>'Success');
              echo json_encode($arry, 200);exit();
      }else
      {
        $arry=array('status'=>'0','message'=>'Something went wrong');
              echo json_encode($arry, 200);exit();
      }
    }else
    {
       $arry=array('status'=>'0','message'=>'Something went wrong');
             echo json_encode($arry, 200);exit;
    }
  }catch(Eception $e)
  {
    $e->getMessage();
    $error = array('status' =>'0', "data" => "Internal Server Error - Please try Later.","StatusCode"=> "HTTP405");
    echo json_encode($error, 200);exit;

  }
}
 public function getCategory()
  {
   
    try
    {
      $cat_id=$_POST['cat_id'];
      $admin_id=$_POST['admin_id'];
      $status=$_POST['status'];
      if(!empty($cat_id))
      {
          $resultant=$this->Cat_model->getCategory($cat_id,$admin_id);
          if(!empty($resultant))
          {

            echo json_encode(array('status'=>1,'result'=>$resultant));exit;
          }
          else
          {
            echo json_encode(array('status'=>0,'result'=>'There is no data'));exit;
          }
          
      }else
      {
        echo json_encode(array('status'=>0,'result'=>'Something went wrong'));exit;
      }
    }catch(Eception $e)
    {
      $e->getMessage();
      echo $e->getMessage();
      $error = array('status' =>'0', "result" => "Internal Server Error - Please try Later.","StatusCode"=> "HTTP405");
      echo json_encode($error);exit;
    }
  }
  function updateCategory()
  {
     try
    {
      $cat_id=$_POST['cat_id'];
      $admin_id=$_POST['admin_id'];
      $cat_name=$_POST['cat_name'];
      if(!empty($cat_id))
      {
          $resultant=$this->Cat_model->updateCategory($cat_id,$admin_id,$cat_name);
          if(!empty($resultant))
          {

            echo json_encode(array('status'=>1,'message'=>'Category updated successfully'));exit;
          }
          else
          {
            echo json_encode(array('status'=>0,'message'=>'Category  not updated'));exit;
          }
          
      }else
      {
        echo json_encode(array('status'=>0,'message'=>'Something went wrong'));exit;
      }
    }catch(Eception $e)
    {
      $e->getMessage();
      echo $e->getMessage();
      $error = array('status' =>'0', "message" => "Internal Server Error - Please try Later.","StatusCode"=> "HTTP405");
      echo json_encode($error);exit;
    }
  }
}