@extends('admin.layout.master')
@section('main_content')
@include('admin.layout.nav')
@include('admin.layout.sidebar')
<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1>Users</h1>
            <div class="ml-auto">
                <a href="{{ route('admin_user_create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Add New</a>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="example1">
                                    <thead>
                                        <tr>
                                            <th>SL</th>
                                            <th>Photo</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($users as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                @php
                                                    $photoSrc = $item->photo != '' ? asset('uploads/'.$item->photo) : asset('uploads/default.png');
                                                @endphp
                                                <a href="javascript:void(0)" class="user-photo-thumb-link" data-image="{{ $photoSrc }}" data-name="{{ $item->name }}" title="Click to view">
                                                    <img src="{{ $photoSrc }}" alt="{{ $item->name }}" class="user-photo-thumb">
                                                </a>
                                            </td>
                                            <td>
                                                {{ $item->name }}
                                            </td>
                                            <td>
                                                <a href="{{ route('admin_user_edit', $item->id) }}">{{ $item->email }}</a>
                                            </td>
                                            <td>
                                                {{ $item->phone }}
                                            </td>
                                            <td>
                                                @if($item->status == 1)
                                                <span class="badge badge-success">Active</span>
                                                @else
                                                <span class="badge badge-danger">Pending</span>
                                                @endif
                                            </td>
                                            <td class="pt_10 pb_10">
                                                <a href="{{ route('admin_user_edit',$item->id) }}" class="btn btn-primary" title="Edit"><i class="fas fa-edit"></i></a>
                                                <a href="{{ route('admin_message_customer', $item->id) }}" class="btn btn-info" title="Message"><i class="fas fa-envelope"></i></a>
                                                <a href="{{ route('admin_user_delete',$item->id) }}" class="btn btn-danger" onClick="return confirm('Are you sure?');" title="Delete"><i class="fas fa-trash"></i></a>
                                                @if(is_super_admin())
                                                <a href="{{ route('admin_user_force_delete',$item->id) }}" class="btn btn-dark" onClick="return confirm('Force delete this customer? This will permanently delete the user, all their bookings, and all their messages. This cannot be undone.');" title="Force Delete"><i class="fas fa-user-times"></i></a>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

{{-- Modal for viewing user photo --}}
<div class="modal fade" id="userPhotoModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userPhotoModalTitle">User Photo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center p-4">
                <img id="userPhotoModalImage" src="" alt="" class="img-fluid" style="max-height: 70vh;">
            </div>
        </div>
    </div>
</div>

<style>
.user-photo-thumb {
    width: 40px;
    height: 40px;
    object-fit: cover;
    border-radius: 4px;
    cursor: pointer;
    transition: opacity 0.2s;
}
.user-photo-thumb:hover {
    opacity: 0.85;
}
.user-photo-thumb-link {
    display: inline-block;
}
</style>

<script>
$(document).on('click', '.user-photo-thumb-link', function(e) {
    e.preventDefault();
    var imgSrc = $(this).data('image');
    var userName = $(this).data('name');
    $('#userPhotoModalImage').attr('src', imgSrc).attr('alt', userName);
    $('#userPhotoModalTitle').text(userName + ' - Photo');
    $('#userPhotoModal').modal('show');
});
</script>
@endsection