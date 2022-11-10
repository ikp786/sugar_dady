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
                  <a href="{{ route('misces.create') }}" class="btn btn-primary">Add New Misc</a>

                    
                  </p>
                  <div class="table-responsive">
                    <table class="table table-hover">
                      <thead>
                        <tr>
                          <th>ID</th>
                          <th>Misc Title</th>
                          <th>Misc Type</th>                          
                          <th>Edit</th>
                          <th>Delete</th>
                        </tr>
                      </thead>
                      <tbody>
                      @forelse ($misce as $misc)
                        <tr>
                          <td>{{$misc->misc_id}} </td>
                          <td>{{$misc->misc_title}}</td>
                          <td>
                            @if($misc->misc_type == 1001)
                            Gender
                            @elseif($misc->misc_type == 1002)
                            Sexual Orientation
                            @elseif($misc->misc_type == 1003)
                            Intrested In
                            @endif
                          </td>                          
                          <td>
                          <a href="{{ route('misces.edit',$misc->misc_id) }}" class="btn btn-sm btn-success"><i class="fa fa-edit"></i></a>
                          </td>
                          <td>
                          <form action="{{ route('misces.destroy', $misc->misc_id) }}" class="d-inline-block" method="post">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Are you sure to delete this Misc?')" class="btn btn-sm btn-danger"><i class="fa fa-trash" style="font-size:14px"></i></button>
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
                    @if($misce->total() > $misce->perPage())
                    <br><br>
                    {{$misce->links()}}
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