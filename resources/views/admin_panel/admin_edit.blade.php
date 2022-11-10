@extends('admin_panel.layouts.app')
@section('content')
<!-- partial -->
<div class="main-panel">        
  <div class="content-wrapper">
    <div class="row">
      @include('admin_panel.inc.validation_message')
      @include('admin_panel.inc.auth_message')
      <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">Update Admin User</h4>
            {!! Form::open(['method' => 'PUT','route' => ['admin.update', isset($record_data) ? base64_encode($record_data->id) : base64_encode(0)] ,'enctype="multipart/form-data"']) !!}
            {{Form::token()}}
              <div class="form-group">
                {{ Form::label('FullName', null) }}
                {{ Form::text('full_name',$record_data->full_name,['class' => 'form-control']) }}                    
              </div>
              <div class="form-group">
                {{ Form::label('Email Address', null) }}

                {{ Form::text('email_address',$record_data->email_address,['class' => 'form-control']) }}
              </div>
              <div class="form-group">
                {{ Form::label('Password', null) }}

                {{ Form::password('password',['class' => 'form-control']) }}
              </div>                  
              <button type="submit" class="btn btn-primary mr-2">Update</button>                  
            </form>
          </div>
        </div>
      </div>            
    </div>
  </div>        
  <!-- partial -->
</div>
<!-- main-panel ends -->
</div>
<!-- page-body-wrapper ends -->
</div>
<!-- container-scroller -->
@endsection  