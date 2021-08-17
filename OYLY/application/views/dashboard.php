<style>
.formcontainer {
      text-align: center;
      margin: 24px 50px 12px;
      }
</style>
<div class="loading">Loading&#8230;</div>
<div class="right_col" role="main">
  <div class="row">
    <div class="x_content">
      <div class="row">
        <div class="col-sm-12">
        <form method="Post" id="advanceSearch" style="border: 20px solid #f1f1f1;">
          <div class="form-group row formcontainer" >
            <label class="control-label col-md-2">From Date:</label>
            <div class="col-md-4">
              <input type="date" name="from_date" id="from_date" placeholder="Enter from date" class="form-control" value="<?php echo set_value('from_date'); ?>" onchange="validationTo();" />
              <span id="e_from_date" style="color:red"></span>
            </div>
            <label class="control-label col-md-2">To Date:</label>
            <div class="col-md-4">
              <input type="date" name="to_date" id="to_date" placeholder="Enter from date" class="form-control" value="<?php echo set_value('to_date'); ?>" onchange="validationTo();" />
              <span id="e_to_date" style="color:red"></span>
            </div>
            </div>
            <div class="form-group row formcontainer" >
            <label class="control-label col-md-2">Restaurant Name:</label>
            <div class="col-md-4">
              <select name="spot_id" id="spot_id" class="form-control">
                <option value="">---Select---</option>
                <?php
                foreach ($rest_list as $value) { ?>
                  <option value="<?php echo $value['spotId'] ?>"><?php echo $value['restaurant_name']; ?></option>
                <?php }
                ?>
              </select>
            </div> </div>
            <div class="text-center">
              <input type="button" class="btn btn-primary" id="search" value="Submit" onclick="advance_search();" />
            </div>
          </form>
          <br><br>
          <?php
          if ($_SESSION['user_role'] == 1) { ?>
          <label class="col-xs-4"></label>
            <button type="button" class="btn btn-primary btn-sm" onclick="open_modal()">Add New</button>
          <?php }
          ?>
          <div class="card-box table-responsive">

            <table class="table table-striped table-bordered" id="datatable_orders" style="width:100%">
              <thead style="white-space: nowrap;">
                <tr>
                  <th>Sr.No.</th>
                  <th>Name</th>
                  <th>Rating</th>
                  <!-- <th>Email</th> -->
                  <!-- <th>Mobile No</th> -->
                  <!-- <th>GST No</th> -->
                  <!-- <th>PAN NO</th> -->
                  <th>Address</th>
                  <th>Date</th>
                  <th>Role</th>
                  <th>KYC Verification</th>
                  <th>Action</th>
                </tr>
              </thead>


              <tbody>

              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  function open_modal() {
    save_method = 'add';
    $('#staff_name').val('');
    $('#restaurant_name').val('');
    $('#mobile_no').val('');
    $('#email').val('');
    $('#password').val('');
    $('#gst_no').val('');
    $('#pan_no').val('');
    $('#address').val('');
    $('#city').val('');
    $('#start_time').val('');
    $('#end_time').val('');
    $('#amenities').val('');
    $('#restaurant_service').val('');
    $('#food_type').val('');
    $('#fileupload').val('');
    $('#service').val('');
    $('.form-group').removeClass('has-error');
    $('.help-block').empty();
    $('#imageName').val('');
    $('.modal-title').text('Add Restaurant');
    $('#restaurant').modal('show');


  }

  function open_addCat(admin_id) {
    save_method = 'add';
    $('#cat_name').val('');
    $('.form-group').removeClass('has-error');
    $('.help-block').empty();
    $('.modal-title').text('Add Category');
    $('#admin_id').val(admin_id);

    $.ajax({
      url: base_url + "Admin/editRestaurant",
      type: "POST",
      data: {
        "admin_id": admin_id
      },
      dataType: "JSON",
      success: function(data) {
        // console.log(data.status);
        if (data.status == 1) {
          $('#AddCategoryModal').modal('show');

        } else {
          alert("There is no record found");
        }
      },
      error: function(jqXHR, textStatus, errorThrown) {
        alert('Error get data');
      }
    });
  }

  function open_addSubCat(admin_id) {
    save_method = 'add';
    $('#cat_name').val('');
    $('.form-group').removeClass('has-error');
    $('.help-block').empty();
    $('.modal-title').text('Add Sub Category');
    $('#admin_id').val(admin_id);
    $('#sub_cat_id').val('');
    $.ajax({
      url: $('#base_url').val() + "Admin/editRestaurant",
      type: "POST",
      data: {
        "admin_id": admin_id
      },
      dataType: "JSON",
      success: function(data) {
        // console.log();
        if (data.status == 1) {
          if (data.carResult.length > 0) {
            var cathtml = '';
            cathtml += '<option value="">Select Category</option>';
            for (var i = 0; i < data.carResult.length; i++) {
              cathtml += '<option value=' + data.carResult[i]['cat_id'] + '>' + data.carResult[i]['cat_name'] + '</option>';
            }
            $('#cat_id').html(cathtml);
          } else {
            $('#cat_id').html('');
          }

          $('#AddSubCategoryModal').modal('show');

        } else {
          alert("There is no record found");
        }
      },
      error: function(jqXHR, textStatus, errorThrown) {
        alert('Error get data');
      }
    });
  }

  function addSubCategory() {
    $.ajax({
      url: $('#base_url').val() + "Supervisor/api/addRestaurantCategory",
      type: "POST",
      data: {
        "admin_id": $('#admin_id').val(),
        'cat_name': $('#cat_name').val()
      },
      dataType: "JSON",
      success: function(data) {
        if (data.status) {
          alert(data.message);
          $('#AddCategoryModal').modal('hide');
        } else {
          alert(data.message);
        }
      }
    });
  }

  function addCategory() {
    $.ajax({
      url: $('#base_url').val() + "Supervisor/api/addRestaurantSubCategory",
      type: "POST",
      data: {
        "admin_id": $('#admin_id').val(),
        'cat_id': $('#cat_id').val(),
        'sub_cat_name': $('#sub_cat_id').val()
      },
      dataType: "JSON",
      success: function(data) {
        if (data.status) {
          alert(data.message);
          $('#AddSubCategoryModal').modal('hide');
        } else {
          alert(data.message);
        }
      }
    });
  }
