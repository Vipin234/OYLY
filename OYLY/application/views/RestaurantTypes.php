<div class="loading">Loading&#8230;</div>
        <div class="right_col" role="main">
          <button type="button" class="btn btn-primary btn-sm" onclick="open_modal()"><i class="fa fa-plus" aria-hidden="true"></i></button>
          <div class="row">
                              <div class="x_content">
                      <div class="row">
                          <div class="col-sm-12">
                            <div class="card-box table-responsive">
                   
                    <table class="table table-striped table-bordered" id="RestaurantTypesTable" style="width:100%">
                      <thead style="white-space: nowrap;">
                        <tr >
                          <th>Sr.No.</th>
                          <th>Type</th>
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
<script type="text/javascript">
    var table;
    var save_method;
  $(document).ready(function() {
    base_url    =$('#base_url').val();
    table       =$('#RestaurantTypesTable').DataTable({ 
        "processing": false,
        "serverSide": true,
        "order": [],
        "ajax": {
            "url": base_url+'RestaurantType/getTypesList',
            "type": "POST",
            "dataType":"JSON",
            "data": function ( data ) {

            }
        },
 
        "columnDefs": [
        { 
            "targets": [ 0,-1 ],
            "orderable": false, 
        },
        ],

    });
  $('input').on('change', function(){
  $(this).parent().parent().removeClass('has-error');
  $(this).next().empty(); 
});
});
function open_modal(){
            save_method = 'add';
           $('#type_name').val('');
           $('.form-group').removeClass('has-error');
           $('.help-block').empty();
           $('.modal-title').text('Add Type');
           $('#types_modal').modal('show');
          }
function addTypes()
{
  $('.loading').show();
  var base_url            =$('#base_url').val();
  var type_name           =$('#type_name').val();
  var type_id             =$('#type_id').val();
  if(save_method == 'add') {
    url =base_url+"RestaurantType/addTypes" ;
} else {
    url =base_url+"RestaurantType/updateTypes" ;
}
  $.ajax({
        url :url,
        type: "POST",
        data:{
          "type_name":type_name,
          "type_id":type_id
           },
        dataType: "JSON",
        success: function(data)
        {
           $('.loading').hide();

            if(data.status==true) //if success close modal and reload ajax table
            {    
                
                 if(data.status==1)
                {
                    alert(data.message);
                   $('#types_modal').modal('hide');
                   $('#RestaurantTypesTable').DataTable().ajax.reload();
                  
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
 function change(id,status)
 {
 if(id  !='')
 {
  var base_url            =$('#base_url').val();
 
        $('.loading').show();
      $.ajax({
        url:base_url+"RestaurantType/change",
        type:"POST",
        data:{'id':id,'status':status},
        dataType:"Json",
        success:function(success)
        { 
          // console.log(success.message);
          if(success.status==1)
          {
             $('#RestaurantTypesTable').DataTable().ajax.reload();
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
function edit(id)
{
    
    var base_url  =$('#base_url').val();
    $('.form-group').removeClass('has-error');
    $('.help-block').empty();
    $('.modal-title').text('Edit Type');
    $('.loading').show();
    $.ajax({
        url : base_url+"RestaurantType/getTypes",
        type: "POST",
        data:{"id":id},
        dataType: "JSON",
        success: function(data)
        {   
          save_method = 'update';
          $('.loading').hide();
          if(data.status==1)
          {
             $('[name="type_id"]').val(id);
             $('[name="type_name"]').val(data.result[0]['name']);
             $('#types_modal').modal('show');
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
<div class="modal fade" id="types_modal" role="dialog"data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
              <h3 class="modal-title"></h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
               
            </div>
            <div class="modal-body form">
                <form  id="" class="form-horizontal" method="POST">
                  <input type="hidden" name="type_id"id="type_id">
                    <div class="form-body">
                    
                        <div class="form-group row ">
                        <label class="control-label col-md-3 col-sm-3 ">Name</label>
                        <div class="col-md-9 col-sm-9 ">
                            <input name="type_name" id="type_name" class="form-control" type="text" autocomplete="off" >
                            <span class="help-block"style="color:red"></span>
                        </div>
                      </div>
                      
            </div>
            <div class="modal-footer">
               
               <button type="button" id="btnSave" onclick="addTypes()" class="btn btn-primary">Save</button>

                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->