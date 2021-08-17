<div class="loading">Loading&#8230;</div>
<div class="right_col" role="main">
    <div class="row">
        <div class="x_content">
            <div class="row">
                <div class="col-sm-12">
                    <form method="Post" id="PaymentTxnForm">
                        <div class="col-md-1">
                            <label>Restaurant:</label>
                        </div>
                        <div class="col-md-2">
                            <select name="admin_id" id="admin_id" class="form-control">
                                <option value="">---Select---</option>

                                <?php

foreach ($kyc_list as $value) {?>

                                <option value="<?php echo $value['admin_id'] ?>"><?php echo $value['name']; ?>
                                </option>
                                <?php }

?>
                            </select>

                        </div>
                        <div class="col-md-1">
                            <label>From date:</label>
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="from_date" id="from_date" placeholder="Enter from date"
                                class="form-control" value="<?php echo set_value('from_date'); ?>"
                               />
                            <span id="e_from_date" style="color:red"></span>
                        </div>
                        <div class="col-md-1">
                            <label>To date:</label>
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="to_date" id="to_date" placeholder="Enter from date"
                                class="form-control" value="<?php echo set_value('to_date'); ?>"
                                />
                            <span id="e_to_date" style="color:red"></span>
                        </div>
                        <div class="col-md-1">
                            <input type="button" class="form-control btn btn-primary" id="search" value="Search"
                                onclick="advance_search_kyc();" />
                        </div>

                    </form>
                    <br><br>
                    <div class="card-box table-responsive">

                        <table class="table table-striped table-bordered" id="datatable_kyc" style="width:100%">
                            <thead style="white-space: nowrap;">
                                <tr>
                                    <th>Sr.No.</th>
                                    <th>Restaurant Name</th>
                                    <th>Registration No.</th>
                                    <th>Registartion Doc</th>
                                    <th>Licence No.</th>
                                    <th>Licence Doc</th>
                                    <th>Shop Act Licence No. </th>
                                    <th>Shop Act Licence Doc </th>
                                    <th>Adhar No.</th>
                                    <th>Adhar Doc</th>
                                    <th>Pan No.</th>
                                    <th>Pan Doc</th>
                                    <th>Accoun No.</th>
                                    <th>IFSC</th>
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

var table_kyc;
$(document).ready(function() {
    base_url = $('#base_url').val();
    datatable();
});
function datatable()
    {
      table_kyc = $('#datatable_kyc').DataTable({
        "processing": false,
        "serverSide": true,
        "order": [],
        "scrollY": 200,
        "scrollX": true,
        'destroy': true,
        "ajax": {
            "url": base_url + 'RestaurantKyc/KycList',
            "type": "POST",
            "dataType": "JSON",
            "data": function(data) {
                console.log(data);
            }
        },

        "columnDefs": [{
            "targets": [-1],
            "orderable": false,
        }, ],

    });
    }
function advance_search_kyc()
{
  $("#e_from_date").html('');
  $("#e_to_date").html('');
    var from_date=$('#from_date').val();
    var to_date=$('#to_date').val();
    var admin_id=$('#admin_id').val();
    console.log(admin_id);
    var startDate='';
    if(from_date=='' && to_date=='' && admin_id=='')
    {
        alert("Please select atleast one filter");
        return false;
    }
    if(from_date!='' && from_date!=undefined)
    {
        var startDate = new Date(from_date);
    }
   
    var endDate='';
    if(to_date!='' && to_date!=undefined)
    {
        var endDate = new Date(to_date);
    }
       var currDate = new Date();
    if (startDate > currDate){
    $("#e_from_date").html("Start date should be not greater than to current date");
    return false;
    }
    if (endDate > currDate){
    $("#e_to_date").html("End date should be not greater than to current date");
    return false;
    }
    if(startDate!='' && endDate!='')
    {
        if (startDate > endDate) {

            $("#e_to_date").html("End date should be greater than or equal to start date");
            return false;
        }
    }

    //table_kyc.destroy();
    table_kyc =$('#datatable_kyc').DataTable({ 
        "processing": false,
        "serverSide": true,
        "order": [],
        'destroy': true,
        "ajax": {
            "url": base_url+'RestaurantKyc/KycList',
            "type": "POST",
            "dataType":"JSON",
            'data': function(data){
                // Read values
                // Append to data
                data.searchByAdminId = admin_id;
                data.searchByFromDate = from_date;
                data.searchByToDate = to_date;
                console.log(data);
            },
        },
 
        "columnDefs": [
        { 
            "targets": [ -1 ],
            "orderable": false, 
        },
        ],

    });
}
function verify_kyc(admin_id,status) {
  var datastaring='';
  datastaring="admin_id="+admin_id+"&status="+status;
    $.ajax({
      url: base_url+'RestaurantKyc/verifyKyc',
      data: datastaring,
      type: "POST",
      success: function(suc) {
        var res=JSON.parse(suc);
        if(res.status=1)
        {
          alert(res.message)
        }
        datatable();
        
      }
    });
  }
</script>