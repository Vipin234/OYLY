<div class="loading">Loading&#8230;</div>
<div class="right_col" role="main" style="min-height: 764px;">
<div class="row">
              <div class="col-md-12 col-sm-12 ">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Upload Transactions Excel</h2>
                    
                    <div class="clearfix"></div>
                  </div>
                  <?php
                    if(!empty($error))
                    {?>
                    <div class="x_content">
                    <br />
                                 
                      <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name" style="color: red;">Errors:
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                         <?php
                          if(!empty($error))
                          {?>
                            <table class="table table-striped table-bordered dataTable no-footer">
                             <thead>
                              <tr>
                               <th>Sr.No.</th>
                               <th>Order Id</th>
                               <th>Message</th>
                               <th>Errors</th>
                               <th></th>
                               </tr>
                             </thead>
                             <body>
                           <?php
                            for($i=0;$i<count($error);$i++)
                            {?>
                              <tr>
                                <td><?Php echo $i;?></td>
                                <td><?Php echo $error[$i]['error'];?></td>
                                <td></td>
                                <td></td>
                                <td></td>
                              </tr>
                            <?php }
                          }

                          ?>
                          </body>
                          </table>
                        </div>
                      </div>
                     
                  </div>
                   <?php 
                   $errors['error']=array();
                 }
                  ?>
                  
                  <div class="x_content">
                    <br />
                    <form id="excelFileUpload"name="excelFileUpload" data-parsley-validate class="form-horizontal form-label-left"enctype="multipart/form-data" action="<?php echo base_url();?>index.php/SpreesdSheetController/uploadEcxelFile" method="Post">

                      <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Excel<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                          <input type="file" id="excelFile" name="excelFile" required="required" class="form-control ">
                        </div>
                      </div>
                      <span style="color: red;margin-left: 269px;" id="err_excelFile"></span>
                      <div class="ln_solid"></div>
                      <div class="item form-group">
                        <div class="col-md-6 col-sm-6 offset-md-3">
                          <button type="submit" id="submit" class="btn btn-success">Submit</button>
                        </div>
                      </div>

                    </form>
                  </div>
                </div>
              </div>
          
          </div>  
</div>
<script>
$('#submit').attr('disabled',true);
$('#excelFile').on("change",function () {
$("#err_excelFile").html("");
    var fileExtension = ["xls","csv","xlsx"];
     if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
        $("#err_excelFile").html("Please select only csv/xls/xlsx file.");
        $("#excelFile").val("");
        $('#submit').attr('disabled',true);
    }
   else{
        $('#submit').attr('disabled',false);
        $("#err_excelFile").html("");    
   }
});
</script>