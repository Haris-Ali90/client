
@extends( 'backend.layouts.app' )

@section('title', 'Toronto Dashboard')
<link href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">

<style>
.dataTables_length,
.dataTables_wrapper {
  font-size: 1.6rem;
  select,
  input {
    background-color: #f9f9f9;
    border: 1px solid #999;
    border-radius: 4px;
    height: 3rem;
    line-height: 2;
    font-size: 1.8rem;
    color: #333;
  }

  .dataTables_length,
  .dataTables_filter {
    margin-top: 30px;
    margin-right: 20px;
    margin-bottom: 10px;
    display: inline-flex;
  }
}

// paginate

.paginate_button {
  min-width: 4rem;
  display: inline-block;
  text-align: center;
  padding: 1rem 1.6rem;
  margin-top: -1rem;
  border: 2px solid lightblue;
  &:not(.previous) {
    border-left: none;
  }
  &.previous {
    border-radius: 8px 0 0 8px;
    min-width: 7rem;
  }
  &.next {
    border-radius: 0 8px 8px 0;
    min-width: 7rem;
  }

  &:hover {
    cursor: pointer;
    background-color: #eee;
    text-decoration: none;
  }
}


th{
    font-family: "Helvetica Neue", Roboto, Arial, "Droid Sans", sans-serif;
    font-size: 13px;
}
.data_not{
        text-align: center;
        font-weight: 700;
    }
</style>

@section('dataTablJs')
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
        $("#example").DataTable();
      });
      
      </script>

@endsection
@section('content')



    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3 class="text-center">Notification Order<small></small></h3>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <form method="get" action="">
                                <div class="row">
                                    <div class="col-md-3" hidden>
                                        <label>Search By Date :</label>
                                        <input type="date" hidden name="datepicker" class="data-selector form-control" required=""
                                               value="{{ isset($_GET['datepicker'])?$_GET['datepicker']: date('Y-m-d') }}">
                                        <input type="hidden" name="type" value="delivered" id="type">
                                    </div>
                                    <div class="col-md-3">
                                       
                                    </div>
                                    <div class="col-md-9 sm_custm">
                                        @if(can_access_route('newexport_MontrealDelivered.excel',$userPermissoins))
                                            <div class="excel-btn" style="float: right">
                                                <a href="{{ route('newexport_MontrealDelivered.excel') }}"
                                                   class="btn buttons-excel buttons-html5 btn-sm sub-ad c-color excelstyleclass">
                                                    Export to Excel
                                                </a>
                                            </div>
                                        @endif
                                        <div class="excel-btn" style="float: right">
                                            <a href="#"
                                               class="btn buttons-reload buttons-html5 btn-sm sub-ad c-color excelstyleclass">
                                                Reload
                                            </a>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </form>

                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                         

                            <div class="container">
                                <div class="row">
                                    <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Customer Name</th>
                                            <th>Address </th>
                                            <th>Tracking Id</th>
                                            <th>Created At</th>
                                            <th>Delivered At</th>
                                        </tr>
                                    </thead>
                                  
                                    <tbody>
                                        @foreach ($notification_query as $delay_order)
                                          <tr>
                                              <td>{{$delay_order->customer_name}}</td>
                                              <td>{{$delay_order->address_line_1}}</td>
                                              <td>{{$delay_order->tracking_id}}</td>
                                              <td>{{$delay_order->created_at}}</td>
                                              <td>{{$delay_order->delivered_at}}</td>
                                          </tr>
                                          @endforeach
                                      </tbody>
                                  </table>
                                  <div class="col-md-12">
                                    @if ($notification_query->isEmpty())
                                    <p class="data_not">    No data available in table
                                    </p>
                                    @endif
                                    {!! $notification_query->links() !!}
                                </div>
                                </div>
                              </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer id="footer" class="d-flex align-items-center justify-content-center ">

<div class="logo-col"><img src="http://localhost/client/public/images/joeyco-footer.png" alt="" width="150"></div>

<ul class="no-list d-flex align-items-center justify-content-center">

    <li><a href="http://localhost/client/public/privacy-policy">Privacy Policy</a></li>

    <li><a href="http://localhost/client/public/terms-conditions">Terms And Conditions</a></li>

    <li><a href="http://localhost/client/public/agreement">Terms Of Use</a></li>

</ul> 

</footer>

    <!-- /#page-wrapper -->

 
@endsection

