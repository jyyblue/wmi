@extends('dashboard.base')

@section('css')
<link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
@endsection

@section('content')

        <div class="container-fluid">
          <div class="animated fadeIn">
            <div class="row">
              <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <div class="card">
                    <div class="card-header">
                      <i class="fa fa-align-justify"></i>{{ __('Track Data') }} | {{$user->email}}  </div>
                      <input type="hidden" id="user_id" value="{{$user->id}}"/>                      
                    <div class="card-body">
                        <table id="tracklist" class="table table-responsive-sm">
                        <thead>
                          <tr>
                            <th>No</th>
                            <th>Time</th>
                            <th>Active App</th>
                          </tr>
                        </thead>
                        <tbody>
                        </tbody>
                      </table>
                    </div>
                </div>
              </div>
            </div>
          </div>
        </div>

@endsection


@section('javascript')
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
  $(function () {
    var data ={};
    data['_token'] = '{{ csrf_token() }}';
    data['user_id'] = $('#user_id').val();
    var table = $('#tracklist').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: 
        {       'url': "{{ route('track.getData') }}",
                'type': 'POST',
                'data': data,
                },
         columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'created_at', name: 'created_at'},
            {data: 'app', name: 'app'},
        ]
    });
  });
</script>
@endsection

