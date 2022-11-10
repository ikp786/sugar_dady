@extends('admin_panel.layouts.app')
@section('content')
<!-- partial -->
<div class="main-panel">        
  <div class="content-wrapper">
    <div class="row">
      @include('admin_panel.inc.validation_message')
      @include('admin_panel.inc.auth_message')
      <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">Add Plan</h4>
            <a href="{{ route('plans.index') }}" class="btn btn-primary"> Plan List</a>

            {!! Form::open(['method' => 'POST','route' => ['plans.store'] ,'enctype="multipart/form-data"']) !!}
            {{Form::token()}}
            <br>
              <div class="form-group">
                {{ Form::label('Plan Title', null) }}
                {{ Form::text('plan_title','',['class' => 'form-control','placeholder' => 'Plan Title']) }}                    
              </div>           
              <div class="form-group">
                {{ Form::label('Plan Price', null) }}
                {{ Form::text('plan_price','', ['class' => 'form-control','placeholder' => 'Plan Price']) }}
              </div>      
              
              <div class="form-group">
                {{ Form::label('Plan Duration In days', null) }}
                {{ Form::text('plan_duration','',['class' => 'form-control','Plan Duration In days']) }}
              </div>    

              <button type="submit" class="btn btn-primary mr-2">Save</button>                  
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