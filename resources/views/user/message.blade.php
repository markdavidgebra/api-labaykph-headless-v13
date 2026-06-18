@extends('front.layout.master')

@section('main_content')
@php
$setting = App\Models\Setting::where('id',1)->first();
$user_data = Auth::guard('web')->user();
$user_photo = $user_data->photo ?? 'default.png';
@endphp
<div class="page-top" style="background-image: url({{ asset('uploads/'.$setting->banner) }})">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Messages</h2>
                <div class="breadcrumb-container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Messages</li>
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
            
            @if($message_check > 0)
                @php
                    $message = App\Models\Message::where('user_id', Auth::guard('web')->user()->id)->first();
                    $admin_data = App\Models\Admin::where('id', 1)->first();
                    $admin_name = $admin_data->name ?? 'Admin';
                    $admin_photo = $admin_data->photo ?? 'default.png';
                @endphp
                <div class="col-lg-9 col-md-12">
                    <div class="messenger-container">
                        <div class="messenger-header">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    @if($admin_photo != 'default.png' && $admin_photo != '')
                                        <img src="{{ asset('uploads/'.$admin_photo) }}" alt="{{ $admin_name }}" class="messenger-avatar">
                                    @else
                                        <img src="{{ asset('uploads/default.png') }}" alt="{{ $admin_name }}" class="messenger-avatar">
                                    @endif
                                    <div class="ml-3">
                                        <h5 class="mb-0 font-weight-600">{{ $admin_name }}</h5>
                                        <small class="text-muted d-flex align-items-center">
                                            <span class="status-indicator"></span>
                                            Active now
                                        </small>
                                    </div>
                                </div>
                                <div class="messenger-header-actions">
                                    <button class="btn btn-sm btn-light" title="More options">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="messenger-messages" id="messengerMessages">
                            @foreach($message_comments->reverse() as $item)
                                @php
                                    if($item->type == 'User'){
                                        $sender_data = App\Models\User::where('id', $item->sender_id)->first();
                                    } else {
                                        $sender_data = App\Models\Admin::where('id', $item->sender_id)->first();
                                    }
                                @endphp
                                
                                <div class="message-bubble-wrapper message-visible {{ $item->type == 'User' ? 'message-sent' : 'message-received' }}" data-comment-id="{{ $item->id }}">
                                    <div class="message-bubble">
                                        <div class="message-content">
                                            {!! $item->comment !!}
                                        </div>
                                        <div class="message-time">
                                            {{ $item->created_at->setTimezone('Asia/Manila')->format('M. j, Y h:i A') }}
                                        </div>
                                    </div>
                                    @if($item->type == 'User')
                                        <div class="message-avatar">
                                            @if($user_photo != '' && $user_photo != 'default.png')
                                                <img src="{{ asset('uploads/'.$user_photo) }}" alt="{{ $user_data->name }}">
                                            @else
                                                <img src="{{ asset('uploads/default.png') }}" alt="{{ $user_data->name }}">
                                            @endif
                                        </div>
                                    @else
                                        <div class="message-avatar">
                                            @if($sender_data && $sender_data->photo != '' && $sender_data->photo != 'default.png')
                                                <img src="{{ asset('uploads/'.$sender_data->photo) }}" alt="{{ $sender_data->name }}">
                                            @else
                                                <img src="{{ asset('uploads/default.png') }}" alt="{{ $sender_data?->name ?? 'Admin' }}">
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        <div class="typing-indicator" id="typingIndicator" style="display: none;">
                            <div class="message-bubble-wrapper message-received">
                                <div class="typing-bubble">
                                    <span></span><span></span><span></span>
                                </div>
                                <div class="message-avatar">
                                    @if($admin_photo != 'default.png' && $admin_photo != '')
                                        <img src="{{ asset('uploads/'.$admin_photo) }}" alt="{{ $admin_name }}" class="messenger-avatar">
                                    @else
                                        <img src="{{ asset('uploads/default.png') }}" alt="{{ $admin_name }}" class="messenger-avatar">
                                    @endif
                                </div>
                            </div>
                            <small class="text-muted">Admin is typing...</small>
                        </div>
                        
                        <div class="messenger-input">
                            <form action="{{ route('user_message_submit') }}" method="post" id="messageForm">
                                @csrf
                                <div class="input-group messenger-input-group">
                                    <textarea name="comment" 
                                              class="form-control messenger-textarea" 
                                              rows="1" 
                                              placeholder="Type your message here..." 
                                              required
                                              id="messageInput"></textarea>
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-primary messenger-send-btn" title="Send message">
                                            <i class="fas fa-paper-plane"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <div class="col-lg-9 col-md-12">
                    <div class="alert alert-danger">
                        No message found<br>
                        <a href="{{ route('user_message_start') }}" class="text-decoration-underline">Please click here to start messaging</a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    // User photo for AJAX messages
    @php
        $user_photo_path = ($user_photo && $user_photo != 'default.png') 
            ? asset('uploads/'.$user_photo) 
            : asset('uploads/default.png');
        $last_comment_id = ($message_check > 0 && isset($message_comments) && count($message_comments) > 0)
            ? (collect($message_comments)->max('id') ?? 0)
            : 0;
    @endphp
    const userPhotoPath = @json($user_photo_path);
    const userName = @json($user_data->name ?? 'User');
    let lastCommentId = {{ $last_comment_id }};
    const pollUrl = @json(route('user_message_poll'));
    const typingCheckUrl = @json(route('user_typing_check'));
    const typingUrl = @json(route('user_typing'));
    const csrfToken = @json(csrf_token());
    const hasMessenger = @json($message_check > 0);

    // Auto-scroll to bottom
    function scrollToBottom() {
        const messagesContainer = document.getElementById('messengerMessages');
        if (messagesContainer) {
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }
    }

    // Auto-scroll on page load
    document.addEventListener('DOMContentLoaded', function() {
        scrollToBottom();
    });

    // Escape HTML for safe display
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // AJAX form submission - optimistic UI (show message instantly, send in background)
    document.getElementById('messageForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const form = this;
        const messageInput = document.getElementById('messageInput');
        const messagesContainer = document.getElementById('messengerMessages');
        const comment = (messageInput.value || '').trim();
        
        if (!comment) return;
        
        // Clear input immediately (instant feel)
        messageInput.value = '';
        messageInput.style.height = 'auto';
        
        // Add message to UI immediately (optimistic)
        const tempId = 'temp-' + Date.now();
        const now = new Date();
        const timeStr = now.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric', hour: 'numeric', minute: '2-digit' });
        const messageWrapper = document.createElement('div');
        messageWrapper.className = 'message-bubble-wrapper message-sent message-appear message-visible';
        messageWrapper.setAttribute('data-comment-id', tempId);
        messageWrapper.innerHTML = `
            <div class="message-bubble">
                <div class="message-content">${escapeHtml(comment)}</div>
                <div class="message-time">${timeStr}</div>
            </div>
            <div class="message-avatar">
                <img src="${userPhotoPath}" alt="${userName}">
            </div>
        `;
        messagesContainer.appendChild(messageWrapper);
        scrollToBottom();
        
        // Send in background (no loading state)
        const formData = new FormData(form);
        formData.set('comment', comment);
        
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                messageWrapper.setAttribute('data-comment-id', data.message.id);
                lastCommentId = Math.max(lastCommentId, data.message.id);
                // Update timestamp if desired (optional)
                const timeEl = messageWrapper.querySelector('.message-time');
                if (timeEl && data.message.created_at) timeEl.textContent = data.message.created_at;
            } else {
                messageWrapper.remove();
                messageInput.value = comment;
                messageInput.style.height = 'auto';
                alert('Error sending message. Please try again.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            messageWrapper.remove();
            messageInput.value = comment;
            messageInput.style.height = 'auto';
            alert('Error sending message. Please try again.');
        });
    });

    // Auto-resize textarea + typing indicator
    const messageInput = document.getElementById('messageInput');
    if (messageInput) {
        messageInput.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
        
        // Allow Enter key to submit (Shift+Enter for new line)
        messageInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                document.getElementById('messageForm').dispatchEvent(new Event('submit'));
            }
        });

        // Typing indicator: debounced POST when user types
        let typingTimeout;
        function sendTyping() {
            if (!typingUrl || !hasMessenger) return;
            const fd = new FormData();
            fd.append('_token', csrfToken);
            fetch(typingUrl, {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                body: fd
            }).catch(() => {});
        }
        messageInput.addEventListener('input', function() {
            clearTimeout(typingTimeout);
            typingTimeout = setTimeout(sendTyping, 300);
        });
    }

    // Real-time: poll for admin typing every 1 second (fast feedback)
    if (hasMessenger && typingCheckUrl) {
        setInterval(function() {
            fetch(typingCheckUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' }, credentials: 'same-origin' })
            .then(r => r.json())
            .then(data => {
                const typingEl = document.getElementById('typingIndicator');
                if (typingEl) {
                    const show = data.admin_typing === true;
                    typingEl.style.display = show ? 'block' : 'none';
                    if (show) scrollToBottom();
                }
            })
            .catch(() => {});
        }, 1000);
    }

    // Real-time: poll for new admin messages every 3 seconds
    if (hasMessenger && document.getElementById('messengerMessages')) {
        setInterval(function() {
            fetch(pollUrl + '?last_id=' + lastCommentId, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                credentials: 'same-origin'
            })
            .then(r => r.json())
            .then(data => {
                const typingEl = document.getElementById('typingIndicator');
                if (typingEl && data.admin_typing !== undefined) typingEl.style.display = (data.admin_typing ? 'block' : 'none');
                if (data.comments && data.comments.length > 0) {
                    const container = document.getElementById('messengerMessages');
                    data.comments.forEach(function(msg) {
                        lastCommentId = Math.max(lastCommentId, msg.id);
                        if (msg.type === 'Admin' && !container.querySelector('[data-comment-id="' + msg.id + '"]')) {
                            const wrapper = document.createElement('div');
                            wrapper.className = 'message-bubble-wrapper message-received message-appear';
                            wrapper.setAttribute('data-comment-id', msg.id);
                            wrapper.innerHTML = `
                                <div class="message-bubble">
                                    <div class="message-content">${msg.comment}</div>
                                    <div class="message-time">${msg.created_at}</div>
                                </div>
                                <div class="message-avatar">
                                    <img src="${msg.sender_photo || (window.location.origin + '/uploads/default.png')}" alt="${msg.sender_name || 'Admin'}">
                                </div>
                            `;
                            container.appendChild(wrapper);
                            setTimeout(() => wrapper.classList.add('message-visible'), 10);
                        }
                    });
                    scrollToBottom();
                }
            })
            .catch(() => {});
        }, 3000);
    }
</script>
@endsection
