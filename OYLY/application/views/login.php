
<body class="login">
  <div>
    <a class="hiddenanchor" id="signup"></a>
    <div class="login_wrapper">
      <div class="animate form login_form">
        <section class="login_content">
          <form method="post" id="loginform">
            <h1>Login Form</h1>
            <input type="hidden" name="base_url"id="base_url"value="<?php echo base_url();?>index.php/">
            <!-- <div class="form-group">
        
              <select class="form-control" id="usertype" name="usertype">
                <option name="usertype" value="">--Please select any--</option>
                <option name="usertype" value="admin">Admin</option>
                <option name="usertype" value="supervisor">Supervisor</option>
              </select>
            </div> -->
            <br>
            <div class="col-md-12 col-sm-12">
              <input type="text" name="username" id="username"class="form-control login_cls" placeholder="Enter Mobile"autocomplete="off" />
            </div>
            <div class="col-md-12 col-sm-12">
              <input type="password" name="password" id="password" class="form-control login_cls" placeholder="Password"autocomplete="off"/>
              <span class="has-error" id="userErr" style="margin-right: 82px;color:red;"></span>
            </div>              
            <div style="margin-top: 146px;">
              <input type="button" class="btn btn-primary" value="Submit" id="login">
              <!-- <a class="reset_pass" style="text-decoration: none" href="#" onclick="open_modal()">Forget password</a> -->
              <a class="reset_pass" style="text-decoration: none" href="#" onclick="send_mail()">Forget password</a>
            </div>

            <div class="clearfix"></div>
          </form>
        </section>
      </div>

    </div>
  </div>
</body>
   <script src="<?php echo base_url()?>/assets/vendors/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>assets/customjs/login.js"></script>

<style>
  .error{
    color: red;
  }
</style>
<script type="text/javascript">
    function open_modal()
  {
   $('#mobile_no').val('');
   $('#email').val('');
   $('#new_password').val('');
   $('.form-group').removeClass('has-error');
   $('.help-block').empty();
   $('#resetModal').modal('show');
  }
     function resetPassword()
  {  
    var data='';
    var base_url          =$('#base_url').val();
    var mobile_no         =$('#mobile_no').val();
    var email             =$('#email').val();
    var password          =$('#new_password').val();
    $.ajax({
        url : base_url+"api/Login/resetPassword",
        type: "POST",
        data:{
          "mobile_no":mobile_no,
          "email":email,
          "new_password":password,
           },
        dataType: "JSON",
        success: function(data)
        {
            console.log(data);
            if(data.status==true) //if success close modal and reload ajax table
            {
                 if(data.status==1)
                {
                   $('#resetModal').modal('hide');
                  // window.location = base_url;
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
                      console.log($('[name="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error'));
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

<div class="modal fade" id="resetModal" role="dialog"data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
              <h3 class="modal-title">Reset Password</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
               
            </div>
            <div class="modal-body form">
                <form  id="restaurant_details" class="form-horizontal" method="POST">
              
                    <div class="form-body">
                      
                        <div class="form-group row ">
                        <label class="control-label col-md-3 col-sm-3 ">Mobile no</label>
                        <div class="col-md-9 col-sm-9 ">
                            <input name="mobile_no" id="mobile_no" class="form-control" type="text" autocomplete="off" >
                            <span class="help-block"style="color:red"></span>
                        </div>
                      </div>
                      <div class="form-group row ">
                        <label class="control-label col-md-3 col-sm-3 ">Email Id</label>
                        <div class="col-md-9 col-sm-9 ">
                           <input name="email" id="email"  class="form-control" type="text"   autocomplete="off">
                            <span class="help-block"style="color:red"></span>
                        </div>
                      </div>
                      <div class="form-group row ">
                        <label class="control-label col-md-3 col-sm-3 ">New Password</label>
                        <div class="col-md-9 col-sm-9 ">
                           <input name="new_password" id="new_password"  class="form-control" type="password"   autocomplete="off">
                            <span class="help-block"style="color:red"></span>
                        </div>
                      </div>
                
            </div>
            <div class="modal-footer">
               
               <button type="button" id="btnSave" onclick="resetPassword()" class="btn btn-primary">Reset</button>

                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
</div>

<div class="modal fade" id="send_mail" role="dialog"data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="loading" style="display:none">Loading&#8230;</div>

            <div class="modal-header">
              <h3 class="modal-title">Reset Password</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body form">
                <form  id="Otp_form" class="form-horizontal" method="POST">
                <span class="" id="succ_message" style="color:green"></span>
                    <div class="form-body">
                        <div class="form-group row ">
                        <label class="control-label col-md-3 col-sm-3 ">Mobile No.</label>
                        <div class="col-md-9 col-sm-9 ">
                        <input name="mobile_no" id="mobile_num"  class="form-control" type="text"   autocomplete="off">
                            <span class="help-block" id="err_mobile_no" style="color:red"></span>
                        </div>
                      </div>
                
            </div>
            <div class="modal-footer">
               
               <button type="button" id="btn_mail" onclick="verify_mail()" class="btn btn-primary" disabled>Submit</button>

                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
</div>
<script>
  function validate(){
  var username=$('#username').val();
  var password=$('#password').val();
  if((username !='') && (password != '')) 
  {
    $('#login').attr('disabled',false);
  }
  else{
    $('#login').attr('disabled',true);
}
  }
$(document).ready(function(){
    $(".login_cls").keyup(function(){
        validate();
    });
    validate();
});
function send_mail()
  {
   $('#mobile_num').val('');
   $('#err_mobile_no').html('');
   $('#succ_message').html('');
   $('#send_mail').modal('show');
   validate_number();
  }
  $("#mobile_num").keyup(function(){
    validate_number();
    });
    function validate_number(){
    var mobile_num=$('#mobile_num').val();
    if((mobile_num !='')) 
    {
      $('#btn_mail').attr('disabled',false);
    }
    else{
      $('#btn_mail').attr('disabled',true);
  }
    }
     function verify_mail()
  {   
    $('.loading').css('display','block');  
    $('#succ_message').html(''); 
    $('#err_mobile_no').html('');
    var data='';
    var base_url=$('#base_url').val();
    var mobile_num =$('#mobile_num').val();
    if(mobile_num=='')
    {
    $('#err_mobile_no').html('Please enter the Mobile no.')
    }
    
    $.ajax({
        url : base_url+"Supervisor/Api/sendEmail",
        type: "POST",
        data:{
          "mobile_num":mobile_num,
           },
        success: function(data)
        {
          $('.loading').css('display','none');  
            var res=JSON.parse(data);
                 if(res['status']==1)
                {
                $('#succ_message').html('Reset link sent on your registered email.');
                setTimeout(function(){ $('#send_mail').modal('hide'); }, 3000);
                   
                  // window.location = base_url;
                }
            else
            { 
              $('#err_mobile_no').html(res['data']);
            }
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error adding');

        }
        
    });
}
</script>