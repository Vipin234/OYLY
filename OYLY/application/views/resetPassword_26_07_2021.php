<?php
 date_default_timezone_set('Asia/kolkata');
$email=base64_decode($email);
$mobile_no=base64_decode($mobile_no);
$time=base64_decode($time);
$expire_time=date('Y-m-d H:i:s',strtotime($time.'+10 minute'));
$expire_time=strtotime($expire_time);
$now = date('Y-m-d H:i:s');
$current_time=strtotime($now);
if($current_time>$expire_time)
{
  $url=base_url();
  echo "<script>alert('This link has been expired');window.location.href='".$url."';</script>";
}
?>
<body class="login">
  <div>
    <a class="hiddenanchor" id="reset_password"></a>
    <div class="login_wrapper">
      <div class="animate form login_form">
        <section class="login_content">
            <form id="restaurant_details" class="form-horizontal" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="base_url"id="base_url" value="<?php echo base_url();?>index.php/">
                  <input type="hidden" name="mobile_no" id="mobile_no" value="<?php echo $mobile_no; ?>">
                  <input type="hidden" name="email" id="email" value="<?php echo $email; ?>">

                  <span class="" id="succ_message" style="color:green"></span>
                    <div class="form-body">
                      <div class="form-group row">
                        <label class="control-label col-md-3 col-sm-3 ">New password</label>
                        <div class="col-md-9 col-sm-9 ">
                           <input name="new_pass" id="new_pass" class="form-control password" type="text" autocomplete="off">
                            <span class="help-block" id="err_new_pass" style="color:red"></span>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label class="control-label col-md-3 col-sm-3 ">Confirm password</label>
                        <div class="col-md-9 col-sm-9 ">
                           <input name="new_passc" id="new_passc" class="form-control password" type="text" autocomplete="off">
                            <span class="help-block" id="err_new_passc" style="color:red"></span>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label class="control-label col-md-3 col-sm-3 "></label>
                      <div class="col-md-9 col-sm-9">
                      <a href="#" ><button type="button" id="submit" onclick="reset_password()" class="btn btn-primary" disabled>Submit</button></a>
                      <a href="<?php echo base_url();?>" ><button type="button" class="btn btn-primary" >Back to login</button></a>
                      </div>
                    </div>
                    </div>
                
            </form>
        </section>
      </div>

    </div>
  </div>
</body>

<style>
  .error{
    color: red;
  }
</style>
<script>
  function validate(){
    $('#err_new_passc').html('');
  var new_pass=$('#new_pass').val();
  var new_passc=$('#new_passc').val();
  if((new_pass !='') && (new_passc != '')) 
  {
    if(new_pass!=new_passc)
    {
      $('#err_new_passc').html('New password and confirm password should be same.');
      $('#submit').attr('disabled',true);
   }
    else{
      $('#submit').attr('disabled',false);
    }
  }
  else{
    $('#submit').attr('disabled',true);
}
  }
$(document).ready(function(){
    $(".password").keyup(function(){
        validate();
    });
    validate();
});

    function reset_password()
    {      
    var data='';
    var base_url=$('#base_url').val();
    var mobile_no         =$('#mobile_no').val();
    var email             =$('#email').val();
    var password          =$('#new_passc').val();
    console.log(mobile_no);
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
                   $('#succ_message').html("Your password has been successfully changed");
                   setTimeout(function(){ window.location = base_url; }, 3000);
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