@extends('admin.layout.master')
@section('main_content')
@include('admin.layout.nav')
@include('admin.layout.sidebar')
<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1>Messages</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover" id="example1">
                                    <thead>
                                        <tr>
                                            <th width="50">#</th>
                                            <th width="80">Photo</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th width="120" class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($messages as $item)
                                        <tr>
                                            <td class="text-muted">{{ $loop->iteration }}</td>
                                            <td class="text-center">
                                                @if($item->user->photo != '')
                                                    <img src="{{ asset('uploads/'.$item->user->photo) }}" 
                                                         alt="{{ $item->user->name }}" 
                                                         class="user-photo-circle">
                                                @else
                                                    <img src="{{ asset('uploads/default.png') }}" 
                                                         alt="Default" 
                                                         class="user-photo-circle">
                                                @endif
                                            </td>
                                            <td>
                                                <span class="font-weight-600">{{ $item->user->name }}</span>
                                            </td>
                                            <td>
                                                <a href="mailto:{{ $item->user->email }}" class="text-primary">
                                                    {{ $item->user->email }}
                                                </a>
                                            </td>
                                            <td>
                                                <a href="tel:{{ $item->user->phone }}" class="text-muted">
                                                    {{ $item->user->phone }}
                                                </a>
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('admin_message_detail', $item->id) }}" 
                                                   class="btn btn-sm btn-primary">
                                                    <i class="fas fa-comments mr-1"></i>
                                                    View
                                                </a>
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
@endsection
