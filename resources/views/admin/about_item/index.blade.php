@extends('admin.layout.master')
@section('main_content')
@include('admin.layout.nav')
@include('admin.layout.sidebar')
<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1>Edit About Item</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('admin_about_item_update',$about_item->id) }}" method="post">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Feature Status</label>
                                    <select name="feature_status" class="form-select">
                                        <option value="Show" {{ $about_item->feature_status == 'Show' ? 'selected' : '' }}>Show</option>
                                        <option value="Hide" {{ $about_item->feature_status == 'Hide' ? 'selected' : '' }}>Hide</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label"></label>
                                    <button type="submit" class="btn btn-primary">Update</button>
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