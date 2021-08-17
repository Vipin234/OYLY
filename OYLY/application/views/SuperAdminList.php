<div class="loading">Loading&#8230;</div>
       <div class="right_col" role="main">
          <button type="button" class="btn btn-primary btn-sm" onclick="signup_modal()">SignUp</button>
          
          <div class="row">
                              <div class="x_content">
                      <div class="row">
                          <div class="col-sm-12">
                            <div class="card-box table-responsive">
                   
                    <table class="table table-striped table-bordered" id="superAdminList" style="width:100%">
                      <thead style="white-space: nowrap;">
                        <tr >
                          <th>Sr.No.</th>
                          <th>Name</th>
                          <th>Email</th>
                          <th>Mobile No</th>
                          <th>Password</th>
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
        <script src="<?php echo base_url()?>/assets/vendors/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>assets/customjs/login.js"></script>
    <script type="text/javascript">
 function change(user_id,status)
 {
 if(user_id  !='')
 {
  var base_url            =$('#base_url').val();
        $('.loading').show();
      $.ajax({
        url:base_url+"SuperAdmin/change",
        type:"POST",
        data:{'user_id':user_id,'status':status},
        dataType:"Json",
        success:function(success)
        { 
          // console.log(success.message);
          if(success.status==1)
          {
             $('#superAdminList').DataTable().ajax.reload();
             $('.loading').hide();
          }else
          {
            alert(success.message);
          }
         
        }

      });
 }else
 {
  alert("Something went wrong");
 }
 }
 function edit(admin_id)
{
    save_method = 'update';
    var base_url  =$('#base_url').val();
    $('.form-group').removeClass('has-error');
    $('.help-block').empty();
    $('.modal-title').text('Edit Admin');
    $('#imageName').val('');
    $.ajax({
        url : base_url+"SuperAdmin/getAdmin",
        type: "POST",
        data:{"admin_id":admin_id},
        dataType: "JSON",
        success: function(data)
        {   
          // console.log(data.status);
          if(data.status==1)
          {
             $('[name="name"]').val(data.message[0]['user_fullname']);
             $('[name="admin_mobile_no"]').val(data.message[0]['mobile_no']);
             $('[name="email_id"]').val(data.message[0]['user_email']);
             $('[name="admi_password"]').val(data.message[0]['password']);
             $('[name="admin_id"]').val(data.message[0]['user_id']);
             $('#signupModal').modal('show');
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
function remove(user_id)
{

 if(confirm("Do you want to remove this admin ?"))
 {
  if(user_id  !='')
 {
        var base_url            =$('#base_url').val();
        $.ajax({
          url:base_url+"SuperAdmin/remove",
          type:"POST",
          data:{'user_id':user_id},
          dataType:"Json",
          success:function(success)
          {
            if(success.status)
            {
               $('#superAdminList').DataTable().ajax.reload();
            }else
            {
              alert(success.message);
            }
           
          }

        });
       }else
       {
        alert("Something went wrong");
       }
 }else
 {
  return false;
 }
}
function signup_modal()
{
    save_method = 'add';
   $('#name').val('');
   $('#admin_mobile_no').val('');
   $('#email_id').val('');
   $('#admi_password').val('');
   $('.form-group').removeClass('has-error');
   $('.help-block').empty();
   $('.modal-title').text('Sign up');
   $('#signupModal').modal('show');

}
function signup()
{
  var base_url            =$('#base_url').val();
  var name                =$('#name').val();
  var admin_mobile_no     =$('#admin_mobile_no').val();
  var email_id            =$('#email_id').val();
  var admin_id            =$('#admin_id').val();
  var admi_password       =$('#admi_password').val();
  if(save_method == 'add') {
    url =base_url+"api/Login/signUp" ;
} else {
    url =base_url+"SuperAdmin/updateAdmin" ;;
}
  $.ajax({
        url :url,
        type: "POST",
        data:{
          "admin_mobile_no":admin_mobile_no,
          "email_id":email_id,
          "name":name,
          "admin_id":admin_id,
          "admi_password":admi_password,
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
                   $('#signupModal').modal('hide');
                   $('#superAdminList').DataTable().ajax.reload();
                  
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
}

</script>
<script type="text/javascript">
  var table;
  $(document).ready(function() {
    base_url    =$('#base_url').val();
    table       =$('#superAdminList').DataTable({ 
        "processing": false,
        "serverSide": true,
        "order": [],
       //  "language": {
       // "infoFiltered": "",
       //  processing:"<img src=<?php echo base_url();?>assets/images/ajax-loader.gif> Please Wait",
       //  search: "",searchPlaceholder:"Search" 

       //  },
        "ajax": {
            "url": base_url+'SuperAdmin/getSuperAdminList',
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

<div class="modal fade" id="signupModal" role="dialog"data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
              <h3 class="modal-title">Sign Up</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
               
            </div>
            <div class="modal-body form">
               <form  id="signup" class="form-horizontal" method="POST">
                   <input type="hidden" name="admin_id"id="admin_id">
                    <div class="form-body">
                      
                        <div class="form-group row ">
                        <label class="control-label col-md-3 col-sm-3 ">Name</label>
                        <div class="col-md-9 col-sm-9 ">
                            <input name="name" id="name" class="form-control" type="text" autocomplete="off" >
                            <span class="help-block"style="color:red"></span>
                        </div>
                      </div>
                      <div class="form-group row ">
                        <label class="control-label col-md-3 col-sm-3 ">Mobile no</label>
                        <div class="col-md-9 col-sm-9 ">
                            <input name="admin_mobile_no" id="admin_mobile_no" class="form-control" type="text" autocomplete="off"disabled="disabled" >
                        
                            <span class="help-block"style="color:red"></span>
                        </div>
                      </div>
                       <div class="form-group row ">
                        <label class="control-label col-md-3 col-sm-3 ">Email Id</label>
                        <div class="col-md-9 col-sm-9 ">
                            <input name="email_id" id="email_id" class="form-control" type="text" autocomplete="off" >
                        
                            <span class="help-block"style="color:red"></span>
                        </div>
                      </div>

                      <div class="form-group row ">
                        <label class="control-label col-md-3 col-sm-3 ">Password</label>
                        <div class="col-md-9 col-sm-9 ">
                           <input name="admi_password" id="admi_password"  class="form-control" type="password"   autocomplete="off">
                            <span class="help-block"style="color:red"></span>
                        </div>
                      </div>
                
            </div>
            <div class="modal-footer">
               
               <button type="button" id="btnSignup" onclick="signup()" class="btn btn-primary">Submit</button>

                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
            </form>
            </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
</div>
