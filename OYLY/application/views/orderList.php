<div class="loading">Loading&#8230;</div>
        <div class="right_col" role="main">
          <div class="row">
                              <div class="x_content">
                      <div class="row">
                          <div class="col-sm-12">
                            <div class="card-box table-responsive">
                   
                    <table class="table table-striped table-bordered" id="orderListTable" style="width:100%">
                      <thead style="white-space: nowrap;">
                        <tr >
                          <th>Sr.No.</th>
                          <th>OrderId</th>
                          <th>Table No.</th>
                          <th>Items</th>
                          <th>Action</th>
                        </tr>
                      </thead>


                      <tbody >
                 
                      </tbody>
                    </table>
                  </div>
                  </div>
              </div>
              <div id="print" style="display:none"><table class="table table-striped table-bordered" id="orderTable" style="width:100%">
            </div>
          </div>
        </div>



<script type="text/javascript">
    $(document ).ready(function() {
     $('input').on('change', function(){
    $(this).parent().parent().removeClass('has-error');
    $(this).next().empty(); 
});
$("select").change(function(){
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });
});
</script>
<script type="text/javascript">
  var table;
  $(document).ready(function() {
    base_url    =$('#base_url').val();
    datatable();

});
function datatable() {
  table       =$('#orderListTable').DataTable({ 
        "processing": false,
        "serverSide": true,
        "order": [],
        'destroy': true,
       //  "language": {
       // "infoFiltered": "",
       //  processing:"<img src=<?php echo base_url();?>assets/images/ajax-loader.gif> Please Wait",
       //  search: "",searchPlaceholder:"Search" 

       //  },
        "ajax": {
            "url": base_url+'KotController/getOrderList',
            "type": "POST",
            "dataType":"JSON",
            "data": function ( data ) {

            }
        },
        "columnDefs": [
            { "orderable": false, "targets": [0,3,4] },
           { "orderable": true, "targets": [1,2] },
           ]

    });
}
function print_status(order_id){
    var base_url  =$('#base_url').val();
    $.ajax({
        url : base_url+"KotController/change_status",
        type: "POST",
        data:{"order_id":order_id},
        dataType: "JSON",
        success: function(data)
        {   
          console.log(data.status);
          if(data.status==1)
          {
            print_slip(order_id);
          }
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data');
        }
    });
}

function print_slip(order_id){
    var base_url  =$('#base_url').val();
    $.ajax({
        url : base_url+"KotController/printData",
        type: "POST",
        data:{"order_id":order_id},
        success: function(data)
        {   
          console.log("asas");
          $('.loading').css('display','none');
         // $('#print').css('display','block');
          $('#print').html(data);
          datatable();
          var a = window.open('', '', 'height=500, width=500');
          var getData=  $('#print').html();
            a.document.write(getData);
            a.document.close();
            a.print();
          //console.log(data.status);
          return false;
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data');
        }
    });
}


</script>