</script>
<div class="modal fade" id="AddCategoryModal" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h3 class="modal-title">Add Category</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

      </div>
      <div class="modal-body form">
        <form class="form-horizontal" method="POST" enctype="multipart/form-data">
          <div class="form-body">

            <div class="form-group row ">
              <label class="control-label col-md-3 col-sm-3 ">Category Name</label>
              <div class="col-md-9 col-sm-9 ">
                <input name="cat_name" id="cat_name" class="form-control" type="text" autocomplete="off">

                <span class="help-block" style="color:red"></span>
              </div>
            </div>
          </div>
          <div class="modal-footer">

            <button type="button" id="btnSubCaregory" onclick="addSubCategory()" class="btn btn-primary">Save</button>

            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
          </div>
        </form>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
</div>
<div class="modal fade" id="AddSubCategoryModal" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h3 class="modal-title">Add Category</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

      </div>
      <div class="modal-body form">
        <form class="form-horizontal" method="POST" enctype="multipart/form-data">
          <div class="form-body">

            <div class="form-group row ">
              <label class="control-label col-md-3 col-sm-3 ">Category Name</label>
              <div class="col-md-9 col-sm-9 ">
                <select class="form-control" name="cat_id" id="cat_id">

                </select>

                <span class="help-block" style="color:red"></span>
              </div>
            </div>
            <div class="form-group row ">
              <label class="control-label col-md-3 col-sm-3 ">Sub Category Name</label>
              <div class="col-md-9 col-sm-9 ">
                <input type="text" class="form-control" name="sub_cat_id" id="sub_cat_id" />

                <span class="help-block" style="color:red"></span>
              </div>
            </div>
          </div>
          <div class="modal-footer">

            <button type="button" id="btnCaregory" onclick="addCategory()" class="btn btn-primary">Save</button>

            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
          </div>
        </form>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
</div>

<div class="modal fade" id="restaurant" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h3 class="modal-title">Add Restaurant</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

      </div>
      <div class="modal-body form">
        <form id="restaurant_details" class="form-horizontal" method="POST" enctype="multipart/form-data">
          <input type="hidden" name="imageName" id="imageName">
          <input type="hidden" name="admin_id" id="admin_id">
          <div class="form-body">

            <div class="form-group row ">
              <label class="control-label col-md-3 col-sm-3 ">Emp Name</label>
              <div class="col-md-9 col-sm-9 ">
                <input name="staff_name" id="staff_name" class="form-control" type="text" autocomplete="off">

                <span class="help-block" style="color:red"></span>
              </div>
            </div>
            <div class="form-group row ">
              <label class="control-label col-md-3 col-sm-3 ">Restaurant Name</label>
              <div class="col-md-9 col-sm-9 ">
                <input name="restaurant_name" id="restaurant_name" class="form-control" type="text" autocomplete="off">
                <span class="help-block" style="color:red"></span>
              </div>
            </div>
            <div class="form-group row ">
              <label class="control-label col-md-3 col-sm-3 ">Mobile No</label>
              <div class="col-md-9 col-sm-9 ">
                <input name="mobile_no" id="mobile_no" class="form-control" type="text" autocomplete="off">
                <span class="help-block" style="color:red"></span>
              </div>
            </div>
            <div class="form-group row ">
              <label class="control-label col-md-3 col-sm-3 ">Email Id</label>
              <div class="col-md-9 col-sm-9 ">
                <input name="email" id="email" class="form-control" type="text" autocomplete="off">
                <span class="help-block" style="color:red"></span>
              </div>
            </div>

            <div class="form-group row ">
              <label class="control-label col-md-3 col-sm-3 ">Password</label>
              <div class="col-md-9 col-sm-9 ">
                <input type="password" name="password" id="password" class="form-control" type="text" autocomplete="off">
                <span class="help-block" style="color:red"></span>
              </div>
            </div>
            <div class="form-group row ">
              <label class="control-label col-md-3 col-sm-3 ">Gst No</label>
              <div class="col-md-9 col-sm-9 ">
                <input name="gst_no" id="gst_no" class="form-control" type="text" autocomplete="off">
                <span class="help-block" style="color:red"></span>
              </div>
            </div>
            <div class="form-group row ">
              <label class="control-label col-md-3 col-sm-3 ">Pan No</label>
              <div class="col-md-9 col-sm-9 ">
                <input name="pan_no" id="pan_no" class="form-control" type="text" autocomplete="off">
                <span class="help-block" style="color:red"></span>
              </div>
            </div>
            <div class="form-group row ">
              <label class="control-label col-md-3 col-sm-3 ">Restaurant Address</label>
              <div class="col-md-9 col-sm-9 ">
                <input name="address" id="address" class="form-control" type="text" autocomplete="off">
                <span class="help-block" style="color:red"></span>
              </div>
            </div>


            <div class="form-group row ">
              <label class="control-label col-md-3 col-sm-3 ">City</label>
              <div class="col-md-9 col-sm-9 ">
                <input name="city" id="city" class="form-control" type="text" autocomplete="off">
                <span class="help-block" style="color:red"></span>
              </div>
            </div>

            <div class="form-group row ">
              <label class="control-label col-md-3 col-sm-3 ">Start Time</label>
              <div class="col-md-9 col-sm-9 ">
                <input type='text' class="form-control" id='start_time' name="start_time" />
                <span class="help-block" style="color:red"></span>

              </div>
            </div>
            <div class="form-group row ">
              <label class="control-label col-md-3 col-sm-3 ">End Time</label>
              <div class="col-md-9 col-sm-9 ">
                <input type='text' class="form-control" id='end_time' name="end_time" />
                <span class="help-block" style="color:red"></span>

              </div>
            </div>


            <div class="form-group row">
              <label class="control-label col-md-3 col-sm-3 ">Select Emenities</label>
              <div class="col-md-9 col-sm-9 ">
                <select class="select2_multiple form-control" multiple="multiple" id="amenities" name="amenities">

                  <?php
                  foreach ($emenities as $emenitie) { ?>

                    <option value="<?php echo $emenitie['amenities_type']; ?>"><?php echo $emenitie['amenities_type']; ?></option>
                  <?php }

                  ?>
                </select>
                <span class="help-block" style="color:red"></span>
              </div>
            </div>
            <div class="form-group row">
              <label class="control-label col-md-3 col-sm-3 ">Select Food type</label>
              <div class="col-md-9 col-sm-9 ">
                <select class="select2_multiple form-control" multiple="multiple" name="food_type" id="food_type">

                  <?php
                  foreach ($food_type as $food_types) { ?>

                    <option value="<?php echo $food_types['food_type']; ?>"><?php echo $food_types['food_type']; ?></option>
                  <?php }

                  ?>
                </select>
                <span class="help-block" style="color:red"></span>
              </div>
            </div>
            <div class="form-group row ">
              <label class="control-label col-md-3 col-sm-3 ">Image</label>
              <div class="col-md-9 col-sm-9 ">
                <input name="fileupload" id="fileupload" class="form-control" type="file" autocomplete="off">
                <span id="percent_fileupload"></span>
              </div>
            </div>
          </div>

      </div>
      <div class="modal-footer">

        <button type="button" id="btnSave" onclick="addRestaurant()" class="btn btn-primary">Save</button>

        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
      </div>
      </form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script type="text/javascript">
  $(document).ready(function() {

    $('input').on('change', function() {
      $(this).parent().parent().removeClass('has-error');
      $(this).next().empty();
    });
    $("select").change(function() {
      $(this).parent().parent().removeClass('has-error');
      $(this).next().empty();
    });
    $("#start_time").change(function() {
      $(this).parent().parent().removeClass('has-error');
      $(this).next().empty();
    });
    $("#end_time").change(function() {
      $(this).parent().parent().removeClass('has-error');
      $(this).next().empty();
    });
    $('.menuFixedPrice').hide();
    $('.menuFullHalfPrice').hide();

  });
