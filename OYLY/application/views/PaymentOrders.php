<div class="loading">Loading&#8230;</div>
        <div class="right_col" role="main">

          <div class="row">
                     
                          <div class="x_content">
                      <div class="row">
                          <div class="col-sm-12">
         <div class="col-md-12">
               
          <!-- top tiles -->
          <div class="row" style="display: inline-block;" >
          <div class="tile_count">
            <div class="col-md-2 col-sm-4  tile_stats_count">
              <span class="count_top">Total Order Amount</span>
              <div class="count" id='total_order_amount'></div>
              
            </div>
            <div class="col-md-2 col-sm-4  tile_stats_count">
              <span class="count_top">Total Settled Amount</span>
              <div class="count"id="total_settlement_amount"></div>
             
            </div>
            <div class="col-md-2 col-sm-4  tile_stats_count">
              <span class="count_top">Total Gst Amount</span>
              <div class="count green"id="total_gst"></div>
             
            </div>
            <div class="col-md-2 col-sm-4  tile_stats_count">
              <span class="count_top">Total Service Charge</span>
              <div class="count"id="total_service_charge"></div>
              
            </div>
            <div class="col-md-2 col-sm-4  tile_stats_count">
              <span class="count_top">Total Restaurant Amount</span>
              <div class="count"id="resto_amount"></div>
             
            </div>
            <div class="col-md-2 col-sm-4  tile_stats_count">
              <span class="count_top">Total Conveinec Fees</span>
              <div class="count"id="conveince_charge"></div>
             
            </div>
            <div class="col-md-2 col-sm-4  tile_stats_count">
              <span class="count_top">Total Refund Amount</span>
              <div class="count"id="Refund_amount"></div>
             
            </div>
          </div>
        </div>
          <hr>
          <form method="Post"id="PaymentTxnForm">
          <div class="col-md-1">
             <label >Select From date:</label>
            </div>
            <div class="col-md-2">
             <input type="date" name="from_date" id="from_date" placeholder="Enter from date"class="form-control" value="<?php echo set_value('from_date'); ?>" />
            </div>
            <div class="col-md-1">
             <label >Select To date:</label>
            </div>
            <div class="col-md-2">
             <input type="date" name="to_date" id="to_date" placeholder="Enter from date"class="form-control" value="<?php echo set_value('to_date'); ?>" />
            </div>
             <div class="col-md-1">
             <label >Select Restaurant:</label>
            </div>
            <div class="col-md-2">
              <select name="admin_id" id="admin_id"class="form-control" onchange="submitForm()">
                <option value="">---Select---</option>

                <?php
                 
                  foreach ($results as $value){?>

                   <option value="<?php echo $value['admin_id']?>"<?php echo set_value('admin_id')==$value['admin_id']?'selected':''?>><?php echo  $value['name'];?></option>
                 <?php }

                ?>
              </select>
            
            </div>
            <!--  <div class="col-md-1">
             <input type="button" class="form-control btn btn-primary" id="search"value="Search" />
            </div> -->

              <div class="col-md-1">
             <input type="button" class="form-control btn btn-primary" id="Payment"value="Payment" />
            </div>

          </form>
            
           
          <div class="card-box table-responsive">
                   
                    <table class="table table-striped table-bordered" id="PaymentOrderTable" style="width:100%">
                      <thead>
                        <tr >
                          <th><input type="checkbox" name="chech_all"class="chech_all"></th>
                          <th>Sr.No.</th>
                          <th>OrderId</th>
                          <th>Mobile No</th>
                          <th>Order Amount(Rs)</th>
                          <th>Service Charge(Rs)</th>
                          <th>Settled Amount(Rs)</th>
                          <th>Gst Amount(Rs)</th>
                          <th>Restaurant Amount(Rs)</th>
                          <th>Convenience+Gst Amount(Rs)</th>
                          <th>Creation Date</th>                      
                          <th>Refund Status</th>
                          <th>Status</th>
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
<style type="text/css">
  .count{
    font-size: 28px ! important;
  }
