@extends('dashboard.base')

@section('content')

<div class="container-fluid">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Hosts</div>
                    <div class="card-body">
                        <table id="host-list" class="table table-responsive   table-responsive-sm">
                            <thead>
                              <tr>
                              <td>Host</td>
                              @for($i=1; $i <= 24; $i++)
                              <td ><span class="t-vertical">{{$i}}:00</span></td>
                              @endfor

                              </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection


@section('javascript')

@endsection