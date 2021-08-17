var table;
$(document).ready(function() {
    base_url    =$('#base_url').val();
    table       =$('#datatable_orders').DataTable({ 
        "processing": false,
        "serverSide": true,
        //"scrollY": 200,
        "scrollX": true,
        "order": [],
        "ajax": {
            "url": base_url+'Admin/restaurantList',
            "type": "POST",
            "dataType":"JSON",
            "data": function ( data ) {
            }
        },
 
        "columnDefs": [
            { "orderable": false, "targets": [0,7,5,6] },
           { "orderable": true, "targets": [1,2,3,4] },
           ]

    });
});
function advance_search()
{
    $("#e_from_date").html("");
    $("#e_to_date").html("");
    var from_date=$('#from_date').val();
    var to_date=$('#to_date').val();
    var spot_id=$('#spot_id').val();
    var startDate='';
    if(from_date=='' && to_date=='' && spot_id=='')
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

    table.destroy();
    table =$('#datatable_orders').DataTable({ 
        "processing": false,
        "serverSide": true,
        "order": [],
        //"scrollY": 200,
        "scrollX": true,
        "ajax": {
            "url": base_url+'Admin/restaurantList',
            "type": "POST",
            "dataType":"JSON",
            'data': function(data){
                // Read values
                // Append to data
                data.searchBySpotId = spot_id;
                data.searchByFromDate = from_date;
                data.searchByToDate = to_date;
                console.log(data);
            },
        },
 
        "columnDefs": [
            { "orderable": false, "targets": [0,7,5,6] },
           { "orderable": true, "targets": [1,2,3,4] },
           ]

    });
}