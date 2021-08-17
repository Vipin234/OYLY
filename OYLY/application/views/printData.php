<?php
$html='<style>
table, th, td {
  border: 1px solid black;
  border-collapse: collapse;
}
th, td {
  padding: 5px;
}
th {
  text-align: left;
}
</style>';
$html.='<table class="table table-striped table-bordered" id="orderListTable" style="width:100%;"><thead style="white-space: nowrap;"><tr ><th>OrderId</th><th>Table No.</th><th>Items</th></tr></thead><tbody id="tbody_data">';
    if(!empty($res))
    {
      foreach($res as $row)
      {
        $html.="<tr>";
        $html.="<td>".$row[0]."</td>";
        $html.="<td>".$row[1]."</td>";
        $html.="<td>".$row[2]."</td>";
      }
    }
    $html.="</tbody> </table>";
    echo $html;
  ?>
