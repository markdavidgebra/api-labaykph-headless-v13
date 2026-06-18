@php
$setting = App\Models\Setting::where('id',1)->first();
@endphp
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Easy Access Travel</title>

        <link rel="icon" type="image/png" href="{{ asset('uploads/'.$setting->favicon) }}">

        <!-- All CSS -->
        <link rel="stylesheet" href="{{ asset('dist-front/css/bootstrap.min.css') }}">
        {{-- Local Roboto – load early so it applies across the entire frontend --}}
        <link rel="stylesheet" href="{{ asset('dist-front/css/roboto.css') }}">
        <link rel="stylesheet" href="{{ asset('dist-front/css/bootstrap-datepicker.min.css') }}">
        <link rel="stylesheet" href="{{ asset('dist-front/css/animate.min.css') }}">
        <link rel="stylesheet" href="{{ asset('dist-front/css/magnific-popup.css') }}">
        <link rel="stylesheet" href="{{ asset('dist-front/css/owl.carousel.min.css') }}">
        <link rel="stylesheet" href="{{ asset('dist-front/css/select2.min.css') }}">
        <link rel="stylesheet" href="{{ asset('dist-front/css/select2-bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('dist-front/css/all.css') }}">
        <link rel="stylesheet" href="{{ asset('dist-front/css/meanmenu.css') }}">
        <link rel="stylesheet" href="{{ asset('dist/css/iziToast.min.css') }}">
        <link rel="stylesheet" href="{{ asset('dist-front/css/spacing.css') }}">
        <link rel="stylesheet" href="{{ asset('dist-front/css/style.css') }}">
        <link rel="stylesheet" href="{{ asset('dist-front/css/front-animations.css') }}">
        
        <!-- All Javascripts -->
        <script src="{{ asset('dist-front/js/jquery-3.6.1.min.js') }}"></script>
        <script src="{{ asset('dist-front/js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('dist-front/js/bootstrap-datepicker.min.js') }}"></script>
        <script src="{{ asset('dist-front/js/jquery.magnific-popup.min.js') }}"></script>
        <script src="{{ asset('dist-front/js/owl.carousel.min.js') }}"></script>
        <script src="{{ asset('dist-front/js/wow.min.js') }}"></script>
        <script src="{{ asset('dist-front/js/select2.full.js') }}"></script>
        <script src="{{ asset('dist-front/js/jquery.waypoints.min.js') }}"></script>
        <script src="{{ asset('dist-front/js/moment.min.js') }}"></script>
        <script src="{{ asset('dist-front/js/counterup.min.js') }}"></script>
        <script src="{{ asset('dist-front/js/multi-countdown.js') }}"></script>
        <script src="{{ asset('dist-front/js/jquery.meanmenu.js') }}"></script>
        <script src="{{ asset('dist/js/iziToast.min.js') }}"></script>

    </head>
    <body>
        <div class="top">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 left-side">
                        <ul>
                            <li class="phone-text"><i class="fas fa-phone"></i> {{ $setting->top_bar_phone }}</li>
                            <li class="email-text"><i class="fas fa-envelope"></i> {{ $setting->top_bar_email }}</li>
                        </ul>
                    </div>
                    <div class="col-md-6 right-side">
                        <ul class="right">
                            @if(Auth::guard('web')->check())
                            @php
                                $top_bar_admin_comments_count = 0;
                                $user_message = \App\Models\Message::where('user_id', Auth::guard('web')->user()->id)->first();
                                if($user_message) {
                                    $query = \App\Models\MessageComment::where('message_id', $user_message->id)->where('type', 'Admin');
                                    if($user_message->user_viewed_at) {
                                        $query->where('created_at', '>', $user_message->user_viewed_at);
                                    }
                                    $top_bar_admin_comments_count = $query->count();
                                }
                            @endphp
                            <li class="menu">
                                <a href="{{ route('user_dashboard') }}" class="d-inline-flex align-items-center">
                                    <i class="fas fa-sign-in-alt"></i> Dashboard
                                    <span id="topBarMessageBadge" class="badge top-bar-message-badge ml-2" style="{{ $top_bar_admin_comments_count > 0 ? '' : 'display:none;' }}">{{ $top_bar_admin_comments_count > 99 ? '99+' : $top_bar_admin_comments_count }}</span>
                                </a>
                            </li>
                            @else
                            <li class="menu">
                                <a href="{{ route('login') }}"><i class="fas fa-sign-in-alt"></i> Login</a>
                            </li>
                            <li class="menu">
                                <a href="{{ route('registration') }}"><i class="fas fa-user"></i> Sign Up</a>
                            </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        @include('front.layout.nav')

        @yield('main_content')
        
        <footer class="footer footer-elegant">
            <div class="container">
                <div class="row footer__row">
                    <div class="col-lg-3 col-md-6 footer__col wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.1s">
                        <div class="footer__block">
                            <h3 class="footer__heading">Explore</h3>
                            <ul class="footer__links">
                                <li><a href="{{ route('home') }}">Home</a></li>
                                <li><a href="{{ route('destinations') }}">Destinations</a></li>
                                <li><a href="{{ route('packages') }}">Packages</a></li>
                                <li><a href="{{ route('blog') }}">Latest News</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 footer__col wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.2s">
                        <div class="footer__block">
                            <h3 class="footer__heading">Support</h3>
                            <ul class="footer__links">
                                <li><a href="{{ route('faq') }}">FAQs</a></li>
                                <li><a href="{{ route('terms') }}">Terms of Use</a></li>
                                <li><a href="{{ route('privacy') }}">Privacy Policy</a></li>
                                <li><a href="{{ route('contact') }}">Contact</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 footer__col wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.3s">
                        <div class="footer__block">
                            <h3 class="footer__heading">Get in touch</h3>
                            <div class="footer__contact">
                                @if($setting->footer_address)
                                <div class="footer__contact-item">
                                    <span class="footer__contact-icon"><i class="fas fa-map-marker-alt"></i></span>
                                    <span class="footer__contact-text">{{ $setting->footer_address }}</span>
                                </div>
                                @endif
                                @if($setting->footer_phone)
                                <div class="footer__contact-item">
                                    <span class="footer__contact-icon"><i class="fas fa-phone"></i></span>
                                    <span class="footer__contact-text">{{ $setting->footer_phone }}</span>
                                </div>
                                @endif
                                @if($setting->footer_email)
                                <div class="footer__contact-item">
                                    <span class="footer__contact-icon"><i class="fas fa-envelope"></i></span>
                                    <span class="footer__contact-text"><a href="mailto:{{ $setting->footer_email }}">{{ $setting->footer_email }}</a></span>
                                </div>
                                @endif
                            </div>
                            
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 footer__col wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.4s">
                        <div class="footer__block footer__block--newsletter">
                            <h3 class="footer__heading">Newsletter</h3>
                            <p class="footer__lead">Get travel inspiration and updates delivered to your inbox.</p>
                            <form action="{{ route('subscriber_submit') }}" method="post" class="footer__newsletter-form">
                                @csrf
                                <div class="footer__newsletter-field">
                                    <input type="email" name="email" class="footer__newsletter-input" placeholder="Your email address" required>
                                    <button type="submit" class="footer__newsletter-btn" aria-label="Subscribe">
                                        <i class="fas fa-paper-plane"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </footer>

        <div class="footer-bottom">
            <div class="container">
                <div class="copyright">
                    {{ $setting->copyright }}
                </div>
            </div>
        </div>

        <div class="scroll-top">
            <i class="fas fa-angle-up"></i>
        </div>

        {{-- Sticky Contact Us Button (only shown when logged out) --}}
        @guest
        <a href="javascript:void(0)" class="contact-us-sticky" id="contactUsStickyBtn" title="Contact Us" aria-label="Contact Us">
            <span class="contact-us-sticky-text">Contact Us</span>
        </a>
        @endguest

        {{-- Contact Us Modal --}}
        <div class="modal fade" id="contactUsModal" tabindex="-1" role="dialog" aria-labelledby="contactUsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content contact-us-modal-content">
                    <div class="modal-header contact-us-modal-header">
                        <h5 class="modal-title" id="contactUsModalLabel">
                            <i class="fas fa-envelope-open-text mr-2"></i>Get In Touch
                        </h5>
                        <button type="button" class="close contact-us-modal-close" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close">
                            <i class="fas fa-times" aria-hidden="true"></i>
                        </button>
                    </div>
                    <form id="contactUsQuickForm" class="contact-us-modal-form">
                        @csrf
                        <div class="modal-body contact-us-modal-body">
                            <p class="contact-us-modal-intro">We'd love to hear from you. Fill out the form below and we'll get back to you soon.</p>
                            <div class="form-group">
                                <label for="quick_contact_name">Your Name</label>
                                <input type="text" class="form-control" id="quick_contact_name" name="name" placeholder="Enter your name" required>
                            </div>
                            <div class="form-group">
                                <label for="quick_contact_email">Your Email</label>
                                <input type="email" class="form-control" id="quick_contact_email" name="email" placeholder="Enter your email" required>
                            </div>
                            <div class="form-group">
                                <label for="quick_contact_phone">Your Phone</label>
                                <input type="tel" class="form-control" id="quick_contact_phone" name="phone" placeholder="Enter your phone number" required>
                            </div>
                        </div>
                        <div class="modal-footer contact-us-modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn contact-us-submit-btn">
                                <i class="fas fa-paper-plane mr-1"></i>Send Message
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <style>
        .contact-us-sticky {
            position: fixed;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            background: linear-gradient(135deg, #9e7102 0%, #b8860b 100%);
            color: #fff;
            padding: 20px 12px;
            text-decoration: none;
            font-weight: bold !important;
            font-size: 18px;
            letter-spacing: 0.8px;
            z-index: 999;
            box-shadow: -4px 0 20px rgba(158, 113, 2, 0.4);
            border-radius: 8px 0 0 8px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            writing-mode: vertical-rl;
            text-orientation: mixed;
            animation: contact-us-entrance 0.8s cubic-bezier(0.34, 1.56, 0.64, 1) 0.5s both, contact-us-glow 3s ease-in-out 1.5s infinite;
        }
        .contact-us-sticky:hover {
            color: #fff;
            text-decoration: none;
            padding-right: 18px;
            box-shadow: -8px 0 30px rgba(158, 113, 2, 0.55), 0 0 25px rgba(255, 255, 255, 0.2);
        }
        .contact-us-sticky-text { transform: rotate(180deg); display: inline-block; }
        @keyframes contact-us-entrance {
            0% { right: -80px; opacity: 0; }
            100% { right: 0; opacity: 1; }
        }
        @keyframes contact-us-glow {
            0%, 100% { box-shadow: -4px 0 20px rgba(158, 113, 2, 0.4), 0 0 0 0 rgba(255, 255, 255, 0.15); }
            50% { box-shadow: -6px 0 28px rgba(158, 113, 2, 0.5), 0 0 20px 0 rgba(255, 255, 255, 0); }
        }
        .contact-us-modal-content {
            background: #fff;
            border: none;
            border-radius: 16px;
            box-shadow: 0 24px 64px rgba(0,0,0,0.12);
        }
        .contact-us-modal-header {
            background: transparent;
            color: #333;
            border-radius: 16px 16px 0 0;
            padding: 20px 24px 16px;
            border: none;
            border-bottom: 1px solid rgba(0,0,0,0.06);
        }
        .contact-us-modal-header .modal-title {
            font-weight: 600;
            font-size: 1.2rem;
            letter-spacing: -0.02em;
        }
        .contact-us-modal-header .modal-title i {
            color: #9e7102;
            margin-right: 8px;
        }
        .contact-us-modal-close {
            background: none;
            border: none;
            color: #999;
            opacity: 0.7;
            font-size: 1.25rem;
            text-shadow: none;
            padding: 0.5rem;
            line-height: 1;
            transition: color 0.2s, opacity 0.2s;
        }
        .contact-us-modal-close:hover {
            color: #333;
            opacity: 1;
        }
        .contact-us-modal-body { padding: 24px; }
        .contact-us-modal-intro {
            color: #6c757d;
            margin-bottom: 20px;
            font-size: 14px;
        }
        .contact-us-modal-body .form-group label { font-weight: 600; }
        .contact-us-modal-body .form-control {
            border-radius: 8px;
            padding: 12px 16px;
            border: 1px solid #e0e0e0;
        }
        .contact-us-modal-body .form-control:focus {
            border-color: #9e7102;
            box-shadow: 0 0 0 3px rgba(158, 113, 2, 0.15);
        }
        .contact-us-modal-footer {
            padding: 16px 24px 24px;
            border-top: 1px solid rgba(0,0,0,0.06);
            background: transparent;
            border-radius: 0 0 16px 16px;
        }
        .contact-us-submit-btn {
            background: linear-gradient(135deg, #9e7102 0%, #b8860b 100%);
            color: #fff;
            border: none;
            padding: 10px 24px;
            border-radius: 8px;
            font-weight: 600;
        }
        .contact-us-submit-btn:hover {
            background: linear-gradient(135deg, #8a6202 0%, #9e7102 100%);
            color: #fff;
        }
        </style>

        <script>
        (function() {
            var stickyBtn = document.getElementById('contactUsStickyBtn');
            var modal = document.getElementById('contactUsModal');
            var form = document.getElementById('contactUsQuickForm');
            var submitUrl = @json(route('contact_quick_submit'));

            if (stickyBtn) {
                stickyBtn.addEventListener('click', function() {
                    $(modal).modal('show');
                });
            }

            if (modal) {
                [].forEach.call(modal.querySelectorAll('.contact-us-modal-close, [data-bs-dismiss="modal"], [data-dismiss="modal"]'), function(btn) {
                    btn.addEventListener('click', function() { $(modal).modal('hide'); });
                });
            }

            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    var btn = form.querySelector('.contact-us-submit-btn');
                    var originalText = btn.innerHTML;
                    btn.disabled = true;
                    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>Sending...';

                    var formData = new FormData(form);
                    fetch(submitUrl, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        },
                        credentials: 'same-origin'
                    })
                    .then(function(r) {
                        return r.json().then(function(data) {
                            if (!r.ok) throw { status: r.status, data: data };
                            return data;
                        });
                    })
                    .then(function(data) {
                        $(modal).modal('hide');
                        form.reset();
                        if (typeof iziToast !== 'undefined') {
                            iziToast.success({ message: data.message || 'Thank you! We will contact you soon.', position: 'topRight' });
                        } else {
                            alert(data.message || 'Thank you! We will contact you soon.');
                        }
                    })
                    .catch(function(err) {
                        var msg = 'Something went wrong. Please try again.';
                        if (err && err.data && err.data.errors) {
                            msg = Object.values(err.data.errors).flat().join(' ');
                        } else if (err && err.data && err.data.message) {
                            msg = err.data.message;
                        }
                        if (typeof iziToast !== 'undefined') {
                            iziToast.error({ message: msg, position: 'topRight' });
                        } else {
                            alert(msg);
                        }
                    })
                    .finally(function() {
                        btn.disabled = false;
                        btn.innerHTML = originalText;
                    });
                });
            }
        })();
        </script>

        <script src="{{ asset('dist-front/js/custom.js') }}"></script>

        @if(Auth::guard('web')->check())
        <script>
        (function() {
            var pollUrl = @json(route('user_message_notification_count'));
            var pollInterval = 15000;

            function updateNotificationBadges(count) {
                var display = count > 0 ? '' : 'none';
                var text = count > 99 ? '99+' : String(count);

                var topBarBadge = document.getElementById('topBarMessageBadge');
                if (topBarBadge) {
                    topBarBadge.textContent = text;
                    topBarBadge.style.display = count > 0 ? '' : 'none';
                }

                var sidebarBadge = document.getElementById('sidebarMessageBadge');
                if (sidebarBadge) {
                    sidebarBadge.textContent = text;
                    sidebarBadge.style.display = count > 0 ? '' : 'none';
                }
            }

            function poll() {
                fetch(pollUrl, { credentials: 'same-origin' })
                    .then(function(r) { return r.json(); })
                    .then(function(data) { updateNotificationBadges(data.count || 0); })
                    .catch(function() {});
            }

            poll();
            setInterval(poll, pollInterval);
        })();
        </script>
        @endif

        @if($errors->any())
            @foreach ($errors->all() as $error)
                <script>
                    iziToast.show({
                        message: '{{ $error }}',
                        color: 'red',
                        position: 'topRight',
                    });
                </script>
            @endforeach
        @endif
        @if(session('success'))
            <script>
                iziToast.show({
                    message: '{{ session("success") }}',
                    color: 'green',
                    position: 'topRight',
                });
            </script>
        @endif

        @if(session('error'))
            <script>
                iziToast.show({
                    message: '{{ session("error") }}',
                    color: 'red',
                    position: 'topRight',
                });
            </script>
        @endif
    </body>
</html>