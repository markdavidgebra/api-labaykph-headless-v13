@extends('admin.layout.master')
@section('main_content')
@include('admin.layout.nav')
@include('admin.layout.sidebar')
<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1>Reviews</h1>
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
                                            <th>Package</th>
                                            <th>User</th>
                                            <th>Rating</th>
                                            <th>Comment</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($reviews as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                {{ $item->package?->name ?? 'N/A' }}<br>
                                                @if($item->package)
                                                <a href="{{ route('package',$item->package->slug) }}" target="_blank">See Detail</a>
                                                @else
                                                <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $item->user?->name ?? 'N/A' }}<br>
                                                {{ $item->user?->email ?? '—' }}
                                            </td>
                                            <td>{{ $item->rating }}</td>
                                            <td>{{ $item->comment }}</td>
                                            <td>
                                                @if($item->status == 'Approved')
                                                    <span class="badge bg-success">Approved</span>
                                                @elseif($item->status == 'Rejected')
                                                    <span class="badge bg-danger">Rejected</span>
                                                @else
                                                    <span class="badge bg-warning">Pending</span>
                                                @endif
                                            </td>
                                            <td class="pt_10 pb_10">
                                                @if($item->status != 'Approved')
                                                    <a href="{{ route('admin_review_approve',$item->id) }}" class="btn btn-success btn-sm" onClick="return confirm('Are you sure you want to approve this review?');" title="Approve">
                                                        <i class="fas fa-check"></i>
                                                    </a>
                                                @endif
                                                @if($item->status != 'Rejected')
                                                    <a href="{{ route('admin_review_reject',$item->id) }}" class="btn btn-danger btn-sm" onClick="return confirm('Are you sure you want to reject this review?');" title="Reject">
                                                        <i class="fas fa-times"></i>
                                                    </a>
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
@endsection