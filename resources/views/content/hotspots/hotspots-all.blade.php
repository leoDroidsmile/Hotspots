@extends('layouts/contentNavbarLayout')

@section('title', 'Hotspot - All Hotspots')

@section('content')
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Hotspot /</span> All Hotspots
    </h4>

    <!-- Basic Bootstrap Table -->
    <div class="card">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="mb-0">All Hotspots</h5> <small class="text-muted float-end"></small>
        <button type="button" class="btn btn-primary" id="from_csv_btn">From CSV</button>
      </div>
      <div class="card-body">

        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif

        @if (session('errors'))
            <div class="alert alert-danger" role="alert">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>City</th>
                        <th>State</th>
                        <th>Country</th>
                        <th>Address</th>
                        <th>Owner</th>
                        <th>Percentage</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($hotspots as $hotspot)
                        <tr>
                            <td>{{ $hotspot->name }}</td>
                            <td>{{ $hotspot->city }}</td>
                            <td>{{ $hotspot->state }}</td>
                            <td>{{ $hotspot->country }}</td>
                            <td>{{ $hotspot->address }}</td>
                            <td>{{ $hotspot->owner["name"] }}</td>
                            <td>{{ $hotspot->percentage }}</td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                        data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href={{ route('hotspots-edit', ['id' => $hotspot->id]) }}><i
                                                class="bx bx-edit-alt me-1"></i> Edit</a>
                                        <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $hotspot->id }}"><i class="bx bx-trash me-1"></i>
                                            Delete</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        
                        <!-- Modal -->
                        <div class="modal fade" id="deleteModal{{ $hotspot->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <form method="POST" action="{{ route('hotspots-delete', $hotspot->id) }}">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $hotspot->id }}">
                                
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel1">Delete Confirmation</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                        <div class="row">
                                            <div class="col mb-3">
                                                <p>Are you sure to delete this hotspot?</p>                                                                        
                                            </div>
                                        </div>
                                        
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary">Delete</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
        </div>
      </div>
    </div>
    <!--/ Basic Bootstrap Table -->
    <!--/ Responsive Table -->
    <script type="module">
        $(document).ready(function(){
            alert();
        });
    </script>
@endsection
