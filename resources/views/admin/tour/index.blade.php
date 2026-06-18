@extends('admin.layout.master')
@section('main_content')
@include('admin.layout.nav')
@include('admin.layout.sidebar')
<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1>Tours</h1>
            <div class="ml-auto">
                <a href="{{ route('admin_tour_create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Add New</a>
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
                                            <th>Package Info</th>
                                            <th>Tour Start</th>
                                            <th>Tour End</th>
                                            <th>Booking End</th>
                                            <th>Total Seat</th>
                                            <th>Booking</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($tours as $tour)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                {{ $tour->package?->name ?? 'N/A' }}<br>
                                                @if($tour->package)
                                                <a href="{{ route('package',$tour->package->slug) }}" target="_blank">See Detail</a>
                                                @else
                                                <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($tour->tour_start_date)->format('M. j, Y') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($tour->tour_end_date)->format('M. j, Y') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($tour->booking_end_date)->format('M. j, Y') }}</td>
                                            <td>
                                                @if($tour->total_seat == -1)
                                                Unlimited
                                                @else
                                                {{ $tour->total_seat }}
                                                @endif
                                            </td>
                                            <td>
                                                @if($tour->package)
                                                <a href="{{ route('admin_tour_booking',[$tour->id,$tour->package->id]) }}" class="btn btn-success btn-sm position-relative">
                                                    <i class="fas fa-calendar-check mr-1"></i> Booking Information
                                                    @if(($tour->unviewed_bookings_count ?? 0) > 0)
                                                        <span class="tour-booking-btn-badge">{{ $tour->unviewed_bookings_count > 99 ? '99+' : $tour->unviewed_bookings_count }}</span>
                                                    @endif
                                                </a>
                                                @else
                                                <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td class="pt_10 pb_10">
                                                <a href="{{ route('admin_tour_edit',$tour->id) }}" class="btn btn-primary" title="Edit"><i class="fas fa-edit"></i></a>
                                                <a href="{{ route('admin_tour_delete',$tour->id) }}" class="btn btn-danger" onClick="return confirm('Are you sure?');" title="Delete"><i class="fas fa-trash"></i></a>
                                                @if(is_super_admin())
                                                <a href="{{ route('admin_tour_force_delete',$tour->id) }}" class="btn btn-dark" onClick="return confirm('Force delete this tour? This will permanently delete the tour and all its bookings. This cannot be undone.');" title="Force Delete"><i class="fas fa-user-times"></i></a>
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