</script>
<script type="text/javascript">
  function addRestaurant() {
    var data = '';
    var base_url = $('#base_url').val();
    var staff_name = $('#staff_name').val();
    var restaurant_name = $('#restaurant_name').val();
    var mobile_no = $('#mobile_no').val();
    var email = $('#email').val();
    var password = $('#password').val();
    var gst_no = $('#gst_no').val();
    var pan_no = $('#pan_no').val();
    var address = $('#address').val();
    var start_time = $('#start_time').val();
    var end_time = $('#end_time').val();
    var amenities = $('#amenities').val();
    var food_type = $('#food_type').val();
    var city = $('#city').val();
    var imageName = $('#imageName').val();
    var admin_id = $('#admin_id').val();
    var service = $('#restaurant_service').val();
    // console.log(new FormData(this));
    // return false;
    var url;

    if (save_method == 'add') {
      url = base_url + "Admin/addRestaurant";
    } else {
      url = base_url + "Admin/updateRestaurant";;
    }
    $.ajax({
      url: url,
      type: "POST",
      data: {
        "staff_name": staff_name,
        "restaurant_name": restaurant_name,
        "mobile_no": mobile_no,
        "email": email,
        "password": password,
        "gst_no": gst_no,
        "pan_no": pan_no,
        "address": address,
        "start_time": start_time,
        "end_time": end_time,
        "amenities": amenities,
        "food_type": food_type,
        "city": city,
        "admin_id": admin_id,
        "service": service,
        "imageName": imageName
      },
      dataType: "JSON",
      success: function(data) {
        console.log(data);
        if (data.status == true) //if success close modal and reload ajax table
        {
          $('#restaurant').modal('hide');
          reload_table();
        } else {
          if (data.inputerror.length == 0) {
            alert(data.message);
          } else {
            for (var i = 0; i < data.inputerror.length; i++) {
              console.log($('[name="' + data.inputerror[i] + '"]').parent().parent().addClass('has-error'));
              $('[name="' + data.inputerror[i] + '"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
              $('[name="' + data.inputerror[i] + '"]').next().text(data.error_string[i]); //select span help-block class set text error string
            }
          }
        }

        $('#btnSave').text('save'); //change button text
        $('#btnSave').attr('disabled', false); //set button enable
      },
      error: function(jqXHR, textStatus, errorThrown) {
        alert('Error adding');
        $('#btnSave').text('save'); //change button text
        $('#btnSave').attr('disabled', false); //set button enable

      }

    });
  }
  $('#fileupload').on('change', function() {
    var oInput = $(this)[0].files[0];
    var base_url = $('#base_url').val();
    var sFileName = oInput['name'];
    var percent = $('#percent_fileupload');
    percent.show();
    var confirm_alert = confirm('Are you sure you want to upload this image');
    if (confirm_alert) {
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
          url: base_url + "Admin/getImageName",
          xhr: function() {
            var xhr = $.ajaxSettings.xhr();
            xhr.upload.onprogress = function(e) {
              count = Math.floor(e.loaded / e.total * 100) + '%';
              percent.html(count);
            };
            return xhr;
          },
          type: 'post',
          cache: false,
          contentType: false,
          processData: false,
          data: formData,
          dataType: "JSON",
          success: function(response) {
            $('#imageName').val(response);
            count = '0' + '%';
            percent.html(count);
            percent.fadeOut(2000);
          }

        });
      }
      reader.readAsDataURL(files);

    } else {
      $(this).val(null);
    }

  });

  function edit(admin_id) {
    save_method = 'update';
    var base_url = $('#base_url').val();
    $('.form-group').removeClass('has-error');
    $('.help-block').empty();
    $('.modal-title').text('Edit Restaurant');
    $('#imageName').val('');
    $.ajax({
      url: base_url + "Admin/editRestaurant",
      type: "POST",
      data: {
        "admin_id": admin_id
      },
      dataType: "JSON",
      success: function(data) {
        // console.log(data.result[0]['amenities']);
        if (data.status == 1) {
          console.log(data.result[0]['amenities']);
          $('[name="admin_id"]').val(data.result[0]['admin_id']);
          $('[name="staff_name"]').val(data.result[0]['user_fullname']);
          $('[name="amenities"]').val(data.result[0]['amenities']);
          $('[name="food_type"]').val(data.result[0]['cuisines']);
          $('[name="restaurant_name"]').val(data.result[0]['restaurant_name']);
          $('[name="mobile_no"]').val(data.result[0]['mobile_no']);
          $('[name="email"]').val(data.result[0]['user_email']);
          $('[name="password"]').val(data.result[0]['password']);
          $('[name="gst_no"]').val(data.result[0]['gst_no']);
          $('[name="pan_no"]').val(data.result[0]['pan_no']);
          $('[name="address"]').val(data.result[0]['address']);
          $('[name="city"]').val(data.result[0]['city']);
          $('[name="start_time"]').val(data.result[0]['openingTime']);
          $('[name="end_time"]').val(data.result[0]['closingTime']);
          $('[name="restaurant_service"]').val(data.services);
          $('#restaurant').modal('show');


        } else {
          alert("There is no record found");
        }
      },
      error: function(jqXHR, textStatus, errorThrown) {
        alert('Error get data');
      }
    });
  }

  function change() {

    var base_url = $('#base_url').val();
    var admin_id = $('#admin_id').val();
    var convenience_fees = $('#convenience_fees option:selected').val();
    var gst = $('#gst option:selected').val();
    var restaurantStatus = $('#restaurant_status option:selected').val();
    $('.loading').show();
    $.ajax({
      url: base_url + "Admin/changeRestaurant",
      type: "POST",
      data: {
        "admin_id": admin_id,
        'change': restaurantStatus,
        'convenience_fees': convenience_fees,
        'gst': gst
      },
      dataType: "JSON",
      success: function(data) {
        // console.log(data.status);
        $('.loading').hide();
        if (data.status == 1) {
          $('#status_modal').modal('hide');
          reload_table();
        } else if(data.status == 2) {
        alert(data.result)
        }
        $('#status_modal').modal('hide');
      },
      error: function(jqXHR, textStatus, errorThrown) {
        alert('Error get data');
      }
    });
  }
