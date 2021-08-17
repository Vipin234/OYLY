$(document).ready(function() {
  $('#login').click(function(e) {
    $('#userErr').html('');
    e.preventDefault();
    var base_url=$('#base_url').val();
    var username=$('#username').val();
    var password=$('#password').val();
    if(username=='' || password=='')
    {
      alert('User Name and Password is required.');
      $('#userErr').html('User Name and Password is required.');
      return false;
    }
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
            $('#userErr').html(data.message);
          }
       }
   });
 });
  $('input').on('change', function(){
  $(this).parent().parent().removeClass('has-error');
  $(this).next().empty(); 
});
});
