@extends('front.layout.master')

@section('main_content')
@php
$setting = App\Models\Setting::where('id',1)->first();
@endphp
<div class="page-top" style="background-image: url({{ asset('uploads/'.$setting->banner) }})">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Bookings</h2>
                <div class="breadcrumb-container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Bookings</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-content user-panel pt_70 pb_70">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-12">
                <div class="card">
                    @include('user.sidebar')
                </div>
            </div>
            <div class="col-lg-9 col-md-12">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th>SL</th>
                                <th>Reference No</th>
                                <th>Total Persons</th>
                                <th>Amount</th>
                                {{-- <th>Payment Method</th> --}}
                                <th>Payment Status</th>
                                <th class="w-100">
                                    Action
                                </th>
                            </tr>
                            @foreach($all_data as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    {{ $item->invoice_no }}
                                </td>
                                <td>
                                    {{ $item->total_person }}
                                </td>
                                <td>${{ $item->paid_amount }}</td>
                                {{-- <td>
                                    {{ $item->payment_method }}
                                </td> --}}
                                <td>
                                    @if($item->payment_status == 'Completed')
                                    <div class="badge bg-success">Completed</div>
                                    @else
                                    <div class="badge bg-danger">Pending</div>
                                    @endif
                                </td>
                                <td>
                                    <a href="" class="btn btn-secondary btn-sm mb-1 w-100-p" data-bs-toggle="modal" data-bs-target="#exampleModal{{ $loop->iteration }}">Detail</a>
                                    {{-- <a href="{{ route('user_invoice',$item->invoice_no) }}" class="btn btn-secondary btn-sm w-100-p">Invoice</a> --}}
                                </td>
                            </tr>
                            <!-- Modal -->
                            <div class="modal fade" id="exampleModal{{ $loop->iteration }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Detail</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3 row modal-seperator">
                                                <div class="col-md-5">
                                                    <b>Reference No:</b>
                                                </div>
                                                <div class="col-md-7">
                                                    {{ $item->invoice_no }}
                                                </div>
                                            </div>
                                            <div class="mb-3 row modal-seperator">
                                                <div class="col-md-5">
                                                    <b>Package Detail:</b>
                                                </div>
                                                <div class="col-md-7">
                                                    <b>Name:</b> {{ $item->package->name }}<br>
                                                    <a href="{{ route('package',$item->package->slug) }}" target="_blank">Show Detail</a>
                                                </div>
                                            </div>
                                            <div class="mb-3 row modal-seperator">
                                                <div class="col-md-5">
                                                    <b>Tour Detail:</b>
                                                </div>
                                                <div class="col-md-7">
                                                    <b>Start Date: </b> {{ \Carbon\Carbon::parse($item->tour->tour_start_date)->format('M. j, Y') }}<br>
                                                    <b>End Date: </b> {{ \Carbon\Carbon::parse($item->tour->tour_end_date)->format('M. j, Y') }}<br>
                                                </div>
                                            </div>
                                            <div class="mb-3 row modal-seperator">
                                                <div class="col-md-5">
                                                    <b>Total Persons:</b>
                                                </div>
                                                <div class="col-md-7">
                                                    {{ $item->total_person }}
                                                </div>
                                            </div>
                                            <div class="mb-3 row modal-seperator">
                                                <div class="col-md-5">
                                                    <b>Amount:</b>
                                                </div>
                                                <div class="col-md-7">
                                                    ${{ $item->paid_amount }}
                                                </div>
                                            </div>
                                            {{-- <div class="mb-3 row modal-seperator">
                                                <div class="col-md-5">
                                                    <b>Payment Method:</b>
                                                </div>
                                                <div class="col-md-7">
                                                    {{ $item->payment_method }}
                                                </div>
                                            </div> --}}
                                            <div class="mb-3 row modal-seperator">
                                                <div class="col-md-5">
                                                    <b>Payment Status:</b>
                                                </div>
                                                <div class="col-md-7">
                                                    @if($item->payment_status == 'Completed')
                                                    <div class="badge bg-success">Completed</div>
                                                    @else
                                                    <div class="badge bg-danger">Pending</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- // Modal -->
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection