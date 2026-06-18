@extends('admin.layout.master')
@section('main_content')
@include('admin.layout.nav')
@include('admin.layout.sidebar')
<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1>Packages</h1>
            <div class="ml-auto">
                <a href="{{ route('admin_package_create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Add New</a>
            </div>
        </div>
        <div class="section-body">
            @if($packages->isEmpty())
                <div class="card">
                    <div class="card-body text-center py-4">
                        <i class="fas fa-suitcase fa-3x text-muted mb-3"></i>
                        <h5 class="mb-2">No packages yet</h5>
                        <p class="text-muted mb-3 small">Add your first package to organize tours and itineraries.</p>
                        <a href="{{ route('admin_package_create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus mr-1"></i>Add Package</a>
                    </div>
                </div>
            @else
                <div class="row pkg-grid">
                    @foreach($packages as $package)
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3 pkg-col">
                        <div class="card package-card h-100">
                            <div class="package-card-image">
                                @php
                                    $photoSrc = asset('uploads/default.png');
                                    if (!empty($package->featured_photo) && file_exists(public_path('uploads/'.$package->featured_photo))) {
                                        $photoSrc = asset('uploads/'.$package->featured_photo);
                                    }
                                @endphp
                                <img src="{{ $photoSrc }}" alt="{{ $package->name }}" class="package-thumb">
                                <span class="package-badge">{{ $package->tours_count }} {{ Str::plural('tour', $package->tours_count) }}</span>
                                @if($package->destination)
                                <span class="package-dest-badge">{{ Str::limit($package->destination->name, 14) }}</span>
                                @endif
                            </div>
                            <div class="package-card-body">
                                <h6 class="package-title">{{ $package->name }}</h6>
                                <div class="package-actions">
                                    <a href="{{ route('admin_package_amenities',$package->id) }}" class="pkg-btn pkg-btn-amenities">Amenities @if($package->package_amenities_count > 0)<span class="pkg-count">({{ $package->package_amenities_count }})</span>@endif</a>
                                    <a href="{{ route('admin_package_itineraries',$package->id) }}" class="pkg-btn pkg-btn-itinerary">Itinerary @if($package->package_itineraries_count > 0)<span class="pkg-count">({{ $package->package_itineraries_count }})</span>@endif</a>
                                    <a href="{{ route('admin_package_faqs',$package->id) }}" class="pkg-btn pkg-btn-faq">FAQ @if($package->package_faqs_count > 0)<span class="pkg-count">({{ $package->package_faqs_count }})</span>@endif</a>
                                    <a href="{{ route('admin_package_photos',$package->id) }}" class="pkg-btn pkg-btn-photos">Photos @if($package->package_photos_count > 0)<span class="pkg-count">({{ $package->package_photos_count }})</span>@endif</a>
                                    <a href="{{ route('admin_package_videos',$package->id) }}" class="pkg-btn pkg-btn-videos">Videos @if($package->package_videos_count > 0)<span class="pkg-count">({{ $package->package_videos_count }})</span>@endif</a>
                                    <a href="{{ route('admin_package_edit',$package->id) }}" class="pkg-btn pkg-btn-edit">Edit</a>
                                    <a href="{{ route('admin_package_delete',$package->id) }}" class="pkg-btn pkg-btn-del" onclick="return confirm('Are you sure?');">Delete</a>
                                    @if(is_super_admin())
                                    <a href="{{ route('admin_package_force_delete',$package->id) }}" class="pkg-btn pkg-btn-force" onclick="return confirm('Force delete? This removes the package and all related data. Cannot be undone.');">Force Delete</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
