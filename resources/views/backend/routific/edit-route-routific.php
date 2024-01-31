<?php

\Laravel\Asset::script('jquery-ui.js', '/js/jquery/jquery-ui-1.10.3.custom.min.js', array('jquery'));
\Laravel\Asset::style('jquery-ui.css', '/css/jqueryui/joeyco/jquery-ui-1.10.3.custom.min.css', array('fonts', 'reset', 'common', 'style'));

\Laravel\Asset::style('jquery-ui-1.10.3.custom.min.css', '/css/jqueryui/joeyco/jquery-ui-1.10.3.custom.min.css');
\Laravel\Asset::style('jquery-ui-timepicker.css', '/css/jqueryui/joeyco/jquery-ui-timepicker.css', array('jquery-ui-1.10.3.custom.min.css'));

\Laravel\Asset::script('jquery-ui-1.10.3.custom.min.js', '/js/jquery/jquery-ui-1.10.3.custom.min.js', array('jquery'));
\Laravel\Asset::script('jquery-ui-timepicker.js', '/js/jquery/jquery-ui-timepicker.js', array('jquery', 'jquery-ui-1.10.3.custom.min.js'));


\Laravel\Section::start('content');

?>
<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" > -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>
<script src="http://192.168.101.225/Scripts/jquery.maskedinput-1.3.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker3.min.css">
    <script type='text/javascript' src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.min.js"></script>
<style>
.modal {
    top: 0 !important;
    left: 0;
    margin: 0px;
}
.modal.fade{
    opacity: 1
}
/* .modal a.close-modal{
    top: -5px;
    right: -5px
} */
.form-control {
    display: block;
    width: 100%;
    height: 34px;
    padding: 6px 12px;
    font-size: 14px;
    line-height: 1.42857143;
    color: #555;
    background-color: #fff;
    background-image: none;
    border: 1px solid #ccc;
    border-radius: 4px;
    -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
    box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
    -webkit-transition: border-color ease-in-out .15s,-webkit-box-shadow ease-in-out .15s;
    -o-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
    transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
}
.form-control:focus {
    border-color: #66afe9;
    outline: 0;
    -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075), 0 0 8px rgba(102,175,233,.6);
    box-shadow: inset 0 1px 1px rgba(0,0,0,.075), 0 0 8px rgba(102,175,233,.6);
}
.form-group {
    margin-bottom: 15px;
}
.orders-table tbody td {
    padding: 12px;
}
.data-table th, .data-table td {
    padding: 15px 5px;
    }
    .modal-header{
        font-size:16px;
    }
    .modal-body h4 {
    background: #f6762c;
    padding: 8px 10px;
    margin-bottom: 10px;
    font-weight: bold;
    color: #fff;
}
span {
  content: "\2713";
}
</style>
<section class="content">
    <div>
        
    
        <h2 style="width:100%; background-color:#3e3e3e; color:white; text-align:center; 
        font-size:20px; font-weight:bold; margin-bottom:25px; padding:10px 0px">
        Route Id R-<?php echo $id?></h2>
           

      
        <div class="clear"></div>


      

        <table class="data-table orders-table">
            <thead>
               
                <tr>
                    <th>Id </th>
                    <th>  Mercahnt Order No </th>
                    <th>  Tracking Id </th>
                    <th>  Order No </th>
                    <th>  First Sort </th>
                    <th>  Second Sort </th>
                    <th>  Has Picked </th>
                    <th> Action </th>
                    
                </tr>
            </thead>
            <tbody>
                
             <?php 
             $i=1;
             foreach($datas as $data) {
                 echo "<tr>";
                 echo "<td>".$i."</td>";
                 echo "<td>".$data->attributes['merchant_order_num']."</td>";
                  echo "<td>".$data->attributes['tracking_id']."</td>";
                  echo "<td>CR-".$data->attributes['order_id']."</td>";
                  $da=JoeyCo\Laravel\TaskHistory::where('sprint__tasks_id','=',$data->attributes['task_id'])->get(['status_id']);
                  $F=0;
                  $S=0;
                  $H=0;

            foreach ($da as  $value) {
              $s=$value->attributes['status_id'];
                if($s==125){
                  $F=1;    
                }elseif ($s==133) {
                    $S=1;
                }
                elseif ($s==121) {
                    $H=1; 
                }
            }
                  if($F){
                      echo "<td> <span>&#10003;</span> First Sort</td>";
                  }
                  else{
                     echo "<td>  First Sort</td>";
                  }
                  if($S){
                    echo "<td> <span>&#10003;</span> Second Sort</td>";
                  }
                  else {
                   echo "<td>  Second Sort</td>";
                  }
                  if($H){
                    echo "<td> <span>&#10003;</span> Has Picked </td>";
                  }
                  else {
                   echo "<td>Has Picked</td>";
                  }
                  echo "<td><button type='button'  class='delete button red-gradient'  data-id = ".
                  $data->attributes['order_id'].">Delete</button></td>";
                 echo "</tr>";
                     $i++; } ?>
            </tbody>
        </table>





<script>
$(function() {
$(".delete").click(function(){
    var x = confirm("Are you sure you want to delete?");
  if(x)
      {
var element = $(this);
var del_id = element.attr("data-id");
    $.ajax({
        type: "GET",
        url: 'hub/'+del_id+'/delete/sorter',
        success: function(){   
         location.reload();  

    }
});
}
//return false;
});
});



 setTimeout(function() {
  location.reload();
}, 120000);
</script>



<?php
\Laravel\Section::stop();
echo \Laravel\View::make('layouts.main')->with(get_defined_vars())->render();
