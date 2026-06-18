@extends('front.layout.master')

@section('main_content')
@php
$setting = App\Models\Setting::where('id',1)->first();
@endphp
<div class="page-top" style="background-image: url({{ asset('uploads/'.$setting->banner) }})">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Wishlist</h2>
                <div class="breadcrumb-container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Wishlist</li>
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
                @if($wishlist->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th>SL</th>
                                <th>Photo</th>
                                <th>Package</th>
                                <th class="w-100">Action</th>
                            </tr>
                            @foreach($wishlist as $item)
                            <tr>
                                <td>1</td>
                                <td>
                                    <img src="{{ asset('uploads/'.$item->package->featured_photo) }}" alt="" class="w-200">
                                </td>
                                <td>
                                    {{ $item->package->name }}
                                </td>
                                <td>
                                    <a href="{{ route('package',$item->package->slug) }}" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('user_wishlist_delete',$item->id) }}" class="btn btn-danger btn-sm" onClick="return confirm('Are You Sure?')"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="alert alert-danger">
                    <h4 class="alert-heading mb_0">No Data Found</h4>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection