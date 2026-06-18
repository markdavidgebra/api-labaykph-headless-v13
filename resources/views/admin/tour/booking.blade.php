@extends('admin.layout.master')
@section('main_content')
@include('admin.layout.nav')
@include('admin.layout.sidebar')
<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1>Booking Information</h1>
            <div class="ml-auto">
                <a href="{{ route('admin_tour_index') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Back to Previous</a>
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
                                            <th>Reference No</th>
                                            <th>Customer</th>
                                            <th>Email Address</th>
                                            <th>Total Persons</th>
                                            <th>Amount to Pay</th>
                                            <th>Payment Method</th>
                                            <th>Payment Status</th>
                                            <th>Client Detail</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($all_data as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                {{ $item->invoice_no }}
                                            </td>
                                            <td>
                                                {{ $item->user->name }}
                                                
                                            </td>
                                            <td>
                                                <a href="{{ route('admin_tour_invoice', $item->invoice_no) }}" class="text-primary">
                                                    {{ $item->user->email }}
                                                </a>
                                            </td>
                                            <td>{{ $item->total_person }}</td>
                                            <td>${{ $item->paid_amount }}</td>
                                            <td>{{ $item->payment_method }}</td>
                                            <td>
                                                @if($item->payment_status == 'Completed')
                                                <span class="badge bg-success">Completed</span>
                                                @else
                                                <span class="badge bg-danger">Pending</span>
                                                <a href="{{ route('admin_tour_booking_approve',$item->id) }}">Approve It</a>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('admin_tour_invoice',$item->invoice_no) }}" class="badge bg-primary text-decoration-none" target="_blank">Show Details</a>
                                            </td>
                                            <td class="pt_10 pb_10">
                                                <a href="{{ route('admin_message_customer', $item->user_id) }}" class="btn btn-info btn-sm" title="Message Customer"><i class="fas fa-envelope"></i></a>
                                                <a href="{{ route('admin_tour_booking_delete',$item->id) }}" class="btn btn-danger btn-sm" onClick="return confirm('Are you sure?');" title="Delete"><i class="fas fa-trash"></i></a>
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