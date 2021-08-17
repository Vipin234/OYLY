<div class="loading">Loading&#8230;</div>
        <div class="right_col" role="main">
          <div class="row">
                              <div class="x_content">
                      <div class="row">
                          <div class="col-sm-12">
                            <div class="card-box table-responsive">
                   
                    <table class="table table-striped table-bordered" id="subCatListTable" style="width:100%">
                      <thead style="white-space: nowrap;">
                        <tr >
                          <th>Sr.No.</th>
                          <th>Catgory Name</th>
                          <th>Sub Catgory Name</th>
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

            <div class="modal-header">
              <h3 class="modal-title">Edit Category</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
               
            </div>
            <div class="modal-body form">
                <form  class="form-horizontal" method="POST"enctype="multipart/form-data" >
                    <div class="form-body">
                      <input type="hidden" name="admin_id"id="admin_id">
                      <input type="hidden" name="sub_cat_id"id="sub_cat_id">
                        <div class="form-group row ">
                        <label class="control-label col-md-3 col-sm-3 ">Category Name</label>
                        <div class="col-md-9 col-sm-9 ">
                            <select class="form-control"name="cat_id"id="cat_id">
                              
                            </select>
                        
                            <span class="help-block"style="color:red"></span>
                        </div>
                      </div>
                      <div class="form-group row ">
                        <label class="control-label col-md-3 col-sm-3 ">Sub Category Name</label>
                        <div class="col-md-9 col-sm-9 ">
                            <input type="text" class="form-control"name="sub_cat_name"id="sub_cat_name" />

                            <span class="help-block"style="color:red"></span>
                        </div>
                      </div>
            </div>
            <div class="modal-footer">
               
               <button type="button" id="btnSubCaregory" class="btn btn-primary">Save</button>

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
    table       =$('#subCatListTable').DataTable({ 
        "processing": false,
        "serverSide": true,
        "order": [],
       //  "language": {
       // "infoFiltered": "",
       //  processing:"<img src=<?php echo base_url();?>assets/images/ajax-loader.gif> Please Wait",
       //  search: "",searchPlaceholder:"Search" 

       //  },
        "ajax": {
            "url": base_url+'SubCat/getSubCategoryList',
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

});

function change(cat_id,status,admin_id,sub_cat_id)
{
    var base_url  =$('#base_url').val();
    $.ajax({
        url : base_url+"SubCat/change",
        type: "POST",
        data:{"cat_id":cat_id,'change':status,'admin_id':admin_id,'sub_cat_id':sub_cat_id},
        dataType: "JSON",
        success: function(data)
        {   
          // console.log(data.status);
          if(data.status==1)
          {
             $('#subCatListTable').DataTable().ajax.reload();
          }
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data');
        }
    });
}
function edit(cat_id,status,admin_id,sub_cat_id)
{

    var base_url  =$('#base_url').val();
    $('.form-group').removeClass('has-error');
    $('.help-block').empty();
    $('.modal-title').text('Edit Category');
    $.ajax({
        url : base_url+"SubCat/getSubCategory",
        type: "POST",
        data:{"status":status,'admin_id':admin_id,'cat_id':cat_id,'sub_cat_id':sub_cat_id},
        dataType: "JSON",
        success: function(data)
        {   
          if(data.status==1)
          {
             $('[name="cat_id"]').val(cat_id);
             $('[name="admin_id"]').val(admin_id);
             $('[name="status"]').val(status);
             $('[name="sub_cat_id"]').val(sub_cat_id);
             if(data.carResult.length > 0)
              {
                var cathtml ='';
                cathtml +='<option value="">Select Category</option>';
                for(var i=0;i < data.carResult.length;i++)
                {
                  cathtml +='<option value='+data.carResult[i]['cat_id']+'>'+data.carResult[i]['cat_name']+'</option>';
                }
                $('#cat_id').html(cathtml);
              }
             $('[name="cat_id"]').val(data.result[0]['cat_id']);
             $('[name="sub_cat_name"]').val(data.result[0]['sub_cat_name']);
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
 var sub_cat_name=$('#sub_cat_name').val();
 var sub_cat_id=$('#sub_cat_id').val();
  $.ajax({
        url : base_url+"SubCat/updateSubCategory",
        type: "POST",
        data:{"sub_cat_name":sub_cat_name,'admin_id':admin_id,'cat_id':cat_id,'sub_cat_id':sub_cat_id},
        dataType: "JSON",
        success: function(data)
        {   
          if(data.status==1)
          {

              $('#editCategoryModal').modal('hide');
              $('#subCatListTable').DataTable().ajax.reload();

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