</div>
<style>
.pkg-grid { margin: -8px; }
.pkg-col { padding: 8px; }
.package-card { 
    border-radius: 12px; overflow: hidden; 
    border: 1px solid #e9ecef; 
    background: #fff;
    transition: transform 0.35s cubic-bezier(0.34, 1.56, 0.64, 1), 
                box-shadow 0.35s ease, 
                border-color 0.3s ease; 
}
.package-card:hover { 
    transform: translateY(-6px) scale(1.02);
    box-shadow: 0 12px 28px rgba(0,0,0,0.12), 0 4px 12px rgba(158,113,2,0.08); 
    border-color: rgba(158,113,2,0.3);
}
.package-card-image { position: relative; overflow: hidden; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); }
.package-card-image::after {
    content: ''; position: absolute; inset: 0;
    background: linear-gradient(180deg, transparent 50%, rgba(0,0,0,0.15) 100%);
    opacity: 0;
    transition: opacity 0.35s ease;
}
.package-card:hover .package-card-image::after { opacity: 1; }
.package-thumb { 
    display: block; width: 100%; height: 155px; object-fit: cover; 
    transition: transform 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
}
.package-card:hover .package-thumb { transform: scale(1.08); }
.package-badge { 
    position: absolute; top: 8px; right: 8px; 
    font-size: 11px; padding: 4px 10px; 
    background: rgba(0,0,0,0.65); color: #fff; 
    border-radius: 6px;
    backdrop-filter: blur(4px);
    transition: transform 0.3s ease, background 0.3s ease;
}
.package-card:hover .package-badge { 
    transform: scale(1.05); 
    background: rgba(158,113,2,0.9);
}
.package-dest-badge {
    position: absolute; bottom: 8px; left: 8px;
    font-size: 11px; padding: 3px 8px;
    background: rgba(255,255,255,0.95); color: #495057;
    border-radius: 6px;
}
.package-card-body { padding: 14px 16px; transition: background 0.3s ease; }
.package-card:hover .package-card-body { background: #fafbfc; }
.package-title { 
    font-size: 15px; font-weight: 600; color: #232323; 
    margin: 0 0 12px 0; 
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    transition: color 0.25s ease;
}
.package-card:hover .package-title { color: #9e7102; }
.package-actions { display: flex; flex-wrap: wrap; gap: 6px; }
.pkg-btn { 
    display: inline-flex; align-items: center; 
    padding: 6px 10px; font-size: 12px; 
    border-radius: 6px; 
    color: #495057; text-decoration: none; 
    transition: all 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
    transform: scale(0.98);
    opacity: 0.9;
}
.pkg-btn .pkg-count { font-size: 11px; opacity: 0.85; margin-left: 1px; }
.package-card:hover .pkg-btn { transform: scale(1); opacity: 1; }
.pkg-btn:hover { color: #fff; text-decoration: none; transform: scale(1.05); }
.pkg-btn:hover .pkg-count { opacity: 1; }
.pkg-btn-amenities:hover { background: #6f42c1; color: #fff; }
.pkg-btn-itinerary:hover { background: #fd7e14; color: #fff; }
.pkg-btn-faq:hover { background: #20c997; color: #fff; }
.pkg-btn-photos:hover { background: #28a745; color: #fff; }
.pkg-btn-videos:hover { background: #17a2b8; color: #fff; }
.pkg-btn-edit:hover { background: #007bff; color: #fff; }
.pkg-btn-del:hover { background: #dc3545; color: #fff; }
.pkg-btn-force:hover { background: #343a40; color: #fff; }
@keyframes pkgCardIn { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }
.pkg-col { animation: pkgCardIn 0.4s ease-out backwards; }
.pkg-col:nth-child(1) { animation-delay: 0.02s; }
.pkg-col:nth-child(2) { animation-delay: 0.04s; }
.pkg-col:nth-child(3) { animation-delay: 0.06s; }
.pkg-col:nth-child(4) { animation-delay: 0.08s; }
.pkg-col:nth-child(5) { animation-delay: 0.1s; }
.pkg-col:nth-child(6) { animation-delay: 0.12s; }
.pkg-col:nth-child(n+7) { animation-delay: 0.14s; }
</style>
@endsection
