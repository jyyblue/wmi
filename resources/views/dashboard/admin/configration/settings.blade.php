@extends('dashboard.base')

@section('content')

        <div class="container-fluid">
          <div class="animated fadeIn">
            <div class="row">
              <div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
                <div class="card">
                    <div class="card-header">
                      <i class="fa fa-align-justify"></i> Settings</div>
                    <div class="card-body">
                      <form method="POST" action="{{route('config.settings.update')}}">
                      @csrf
                        <div class="row form-group">
                          <div class="col-md-6">
                            <label for="interval" class="from-control">Track Interval (min)</label>
                          </div>
                          <div class="col-md-6">
                            <input type="number" min="1" class="form-control" name="track_interval" 
                            value="{{$interval->value}}"/>
                          </div>
                        </div>
                        <div class="form-group row">
                          <div class="col-12">
                          <button type="submit" class="btn btn-pill btn-info pull-right">Update</button>
                          </div>
                        </div>
                      </form>
                    </div>
                </div>
              </div>
            </div>
          </div>
        </div>

@endsection


@section('javascript')

@endsection