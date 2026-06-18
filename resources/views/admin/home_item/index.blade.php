@extends('admin.layout.master')
@section('main_content')
@include('admin.layout.nav')
@include('admin.layout.sidebar')
<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1>Edit Home Item</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('admin_home_item_update',$home_item->id) }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <h4>Destination Section</h4>
                                <div class="mb-3">
                                    <label class="form-label">Destination Heading *</label>
                                    <input type="text" class="form-control" name="destination_heading" value="{{ $home_item->destination_heading }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Destination Subheading *</label>
                                    <input type="text" class="form-control" name="destination_subheading" value="{{ $home_item->destination_subheading }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Destination Status</label>
                                    <select name="destination_status" class="form-select">
                                        <option value="Show" {{ $home_item->destination_status == 'Show' ? 'selected' : '' }}>Show</option>
                                        <option value="Hide" {{ $home_item->destination_status == 'Hide' ? 'selected' : '' }}>Hide</option>
                                    </select>
                                </div>

                                <h4>Feature Section</h4>
                                <div class="mb-3">
                                    <label class="form-label">Feature Status</label>
                                    <select name="feature_status" class="form-select">
                                        <option value="Show" {{ $home_item->feature_status == 'Show' ? 'selected' : '' }}>Show</option>
                                        <option value="Hide" {{ $home_item->feature_status == 'Hide' ? 'selected' : '' }}>Hide</option>
                                    </select>
                                </div>

                                <h4>Package Section</h4>
                                <div class="mb-3">
                                    <label class="form-label">Package Heading *</label>
                                    <input type="text" class="form-control" name="package_heading" value="{{ $home_item->package_heading }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Package Subheading *</label>
                                    <input type="text" class="form-control" name="package_subheading" value="{{ $home_item->package_subheading }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Package Status</label>
                                    <select name="package_status" class="form-select">
                                        <option value="Show" {{ $home_item->package_status == 'Show' ? 'selected' : '' }}>Show</option>
                                        <option value="Hide" {{ $home_item->package_status == 'Hide' ? 'selected' : '' }}>Hide</option>
                                    </select>
                                </div>

                                <h4>Testimonial Section</h4>
                                <div class="mb-3">
                                    <label class="form-label">Testimonial Heading *</label>
                                    <input type="text" class="form-control" name="testimonial_heading" value="{{ $home_item->testimonial_heading }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Testimonial Subheading *</label>
                                    <input type="text" class="form-control" name="testimonial_subheading" value="{{ $home_item->testimonial_subheading }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Testimonial Existing Background</label>
                                    <div>
                                        <img src="{{ asset('uploads/'.$home_item->testimonial_background) }}" alt="" class="w_200">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Testimonial Change Background</label>
                                    <div>
                                        <input type="file" name="testimonial_background">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Testimonial Status</label>
                                    <select name="testimonial_status" class="form-select">
                                        <option value="Show" {{ $home_item->testimonial_status == 'Show' ? 'selected' : '' }}>Show</option>
                                        <option value="Hide" {{ $home_item->testimonial_status == 'Hide' ? 'selected' : '' }}>Hide</option>
                                    </select>
                                </div>

                                <h4>Blog Section</h4>
                                <div class="mb-3">
                                    <label class="form-label">Blog Heading *</label>
                                    <input type="text" class="form-control" name="blog_heading" value="{{ $home_item->blog_heading }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Blog Subheading *</label>
                                    <input type="text" class="form-control" name="blog_subheading" value="{{ $home_item->blog_subheading }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Blog Status</label>
                                    <select name="blog_status" class="form-select">
                                        <option value="Show" {{ $home_item->blog_status == 'Show' ? 'selected' : '' }}>Show</option>
                                        <option value="Hide" {{ $home_item->blog_status == 'Hide' ? 'selected' : '' }}>Hide</option>
                                    </select>
                                </div>

                                <h4 id="cta-journey" class="pt-3">CTA Journey Section</h4>
                                <div class="mb-3">
                                    <label class="form-label">CTA Label (e.g. Popular Destination)</label>
                                    <input type="text" class="form-control" name="cta_label" value="{{ $home_item->cta_label ?? 'Popular Destination' }}" placeholder="Popular Destination">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">CTA Title</label>
                                    <input type="text" class="form-control" name="cta_title" value="{{ $home_item->cta_title ?? 'Embark on your Spiritual Umrah with us.' }}" placeholder="Embark on your Spiritual Umrah with us.">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">CTA Text (HTML allowed - use &lt;a href="/packages" class="cta-journey-highlight"&gt;link text&lt;/a&gt; for golden highlighted links)</label>
                                    <textarea class="form-control" name="cta_text" rows="4" placeholder="Your CTA paragraph...">{{ $home_item->cta_text ?? '' }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">CTA Background Image</label>
                                    @if($home_item->cta_background ?? null)
                                        <div class="mb-2"><img src="{{ asset('uploads/'.$home_item->cta_background) }}" alt="" class="w_200"></div>
                                    @else
                                        <p class="text-muted small">Uses cta-journey-bg.png if no image uploaded.</p>
                                    @endif
                                    <input type="file" name="cta_background" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">CTA Status</label>
                                    <select name="cta_status" class="form-select">
                                        <option value="Show" {{ ($home_item->cta_status ?? 'Show') == 'Show' ? 'selected' : '' }}>Show</option>
                                        <option value="Hide" {{ ($home_item->cta_status ?? 'Show') == 'Hide' ? 'selected' : '' }}>Hide</option>
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