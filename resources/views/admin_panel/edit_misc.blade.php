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
            <h4 class="card-title">Edit Misc</h4>
            {!! Form::open(['method' => 'PUT','route' => ['misces.update', isset($misc_data->misc_id) ? ($misc_data->misc_id) : 0] ,'enctype="multipart/form-data"']) !!}
            {{Form::token()}}
              <div class="form-group">
                {{ Form::label('Misc Title', null) }}
                {{ Form::text('misc_title',isset($misc_data->misc_title) ? $misc_data->misc_title : '',['class' => 'form-control']) }}                    
              </div>
              @php
                $misc_type = [
                1001     =>  'Gender',
                1002     =>  'Sexual Orientation',
                1003       =>  'Intrested In'
                ];
                @endphp
              <div class="form-group">
                {{ Form::label('Select Misc Type', null) }}
                {{ Form::select('misc_type', $misc_type, $misc_data->misc_type, ['class' => 'form-control','placeholder' => 'Select Misc Type']) }}
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