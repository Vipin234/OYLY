<div class="loading">Loading&#8230;</div>
        <div class="right_col" role="main">
          <div class="row">
                              <div class="x_content">
                      <div class="row">
                          <div class="col-sm-12">
                            <div class="card-box table-responsive">
                   
                    <table class="table table-striped table-bordered" id="staffListTable" style="width:100%">
                      <thead style="white-space: nowrap;">
                        <tr >
                          <th>Sr.No.</th>
                          <th>Name</th>
                         <!--  <th>Email</th>
                          <th>Mobile No</th>
                          <th>Password</th>
                          <th>UAID No</th>
                          <th>PAN NO</th> -->
                          <th>Image</th>
                          <th>Doc</th>
                          <th>Address</th>
                          <th>Restaurant</th>
                          <th>Date</th>
                          <th>Role</th>
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



<script type="text/javascript">
    $(document ).ready(function() {
     $('input').on('change', function(){
    $(this).parent().parent().removeClass('has-error');
    $(this).next().empty(); 
});
$("select").change(function(){
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });
});
</script>
<script type="text/javascript">
  var table;
  $(document).ready(function() {
    base_url    =$('#base_url').val();
    table       =$('#staffListTable').DataTable({ 
        "processing": false,
        "serverSide": true,
        "order": [],
       //  "language": {
       // "infoFiltered": "",
       //  processing:"<img src=<?php echo base_url();?>assets/images/ajax-loader.gif> Please Wait",
       //  search: "",searchPlaceholder:"Search" 

       //  },
        "ajax": {
            "url": base_url+'StaffListController/getStaffList',
            "type": "POST",
            "dataType":"JSON",
            "data": function ( data ) {

            }
        },
 
        "columnDefs": [
            { "orderable": false, "targets": [0,2,3,7,8] },
           { "orderable": true, "targets": [1,4,5,6] },
           ]

    });

});
function change(id,staffStatus,admin_id)
{
  
    var base_url  =$('#base_url').val();
    $.ajax({
        url : base_url+"StaffListController/change",
        type: "POST",
        data:{"id":id,'status':staffStatus,'admin_id':admin_id},
        dataType: "JSON",
        success: function(data)
        {   
          // console.log(data.status);
          if(data.status==1)
          {
             $('#staffListTable').DataTable().ajax.reload();
          }
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data');
        }
    });
}
function edit(id)
{
    save_method = 'update';
    var base_url  =$('#base_url').val();
    $('.form-group').removeClass('has-error');
    $('.help-block').empty();
    $('.modal-title').text('Edit Staff');
    $.ajax({
        url : base_url+"StaffListController/getStaff",
        type: "POST",
        data:{"id":id},
        dataType: "JSON",
        success: function(data)
        {   
        console.log(data);
          if(data.status==1)
          {
            $('[name="id"]').val(data.message[0]['id']);
             $('[name="admin_id"]').val(data.message[0]['admin_id']);
             $('[name="rest_staff_name"]').val(data.message[0]['name']);
             $('[name="staff_mobile_no"]').val(data.message[0]['mobile_no']);
             $('[name="staff_email"]').val(data.message[0]['email']);
             $('[name="staff_dob"]').val(data.message[0]['date_of_birth']);
             $('[name="staff_Gender"]').val(data.message[0]['gender']);
             $('[name="staff_password"]').val(data.message[0]['password']);
             $('[name="staff_role"]').val(data.message[0]['desingination']);
             $('[name="staff_uaid_no"]').val(data.message[0]['aadhar_no']);
             $('[name="staff_pan_no"]').val(data.message[0]['pan_number']);
             $('[name="staff_address"]').val(data.message[0]['permanent_address']);
             $('[name="staff_cur_address"]').val(data.message[0]['current_address']);
             $('.block_mobile').css('display','none');
             $('#StaffList').modal('show');


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

</script> 
<div class="modal fade" id="StaffList" role="dialog"data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
              <h3 class="modal-title"></h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
               
            </div>
            <div class="modal-body form">
                <form  id="staff_deatils" class="form-horizontal" method="POST"enctype="multipart/form-data" >
                 <input type="hidden" name="resto_staff_image"id="resto_staff_image">
                  <input type="hidden" name="resto_staff_document"id="resto_staff_document">
                  <input type="hidden" name="id" id="id">
                    <div class="form-body">
                      
                        <div class="form-group row ">
                        <label class="control-label col-md-3 col-sm-3 ">Name</label>
                        <div class="col-md-9 col-sm-9 ">
                            <input name="rest_staff_name" id="rest_staff_name" class="form-control" type="text" autocomplete="off" >
                        
                            <span class="help-block"style="color:red"></span>
                        </div>
                      </div>
                     
                      <div class="form-group row  block_mobile">
                        <label class="control-label col-md-3 col-sm-3">Mobile No</label>
                        <div class="col-md-9 col-sm-9 ">
                           <input name="staff_mobile_no" id="staff_mobile_no"  class="form-control" type="text"   autocomplete="off">
                            <span class="help-block"style="color:red"></span>
                        </div>
                      </div>
                      <div class="form-group row ">
                        <label class="control-label col-md-3 col-sm-3 ">Email Id</label>
                        <div class="col-md-9 col-sm-9 ">
                           <input name="staff_email"id="staff_email"  class="form-control" type="text"  autocomplete="off">
                            <span class="help-block"style="color:red"></span>
                        </div>
                      </div>
                      <div class="form-group row ">
                        <label class="control-label col-md-3 col-sm-3 ">DOB</label>
                        <div class="col-md-9 col-sm-9 ">
                           <input name="staff_dob"id="staff_dob"  class="form-control" type="date"  autocomplete="off">
                            <span class="help-block"style="color:red"></span>
                        </div>
                      </div>
                       <div class="form-group row ">
                        <label class="control-label col-md-3 col-sm-3 ">Gender</label>
                        <div class="col-md-9 col-sm-9 ">
                            <select class="form-control" id="staff_Gender"name="staff_Gender">
                              <option value="">Select Gender</option>
                              <option value="Male">Male</option>
                              <option value="Fmale">Female</option>
                            </select>
                            <span class="help-block"style="color:red"></span>
                        </div>
                      </div>
                      <div class="form-group row ">
                        <label class="control-label col-md-3 col-sm-3 ">Password</label>
                        <div class="col-md-9 col-sm-9 ">
                           <input type ="password"name="staff_password" id="staff_password"  class="form-control" type="text"  autocomplete="off">
                            <span class="help-block"style="color:red"></span>
                        </div>
                      </div>
                      <div class="form-group row ">
                        <label class="control-label col-md-3 col-sm-3 ">Role</label>
                        <div class="col-md-9 col-sm-9 ">
                            <select class="form-control" id="staff_role"name="staff_role">
                              <option value="">Select Role</option>
                              <option value="Waiter">Waiter</option>
                              <option value="KOT">KOT</option>
                              <option value="Supervisor">Supervisor</option>
                              <option value="Chef">Chef</option>
                              <option value="Cashier">Cashier</option>
                            </select>
                            <span class="help-block"style="color:red"></span>
                        </div>
                      </div>
                      <div class="form-group row ">
                        <label class="control-label col-md-3 col-sm-3 ">UAID No</label>
                        <div class="col-md-9 col-sm-9 ">
                          <input name="staff_uaid_no" id="staff_uaid_no" class="form-control" type="text"  autocomplete="off">
                            <span class="help-block"style="color:red"></span>
                        </div>
                      </div>
                      <div class="form-group row ">
                        <label class="control-label col-md-3 col-sm-3 ">Pan No</label>
                        <div class="col-md-9 col-sm-9 ">
                           <input name="staff_pan_no" id="staff_pan_no"  class="form-control" type="text"  autocomplete="off">
                            <span class="help-block"style="color:red"></span>
                        </div>
                      </div>
                      <div class="form-group row ">
                        <label class="control-label col-md-3 col-sm-3 ">Permanent Address</label>
                        <div class="col-md-9 col-sm-9 ">
                           <input name="staff_address" id="staff_address"  class="form-control" type="text"  autocomplete="off">
                            <span class="help-block"style="color:red"></span>
                        </div>
                      </div>
                       <div class="form-group row ">
                        <label class="control-label col-md-3 col-sm-3 ">Current Address</label>
                        <div class="col-md-9 col-sm-9 ">
                           <input name="staff_cur_address" id="staff_cur_address"  class="form-control" type="text"  autocomplete="off">
                            <span class="help-block"style="color:red"></span>
                        </div>
                      </div>
                      <div class="form-group row ">
                        <label class="control-label col-md-3 col-sm-3 ">Image</label>
                        <div class="col-md-9 col-sm-9 ">
                           <input name="staff_image" id="staff_image"  class="form-control" type="file"  autocomplete="off">
                             <span id="percent_fileupload_img"></span>
                        </div>
                      </div>
                      <div class="form-group row ">
                        <label class="control-label col-md-3 col-sm-3 ">Document</label>
                        <div class="col-md-9 col-sm-9 ">
                           <input name="staff_document" id="staff_document"  class="form-control" type="file"  autocomplete="off">
                            <span id="percent_fileupload_doc"></span>
                        </div>
                      </div>
                    </div>
                
            </div>
            <div class="modal-footer">
               
               <button type="button" id="btnAddStaff"class="btn btn-primary">Save</button>

                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script type="text/javascript">
  $('#btnAddStaff').click(function(){

   $('.loading').show(); 
  var base_url            =$('#base_url').val();
  var name                =$('#rest_staff_name').val();
  var admin_mobile_no     =$('#staff_mobile_no').val();
  var email_id            =$('#staff_email').val();
  var dob                 =$('#staff_dob').val();
  var gender              =$('#staff_Gender').val();
  var password            =$('#staff_password').val();
  var uaid_no             =$('#staff_uaid_no').val();
  var pan_no              =$('#staff_pan_no').val();
  var address             =$('#staff_address').val();
  var role                =$('#staff_role').val();
  var staff_cur_address   =$('#staff_cur_address').val();
  var resto_staff_image   =$('#resto_staff_image').val();
  var resto_staff_document=$('#resto_staff_document').val();
  var admin_id            =$('#admin_id').val();
  var id                  =$('#id').val();

  if(save_method == 'add') {
    url =base_url+"StaffListController/addStaff" ;
    } else {
        url =base_url+"StaffListController/updateStaff" ;
    }
  $.ajax({
        url :url,
        type: "POST",
        data:{
          "staff_mobile_no":admin_mobile_no,
          "staff_email":email_id,
          "staff_dob":dob,
          "rest_staff_name":name,
          "staff_Gender":gender,
          "staff_password":password,
          "resto_staff_document":resto_staff_document,
          "resto_staff_image":resto_staff_image,
          "staff_uaid_no":uaid_no,
          "staff_pan_no":pan_no,
          "staff_role":role,
          "staff_cur_address":staff_cur_address,
          "admin_id":admin_id,
          "staff_address":address,
          "id":id
           },
        dataType: "JSON",
        success: function(data)
        {
            console.log(data);
             $('.loading').hide(); 
            if(data.status==true) //if success close modal and reload ajax table
            {    

                 if(data.status==1)
                {
                    alert(data.message);
                   $('#StaffList').modal('hide');
                   $('#staffListTable').DataTable().ajax.reload();
                  
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

            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error adding');
            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable

        }
        
    });
});
    $('#staff_image').on('change',function(){
    var oInput        =$(this)[0].files[0];
    var base_url      =$('#base_url').val();
    var sFileName     =oInput['name'];
    var percent       =$('#percent_fileupload_img');
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
        $('#resto_staff_image').val(response);
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
  $('#staff_document').on('change',function(){
    var oInput        =$(this)[0].files[0];
    var base_url      =$('#base_url').val();
    var sFileName     =oInput['name'];
    var percent       =$('#percent_fileupload_doc');
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
        $('#resto_staff_document').val(response);
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