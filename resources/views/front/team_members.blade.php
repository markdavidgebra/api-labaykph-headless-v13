@extends('front.layout.master')

@section('main_content')
@php
$setting = App\Models\Setting::where('id',1)->first();
@endphp
<div class="page-top" style="background-image: url({{ asset('uploads/'.$setting->banner) }})">
    <div class="container">
        <div class="row">
            <div class="col-md-12 wow fadeInDown" data-wow-duration="0.5s">
                <h2>Team Members</h2>
                <div class="breadcrumb-container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Team Members</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="team-section pt_70 pb_70">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="heading elegant-heading wow fadeInUp" data-wow-duration="0.6s">
                    <span class="section-label">Our Team</span>
                    <h2>Meet Our Expert Team</h2>
                    <p class="heading-description">
                        Get to know the passionate professionals who make your travel dreams come true
                    </p>
                </div>
            </div>
        </div>
        <div class="row team-grid">
            @foreach($team_members as $team_member)
            <div class="col-lg-3 col-md-6 mb-4 wow fadeInUp" data-wow-duration="0.6s" data-wow-delay="{{ $loop->index * 0.1 }}s">
                <div class="team-card animate-hover">
                    <div class="team-image-wrapper">
                        <a href="{{ route('team_member',$team_member->slug) }}" class="team-link">
                            <img src="{{ asset('uploads/'.$team_member->photo) }}" alt="{{ $team_member->name }}" class="team-image">
                            <div class="team-overlay">
                                <div class="overlay-content">
                                    <i class="fas fa-user"></i>
                                    <span>View Profile</span>
                                </div>
                            </div>
                        </a>
                        @if($team_member->facebook != '' || $team_member->twitter != '' || $team_member->linkedin != '' || $team_member->instagram != '')
                        <div class="team-social">
                            <ul class="social-links">
                                @if($team_member->facebook != '')
                                <li>
                                    <a href="{{ $team_member->facebook }}" target="_blank" class="social-link facebook">
                                        <i class="fab fa-facebook-f"></i>
                                    </a>
                                </li>
                                @endif
                                @if($team_member->twitter != '')
                                <li>
                                    <a href="{{ $team_member->twitter }}" target="_blank" class="social-link twitter">
                                        <i class="fab fa-twitter"></i>
                                    </a>
                                </li>
                                @endif
                                @if($team_member->linkedin != '')
                                <li>
                                    <a href="{{ $team_member->linkedin }}" target="_blank" class="social-link linkedin">
                                        <i class="fab fa-linkedin-in"></i>
                                    </a>
                                </li>
                                @endif
                                @if($team_member->instagram != '')
                                <li>
                                    <a href="{{ $team_member->instagram }}" target="_blank" class="social-link instagram">
                                        <i class="fab fa-instagram"></i>
                                    </a>
                                </li>
                                @endif
                            </ul>
                        </div>
                        @endif
                    </div>
                    <div class="team-content">
                        <h3 class="team-name">
                            <a href="{{ route('team_member',$team_member->slug) }}">{{ $team_member->name }}</a>
                        </h3>
                        <div class="team-designation">
                            <i class="fas fa-briefcase"></i>
                            <span>{{ $team_member->designation }}</span>
                        </div>
                        <div class="team-action">
                            <a href="{{ route('team_member',$team_member->slug) }}" class="view-profile-btn">
                                View Profile <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @if($team_members->hasPages())
        <div class="row">
            <div class="col-md-12">
                <div class="pagination-wrapper">
                    <div class="elegant-pagination">
                        {{ $team_members->links() }}
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection