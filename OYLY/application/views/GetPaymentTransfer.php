<!-- <div class="loading">Loading&#8230;</div> -->
        <div class="right_col" role="main">
          <div class="row">
                              <div class="x_content">
                      <div class="row">
                          <div class="col-sm-12">
                            <div class="card-box table-responsive">
                   
                    <table class="table table-striped table-bordered" id="PaymentTable" style="width:100%">
                      <thead style="white-space: nowrap;">
                        <tr >
                          <th>Sr.No.</th>
                          <th>Invoice No</th>
                          <th>OrderId</th>
                          <th>Total Order Amount(Rs)</th>
                          <th>Discount(Rs)</th>
                          <th>Gst(Rs)</th>
                          <th>Discounted Amount(Rs)</th>
                          <th>Payble Amount By Customer(Rs)</th>
                          <!-- <th>Service Charge(Rs)</th> -->
                          <!-- <th>Refund Amount(Rs)</th>                       -->
                          <!-- <th>Payble Amount To Restaurant(Rs)</th> -->
                          <!-- <th>Convince Charge with Gst(Rs)</th> -->
                          <!-- <th>Uploaded Date</th> -->
                          <!-- <th>Status</th> -->
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
  $(document).ready(function() {
    base_url    =$('#base_url').val();
    table       =$('#PaymentTable').DataTable({ 
        "processing": false,
        "serverSide": true,
        "order": [],
       //  "language": {
       // "infoFiltered": "",
       //  processing:"<img src=<?php echo base_url();?>assets/images/ajax-loader.gif> Please Wait",
       //  search: "",searchPlaceholder:"Search" 

       //  },
        "ajax": {
            "url": base_url+'GetPaymentTransfer/GetPaymnetTransferHistory',
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
</script>