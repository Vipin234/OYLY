$(document).ready(function() {
  $('#login').click(function(e) {
    e.preventDefault();
    var base_url=$('#base_url').val();
    // var usertype=$('#usertype').val();
    // if(usertype=='')
    // {
    //   alert('User type is required.');
    //   return false;
    // }
    $.ajax({
       type: "POST",
       url: base_url+'api/Login/login',
       data: $('#loginform').serialize(),
       dataType:"JSON",
       success: function(data)
       {
          
          if(data.status==1)
          {
            window.location = base_url+'Admin/dashboard';
          }else
          {
            alert(data.message);
          }
       }
   });
 });
  $('input').on('change', function(){
  $(this).parent().parent().removeClass('has-error');
  $(this).next().empty(); 
});
});
