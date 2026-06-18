@extends('admin.layout.master')
@section('main_content')
@include('admin.layout.nav')
@include('admin.layout.sidebar')
<div class="main-content">
    <section class="section">
        <div class="section-body">
            <div class="invoice">
                <div class="invoice-header text-center mb-4">
                    @if(isset($setting) && $setting->logo)
                        <img src="{{ asset('uploads/'.$setting->logo) }}" alt="Logo" class="invoice-logo">
                    @endif
                </div>
                <h3 class="text-center">Reference No: {{ $booking->invoice_no }}</h3>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <td>Reference No: </td>
                                <td>{{ $booking->invoice_no }}</td>
                            </tr>
                            <tr>
                                <td>Customer Info: </td>
                                <td>
                                    Name: {{ $booking->user->name }}<br>
                                    Email: {{ $booking->user->email }}<br>
                                    Phone: {{ $booking->user->phone }}
                                </td>
                            </tr>
                            <tr>
                                <td>Contact Us </td>
                                <td>
                                    Name: {{ current_admin_user()->name }}<br>
                                    Email: {{ current_admin_user()->email }}
                                </td>
                            </tr>
                            <tr>
                                <td>Tour Information: </td>
                                <td>
                                    Start Date: {{ \Carbon\Carbon::parse($booking->tour->tour_start_date)->format('M. j, Y') }}<br>
                                    End Date: {{ \Carbon\Carbon::parse($booking->tour->tour_end_date)->format('M. j, Y') }}<br>
                                </td>
                            </tr>
                            <tr>
                                <td>Package Information: </td>
                                <td>
                                    Name: {{ $booking->package->name }}<br>
                                </td>
                            </tr>
                            <tr>
                                <td>Booking Date: </td>
                                <td>{{ $booking->created_at->format('M. j, Y') }}</td>
                            </tr>
                            <tr>
                                <td>Payment Method: </td>
                                <td>{{ $booking->payment_method }}</td>
                            </tr>
                            <tr>
                                <td>Payment Status: </td>
                                <td>
                                    @if($booking->payment_status == 'Completed')
                                    Completed
                                    @else
                                    Pending
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Total Persons: </td>
                                <td>{{ $booking->total_person }}</td>
                            </tr>
                            <tr>
                                <td>Amount: </td>
                                <td>${{ $booking->paid_amount }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="text-md-right">
                    <a href="javascript:window.print();" class="btn btn-warning btn-icon icon-left text-white print-invoice-button"><i class="fas fa-print"></i> Print</a>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
    /* Print Styles */
    @media print {
        .main-content {
            padding: 0 !important;
        }
        
        .section {
            padding: 0 !important;
        }
        
        .invoice-header {
            margin-bottom: 20px;
        }
        
        .invoice-logo {
            max-width: 200px;
            max-height: 100px;
            height: auto;
        }
        
        .print-invoice-button {
            display: none !important;
        }
        
        .invoice {
            page-break-inside: avoid;
        }
        
        body {
            background: white !important;
        }
        
        .navbar,
        .sidebar,
        .section-header {
            display: none !important;
        }
    }
    
    /* Screen Styles */
    .invoice-header {
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 2px solid #e9ecef;
    }
    
    .invoice-logo {
        max-width: 200px;
        max-height: 100px;
        height: auto;
        object-fit: contain;
    }
</style>
@endsection
