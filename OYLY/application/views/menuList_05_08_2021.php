<div class="loading">Loading&#8230;</div>
        <div class="right_col" role="main">
          <div class="row">
                              <div class="x_content">
                      <div class="row">
                          <div class="col-sm-12">
                            <div class="card-box table-responsive">
                   
                    <table class="table table-striped table-bordered" id="menuListTable" style="width:100%">
                      <thead style="white-space: nowrap;">
                        <tr >
                          <th>Sr.No.</th>
                          <th>Menu Name</th>
                          <th>Food Type</th>
                          <th>GST(%)</th>
                          <th>Category</th>
                          <th>Sub Category</th>
                          <th>Image</th>
                          <th>Detail</th>
                          <th>Rating</th>
                          <th>Half price</th>
                          <th>Full price</th>
                          <th>Fixed Price</th>
                          <th>Date</th>
                          <th>Action</th>
                        </tr>
                      </thead>


                      <tbody >
                 
                      </tbody>
                    </table>
                  </div>
                  </div>
              </div>
            </div>
          </div>
        </div> 
  <div class="modal fade" id="MenuModal" role="dialog"data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
              <h3 class="modal-title"></h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
               
            </div>
            <div class="modal-body form">
                <form  class="form-horizontal" method="POST"enctype="multipart/form-data" >
                  <input type="hidden" name="imageName"id="imageName">
                  <input type="hidden" name="admin_id"id="admin_id">
                  <input type="hidden" name="menu_id"id="menu_id">
                    <div class="form-body">
                      
                        <div class="form-group row ">
                        <label class="control-label col-md-3 col-sm-3 ">Menu Name</label>
                        <div class="col-md-9 col-sm-9 ">
                            <input name="menu_name" id="menu_name" class="form-control" type="text" autocomplete="off" >
                        
                            <span class="help-block"style="color:red"></span>
                        </div>
                      </div>
                     
                      <div class="form-group row ">
                        <label class="control-label col-md-3 col-sm-3 ">Menu Detail</label>
                        <div class="col-md-9 col-sm-9 ">
                           <input name="menu_details" id="menu_details"  class="form-control" type="text"   autocomplete="off">
                            <span class="help-block"style="color:red"></span>
                        </div>
                      </div>
                      <div class="form-group row ">
                        <label class="control-label col-md-3 col-sm-3 ">Food Type</label>
                        <div class="col-md-9 col-sm-9 ">
                           <select id="menu_food_type" name="menu_food_type" class="form-control">
                             <option value="">Select Food Type</option>
                             <option value="Veg">Veg</option>
                             <option value="Non-Veg">Non-Veg</option>
                             
                           </select>
                            <span class="help-block"style="color:red"></span>
                        </div>
                      </div>
                      
                       <div class="form-group row ">
                        <label class="control-label col-md-3 col-sm-3 ">Menu Price</label>
                        <div class="col-md-9 col-sm-9 ">
                            <select class="form-control" id="menu_price"name="menu_price" onchange="selectPrice()">
                              <option value="">Select Menu Price</option>
                              <option value="Fixed">Fixed</option>
                              <option value="Half & Full">Half & Full</option>
                            </select>
                            <span class="help-block"style="color:red"></span>
                        </div>
                      </div>
                       <div class="form-group row menuFixedPrice">
                        <label class="control-label col-md-3 col-sm-3 ">Price</label>
                        <div class="col-md-9 col-sm-9 ">
                           <input name="menu_fixed_price" id="menu_fixed_price" class="form-control" type="number" autocomplete="off">
                            <span class="help-block"style="color:red"></span>
                        </div>
                      </div>
                       <div class="form-group row menuFullHalfPrice">
                        <label class="control-label col-md-3 col-sm-3 ">Full Price</label>
                        <div class="col-md-9 col-sm-9 ">
                           <input name="menu_full_price" id="menu_full_price" class="form-control" type="number" autocomplete="off">
                            <span class="help-block"style="color:red"></span>
                        </div>
                      </div>
                      <div class="form-group row menuFullHalfPrice">
                        <label class="control-label col-md-3 col-sm-3 ">Half Price</label>
                        <div class="col-md-9 col-sm-9 ">
                           <input name="menu_half_price" id="menu_half_price" class="form-control" type="number" autocomplete="off" max="4">
                            <span class="help-block"style="color:red"></span>
                        </div>
                      </div>
                      <div class="form-group row ">
                        <label class="control-label col-md-3 col-sm-3 ">Menu Category</label>
                        <div class="col-md-9 col-sm-9 ">
                            <select class="form-control" id="menu_category"name="menu_category">
                              
                            </select>
                            <span class="help-block"style="color:red"></span>
                        </div>
                      </div>
                      <div class="form-group row ">
                        <label class="control-label col-md-3 col-sm-3 ">Menu Sub Category</label>
                        <div class="col-md-9 col-sm-9 ">
                            <select class="form-control" id="menu_sub_category"name="menu_sub_category">
                            </select>
                            <span class="help-block"style="color:red"></span>
                        </div>
                      </div>
                      <div class="form-group row ">
                        <label class="control-label col-md-3 col-sm-3 ">Menu GST</label>
                        <div class="col-md-9 col-sm-9 ">
                            <select class="form-control" id="menu_gst"name="menu_gst">
                              <option value="">Select GST</option>
                            
                            </select>
                            </select>
                            <span class="help-block"style="color:red"></span>
                        </div>
                      </div>
                    
                       <div class="form-group row ">
                        <label class="control-label col-md-3 col-sm-3 ">Menu Image</label>
                        <div class="col-md-9 col-sm-9 ">
                           <input name="menu_image" id="fileupload"  class="form-control" type="file"  autocomplete="off">
                            <span class="help-block"style="color:red"></span>
                        </div>
                      </div>
                    </div>
                
            </div>
            <div class="modal-footer">
               
               <button type="button" id="btnMenuAdd"class="btn btn-primary">Save</button>

                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script type="text/javascript">
    var table;
  $(document).ready(function() {
    base_url    =$('#base_url').val();
    table       =$('#menuListTable').DataTable({ 
        "processing": false,
        "serverSide": true,
        "order": [],
       //  "language": {
       // "infoFiltered": "",
       //  processing:"<img src=<?php echo base_url();?>assets/images/ajax-loader.gif> Please Wait",
       //  search: "",searchPlaceholder:"Search" 

       //  },
        "ajax": {
            "url": base_url+'Menu/getMenuList',
            "type": "POST",
            "dataType":"JSON",
            "data": function ( data ) {

            }
        },
 
        "columnDefs": [
        { 
            "targets": [ -1 ],
            "orderable": false, 
        },
        ],

    });

});
</script>
<script type="text/javascript">
function change(menu_id,menu_status)
{
    var base_url  =$('#base_url').val();
    $.ajax({
        url : base_url+"Menu/change",
        type: "POST",
        data:{"menu_id":menu_id,'change':menu_status},
        dataType: "JSON",
        success: function(data)
        {   
          // console.log(data.status);
          if(data.status==1)
          {
             $('#menuListTable').DataTable().ajax.reload();
          }
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data');
        }
    });
}
function edit(menu_id,admin_id,cat_id,sub_cat_id,menu_category_id)
{
    save_method = 'update';
    var base_url  =$('#base_url').val();
    $('.form-group').removeClass('has-error');
    $('.help-block').empty();
    $('.modal-title').text('Edit Menu');
    $('#fileupload').val();
    $.ajax({
        url : base_url+"Menu/getMenu",
        type: "POST",
        data:{"menu_id":menu_id,'admin_id':admin_id,'cat_id':cat_id,'sub_cat_id':sub_cat_id},
        dataType: "JSON",
        success: function(data)
        {   
          if(data.status==1)
          {
             $('[name="admin_id"]').val(data.result[0]['admin_id']);
             $('[name="menu_name"]').val(data.result[0]['menu_name']);
             $('[name="menu_id"]').val(data.result[0]['menu_id']);
             $('[name="menu_details"]').val(data.result[0]['menu_detail']);
             $('[name="menu_food_type"]').val(data.result[0]['menu_food_type']);
             $('[name="menu_price"]').val(data.result[0]['menu_price_type']);            
             if((data.result[0]['menu_price_type'])=='Half & Full' && (data.result[0]['menu_price_type'] !=''))
             {
              $('[name="menu_full_price"]').val(data.result[0]['menu_full_price']);
              $('[name="menu_half_price"]').val(data.result[0]['menu_half_price']);
              $('.menuFixedPrice').hide();
              $('.menuFullHalfPrice').show();
              $('.menu_half_price').show();
             }
             if(data.result[0]['menu_price_type']=='Fixed' && data.result[0]['menu_price_type'] !='')
             {
              $('[name="menu_fixed_price"]').val(data.result[0]['menu_fix_price']);
              $('.menuFixedPrice').show();
              $('.menuFullHalfPrice').hide();
              $('.menu_half_price').hide();
             }
             
             var html='';
              html +='<option value="">Select Category</option>';
              for($i=0;$i<data.menuList.length;$i++)
              {
                html +='<option value='+data.menuList[$i]['cat_id']+'>'+data.menuList[$i]['cat_name']+'</option>';
              }
              $('#menu_category').html(html);

              var html2='';
              html2 +='<option value="">Select Sub Category</option>';
              for($i=0;$i<data.subCatList.length;$i++)
              {
                html2 +='<option value='+data.subCatList[$i]['sub_cat_id']+'>'+data.subCatList[$i]['sub_cat_name']+'</option>';
              }
              console.log(html2);
               $('#menu_sub_category').html(html2);
               var html3='';
               html3 +='<option value="">Select Sub Category</option>';
              for($i=0;$i<data.gstList.length;$i++)
              {
                html3 +='<option value='+data.gstList[$i]['id']+'>'+data.gstList[$i]['category_name']+'</option>';
              }
              $('#menu_gst').html(html3);
              $('[name="menu_category"]').val(cat_id);
             $('[name="menu_sub_category"]').val(sub_cat_id);
             $('[name="menu_gst"]').val(menu_category_id);
             $('#MenuModal').modal('show');
          }else
          {
            alert("There is no record found");
          }
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data');
        }
    });
}
function selectPrice()
{
  var menu_price=$('#menu_price option:selected').val();
  if(menu_price=='Fixed')
  {
    $('.menuFullHalfPrice').hide();
    $('.menuFixedPrice').show();
  }else if(menu_price=='Half & Full') 
  {
    $('.menuFullHalfPrice').show();
    $('.menuFixedPrice').hide();
  }else
  {
    $('.menuFixedPrice').hide();
    $('.menuFullHalfPrice').hide();
    return false;
  }
}
$('#menu_category').change(function(){

   var cat_id=$('#menu_category option:selected').val();
   var admin_id=$('#admin_id').val();
   if(cat_id !='')
   {
       var base_url            =$('#base_url').val();
       $.ajax({
        url:base_url+'Menu/getSubCategory',
        type:'POST',
        data:{'cat_id':cat_id,'admin_id':admin_id},
        dataType:"Json",
        success:function(result)
        {
           console.log(result);
           if(result.status)
           {
              var html='';
              for(var i=0;i< result.data.length;i++)
              {
                html += '<option value='+result.data[i]['sub_cat_id']+'>'+result.data[i]['sub_cat_name']+'</option>';
              }
              $('#menu_sub_category').html(html);
              
          }else
          {
            $('#menu_sub_category').html('');
            alert(result.data);
            
          }
          /*if(result.gst.status)
          {
            var html2='';
            for(var j=0;j< result.gst.data.length;j++)
            {
              html2 += '<option value='+result.gst.data[j]['id']+'>'+result.gst.data[j]['category_name']+'</option>';
            }
            $('#menu_gst').html(html2); 
          }else
          {
            alert(result.data);
          }*/
          
        }

      });

   }else
   {
    $('#menu_sub_category').html('');
    alert("Please select menu category");return false;
   }
});

