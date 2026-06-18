@extends('admin.layout.master')
@section('main_content')
@include('admin.layout.nav')
@include('admin.layout.sidebar')
<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1>Add Office</h1>
            <div class="ml-auto">
                <a href="{{ route('admin_contact_item_index') }}" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Back to Contact Page</a>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('admin_contact_office_create_submit') }}" method="post">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Office Name *</label>
                                    <input type="text" class="form-control" name="name" value="{{ old('name') }}" placeholder="e.g. Makati Central Office" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Address</label>
                                    <textarea name="address" class="form-control h_100" cols="30" rows="4" placeholder="Full address (use &lt;br&gt; for line breaks)">{{ old('address') }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Landline</label>
                                    <input type="text" class="form-control" name="landline" value="{{ old('landline') }}" placeholder="e.g. (02) 84 426 857">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Globe</label>
                                    <input type="text" class="form-control" name="globe" value="{{ old('globe') }}" placeholder="e.g. 0967-415-8601">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Smart</label>
                                    <input type="text" class="form-control" name="smart" value="{{ old('smart') }}" placeholder="e.g. 0961-297-7633">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label"></label>
                                    <button type="submit" class="btn btn-primary">Add Office</button>
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