</style>
<script type="text/javascript">
  var table;
  $(document).ready(function(){
    var base_url     =$('#base_url').val();
    var from_date   =$('#from_date').val();
    var to_date     =$('#to_date').val();
    var admin_id    =$('#admin_id option:selected').val();
    table       =$('#PaymentOrderTable').DataTable({ 

        "processing": false,
        "serverSide": true,
        "order": [],
        "ajax": {
            "url": base_url+'PaymentTxns/GetPaymentTxns',
            "type": "POST",
            "data":{'from_date':from_date,'to_date':to_date,'admin_id':admin_id},
            "dataType":"JSON",
            // "success": function ( data ) {

            // }
        },
 
        "columnDefs": [
            { "orderable": false, "targets": [0,1,9,10,12,13] },
           { "orderable": true, "targets": [2,3,4,5,6,7,8,11] },
           ]

    });
    getPaymentsAmount(admin_id,from_date,to_date);
   

});
$('.chech_all').on('change', function() {     

          if($(this).prop("checked") == true){
                $('.chechall').prop('checked',true);
                
            }
            else if($(this).prop("checked") == false){
               $('.chechall').prop('checked',false);
              
            }

});

  function getPaymentsAmount(admin_id,from_date,to_date)
  {
      var base_url  =$('#base_url').val();
      $.ajax({
          url : base_url+"PaymentTxns/getPaymentsAmount",
          type: "POST",
          data:{"admin_id":admin_id,'from_date':from_date,'to_date':to_date},
          dataType: "JSON",
          success: function(data)
          {   
            console.log(data);
            if(data.status)
            {

              $('#total_order_amount').html((data.result['Total_amount']));
              $('#total_settlement_amount').html(data.result['settled_amount']);
              $('#total_gst').html(data.result['total_gst']);
              $('#total_service_charge').html(data.result['service_charge']);
              $('#resto_amount').html(data.result['total_resto_amount']);
              $('#conveince_charge').html(data.result['convenience']);
              $('#Refund_amount').html(data.result['Refund_amount']);
              $('#admin_id').val('');
            }
          }
      });
  }
$('#search').click(function(){

      var from_date   =$('#from_date').val();
      var to_date     =$('#to_date').val();
      var base_url    =$('#base_url').val();
      var admin_id    =$('#admin_id').val();
      $('#PaymentOrderTable').DataTable().destroy();
       table = $('#PaymentOrderTable').DataTable({
      "processing": false,
      "serverSide": true,
      "order": [],
      "ajax": {
          url : base_url+"PaymentTxns/GetPaymentTxns",
          type: "POST",
          data:{'from_date':from_date,'to_date':to_date,'admin_id':admin_id},
      },
      "columnDefs": [
            { "orderable": false, "targets": [0,1,9,10,12,13] },
           { "orderable": true, "targets": [2,3,4,5,6,7,8,11] },
           ]

    });
});
function submitForm()
{
  $('#PaymentTxnForm').submit();
}
$('#Payment').click(function(){

     var didConfirm = confirm("Are you sure You want to Payment ?");

     if(didConfirm)
     {
      if($(":checkbox:checked").length > 0)
        {
            var base_url  =$('#base_url').val();
            var array=[];
            $('input:checkbox[name=chechall]:checked').each(function() 
            {
              if($(this).is(':checked'))
              {
               array.push($(this).val());
              }
            });
            $.ajax({
                url : base_url+"PaymentTxns/UpdatePayment",
                type: "POST",
                data:{"order_idString":array},
                dataType: "JSON",
                success: function(data)
                {   
                  if(data.status){
                    alert(data.result);
                    $('#PaymentOrderTable').DataTable().ajax.reload();
                  }else{
                      alert(data.result);
                  }

          }
      });
        }else
        {
          alert("Please select Orders.");
        }
     }else
     {

     }
    
});
</script>