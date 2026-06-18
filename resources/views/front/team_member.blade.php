@extends('front.layout.master')

@section('main_content')
@php
$setting = App\Models\Setting::where('id',1)->first();
@endphp
<div class="page-top" style="background-image: url({{ asset('uploads/'.$setting->banner) }})">
    <div class="container">
        <div class="row">
            <div class="col-md-12 wow fadeInDown" data-wow-duration="0.5s">
                <h2>{{ $team_member->name }}</h2>
                <div class="breadcrumb-container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('team_members') }}">Team Members</a></li>
                        <li class="breadcrumb-item active">{{ $team_member->name }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="team-member-section pt_70 pb_70">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-12 mb-4 mb-lg-0 wow fadeInLeft" data-wow-duration="0.6s">
                <div class="member-profile-card animate-hover">
                    <div class="member-photo-wrapper">
                        <img src="{{ asset('uploads/'.$team_member->photo) }}" alt="{{ $team_member->name }}" class="member-photo">
                        <div class="photo-frame"></div>
                    </div>
                    @if($team_member->facebook != '' || $team_member->twitter != '' || $team_member->linkedin != '' || $team_member->instagram != '')
                    <div class="member-social">
                        <h4>Connect With Me</h4>
                        <ul class="social-links">
                            @if($team_member->facebook != '')
                            <li>
                                <a href="{{ $team_member->facebook }}" target="_blank" class="social-link facebook" title="Facebook">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                            </li>
                            @endif
                            @if($team_member->twitter != '')
                            <li>
                                <a href="{{ $team_member->twitter }}" target="_blank" class="social-link twitter" title="Twitter">
                                    <i class="fab fa-twitter"></i>
                                </a>
                            </li>
                            @endif
                            @if($team_member->linkedin != '')
                            <li>
                                <a href="{{ $team_member->linkedin }}" target="_blank" class="social-link linkedin" title="LinkedIn">
                                    <i class="fab fa-linkedin-in"></i>
                                </a>
                            </li>
                            @endif
                            @if($team_member->instagram != '')
                            <li>
                                <a href="{{ $team_member->instagram }}" target="_blank" class="social-link instagram" title="Instagram">
                                    <i class="fab fa-instagram"></i>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </div>
                    @endif
                </div>
            </div>
            
            <div class="col-lg-8 col-md-12 wow fadeInRight" data-wow-duration="0.6s" data-wow-delay="0.1s">
                <div class="member-info-card animate-hover">
                    <div class="member-header">
                        <h1 class="member-name">{{ $team_member->name }}</h1>
                        <div class="member-designation">
                            <i class="fas fa-briefcase"></i>
                            <span>{{ $team_member->designation }}</span>
                        </div>
                    </div>
                    
                    <div class="member-details">
                        <div class="info-table-wrapper">
                            <table class="elegant-info-table">
                                <tr>
                                    <td class="info-label"><i class="fas fa-user"></i> <span>Full Name</span></td>
                                    <td class="info-value">{{ $team_member->name }}</td>
                                </tr>
                                <tr>
                                    <td class="info-label"><i class="fas fa-briefcase"></i> <span>Designation</span></td>
                                    <td class="info-value">{{ $team_member->designation }}</td>
                                </tr>
                                @if($team_member->address != '')
                                <tr>
                                    <td class="info-label"><i class="fas fa-map-marker-alt"></i> <span>Address</span></td>
                                    <td class="info-value">{{ $team_member->address }}</td>
                                </tr>
                                @endif
                                @if($team_member->email != '')
                                <tr>
                                    <td class="info-label"><i class="fas fa-envelope"></i> <span>Email</span></td>
                                    <td class="info-value">
                                        <a href="mailto:{{ $team_member->email }}" class="email-link">{{ $team_member->email }}</a>
                                    </td>
                                </tr>
                                @endif
                                @if($team_member->phone != '')
                                <tr>
                                    <td class="info-label"><i class="fas fa-phone"></i> <span>Phone</span></td>
                                    <td class="info-value">
                                        <a href="tel:{{ $team_member->phone }}" class="phone-link">{{ $team_member->phone }}</a>
                                    </td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
                
                @if($team_member->biography != '')
                <div class="member-biography-card wow fadeInUp" data-wow-duration="0.6s" data-wow-delay="0.2s">
                    <div class="section-header">
                        <span class="section-icon"><i class="fas fa-book-open"></i></span>
                        <h2 class="section-title">Biography</h2>
                    </div>
                    <div class="biography-content elegant-description">
                        {!! $team_member->biography !!}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection