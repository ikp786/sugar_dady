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
            <h4 class="card-title">Add Misc</h4>
            <a href="{{ route('misces.index') }}" class="btn btn-primary"> Misc List</a>

            {!! Form::open(['method' => 'POST','route' => ['misces.store'] ,'enctype="multipart/form-data"']) !!}
            {{Form::token()}}
              <div class="form-group">
                {{ Form::label('Misc Title', null) }}
                {{ Form::text('misc_title','',['class' => 'form-control']) }}                    
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
                {{ Form::select('misc_type', $misc_type, $misc_type, ['class' => 'form-control','placeholder' => 'Select Misc Type']) }}
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