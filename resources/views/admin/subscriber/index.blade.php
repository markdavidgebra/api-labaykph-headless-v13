@extends('admin.layout.master')
@section('main_content')
@include('admin.layout.nav')
@include('admin.layout.sidebar')
<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1>Subscribers</h1>
            <div class="ml-auto">
                <a href="{{ route('admin_subscriber_send_email') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Send Email</a>
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
                                            <th>Email</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($subscribers as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->email }}</td>
                                            <td>
                                                @if($item->status == 'Active')
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-warning text-dark">Pending</span>
                                                @endif
                                            </td>
                                            <td class="pt_10 pb_10">
                                                @if($item->status == 'Pending')
                                                    <a href="{{ route('admin_subscriber_approve', $item->id) }}" class="btn btn-success btn-sm" title="Approve"><i class="fas fa-check"></i></a>
                                                @endif
                                                <a href="{{ route('admin_subscriber_delete',$item->id) }}" class="btn btn-danger btn-sm" onClick="return confirm('Are you sure?');" title="Delete"><i class="fas fa-trash"></i></a>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4">No subscribers yet.</td>
                                        </tr>
                                        @endforelse
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