<div class="loading">Loading&#8230;</div>
        <div class="right_col" role="main">
          <div class="row">
                              <div class="x_content">
                      <div class="row">
                          <div class="col-sm-12">
                            <div class="card-box table-responsive">
                   
                    <table class="table table-striped table-bordered" id="catListTable" style="width:100%">
                      <thead style="white-space: nowrap;">
                        <tr >
                          <th>Sr.No.</th>
                          <th>Category</th>
                          <th>Restaurant Name</th>
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
<div class="modal fade" id="editCategoryModal" role="dialog"data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
          <input type="hidden" name="admin_id"id="admin_id">
          <input type="hidden" name="status"id="status">
          <input type="hidden" name="cat_id"id="cat_id">
            <div class="modal-header">
              <h3 class="modal-title">Add Category</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
               
            </div>
            <div class="modal-body form">
                <form  class="form-horizontal" method="POST"enctype="multipart/form-data" >
                    <div class="form-body">
                      
                        <div class="form-group row ">
                        <label class="control-label col-md-3 col-sm-3 ">Category Name</label>
                        <div class="col-md-9 col-sm-9 ">
                            <input name="cat_name" id="cat_name" class="form-control" type="text" autocomplete="off" >
                        
                            <span class="help-block"style="color:red"></span>
                        </div>
                      </div>
            </div>
            <div class="modal-footer">
               
               <button type="button" id="btnSubCaregory"class="btn btn-primary">Save</button>

                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
</div>
<script type="text/javascript">
    var table;
  $(document).ready(function() {
    base_url    =$('#base_url').val();
    table       =$('#catListTable').DataTable({ 
        "processing": false,
        "serverSide": true,
        "order": [],
       //  "language": {
       // "infoFiltered": "",
       //  processing:"<img src=<?php echo base_url();?>assets/images/ajax-loader.gif> Please Wait",
       //  search: "",searchPlaceholder:"Search" 

       //  },
        "ajax": {
            "url": base_url+'Cat/getCategoryList',
            "type": "POST",
            "dataType":"JSON",
            "data": function ( data ) {

            }
        },
 
        "columnDefs": [
            { "orderable": false, "targets": [0,4] },
           { "orderable": true, "targets": [1,2,3] },
           ]

    });

});
function change(cat_id,status,admin_id)
{
    var base_url  =$('#base_url').val();
    $.ajax({
        url : base_url+"Cat/change",
        type: "POST",
        data:{"cat_id":cat_id,'change':status,'admin_id':admin_id},
        dataType: "JSON",
        success: function(data)
        {   
          // console.log(data.status);
          if(data.status==1)
          {
             $('#catListTable').DataTable().ajax.reload();
          }
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data');
        }
    });
}
function edit(cat_id,status,admin_id)
{

    var base_url  =$('#base_url').val();
    $('.form-group').removeClass('has-error');
    $('.help-block').empty();
    $('.modal-title').text('Edit Category');
    $.ajax({
        url : base_url+"Cat/getCategory",
        type: "POST",
        data:{"status":status,'admin_id':admin_id,'cat_id':cat_id},
        dataType: "JSON",
        success: function(data)
        {   
          if(data.status==1)
          {
             $('[name="cat_id"]').val(cat_id);
             $('[name="admin_id"]').val(admin_id);
             $('[name="status"]').val(status);
             $('[name="cat_name"]').val(data.result[0]['cat_name']);
             $('#editCategoryModal').modal('show');

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
$('#btnSubCaregory').click(function(){
 var cat_id=$('#cat_id').val();
 var admin_id=$('#admin_id').val();
 var cat_name=$('#cat_name').val();
  $.ajax({
        url : base_url+"Cat/updateCategory",
        type: "POST",
        data:{"cat_name":cat_name,'admin_id':admin_id,'cat_id':cat_id},
        dataType: "JSON",
        success: function(data)
        {   
          if(data.status==1)
          {

              $('#editCategoryModal').modal('hide');
              $('#catListTable').DataTable().ajax.reload();

          }else
          {
            alert(data.message);
          }
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data');
        }
    });

});
</script> 