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
            <h4 class="card-title">Edit Plan</h4>
            {!! Form::open(['method' => 'PUT','route' => ['plans.update', isset($plans->plan_id) ? ($plans->plan_id) : 0] ,'enctype="multipart/form-data"']) !!}
            {{Form::token()}}
              <div class="form-group">
                {{ Form::label('Plan Title', null) }}
                {{ Form::text('plan_title',isset($plans->plan_title) ? $plans->plan_title : '',['class' => 'form-control']) }}                    
              </div>
              <div class="form-group">
              <div class="form-group">
                {{ Form::label('Plan Price', null) }}
                {{ Form::text('plan_price',isset($plans->plan_price) ? $plans->plan_price : '',['class' => 'form-control']) }}                    
              </div>    
                {{ Form::label('Plan Duration is days', null) }}
                {{ Form::text('plan_duration',isset($plans->plan_duration) ? $plans->plan_duration : '',['class' => 'form-control']) }}                    
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