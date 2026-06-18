@extends('admin.layout.master')
@section('main_content')
@include('admin.layout.nav')
@include('admin.layout.sidebar')
<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1>Send Email to All</h1>
            <div class="ml-auto">
                <a href="{{ route('admin_subscribers') }}" class="btn btn-primary"><i class="fas fa-plus"></i> All Subscribers</a>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('admin_subscriber_send_email_submit') }}" method="post">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Subject *</label>
                                    <input type="text" class="form-control" name="subject">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Message *</label>
                                    <textarea name="message" class="form-control h_200" cols="30" rows="10"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label"></label>
                                    <button type="submit" class="btn btn-primary">Send Email</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection