@extends('layouts/contentNavbarLayout')

@section('title', ' Users - Edit User')

@section('content')
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Users /</span> Edit User</h4>

    <!-- Basic Layout -->
    <div class="row">
        <div class="col-xxl">
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Edit User</h5> <small class="text-muted float-end"></small>
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
                    <form id="formAuthentication" class="mb-3" action="{{ url('/users/update') }}" method="post">
                        @csrf
                        <input type="hidden" name="id" value="{{ $user->id }}" id="user_id"/>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="basic-default-name">Full Name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="basic-default-name" name="name" value="{{ $user->name }}" 
                                    placeholder="John Doe" required/>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="basic-default-email">Email</label>
                            <div class="col-sm-10">
                                <div class="input-group input-group-merge">
                                    <input type="text" id="basic-default-email" name="email" class="form-control"
                                        placeholder="john.doe" aria-label="john.doe" value="{{ $user->email }}" 
                                        aria-describedby="basic-default-email2" required/>
                                    <span class="input-group-text" id="basic-default-email2">@example.com</span>
                                </div>
                                <div class="form-text"> You can use letters, numbers & periods </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="basic-default-password">Password</label>
                            <div class="col-sm-10">
                                <input type="password" class="form-control" id="basic-default-password" name="password"
                                    placeholder="Password" required/>
                            </div>
                        </div>
                        <div class="row justify-content-end">
                            <div class="col-sm-10">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
