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
                      <i class="fa fa-align-justify"></i>{{ __('Users') }}</div>
                    <div class="card-body">
                        <table id="userlist" class="table table-responsive-sm table-striped">
                        <thead>
                          <tr>
                            <th>No</th>
                            <th>Mac Address</th>
                            <th></th>
                          </tr>
                        </thead>
                        <tbody>
                          <!-- @foreach($users as $user)
                            <tr>
                              <td>{{ $user->name }}</td>
                              <td>{{ $user->email }}</td>
                              <td>{{ $user->menuroles }}</td>
                              <td>{{ $user->email_verified_at }}</td>
                              <td>
                                <a href="{{ url('/users/' . $user->id) }}" class="btn btn-block btn-primary">View</a>
                              </td>
                              <td>
                                <a href="{{ url('/users/' . $user->id . '/edit') }}" class="btn btn-block btn-primary">Edit</a>
                              </td>
                              <td>
                                @if( $you->id !== $user->id )
                                <form action="{{ route('users.destroy', $user->id ) }}" method="POST">
                                    @method('DELETE')
                                    @csrf
                                    <button class="btn btn-block btn-danger">Delete User</button>
                                </form>
                                @endif
                              </td>
                            </tr>
                          @endforeach -->
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
    
    var table = $('#userlist').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: 
        {       'url': "{{ route('users.getlist') }}",
                'type': 'POST',
                'data': {
                    '_token': '{{ csrf_token() }}',
                },},
         columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'email', name: 'email'},
            {data: 'view', name: 'view', orderable: false, searchable: false},
        ]
    });
    
  });
</script>
@endsection

