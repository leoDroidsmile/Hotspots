@extends('layouts/contentNavbarLayout')

@section('title', ' Hotspots - Add Hotspot')

@section('content')
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Hotspots /</span> Add Hotspot</h4>

    <!-- Basic Layout -->
    <div class="row">
        <div class="col-xxl">
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Add Hotspot</h5> <small class="text-muted float-end"></small>
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
                    <form id="createhotspot" class="mb-3" action="{{ url('/hotspots/store') }}" method="POST">
                        @csrf
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="basic-default-name">Name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="basic-default-name" name="name"
                                    placeholder="Name" required/>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="hotspot-address">Address</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="hotspot-address" name="address"
                                    placeholder="Address Key" required/>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="fill_auto_address"></label>
                            <div class="col-sm-10">
                                <button type="button" class="btn btn-primary" id="fill_auto_address">Fill automatically</button>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="hotspot-city">City</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="hotspot-city" name="city"
                                    placeholder="City" readonly required/>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="hotspot-state">State</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="hotspot-state" name="state"
                                    placeholder="State" readonly required/>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="hotspot-country">Country</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="hotspot-country" name="country"
                                    placeholder="Country" readonly required/>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="hotspot-owner">Owner</label>
                            <div class="btn-group col-sm-10">
                                <select class="form-select form-select-md" name="owner_id">
                                    <option selected>Select Owner</option>
                                    @foreach ($users as $user)
                                        <option value="{{$user->id}}">{{$user->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="hotspot-percentage">Percentage</label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control" id="hotspot-percentage" name="percentage"
                                    placeholder="Percentage" min="0" max="100" required/>
                            </div>
                        </div>
                        <div class="row justify-content-end">
                            <div class="col-sm-10">
                                <button type="submit" class="btn btn-primary">Create</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
