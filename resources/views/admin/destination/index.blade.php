@extends('admin.layout.master')
@section('main_content')
@include('admin.layout.nav')
@include('admin.layout.sidebar')
<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1>Destinations</h1>
            <div class="ml-auto">
                <a href="{{ route('admin_destination_create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Add New</a>
            </div>
        </div>
        <div class="section-body">
            @if($destinations->isEmpty())
                <div class="card">
                    <div class="card-body text-center py-4">
                        <i class="fas fa-map-marker-alt fa-3x text-muted mb-3"></i>
                        <h5 class="mb-2">No destinations yet</h5>
                        <p class="text-muted mb-3 small">Add your first destination to organize packages and tours.</p>
                        <a href="{{ route('admin_destination_create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus mr-1"></i>Add Destination</a>
                    </div>
                </div>
            @else
                <div class="row dest-grid">
                    @foreach($destinations as $destination)
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3 dest-col">
                        <div class="card destination-card h-100">
                            <div class="destination-card-image">
                                @php
                                    $photoSrc = asset('uploads/default.png');
                                    if (!empty($destination->featured_photo) && file_exists(public_path('uploads/'.$destination->featured_photo))) {
                                        $photoSrc = asset('uploads/'.$destination->featured_photo);
                                    }
                                @endphp
                                <img src="{{ $photoSrc }}" alt="{{ $destination->name }}" class="destination-thumb">
                                <span class="destination-badge">{{ $destination->packages_count }} {{ Str::plural('package', $destination->packages_count) }}</span>
                            </div>
                            <div class="destination-card-body">
                                <h6 class="destination-title">{{ $destination->name }}</h6>
                                <div class="destination-actions">
                                    <a href="{{ route('admin_destination_photos',$destination->id) }}" class="dest-btn dest-btn-photos">Photos @if($destination->photos_count > 0)<span class="dest-count">({{ $destination->photos_count }})</span>@endif</a>
                                    <a href="{{ route('admin_destination_videos',$destination->id) }}" class="dest-btn dest-btn-videos">Videos @if($destination->videos_count > 0)<span class="dest-count">({{ $destination->videos_count }})</span>@endif</a>
                                    <a href="{{ route('admin_destination_edit',$destination->id) }}" class="dest-btn dest-btn-edit">Edit</a>
                                    <a href="{{ route('admin_destination_delete',$destination->id) }}" class="dest-btn dest-btn-del" onclick="return confirm('Are you sure?');">Delete</a>
                                    @if(is_super_admin())
                                    <a href="{{ route('admin_destination_force_delete',$destination->id) }}" class="dest-btn dest-btn-force" onclick="return confirm('Force delete? This removes the destination and all related data. Cannot be undone.');">Force Delete</a>
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
.dest-grid { margin: -8px; }
.dest-col { padding: 8px; }
.destination-card { 
    border-radius: 12px; overflow: hidden; 
    border: 1px solid #e9ecef; 
    background: #fff;
    transition: transform 0.35s cubic-bezier(0.34, 1.56, 0.64, 1), 
                box-shadow 0.35s ease, 
                border-color 0.3s ease; 
}
.destination-card:hover { 
    transform: translateY(-6px) scale(1.02);
    box-shadow: 0 12px 28px rgba(0,0,0,0.12), 0 4px 12px rgba(158,113,2,0.08); 
    border-color: rgba(158,113,2,0.3);
}
.destination-card-image { position: relative; overflow: hidden; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); }
.destination-card-image::after {
    content: ''; position: absolute; inset: 0;
    background: linear-gradient(180deg, transparent 50%, rgba(0,0,0,0.15) 100%);
    opacity: 0;
    transition: opacity 0.35s ease;
}
.destination-card:hover .destination-card-image::after { opacity: 1; }
.destination-thumb { 
    display: block; width: 100%; height: 155px; object-fit: cover; 
    transition: transform 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
}
.destination-card:hover .destination-thumb { transform: scale(1.08); }
.destination-badge { 
    position: absolute; top: 8px; right: 8px; 
    font-size: 11px; padding: 4px 10px; 
    background: rgba(0,0,0,0.65); color: #fff; 
    border-radius: 6px;
    backdrop-filter: blur(4px);
    transition: transform 0.3s ease, background 0.3s ease;
}
.destination-card:hover .destination-badge { 
    transform: scale(1.05); 
    background: rgba(158,113,2,0.9);
}
.destination-card-body { padding: 14px 16px; transition: background 0.3s ease; }
.destination-card:hover .destination-card-body { background: #fafbfc; }
.destination-title { 
    font-size: 15px; font-weight: 600; color: #232323; 
    margin: 0 0 12px 0; 
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    transition: color 0.25s ease;
}
.destination-card:hover .destination-title { color: #9e7102; }
.destination-actions { display: flex; flex-wrap: wrap; gap: 6px; }
.dest-btn { 
    display: inline-flex; align-items: center; 
    padding: 6px 10px; font-size: 12px; 
    border-radius: 6px; 
    color: #495057; text-decoration: none; 
    transition: all 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
    transform: scale(0.98);
    opacity: 0.9;
}
.dest-btn .dest-count { font-size: 11px; opacity: 0.85; margin-left: 1px; }
.destination-card:hover .dest-btn { transform: scale(1); opacity: 1; }
.dest-btn:hover { color: #fff; text-decoration: none; transform: scale(1.05); }
.dest-btn:hover .dest-count { opacity: 1; }
.dest-btn-photos:hover { background: #28a745; color: #fff; }
.dest-btn-videos:hover { background: #17a2b8; color: #fff; }
.dest-btn-edit:hover { background: #007bff; color: #fff; }
.dest-btn-del:hover { background: #dc3545; color: #fff; }
.dest-btn-force:hover { background: #343a40; color: #fff; }
@keyframes destCardIn { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }
.dest-col { animation: destCardIn 0.4s ease-out backwards; }
.dest-col:nth-child(1) { animation-delay: 0.02s; }
.dest-col:nth-child(2) { animation-delay: 0.04s; }
.dest-col:nth-child(3) { animation-delay: 0.06s; }
.dest-col:nth-child(4) { animation-delay: 0.08s; }
.dest-col:nth-child(5) { animation-delay: 0.1s; }
.dest-col:nth-child(6) { animation-delay: 0.12s; }
.dest-col:nth-child(n+7) { animation-delay: 0.14s; }
</style>
@endsection