</script>
<script type="text/javascript">
  $(function() {
    $('#end_time').datetimepicker({
      format: 'hh:mm A'
    });
    $('#start_time').datetimepicker({
      format: 'hh:mm A'
    });
  });

  function reload_table() {
    table.ajax.reload(null, false);
  }
</script>
<div class="modal fade" id="StaffList" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h3 class="modal-title"></h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

      </div>
      <div class="modal-body form">
        <form id="staff_deatils" class="form-horizontal" method="POST" enctype="multipart/form-data">
          <input type="hidden" name="resto_staff_image" id="resto_staff_image">
          <input type="hidden" name="resto_staff_document" id="resto_staff_document">

          <div class="form-body">

            <div class="form-group row ">
              <label class="control-label col-md-3 col-sm-3 ">Name</label>
              <div class="col-md-9 col-sm-9 ">
                <input name="rest_staff_name" id="rest_staff_name" class="form-control" type="text" autocomplete="off">

                <span class="help-block" style="color:red"></span>
              </div>
            </div>

            <div class="form-group row ">
              <label class="control-label col-md-3 col-sm-3 ">Mobile No</label>
              <div class="col-md-9 col-sm-9 ">
                <input name="staff_mobile_no" id="staff_mobile_no" class="form-control" type="text" autocomplete="off">
                <span class="help-block" style="color:red"></span>
              </div>
            </div>
            <div class="form-group row ">
              <label class="control-label col-md-3 col-sm-3 ">Email Id</label>
              <div class="col-md-9 col-sm-9 ">
                <input name="staff_email" id="staff_email" class="form-control" type="text" autocomplete="off">
                <span class="help-block" style="color:red"></span>
              </div>
            </div>
            <div class="form-group row ">
              <label class="control-label col-md-3 col-sm-3 ">DOB</label>
              <div class="col-md-9 col-sm-9 ">
                <input name="staff_dob" id="staff_dob" class="form-control" type="date" autocomplete="off">
                <span class="help-block" style="color:red"></span>
              </div>
            </div>
            <div class="form-group row ">
              <label class="control-label col-md-3 col-sm-3 ">Gender</label>
              <div class="col-md-9 col-sm-9 ">
                <select class="form-control" id="staff_Gender" name="staff_Gender">
                  <option value="">Select Gender</option>
                  <option value="Male">Male</option>
                  <option value="Fmale">Female</option>
                </select>
                <span class="help-block" style="color:red"></span>
              </div>
            </div>
            <div class="form-group row ">
              <label class="control-label col-md-3 col-sm-3 ">Password</label>
              <div class="col-md-9 col-sm-9 ">
                <input type="password" name="staff_password" id="staff_password" class="form-control" type="text" autocomplete="off">
                <span class="help-block" style="color:red"></span>
              </div>
            </div>
            <div class="form-group row ">
              <label class="control-label col-md-3 col-sm-3 ">Role</label>
              <div class="col-md-9 col-sm-9 ">
                <select class="form-control" id="staff_role" name="staff_role">
                  <option value="">Select Role</option>
                  <option value="Waiter">Waiter</option>
                  <option value="KOT">KOT</option>
                  <option value="Supervisor">Supervisor</option>
                  <option value="Chef">Chef</option>
                  <option value="Cashier">Cashier</option>
                </select>
                <span class="help-block" style="color:red"></span>
              </div>
            </div>
            <div class="form-group row ">
              <label class="control-label col-md-3 col-sm-3 ">UAID No</label>
              <div class="col-md-9 col-sm-9 ">
                <input name="staff_uaid_no" id="staff_uaid_no" class="form-control" type="text" autocomplete="off">
                <span class="help-block" style="color:red"></span>
              </div>
            </div>
            <div class="form-group row ">
              <label class="control-label col-md-3 col-sm-3 ">Pan No</label>
              <div class="col-md-9 col-sm-9 ">
                <input name="staff_pan_no" id="staff_pan_no" class="form-control" type="text" autocomplete="off">
                <span class="help-block" style="color:red"></span>
              </div>
            </div>
            <div class="form-group row ">
              <label class="control-label col-md-3 col-sm-3 ">Permanent Address</label>
              <div class="col-md-9 col-sm-9 ">
                <input name="staff_address" id="staff_address" class="form-control" type="text" autocomplete="off">
                <span class="help-block" style="color:red"></span>
              </div>
            </div>
            <div class="form-group row ">
              <label class="control-label col-md-3 col-sm-3 ">Current Address</label>
              <div class="col-md-9 col-sm-9 ">
                <input name="staff_cur_address" id="staff_cur_address" class="form-control" type="text" autocomplete="off">
                <span class="help-block" style="color:red"></span>
              </div>
            </div>
            <div class="form-group row ">
              <label class="control-label col-md-3 col-sm-3 ">Image</label>
              <div class="col-md-9 col-sm-9 ">
                <input name="staff_image" id="staff_image" class="form-control" type="file" autocomplete="off">
                <span id="percent_fileupload_img"></span>
              </div>
            </div>
            <div class="form-group row ">
              <label class="control-label col-md-3 col-sm-3 ">Document</label>
              <div class="col-md-9 col-sm-9 ">
                <input name="staff_document" id="staff_document" class="form-control" type="file" autocomplete="off">
                <span id="percent_fileupload_doc"></span>
              </div>
            </div>
          </div>

      </div>
      <div class="modal-footer">

        <button type="button" id="btnAddStaff" class="btn btn-primary">Save</button>

        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
      </div>
      </form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div class="modal fade" id="MenuModal" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h3 class="modal-title"></h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

      </div>
      <div class="modal-body form">
        <form id="staff_deatils" class="form-horizontal" method="POST" enctype="multipart/form-data">
          <!-- <input type="hidden" name="imageName"id="imageName"> -->
          <!-- <input type="hidden" name="admin_id"id="admin_id"> -->
          <div class="form-body">

            <div class="form-group row ">
              <label class="control-label col-md-3 col-sm-3 ">Menu Name</label>
              <div class="col-md-9 col-sm-9 ">
                <input name="menu_name" id="menu_name" class="form-control" type="text" autocomplete="off">

                <span class="help-block" style="color:red"></span>
              </div>
            </div>

            <div class="form-group row ">
              <label class="control-label col-md-3 col-sm-3 ">Menu Detail</label>
              <div class="col-md-9 col-sm-9 ">
                <input name="menu_details" id="menu_details" class="form-control" type="text" autocomplete="off">
                <span class="help-block" style="color:red"></span>
              </div>
            </div>
            <div class="form-group row ">
              <label class="control-label col-md-3 col-sm-3 ">Food Type</label>
              <div class="col-md-9 col-sm-9 ">
                <select id="menu_food_type" name="menu_food_type" class="form-control">
                  <option value="">Select Food Type</option>
                  <option value="Veg">Veg</option>
                  <option value="Non-Veg">Non-Veg</option>

                </select>
                <span class="help-block" style="color:red"></span>
              </div>
            </div>

            <div class="form-group row ">
              <label class="control-label col-md-3 col-sm-3 ">Menu Price</label>
              <div class="col-md-9 col-sm-9 ">
                <select class="form-control" id="menu_price" name="menu_price" onchange="selectPrice()">
                  <option value="">Select Menu Price</option>
                  <option value="Fixed">Fixed</option>
                  <option value="Half & Full">Half & Full</option>
                </select>
                <span class="help-block" style="color:red"></span>
              </div>
            </div>
            <div class="form-group row menuFixedPrice">
              <label class="control-label col-md-3 col-sm-3 ">Price</label>
              <div class="col-md-9 col-sm-9 ">
                <input name="menu_fixed_price" id="menu_fixed_price" class="form-control" type="number" autocomplete="off">
                <span class="help-block" style="color:red"></span>
              </div>
            </div>
            <div class="form-group row menuFullHalfPrice">
              <label class="control-label col-md-3 col-sm-3 ">Full Price</label>
              <div class="col-md-9 col-sm-9 ">
                <input name="menu_full_price" id="menu_full_price" class="form-control" type="number" autocomplete="off">
                <span class="help-block" style="color:red"></span>
              </div>
            </div>
            <div class="form-group row menuFullHalfPrice">
              <label class="control-label col-md-3 col-sm-3 ">Half Price</label>
              <div class="col-md-9 col-sm-9 ">
                <input name="menu_half_price" id="menu_half_price" class="form-control" type="number" autocomplete="off" max="4">
                <span class="help-block" style="color:red"></span>
              </div>
            </div>
            <div class="form-group row ">
              <label class="control-label col-md-3 col-sm-3 ">Menu Category</label>
              <div class="col-md-9 col-sm-9 ">
                <select class="form-control" id="menu_category" name="menu_category" onchange="getSubCategory()">


                </select>
                <span class="help-block" style="color:red"></span>
              </div>
            </div>
            <div class="form-group row ">
              <label class="control-label col-md-3 col-sm-3 ">Menu Sub Category</label>
              <div class="col-md-9 col-sm-9 ">
                <select class="form-control" id="menu_sub_category" name="menu_sub_category">
                </select>
                <span class="help-block" style="color:red"></span>
              </div>
            </div>
            <div class="form-group row ">
              <label class="control-label col-md-3 col-sm-3 ">Menu GST</label>
              <div class="col-md-9 col-sm-9 ">
                <select class="form-control" id="menu_gst" name="menu_gst">
                  <option value="">Select GST</option>

                </select>
                </select>
                <span class="help-block" style="color:red"></span>
              </div>
            </div>

            <div class="form-group row ">
              <label class="control-label col-md-3 col-sm-3 ">Menu Image</label>
              <div class="col-md-9 col-sm-9 ">
                <input name="menu_image" id="menu_image" class="form-control" type="file" autocomplete="off">
                <span class="help-block" style="color:red"></span>
              </div>
            </div>
          </div>

      </div>
      <div class="modal-footer">

        <button type="button" id="btnMenuAdd" class="btn btn-primary">Save</button>

        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
      </div>
      </form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script type="text/javascript">
  function open_staff_modal(admin_id) {
    save_method = 'add';
    $('#rest_staff_name').val('');
    $('#staff_mobile_no').val('');
    $('#staff_email').val('');
    $('#staff_dob').val('');
    $('#staff_Gender').val('');
    $('#staff_password').val('');
    $('#staff_uaid_no').val('');
    $('#staff_pan_no').val('');
    $('#staff_address').val('');
    $('#staff_role').val('');
    $('#staff_cur_address').val('');
    $('#admin_id').val(admin_id);
    $('.form-group').removeClass('has-error');
    $('.help-block').empty();
    $.ajax({
      url: base_url + 'StaffListController/checkRestaurant',
      type: 'Post',
      data: {
        'admin_id': admin_id
      },
      dataType: 'json',
      success: function(result) {
        if (result.status) {
          $('.modal-title').text(result.result[0].name);
          $('#StaffList').modal('show');
        } else {
          // console.log(resul);
          alert(result.result);
        }
      }

    });
  }

  $('#btnAddStaff').click(function() {

    var base_url = $('#base_url').val();
    var name = $('#rest_staff_name').val();
    var admin_mobile_no = $('#staff_mobile_no').val();
    var email_id = $('#staff_email').val();
    var dob = $('#staff_dob').val();
    var gender = $('#staff_Gender').val();
    var password = $('#staff_password').val();
    var uaid_no = $('#staff_uaid_no').val();
    var pan_no = $('#staff_pan_no').val();
    var address = $('#staff_address').val();
    var role = $('#staff_role').val();
    var resto_staff_image = $('#resto_staff_image').val();
    var resto_staff_document = $('#resto_staff_document').val();
    var staff_cur_address = $('#staff_cur_address').val();
    var admin_id = $('#admin_id').val();

    if (save_method == 'add') {
      url = base_url + "StaffListController/addStaff";
    } else {
      url = base_url + "StaffListController/updateStaff";
    }
    $.ajax({
      url: url,
      type: "POST",
      data: {
        "staff_mobile_no": admin_mobile_no,
        "staff_email": email_id,
        "staff_dob": dob,
        "rest_staff_name": name,
        "staff_Gender": gender,
        "staff_password": password,
        "staff_uaid_no": uaid_no,
        "staff_pan_no": pan_no,
        "resto_staff_image": resto_staff_image,
        "resto_staff_document": resto_staff_document,
        "staff_role": role,
        "staff_cur_address": staff_cur_address,
        "admin_id": admin_id,
        "staff_address": address
      },
      dataType: "JSON",
      success: function(data) {
        console.log(data);
        if (data.status == true) //if success close modal and reload ajax table
        {

          if (data.status == 1) {
            alert(data.message);
            $('#StaffList').modal('hide');
            $('#staffListTable').DataTable().ajax.reload();

          } else {
            alert(data.message);
          }

        } else {
          if (data.inputerror.length == 0) {
            alert(data.message);
          } else {
            for (var i = 0; i < data.inputerror.length; i++) {
              $('[name="' + data.inputerror[i] + '"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
              $('[name="' + data.inputerror[i] + '"]').next().text(data.error_string[i]); //select span help-block class set text error string
            }
          }

        }

        $('#btnSave').text('save'); //change button text
        $('#btnSave').attr('disabled', false); //set button enable
      },
      error: function(jqXHR, textStatus, errorThrown) {
        alert('Error adding');
        $('#btnSave').text('save'); //change button text
        $('#btnSave').attr('disabled', false); //set button enable

      }

    });
  });
</script>
<script type="text/javascript">
  $('#menu_image').on('change', function() {
    var oInput = $(this)[0].files[0];
    var base_url = $('#base_url').val();
    var sFileName = oInput['name'];
    var percent = $('#percent_fileupload');
    percent.show();
    var confirm_alert = confirm('Are you sure you want to upload this image');
    if (confirm_alert) {
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
          url: base_url + "Admin/getImageName",
          xhr: function() {
            var xhr = $.ajaxSettings.xhr();
            xhr.upload.onprogress = function(e) {
              count = Math.floor(e.loaded / e.total * 100) + '%';
              percent.html(count);
            };
            return xhr;
          },
          type: 'post',
          cache: false,
          contentType: false,
          processData: false,
          data: formData,
          dataType: "JSON",
          success: function(response) {
            $('#imageName').val(response);
            count = '0' + '%';
            percent.html(count);
            percent.fadeOut(2000);
          }

        });
      }
      reader.readAsDataURL(files);

    } else {
      $(this).val(null);
    }

  });
  $('#staff_image').on('change', function() {
    var oInput = $(this)[0].files[0];
    var base_url = $('#base_url').val();
    var sFileName = oInput['name'];
    var percent = $('#percent_fileupload_img');
    percent.show();
    var confirm_alert = confirm('Are you sure you want to upload this image');
    if (confirm_alert) {
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
          url: base_url + "Admin/getImageName",
          xhr: function() {
            var xhr = $.ajaxSettings.xhr();
            xhr.upload.onprogress = function(e) {
              count = Math.floor(e.loaded / e.total * 100) + '%';
              percent.html(count);
            };
            return xhr;
          },
          type: 'post',
          cache: false,
          contentType: false,
          processData: false,
          data: formData,
          dataType: "JSON",
          success: function(response) {
            $('#resto_staff_image').val(response);
            count = '0' + '%';
            percent.html(count);
            percent.fadeOut(2000);
          }

        });
      }
      reader.readAsDataURL(files);

    } else {
      $(this).val(null);
    }

  });
  $('#staff_document').on('change', function() {
    var oInput = $(this)[0].files[0];
    var base_url = $('#base_url').val();
    var sFileName = oInput['name'];
    var percent = $('#percent_fileupload_doc');
    percent.show();
    var confirm_alert = confirm('Are you sure you want to upload this image');
    if (confirm_alert) {
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
          url: base_url + "Admin/getImageName",
          xhr: function() {
            var xhr = $.ajaxSettings.xhr();
            xhr.upload.onprogress = function(e) {
              count = Math.floor(e.loaded / e.total * 100) + '%';
              percent.html(count);
            };
            return xhr;
          },
          type: 'post',
          cache: false,
          contentType: false,
          processData: false,
          data: formData,
          dataType: "JSON",
          success: function(response) {
            $('#resto_staff_document').val(response);
            count = '0' + '%';
            percent.html(count);
            percent.fadeOut(2000);
          }

        });
      }
      reader.readAsDataURL(files);

    } else {
      $(this).val(null);
    }

  });

  function open_add_menu(admin_id) {
    save_method = 'add';
    $('#menu_name').val('');
    $('#menu_details').val('');
    $('#menu_food_type').val('');
    $('#menu_price').val('');
    $('#menu_category').val('');
    $('#menu_sub_category').val('');
    $('#menu_gst').val('');
    $('#menu_image').val('');
    $('#admin_id').val(admin_id);
    $('.form-group').removeClass('has-error');
    $('.help-block').empty();
    var base_url = $('#base_url').val();
    $.ajax({
      url: base_url + 'StaffListController/checkRestaurant',
      type: 'Post',
      data: {
        'admin_id': admin_id
      },
      dataType: 'json',
      success: function(result) {
        if (result.status) {
          var html = '';
          html += '<option value="">Select Category</option>';
          for ($i = 0; $i < result.menuList.length; $i++) {
            html += '<option value=' + result.menuList[$i]['cat_id'] + '>' + result.menuList[$i]['cat_name'] + '</option>';
          }
          $('#menu_category').html(html);
          var html3 = '';
          html3 += '<option value="">Select Sub Category</option>';
          for ($i = 0; $i < result.gstList.length; $i++) {
            html3 += '<option value=' + result.gstList[$i]['id'] + '>' + result.gstList[$i]['category_name'] + '</option>';
          }
          $('#menu_gst').html(html3);
          $('.modal-title').text(result.result[0].name);
          $('#MenuModal').modal('show');


        } else {
          // console.log(resul);
          alert(result.result);
        }
      }

    });
  }

  function selectPrice() {
    var menu_price = $('#menu_price option:selected').val();
    if (menu_price == 'Fixed') {
      $('.menuFullHalfPrice').hide();
      $('.menuFixedPrice').show();
    } else if (menu_price == 'Half & Full') {
      $('.menuFullHalfPrice').show();
      $('.menuFixedPrice').hide();
    } else {
      $('.menuFixedPrice').hide();
      $('.menuFullHalfPrice').hide();
      return false;
    }
  }

  function getSubCategory() {
    var cat_id = $('#menu_category option:selected').val();
    var admin_id = $('#admin_id').val();
    if (cat_id != '') {
      var base_url = $('#base_url').val();
      $.ajax({
        url: base_url + 'Menu/getSubCategory',
        type: 'POST',
        data: {
          'cat_id': cat_id,
          'admin_id': admin_id
        },
        dataType: "Json",
        success: function(result) {
          console.log(result);
          if (result.status) {
            var html = '';
            for (var i = 0; i < result.data.length; i++) {
              html += '<option value=' + result.data[i]['sub_cat_id'] + '>' + result.data[i]['sub_cat_name'] + '</option>';
            }
            $('#menu_sub_category').html(html);

          } else {
            $('#menu_sub_category').html('');
            alert(result.data);

          }
          if (result.gst.status) {
            var html2 = '';
            for (var j = 0; j < result.gst.data.length; j++) {
              html2 += '<option value=' + result.gst.data[j]['id'] + '>' + result.gst.data[j]['category_name'] + '</option>';
            }
            $('#menu_gst').html(html2);
          } else {
            alert(result.data);
          }

        }

      });

    } else {
      $('#menu_sub_category').html('');
      alert("Please select menu category");
      return false;
    }
  }

  $('#btnMenuAdd').click(function() {

    var base_url = $('#base_url').val();
    var menu_name = $('#menu_name').val();
    var menu_food_type = $('#menu_food_type').val();
    var menu_price = $('#menu_price').val();
    var menu_full_price = $('#menu_full_price').val();
    var menu_half_price = $('#menu_half_price').val();
    var menu_fixed_price = $('#menu_fixed_price').val();
    var menu_sub_category = $('#menu_sub_category').val();
    var menu_category = $('#menu_category').val();
    var menu_gst = $('#menu_gst').val();
    var imageName = $('#imageName').val();
    var admin_id = $('#admin_id').val();
    var menu_details = $('#menu_details').val();

    if (save_method == 'add') {
      url = base_url + "Menu/addMenu";
    } else {
      url = base_url + "Menu/updateMenu";
    }
    $.ajax({
      url: url,
      type: "POST",
      data: {
        "menu_name": menu_name,
        "menu_food_type": menu_food_type,
        "menu_price": menu_price,
        "menu_full_price": menu_full_price,
        "menu_half_price": menu_half_price,
        "menu_fixed_price": menu_fixed_price,
        "menu_sub_category": menu_sub_category,
        "menu_category": menu_category,
        "menu_gst": menu_gst,
        "admin_id": admin_id,
        "menu_details": menu_details,
        "imageName": imageName
      },
      dataType: "JSON",
      success: function(data) {
        console.log(data);
        if (data.status == true) //if success close modal and reload ajax table
        {

          if (data.status == 1) {
            alert(data.message);
            $('#MenuModal').modal('hide');
          } else {
            alert(data.message);
          }

        } else {
          if (data.inputerror.length == 0) {
            alert(data.message);
          } else {
            for (var i = 0; i < data.inputerror.length; i++) {
              $('[name="' + data.inputerror[i] + '"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
              $('[name="' + data.inputerror[i] + '"]').next().text(data.error_string[i]); //select span help-block class set text error string
            }
          }

        }

        $('#btnMenuAdd').text('save'); //change button text
        $('#btnMenuAdd').attr('disabled', false); //set button enable
      },
      error: function(jqXHR, textStatus, errorThrown) {
        alert('Error adding');
        $('#btnMenuAdd').text('save'); //change button text
        $('#btnMenuAdd').attr('disabled', false); //set button enable

      }

    });
  });

  function open_status_modal(admin_id, status) {

    $('#admin_id').val(admin_id);
    var base_url = $('#base_url').val();
    var admin_id = $('#admin_id').val();
    $.ajax({
      url: base_url + 'StaffListController/checkResto',
      type: 'Post',
      data: {
        'admin_id': admin_id
      },
      dataType: 'json',
      success: function(result) {
        var html = '';
        var html2 = '';
        // console.log(result);
        if (result.status) {
          $('.modal-title').text(result.result[0].name);
          $('#restaurant_status').val(result.result[0].status);
          if (result.convenienceFees.length > 0) {
            for (var i = 0; i < result.convenienceFees.length; i++) {
              html += '<option value=' + result.convenienceFees[i]['id'] + '>' + result.convenienceFees[i]['convenience'] + '</option>';
            }

            $('#gst').html(html);
          } else {
            $('#gst').html('');
          }
          if (result.restGst.length > 0) {
            for (var j = 0; j < result.restGst.length; j++) {
              html2 += '<option value=' + result.restGst[j]['id'] + '>' + result.restGst[j]['gst'] + '</option>';
            }

            $('#convenience_fees').html(html2);
          } else {
            $('#convenience_fees').html('');
          }

          $('#status_modal').modal('show');
        } else {
          // console.log(resul);
          alert(result.result);
        }
      }

    });

  }
</script>
<div class="modal fade" id="status_modal" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h3 class="modal-title"></h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

      </div>
      <div class="modal-body form">
        <form id="" class="form-horizontal" method="POST">

          <div class="form-body">

            <div class="form-group row ">
              <label class="control-label col-md-3 col-sm-3 ">Gst</label>
              <div class="col-md-9 col-sm-9 ">
                <select id="gst" class="form-control" name="gst">

                </select>
                <span class="help-block" style="color:red"></span>
              </div>
            </div>
            <div class="form-group row ">
              <label class="control-label col-md-3 col-sm-3 ">Convenience Fee</label>
              <div class="col-md-9 col-sm-9 ">
                <select id="convenience_fees" class="form-control" name="convenience_fees">

                </select>

                <span class="help-block" style="color:red"></span>
              </div>
            </div>
            <div class="form-group row ">
              <label class="control-label col-md-3 col-sm-3 ">Status</label>
              <div class="col-md-9 col-sm-9 ">
                <select name="restaurant_status" id="restaurant_status" class="form-control">
                  <!-- <option value="">--Select--</option> -->
                  <option value="1">Active</option>
                  <option value="0">InActive</option>
                </select>
                <span class="help-block" style="color:red"></span>
              </div>
            </div>

          </div>
          <div class="modal-footer">

            <button type="button" id="btnSave" onclick="change()" class="btn btn-primary">Save</button>

            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
          </div>
        </form>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->