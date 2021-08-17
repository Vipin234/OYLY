<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Convenience extends CI_Controller
{
	
	function __construct() 
    {   
       parent::__construct();
       $this->load->library('session');
       $this->load->library('form_validation'); 
       $this->load->model('Convenience_model'); 
       $this->load->library('encrypt');     
       $this->load->helper('main_helper');
       date_default_timezone_set('Asia/kolkata');    
       if(empty($_SESSION['mobile_no']))
       {
       	redirect(base_url());
       }
    }
    function convenience(){
    	$session_data=$this->session->all_userdata();
  		$this->load->view('header',$session_data);
  		$this->load->view('leftSidebar',$session_data);
  		$this->load->view('ConvenienceFees');
  		$this->load->view('footer');
    }
   function addConvenience(){
   	try{
   		$this->_validate();
   		$convenience=$_POST['convenience'];
   		if(is_numeric($convenience)&& !empty($convenience)){
   				$array=array('convenience'=>$convenience,'status'=>1,'creation_date'=>date('Y-m-d'));
   				$result=$this->Convenience_model->addConvenience($array);
   				if($result){
   						$arry=array('status'=>'1','message'=>'Convenience added successfully.');
             			echo json_encode($arry, 200);exit;
   				}else{
   					$arry=array('status'=>'0','message'=>'Convenience not addedd.');
             		echo json_encode($arry, 200);exit;
   				}
   		}else{
   			$arry=array('status'=>'0','message'=>'Something went wrong');
             echo json_encode($arry, 200);exit;
   		}
   	}catch(Exception $e){
   		 	echo $e->getMessage();
            $error = array('status' =>'0', "data" => "Internal Server Error - Please try Later.","StatusCode"=> "HTTP405");
            echo json_encode($error, 200);exit;
   	}
   }
   function getConvenienceList(){
   	try
    {	
        
		$restaurantList =$this->Convenience_model->get_datatables();
		// print_r($restaurantList);exit;
		$data = array();
		$no = $_POST['start'];
		foreach ($restaurantList as $list) {
			$no++;
			$row = array();
			$row[] 	=$no;
			$row[] 	=$list->convenience;
			$row[] 	=$list->creation_date;
      		$button =$list->status==1?'Active':'Deactive';
			$row[] 	='<div class="row text-center" style="width: max-content;margin-right: auto; margin-left: auto;"><a class="btn btn-sm btn-primary btn-xs" href="javascript:void(0)" title="Edit" onclick="edit('."'".$list->id."'".')"style="height: 33px"><i class="glyphicon glyphicon-eye-open"></i>Edit</a>
		                  <a class="btn btn-info btn-xs" href="javascript:void(0)" title="'.$button.'" onclick="change('."'".$list->id."'".','."'".$list->status."'".')"style="
    height: 33px;
"><i class="glyphicon glyphicon-saved"></i>'.$button.'</a>
		          </div>';
			$data[]	=$row;
		}
		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->Convenience_model->count_all(),
						"recordsFiltered" => $this->Convenience_model->count_filtered(),
						"data" => $data,
				);
	echo json_encode($output);
            
    }catch(Eception $e)
    {
		    echo $e->getMessage();
		    $error = array('status' =>'0', "data" => "Internal Server Error - Please try Later.","StatusCode"=> "HTTP405");
		    $this->response($error, 200);
    }
   }
    public function change()
    {
      
      $id=$_POST['id'];
      $change=$_POST['status'];
      if(!empty($id))
      {

        $result=$this->Convenience_model->changeStatus($id,($change==1?'0':'1'));
        if(!empty($result))
        {
           echo json_encode(array('status'=>1,'result'=>'Updated successfully'));exit;
        }else
        {
           echo json_encode(array('status'=>0,'result'=>'No change'));exit;
        }

      }else
      {
         echo json_encode(array('status'=>0,'result'=>'Somthing went wrong'));exit;
      }
    }

    function getConvenience(){
    	try{
    		$id=$_POST['id'];
    		if(!empty($id)){
    			$result=$this->Convenience_model->getConvenience($id);
	    		if(!empty($result)){
	    			echo json_encode(array('status'=>1,'result'=>$result));exit;
	    		}else{
	    			echo json_encode(array('status'=>0,'result'=>'There is no record'));exit;
	    		}
    		}else{
    			echo json_encode(array('status'=>0,'result'=>'There is no record'));exit;
    		}
    	}catch(Eception $e){
    		
		      echo $e->getMessage();
		      $error = array('status' =>'0', "result" => "Internal Server Error - Please try Later.","StatusCode"=> "HTTP405");
		      echo json_encode($error);exit;
    	}
    }
function updateConvenience(){
		try{
		$this->_validate();
		$id=$_POST['convenience_id'];
		$convenience=$_POST['convenience'];
		$result=$this->Convenience_model->updateConvenience($id,array('convenience'=>$convenience));
		if($result){
			echo json_encode(array('status'=>1,'message'=>'Convenience updated successfully','inputerror'=>array()));exit;
		}else{
			echo json_encode(array('status'=>0,'message'=>'Convenience not updated','inputerror'=>array()));exit;
		}
		}catch(Eception $e){
			 
		      echo $e->getMessage();
		      $error = array('status' =>'0', "message" => "Internal Server Error - Please try Later.","StatusCode"=> "HTTP405");
		      echo json_encode($error);exit;
		}
}

     private function _validate()
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;
 
        if($this->input->post('convenience') == '')
        {
            $data['inputerror'][] = 'convenience';
            $data['error_string'][] = 'Convenience is required';
            $data['status'] = FALSE;
        }
        if($data['status'] === FALSE)
        {
            echo json_encode($data);
            exit();
        }
    }
}