$('#btnMenuAdd').click(function(){

  var base_url      =$('#base_url').val();
  var menu_name        =$('#menu_name').val();
  var menu_details     =$('#menu_details').val();
  var menu_price       =$('#menu_price').val();
  var menu_food_type   =$('#menu_food_type').val();
  var menu_full_price  =$('#menu_full_price').val();
  var menu_half_price  =$('#menu_half_price').val();
  var menu_fixed_price =$('#menu_fixed_price').val();
  var menu_category    =$('#menu_category').val();
  var menu_sub_category=$('#menu_sub_category').val();
  var menu_gst         =$('#menu_gst').val();
  var imageName        =$('#imageName').val();
  var admin_id         =$('#admin_id').val();
  var menu_id          =$('#menu_id').val();

  url =base_url+"Menu/updateMenu" ;
  $.ajax({
        url :url,
        type: "POST",
        data:{
          "menu_name":menu_name,
          "menu_food_type":menu_food_type,
          "menu_price":menu_price,
          "menu_full_price":menu_full_price,
          "menu_half_price":menu_half_price,
          "menu_fixed_price":menu_fixed_price,
          "menu_sub_category":menu_sub_category,
          "menu_category":menu_category,
          "menu_gst":menu_gst,
          "admin_id":admin_id,
          "menu_id":menu_id,
          "menu_details":menu_details,
          "imageName":imageName
           },
        dataType: "JSON",
        success: function(data)
        {
            console.log(data);
            if(data.status==true) //if success close modal and reload ajax table
            {    

                 if(data.status==1)
                {
                    alert(data.message);
                   $('#MenuModal').modal('hide');
                   $('#menuListTable').DataTable().ajax.reload();
                }else
                {
                   alert(data.message);
                }

            }
            else
            { 
                if(data.inputerror.length==0)
                {
                  alert(data.message);
                }else
                {
                  for (var i = 0; i < data.inputerror.length; i++)
                  {
                      $('[name="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
                      $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                  }
                }
                
            }

            $('#btnMenuAdd').text('save'); //change button text
            $('#btnMenuAdd').attr('disabled',false); //set button enable
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error adding');
            $('#btnMenuAdd').text('save'); //change button text
            $('#btnMenuAdd').attr('disabled',false); //set button enable

        }
        
    });
});
$('#fileupload').on('change',function(){
    var oInput        =$(this)[0].files[0];
    var base_url      =$('#base_url').val();
    var sFileName     =oInput['name'];
    var percent       =$('#percent_fileupload');
                      percent.show();
    var confirm_alert = confirm('Are you sure you want to upload this image');
    if(confirm_alert){
    var files = $(this)[0].files[0];
    var reader = new FileReader();
    reader.onloadend = function() {     
    var formData = new FormData();
    formData.append('image', reader.result);
    sFileName.split('.');
    var get_ext = sFileName.split('.');
    var ext = get_ext.slice(-1)[0];
    formData.append('ext', ext);
    $.ajax({
      url:base_url+"Admin/getImageName",
      xhr: function() {
                    var xhr = $.ajaxSettings.xhr();
                    xhr.upload.onprogress = function(e) {
                    count = Math.floor(e.loaded / e.total *100) + '%';
                    percent.html(count);
                    };
                    return xhr;
                 },
      type:'post',
      cache: false,
      contentType: false,
      processData: false,
      data:formData,
      dataType:"JSON",
      success:function(response){
        $('#imageName').val(response);
        count = '0' + '%';
        percent.html(count);
        percent.fadeOut(2000);
      }
     
    });
    }
    reader.readAsDataURL(files);
           
      }else{
        $(this).val(null);
      }
     
});
</script>