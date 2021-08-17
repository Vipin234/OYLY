var table;
$(document).ready(function() {
    base_url    =$('#base_url').val();
    table       =$('#datatable_orders').DataTable({ 
        "processing": false,
        "serverSide": true,
        "order": [],
        "ajax": {
            "url": base_url+'Admin/restaurantList',
            "type": "POST",
            "dataType":"JSON",
            "data": function ( data ) {
            }
        },
 
        "columnDefs": [
        { 
            "targets": [ -1 ],
            "orderable": false, 
        },
        ],

    });
});

