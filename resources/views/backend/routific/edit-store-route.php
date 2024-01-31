<?php

use JoeyCo\Laravel\Joey;
use JoeyCo\Laravel\MerchantIds;
use JoeyCo\Laravel\Task;

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
<script src="https://assets.staging.joeyco.com/js/admin/dispatch/jquery.maskedinput-1.3.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker3.min.css">
<script type='text/javascript' src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.min.js"></script>
<style>
.orders-table tbody td {
    padding: 12px;
}
.data-table th, .data-table td {
    padding: 15px 5px;
}
</style>
<section class="content">
    
        <h2 style="width:100%; background-color:#3e3e3e; color:white; text-align:center; font-size:20px; font-weight:bold; margin-bottom:25px; padding:10px 0px">Edit Store Route</h2>
                   
        <div class="clear"></div>

        <table class="data-table orders-table">
            <thead>
               
                <tr>
                    <th>Id</th>
                    <th>Task Id</th>
                    <th>Sprint Id</th>
                    <th>Type</th>
                    <th>Tracking Id</th>
                    <th>Merchant Order Number</th>
                    <th>Delivery Window</th>
                    <th>Address</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                
             <?php 
             date_default_timezone_set('America/Toronto');

             $i=1;
             foreach($route as $routeLoc) {
                 $task = Task::join('locations','location_id','=','locations.id')
                 ->where('sprint__tasks.id','=',$routeLoc->task_id)->first();

                 $merchantids = MerchantIds::join('sprint__tasks','task_id','=','sprint__tasks.id')
                 ->join('sprint__sprints','sprint_id','=','sprint__sprints.id')->where('sprint_id','=',$task->sprint_id)->first();
               
                 echo "<tr>";
                 echo "<td>".$i."</td>";
                 echo "<td>".$routeLoc->task_id."</td>";
                 echo "<td>CR-".$merchantids->sprint_id."</td>";
                 echo "<td>".$task->type."</td>";
                echo "<td>".$merchantids->tracking_id."</td>";
                 echo "<td>".$merchantids->merchant_order_num."</td>";
                 echo "<td>".$merchantids->start_time."-".$merchantids->end_time."</td>";
                 echo "<td>".$task->address."</td>";
                 echo "<td> <a class='button red-gradient' href='route/".$routeLoc->id."/delete/store'>Delete</a></td>";
                 echo "</tr>";
              
                 $i++;
             } ?>
            </tbody>
        </table>
    
</section>

<?php
\Laravel\Section::stop();
echo \Laravel\View::make('layouts.main')->with(get_defined_vars())->render();
