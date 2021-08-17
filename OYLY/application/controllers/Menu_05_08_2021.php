<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Menu extends CI_Controller
{
	
	function __construct() 
    {   
       parent::__construct();
       $this->load->library('session');
       $this->load->library('form_validation'); 
       $this->load->library('encrypt');     
       $this->load->model('Login_model'); 
       $this->load->model('Admin_model');
       $this->load->model('SuperAdmin_model');
       $this->load->model('StaffList_model');
       $this->load->model('Menu_model');
       $this->load->helper('main_helper');   
       if(empty($_SESSION['mobile_no']))
       {
       	redirect(base_url());
       }
    }

    public function menu()
    {
    	$session_data=$this->session->all_userdata();
      // $result['misc_category']=$this->Menu_model->getMenuCategory();
  		$this->load->view('header',$session_data);
  		$this->load->view('leftSidebar',$session_data);
  		$this->load->view('menuList');
  		$this->load->view('footer');
    }
    public function getMenuList()
    {
		   try
		    {	
				$restaurantList =$this->Menu_model->get_datatables();
				// echo '<pre>';print_r($restaurantList);exit;
				$data = array();
				$no = $_POST['start'];
				foreach ($restaurantList as $list) {
					$no++;
					$row = array();
					$row[] 	=$no;
					$row[] 	=$list->menu_name;
					$row[] 	=$list->menu_food_type;
          $gst    =$this->Menu_model->getGst($list->menu_category_id);
					$row[] 	=!empty($gst[0]['gst'])?$gst[0]['gst']:'';
          $cat    =$this->Menu_model->getCat($list->cat_id);
          $row[]  =$cat[0]['cat_name'];
          $subcat =$this->Menu_model->getSubCat($list->sub_cat_id,$list->cat_id);
          // echo '<pre>';print_r($subcat[0]);exit;
					$row[]  =!empty($subcat)?$subcat[0]['sub_cat_name']:"";
					$row[] 	= !empty($list->menu_image)?'<a href="'.base_url().'uploads/'.$list->menu_image.'"target="_blank">view</a>':'';
					$row[] 	=$list->menu_detail;
					$row[] 	=$list->rating;
					$row[] 	=$list->menu_half_price;
					$row[] 	=$list->menu_full_price;
					$row[] 	=$list->menu_fix_price;
					$row[] 	=$list->create_date;
					// $row[] 	=$list->status=='1'?'Active':'In Active';
		      $button =$list->status==1?'Active':'Deactive';
					$row[] 	='<div class="row text-center" style="width: max-content;margin-right: auto; margin-left: auto;"><a class="btn btn-sm btn-primary btn-xs" href="javascript:void(0)" title="Edit" onclick="edit('."'".$list->menu_id."'".','."'".$list->admin_id."'".','."'".$list->cat_id."'".','."'".$list->sub_cat_id."'".','."'".$list->menu_category_id."'".')"style="height: 33px"><i class="glyphicon glyphicon-eye-open"></i>Edit</a>
				                  <a class="btn btn-info btn-xs" href="javascript:void(0)" title="'.$button.'" onclick="change('."'".$list->menu_id."'".','."'".$list->status."'".')"style="height: 33px;"><i class="glyphicon glyphicon-saved"></i>'.$button.'</a>
				                  <a class="btn btn-danger btn-xs" href="javascript:void(0)" title="Delete" onclick="remove('."'".$list->menu_id."'".')"style="height: 33px;display:none;"><i class="glyphicon glyphicon-deleted"></i>Delete</a></div>';
					$data[]	=$row;
				}
				$output = array(
								"draw" => $_POST['draw'],
								"recordsTotal" => $this->Menu_model->count_all(),
								"recordsFiltered" => $this->Menu_model->count_filtered(),
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
  public function addMenu()
  {
  	try
  	{
  		 $this->_validate();
  		$menu_name 			                    =$_POST['menu_name'];
  		$menu_food_type 			              =$_POST['menu_food_type'];
  		$menu_price 				                =$_POST['menu_price'];
  		$menu_full_price 			              =$_POST['menu_full_price'];
  		$menu_half_price 			              =$_POST['menu_half_price'];
  		$menu_fixed_price 			            =$_POST['menu_fixed_price'];
  		$admin_id 			                    =$_POST['admin_id'];
  		$menu_sub_category 			            =$_POST['menu_sub_category'];
      $menu_category                      =$_POST['menu_category'];
      $menu_gst                           =$_POST['menu_gst'];
      $menu_detail                        =$_POST['menu_details'];
      $imageName                          =$_POST['imageName'];
      if(!empty($imageName))
      {
      $t                        =time()."".date('Ymd');
      $path                     ='uploads/';
      $image_parts              =explode(";base64,",$imageName);
      $image_base64             =base64_decode($image_parts[1]);
      $menu_image                 ='menu'."_".$t.".jpeg";
      $file                     ='uploads/'.$menu_image;
      file_put_contents($file, $image_base64);
      }else
      {
        $menu_image='';
      }
      $menu_id                            ='MENU_0000'.$this->Menu_model->getMenuMax();
      $array=array(
          'admin_id'=>$admin_id,
          'menu_id'=>$menu_id,
          'menu_category_id'=>$menu_gst,
          'cat_id'=>$menu_category,
          'sub_cat_id'=>$menu_sub_category,
          'menu_food_type'=>$menu_food_type,
          'menu_price_type'=>$menu_price,
          'menu_name'=>$menu_name,
          'menu_image'=>$menu_image,
          'menu_detail'=>$menu_detail,
          'menu_half_price'=>$menu_half_price,
          'menu_full_price  '=>$menu_full_price,
          'menu_fix_price'=>$menu_fixed_price,
          'create_date'=>date('Y-m-d'),
          'status'=>1
        );
      // print_r($array);exit;
  		$result  =$this->Menu_model->addMenu($array);
  		if(!empty($result))
  		{
  			$arry=array('status'=>'1','message'=>'Menu added successfully','inputerror'=>array());
             echo json_encode($arry);exit;
  		}else
  		{
  			$arry=array('status'=>'0','message'=>'Menu not added','inputerror'=>array());
             echo json_encode($arry);exit;
  		}

  	}catch(Eception $e)
  	{
  		$e->getMessage();
		$error = array('status' =>'0', "data" => "Internal Server Error - Please try Later.","StatusCode"=> "HTTP405");
		echo json_encode($error, 200);exit;
  	}
  }
public function change()
{
	try
	{
		$menu_id =$_POST['menu_id'];
		$status  =$_POST['change'];
		if(!empty($menu_id))
		{

			$result=$this->Menu_model->change($menu_id,($status==1?'0':'1'));
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
 public function getMenu()
  {
   
    try
    {
      $menu_id=$_POST['menu_id'];
      $admin_id=$_POST['admin_id'];
      $cat_id=$_POST['cat_id'];
      if(!empty($menu_id))
      {
          $resultant=$this->Menu_model->getMenu($menu_id);
          if(!empty($resultant))
          {
            $categoryresult=$this->Menu_model->getMenuCategory($admin_id);
            $menuGst=$this->Menu_model->getMenuGST($admin_id);
            $getSubCat=$this->Menu_model->getMenuSubCategory($cat_id,$admin_id);
            echo json_encode(array('status'=>1,'result'=>$resultant,'menuList'=>$categoryresult,'subCatList'=>$getSubCat,'gstList'=>$menuGst));exit;
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
   public function updateMenu()
    {
      try
    {
      $this->_validate();
      $menu_image=array();
      $menu_name                          =$_POST['menu_name'];
      $menu_food_type                     =$_POST['menu_food_type'];
      $menu_price                         =$_POST['menu_price'];
      $admin_id                           =$_POST['admin_id'];
      $menu_sub_category                  =$_POST['menu_sub_category'];
      $menu_category                      =$_POST['menu_category'];
      $menu_gst                           =$_POST['menu_gst'];
      $menu_detail                        =$_POST['menu_details'];
      $imageName                          =$_POST['imageName'];
      $menu_id                            =$_POST['menu_id'];
      if($menu_price=='Fixed')
      {
         $menu_fixed_price                   =$_POST['menu_fixed_price'];
         $menu_full_price                    ='';
         $menu_half_price                    ='';
      }elseif($menu_price=='Half & Full')
      {
         $menu_fixed_price                   ='';
         $menu_full_price                    =$_POST['menu_full_price'];
         $menu_half_price                    =$_POST['menu_half_price'];
      }
      if(!empty($imageName))
      {
      $t                        =time()."".date('Ymd');
      $path                     ='uploads/';
      $image_parts              =explode(";base64,",$imageName);
      $image_base64             =base64_decode($image_parts[1]);
      $menu_image                ='menu'."_".$t.".jpeg";
      $file                     ='uploads/'.$menu_image;
      file_put_contents($file, $image_base64);
      $menu_image               =array('menu_image'=>$menu_image);
      }
      $array=array(
          'menu_category_id'=>$menu_gst,
          'cat_id'=>$menu_category,
          'sub_cat_id'=>empty($menu_sub_category)?'NA':$menu_sub_category,
          'menu_food_type'=>$menu_food_type,
          'menu_price_type'=>$menu_price,
          'menu_name'=>$menu_name,
          'menu_detail'=>$menu_detail,
          'menu_half_price'=>$menu_half_price,
          'menu_full_price  '=>$menu_full_price,
          'menu_fix_price'=>$menu_fixed_price,
          'modified_date'=>date('Y-m-d'),
          'status'=>1
        );
      // print_r($array);exit;
        if(!empty($menu_id))
        {
          $result  =$this->Menu_model->updateMenu(($array+$menu_image),$menu_id);
        }else
        {
           $arry=array('status'=>'0','message'=>'Something went wrong','inputerror'=>array());
               echo json_encode($arry);exit;
        }
      if(!empty($result))
      {
        $arry=array('status'=>'1','message'=>'Menu updated successfully','inputerror'=>array());
             echo json_encode($arry);exit;
      }else
      {
        $arry=array('status'=>'0','message'=>'Menu not updated','inputerror'=>array());
             echo json_encode($arry);exit;
      }

    }catch(Eception $e)
    {
      $e->getMessage();
    $error = array('status' =>'0', "data" => "Internal Server Error - Please try Later.","StatusCode"=> "HTTP405");
    echo json_encode($error, 200);exit;
    }

    }
  public function getSubCategory()
  {
    $cat_id=$_POST['cat_id'];
    $admin_id=$_POST['admin_id'];

    if(!empty($cat_id) && !empty($admin_id))
    {
      $result=$this->Menu_model->getMenuSubCategory($cat_id,$admin_id);
      if(!empty($result))
      {
        $gst=$this->getMenuGst($admin_id);

        echo json_encode(array('status'=>1,'data'=>$result,'gst'=>$gst));exit;
      }else
      {
        echo json_encode(array('status'=>0,'data'=>'Sub Category not found'));exit;
      }
    }else
    {
      echo json_encode(array('status'=>0,'data'=>'Please select category'));exit;
    }
  }
    public function getMenuGst($admin_id)
  {
    if(!empty($admin_id))
    {
      $result=$this->Menu_model->getMenuGST($admin_id);
      if(!empty($result))
      {
        return array('status'=>1,'data'=>$result);
      }else
      {
       return array('status'=>0,'data'=>'GST not defined');
      }
    }else
    {
      return array('status'=>0,'data'=>'Something went wrong');
    }
  }

     private function _validate()
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;
 
        if($this->input->post('menu_name') == '')
        {
            $data['inputerror'][] = 'menu_name';
            $data['error_string'][] = 'Menu name is required';
            $data['status'] = FALSE;
        }

        if($this->input->post('menu_food_type') == '')
        {
            $data['inputerror'][] = 'menu_food_type';
            $data['error_string'][] = 'Food type is required';
            $data['status'] = FALSE;
        }
        if($this->input->post('menu_price') == '')
        {
            $data['inputerror'][] = 'menu_price';
            $data['error_string'][] = 'Price is required';
            $data['status'] = FALSE;
        }
        // if($this->input->post('menu_sub_category') == '')
        // {
        //     $data['inputerror'][] = 'menu_sub_category';
        //     $data['error_string'][] = 'Sub Category is required';
        //     $data['status'] = FALSE;
        // }
        if($this->input->post('menu_category') == '')
        {
            $data['inputerror'][] = 'menu_category';
            $data['error_string'][] = 'Category is required';
            $data['status'] = FALSE;
        }
        if($this->input->post('menu_gst') == '')
        {
            $data['inputerror'][] = 'menu_gst';
            $data['error_string'][] = 'GST is required';
            $data['status'] = FALSE;
        }
        if($this->input->post('menu_details') == '')
        {
            $data['inputerror'][] = 'menu_details';
            $data['error_string'][] = 'Menu details is required';
            $data['status'] = FALSE;
        }
 
        if($data['status'] === FALSE)
        {
            echo json_encode($data);
            exit();
        }
    }
}


?>