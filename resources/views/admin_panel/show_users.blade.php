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
                  <h4 class="card-title">User Details</h4>
                  <p class="card-description">                                
                  </p>
                  <div class="table-responsive">
                  <table class="table table-borderless">
                    <tr>
                    @php
                        $image = isset($user->userImages[0]->image_name) ? $user->userImages[0]->image_name : '';//;die;
                        @endphp
                        <th>Photo</th>
                        <td><img src="{{asset('storage/user_images/'.$image)}}" style="max-height: 150px; max-width: 150px; border-radius: 5px;"></td>
                    </tr>

                    <tr>
                        <th>User ID</th>
                        <td>{{ $user->user_id }}</td>
                    </tr>
                    <tr>
                        <th>Name</th>
                        <td>{{ $user->full_name }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ $user->email_address }}</td>
                    </tr>

                    <tr>
                        <th>Mobile</th>
                        <td>{{ $user->mobile_number }}</td>
                    </tr>
                    
                    <tr>
                        <th>Location</th>
                        <td>{{ $user->user_location }}</td>
                    </tr>

                    <tr>
                        <th>Gender</th>
                        <td>{{ $user->gender->misc_title }}</td>
                    </tr>

                    <tr>
                        <th>Sexcual Orientation</th>
                        <td>{{ $user->sexcualOrientation->misc_title }}</td>
                    </tr>

                    <tr>
                        <th>Bio</th>
                        <td>{{ $user->user_bio }}</td>
                    </tr>
                    <tr>
                      <th>About Me</th>
                      <td>{{$user->about_me}}</td>
                    </tr>

                  </table>

                   
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