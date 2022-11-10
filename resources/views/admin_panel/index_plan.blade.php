@extends('admin_panel.layouts.app')
@section('content')
      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">            
            @include('admin_panel.inc.validation_message')
            @include('admin_panel.inc.auth_message')           
            <div class="col-lg-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Plan List</h4>
                  <p class="card-description">
                  <a href="{{ route('plans.create') }}" class="btn btn-primary">Add New Plan</a>                    
                  </p>
                  <div class="table-responsive">
                    <table class="table table-hover">
                      <thead>
                        <tr>
                          <th>ID</th>
                          <th>Plan Title</th>
                          <th>Plan Price</th>                          
                          <th>Plan Duration</th>
                          <th>Edit</th>
                          <th>Delete</th>
                        </tr>
                      </thead>
                      <tbody>
                      @forelse ($plans as $plan)
                        <tr>
                          <td>{{$plan->plan_id}} </td>                          
                          <td>{{$plan->plan_title}}</td>
                          <td>{{$plan->plan_price}}</td>
                          <td>{{$plan->plan_duration}}</td>
                          <td>
                          <a href="{{ route('plans.edit',$plan->plan_id) }}" class="btn btn-sm btn-success">
                             <i class="fa fa-edit"></i>
                          </a>
                          </td>
                          <td>
                          <form action="{{ route('plans.destroy', $plan->plan_id) }}" class="d-inline-block" method="post">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Are you sure to delete this Plan?')" class="btn btn-sm btn-danger"><i class="fa fa-trash" style="font-size:14px"></i></button>
                          </form>
                          </td>
                        </tr>
                        @empty
                        <tr>
                        <td colspan="100%" class="text-center text-muted py-3">No Misc Found</td>
                        </tr>
                        @endforelse
                      </tbody>
                    </table>
                    @if($plans->total() > $plans->perPage())
                    <br><br>
                    {{$plans->links()}}
                    @endif
                  </div>
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