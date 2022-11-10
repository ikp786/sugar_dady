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
                  <h4 class="card-title">Misc List</h4>
                  <p class="card-description">                                
                  </p>
                  <div class="table-responsive">
                    <table class="table table-hover">
                      <thead>
                        <tr>
                          <td>User Photo</td>
                          <th>ID</th>
                          <th>Full Name</th>
                          <th>Mobile</th>                          
                          <th>Email</th>
                          <th>Location</th>
                          <th>Show</th>
                        </tr>
                      </thead>
                      <tbody>
                      @forelse ($users as $user)
                        <tr>                          
                        @php
                        $image = isset($user->userImages[0]->image_name) ? $user->userImages[0]->image_name : '';//;die;
                        @endphp
                          <td><img src="{{asset('storage/user_images/'.$image)}}" style="max-height: 50px; max-width: 50px; border-radius: 15px;"></td>
                          <td>{{$user->user_id}} </td>
                          <td>{{$user->full_name}}</td>
                          <td>{{$user->mobile_number}}</td>
                          <td>{{$user->email_address}} </td>  
                          <td>{{$user->user_location}} </td>
                          <td><a href="{{route('users.show',$user->user_id)}}"><i class="fa fa-eye" style="font-size:25px;color:red"></i></a> </td>                          
                        </tr>
                        @empty
                        <tr>
                        <td colspan="100%" class="text-center text-muted py-3">No User Found</td>
                        </tr>
                        @endforelse
                      </tbody>
                    </table>
                    @if($users->total() > $users->perPage())
                    <br><br>
                    {{$users->links()}}
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