@extends('admin.layout.master')
@section('main_content')
@include('admin.layout.nav')
@include('admin.layout.sidebar')
<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1>Contact Page</h1>
        </div>
        <div class="section-body">
            {{-- Map Code --}}
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Map Code</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin_contact_item_update') }}" method="post">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Map Code</label>
                                    <textarea name="map_code" class="form-control h_150" cols="30" rows="10">{{ $contact_item->map_code ?? '' }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <button type="submit" class="btn btn-primary">Update Map</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Our Offices --}}
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5>Our Offices</h5>
                            <a href="{{ route('admin_contact_office_create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Add Office</a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>SL</th>
                                            <th>Office Name</th>
                                            <th>Address</th>
                                            <th>Contact</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($contact_offices as $office)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $office->name }}</td>
                                            <td>{!! nl2br(e($office->address ?? '-')) !!}</td>
                                            <td>
                                                @if($office->landline) <small>Landline: {{ $office->landline }}</small><br> @endif
                                                @if($office->globe) <small>Globe: {{ $office->globe }}</small><br> @endif
                                                @if($office->smart) <small>Smart: {{ $office->smart }}</small> @endif
                                                @if(!$office->landline && !$office->globe && !$office->smart) - @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('admin_contact_office_edit', $office->id) }}" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>
                                                <a href="{{ route('admin_contact_office_delete', $office->id) }}" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?');"><i class="fas fa-trash"></i></a>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center">No offices added yet. <a href="{{ route('admin_contact_office_create') }}">Add your first office</a></td>
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