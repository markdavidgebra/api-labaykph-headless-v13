@extends('admin.layout.master')
@section('main_content')
@include('admin.layout.nav')
@include('admin.layout.sidebar')
<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1>Edit User</h1>
            <div class="ml-auto">
                <a href="{{ route('admin_users') }}" class="btn btn-primary"><i class="fas fa-plus"></i> View All</a>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('admin_user_edit_submit',$user->id) }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Existing Photo</label>
                                    <div>
                                        @if($user->photo != '')
                                        <img src="{{ asset('uploads/'.$user->photo) }}" alt="" class="w_200">
                                        @else
                                        <img src="{{ asset('uploads/default.png') }}" alt="" class="w_200">
                                        @endif
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Change Photo</label>
                                    <div><input type="file" name="photo"></div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Name *</label>
                                    <input type="text" class="form-control" name="name" value="{{ $user->name }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Email *</label>
                                    <input type="text" class="form-control" name="email" value="{{ $user->email }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Phone *</label>
                                    <input type="text" class="form-control" name="phone" value="{{ $user->phone }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Country *</label>
                                    <input type="text" class="form-control" name="country" value="{{ $user->country }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Address *</label>
                                    <input type="text" class="form-control" name="address" value="{{ $user->address }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">State *</label>
                                    <input type="text" class="form-control" name="state" value="{{ $user->state }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">City *</label>
                                    <input type="text" class="form-control" name="city" value="{{ $user->city }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Zip *</label>
                                    <input type="text" class="form-control" name="zip" value="{{ $user->zip }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Password</label>
                                    <input type="password" class="form-control" name="password">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Status *</label>
                                    <select name="status" class="form-control">
                                        <option value="1" @if($user->status == 1) selected @endif>Active</option>
                                        <option value="0" @if($user->status == 0) selected @endif>Pending</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label"></label>
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection