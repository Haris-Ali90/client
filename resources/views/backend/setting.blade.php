@extends( 'backend.layouts.app' )

@section('title', 'Dashboard')

@section('CSSLibraries')
    <style>
        .dashboard-statistics-box {
            min-height: 400px;
            margin: 15px 0px;
            position: relative;
            box-sizing: border-box;
        }

        .dashboard-statistics-box.dashboard-statistics-tbl-show td {
            padding-top: 52px;
            padding-bottom: 52px;
        }

    </style>
@endsection
@section('JSLibraries')
    <script src="{{ backend_asset('libraries/Chart.js/dist/Chart.min.js') }}"></script>
    <script src="{{ backend_asset('nprogress/nprogress.js') }}"></script>
    <script src="{{ backend_asset('libraries/gauge.js/dist/gauge.min.js') }}"></script>
    <script src="{{ backend_asset('libraries/skycons/skycons.js') }}"></script>
    <script src="{{ backend_asset('libraries/Chart.js/dist/Chart.min.js') }}"></script>

@endsection


@section('content')
    <!--right_col open-->
    <div class="right_col" role="main">
        @if (Session::has('error'))
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                {!! Session::pull('error') !!}
            </div>
    @endif

    <!-- BEGIN CONTENT -->
        <div class="page-content-wrapper">

            <!--loader-mian-wrap-open-->
            <div class="row loader-mian-wrap show">

                <!--loader-inner-wrap-open-->
                <div class="col-sm-12 loader-inner-wrap">
                    <div class="lds-roller">
                        <div class="dot-1"></div>
                        <div class="dot-2"></div>
                        <div class="dot-3"></div>
                        <div class="dot-4"></div>
                        <div class="dot-5"></div>
                        <div class="dot-6"></div>
                        <div class="dot-7"></div>
                        <div class="dot-8"></div>
                    </div>
                </div>
                <!--loader-inner-wrap-close-->

            </div>
            <!--loader-mian-wrap-close-->

            <!--progress-bar-loader-open-->
            <div class="progress-main-wrap">
                <div class="progress">
                    <p class="progress-label">Downloading in progress . . . .</p>
                    <p class="error-report">Connection lost, trying to reconnect . . .</p>
                    <div aria-valuemax="100" aria-valuemin="0" aria-valuenow="0"
                         class="progress-bar progress-bar-striped active" role="progressbar" style="width:0%">Creating file in progress ...
                    </div>
                </div>
            </div>
            <!--progress-bar-loader-close-->
            <div class="page-content">
                <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
                <div aria-hidden="true" aria-labelledby="myModalLabel" class="modal fade" id="confirmDelete" role="dialog"
                     tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button aria-hidden="true" class="close" data-dismiss="modal" type="button"></button>
                                <h4 class="modal-title">Delete Record</h4>
                            </div>
                            <div class="modal-body">
                                Are you sure you want to delete this record?
                            </div>
                            <div class="modal-footer">
                                <button class="btn default" data-dismiss="modal" type="button">No</button>
                                <button class="btn blue" data-dismiss="modal" id="deleteButton" type="button">Yes</button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
                <!-- /.modal -->
                <!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
                <!-- BEGIN PAGE HEADER-->
                <div class="session-wrapper ">


                </div>
                <div class="row">
                    <div class="col-md-12">
                        <!-- BEGIN PAGE TITLE & BREADCRUMB-->
                        <h3 class="page-title">Permissions List<small> </small></h3>



                        <!-- END PAGE TITLE & BREADCRUMB-->
                    </div>
                </div>
                <!-- END PAGE HEADER-->

                <!-- BEGIN PAGE CONTENT-->
                <div class="row">
                    <div class="col-md-12">

                        <!-- Action buttons Code Start -->
                        <div class="row">
                            <div class="col-md-12">
                                <!-- Add New Button Code Moved Here -->
                                <div class="table-toolbar pull-right">
                                </div>
                                <!-- Add New Button Code Moved Here -->
                            </div>
                        </div>
                        <!-- Action buttons Code End -->


                        <!-- BEGIN EXAMPLE TABLE PORTLET-->
                        <div class="portlet box blue">

                            <div class="portlet-title">
                            </div>

                            <div class="portlet-body custom_permissions">
                                <!--table-top-form-from-open-->
                                <!--apply-system-parameters-sec-wrap-open-->
                                <div class="row apply-system-parameters-sec-wrap custom_permissions" id="permit_div">

                                    <div class="col-md-12 apply-system-parameters-heading">
{{--                                        <h2>Permissions</h2>--}}
                                    </div>


                                </div>
                                <!--check-box-wapper-row-close-->


                            </div>
                            <!--apply-system-parameters-sec-wrap-close-->

                            <!--table-top-form-from-close-->
                            <!--totals table close-->

                        </div>
                    </div>
                    <!-- END EXAMPLE TABLE PORTLET-->
                </div>
            </div>

            <form action="" method="">

            </form>
            <!-- END PAGE CONTENT-->
        </div>
    </div>
    <!-- END CONTENT -->


    </div>

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>


    {{-- My Ajax call -- By Daniyal Khan --}}
    <script>

        // Main Ajax Call For Setting Permission...
        $(document).ready(function(){
            function load() {

                getPermissionsMain();
            }
            window.onload = load;


            //Main Function to get the permissions...
            function getPermissionsMain(){

                <?php
                $all_requested_permissions_array = [];


                foreach ($all_requested_permissions as $x => $value){

                    array_push($all_requested_permissions_array,str_slug($value['process_title']));
                }

//                                dd($all_requested_permissions_array);
                ?>
                var all_requested_parsed_data = <?php echo json_encode($all_requested_permissions_array); ?>;
                console.log(all_requested_parsed_data);




                $.ajax({
                    url: '../getAllDefinedPermissions',
                    type:'get',
                    dataType:'json',
                    success: function(response){
            // console.log(response)
                        <?php
                        //Mark:- Checking User Credential...
                        $auth_user = Auth::user();
                        $hubPermissoins = $auth_user->hubPermissions();

                        $all_assigned_permission_array = [];


                        foreach ($hubPermissoins as $x){
                            array_push($all_assigned_permission_array,str_slug($x));
                        }

                        ?>

                        var all_assigned_parsed_data = <?php echo json_encode($all_assigned_permission_array); ?>;
                        var users_hub_id = <?php echo $auth_user->hub_id ?>;
                        var users_id = <?php echo $auth_user->id ?>;

                        // console.log(all_assigned_parsed_data)

                        $.each(response, function( index, value ) {
                            console.log(value)

                            var response_value = value['process_label']

                            // response_value = response_value.toLowerCase();
                            // response_value = response_value.replace(/ /g,"-");

                            if(all_assigned_parsed_data.includes(response_value)){
                                var is_requested = "approved";
                                var permit = "checked disabled";
                                var is_check = "";
                            }else{

                                    if(all_requested_parsed_data.length == 0){
                                        console.log("No pending request")
                                    }else{
                                        if(all_requested_parsed_data.includes(response_value)){
                                            var is_requested = "pending"
                                            console.log("The pending request of: ")
                                            console.log(response_value)
                                        }


                                    }


                                var id = response[index]['id']

                                var permit = "";
                                var is_check = "pop_desc";



                                $(document).on('click','.pop_desc',function (){
                                    var checkbox_value = $(this);
                                    checkbox_value = checkbox_value.find('.checkbox').val();
                                    // console.log(el.find('.checkbox').val());
                                    const swalWithBootstrapButtons = swal.mixin({
                                        customClass: {
                                            confirmButton: 'btn btn-success',
                                            cancelButton: 'btn btn-danger'
                                        },
                                        buttonsStyling: false
                                    })

                                    swalWithBootstrapButtons.fire({
                                        title: 'Are you sure?',
                                        text: "You Will Pay 10$ For This Request Request!",
                                        icon: 'warning',
                                        showCancelButton: true,
                                        confirmButtonText: 'Yes,I payed!',
                                        cancelButtonText: 'No, cancel!',
                                        reverseButtons: true
                                    }).then((result) => {
                                        if (result.isConfirmed) {

                                            //Submitting Data in Hub_Process Table...
                                            $.ajax({
                                                url: '../microhub/RequestNewPermission',
                                                type:'POST',
                                                data:{
                                                    "hub_id": users_hub_id,
                                                    "process_id": checkbox_value
                                                },
                                                success: function(response){
                                                    var is_requested = "pending";
                                                    console.log(response);



                                                    //Submitting Data in Micro_hub_Permissions Table...
                                                    $.ajax({
                                                        url: '../microhub/PostToMicroHubPermissons',
                                                        type:'POST',
                                                        data:{
                                                            "hub_process_id": response
                                                        },
                                                        success: function(response){
                                                            var is_requested = "pending";
                                                            console.log(response);
                                                            window.location.reload();
                                                        },
                                                        error: function (error) {
                                                            console.log(error);
                                                        }
                                                    })//Submitting Data in Micro_hub_Permissions Table End here...



                                                    window.location.reload();
                                                },
                                                error: function (error) {
                                                    console.log(error);
                                                }
                                            }) //Submitting Data in Hub_Process Table End here...




                                        } else if (
                                            result.dismiss === Swal.DismissReason.cancel
                                        ) {
                                            swalWithBootstrapButtons.fire(
                                                'Cancelled',
                                                'I dont Need:)',
                                                'Done'
                                            )
                                        }
                                    })
                                })


                            }

                            $("#permit_div").append(`<div class="row check-box-wapper-row"  >
                                    <div class="col-md-4 col-sm-4 col-xs-6 apply-show-hide-col-joeypayout ${is_requested}">
                                        <div class="form-group permission inner ${is_check}">
                                                <input class="datatable-column-handle checkbox"
                                                   data-targeted-column="30" type="checkbox" ${permit} id="myCheck" value="${response[index]['id']}">
                                            <lable class="control-label">${response[index]['process_title']}</lable>
                                        </div>
                                    </div>`);

                        })
                    }
                });
            }


        });

    </script>
@endsection

