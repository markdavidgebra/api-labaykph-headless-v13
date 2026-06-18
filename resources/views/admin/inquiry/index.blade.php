@extends('admin.layout.master')
@section('main_content')
@include('admin.layout.nav')
@include('admin.layout.sidebar')
<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1>Inquiries</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <p class="text-muted mb-4">Inquiries submitted via the Contact Us modal on the website.</p>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="example1">
                                    <thead>
                                        <tr>
                                            <th>SL</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Submitted</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($inquiries as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->name }}</td>
                                            <td><a href="mailto:{{ $item->email }}">{{ $item->email }}</a></td>
                                            <td><a href="tel:{{ $item->phone }}">{{ $item->phone }}</a></td>
                                            <td>{{ $item->created_at->format('M d, Y h:i A') }}</td>
                                            <td class="pt_10 pb_10">
                                                <a href="{{ route('admin_inquiry_delete', $item->id) }}" class="btn btn-danger btn-sm" onClick="return confirm('Are you sure?');" title="Delete"><i class="fas fa-trash"></i></a>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-4">No inquiries yet.</td>
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
