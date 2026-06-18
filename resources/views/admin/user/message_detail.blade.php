@extends('admin.layout.master')
@section('main_content')
@include('admin.layout.nav')
@include('admin.layout.sidebar')
<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <div>
                <h1 class="mb-0">Messages</h1>
                <p class="text-muted mb-0">Manage customer conversations</p>
            </div>
            <div class="ml-auto d-flex align-items-center">
                @if(is_super_admin())
                <form action="{{ route('admin_message_clear', $id) }}" method="post" class="d-inline" onsubmit="return confirm('Are you sure you want to clear all messages in this conversation? This cannot be undone.');">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-danger mr-2">
                        <i class="fas fa-trash-alt mr-1"></i> Clear all messages
                    </button>
                </form>
                @endif
                <a href="{{ route('admin_message') }}" class="btn btn-sm btn-light">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Messages
                </a>
            </div>
        </div>
        <div class="section-body">
            @php
                $message = \App\Models\Message::with('user')->where('id', $id)->first();
                $user_name = $message->user->name ?? 'User';
                $user_photo = $message->user->photo ?? 'default.png';
                $admin_data = \App\Models\Admin::where('id', 1)->first();
                $admin_photo = $admin_data->photo ?? 'default.png';
                $admin_name = $admin_data->name ?? 'Admin';
            @endphp
            <div class="row">
                <div class="col-12">
                    <div class="messenger-container">
                        <div class="messenger-header">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    @if($user_photo != 'default.png' && $user_photo != '')
                                        <img src="{{ asset('uploads/'.$user_photo) }}" alt="{{ $user_name }}" class="messenger-avatar">
                                    @else
                                        <img src="{{ asset('uploads/default.png') }}" alt="{{ $user_name }}" class="messenger-avatar">
                                    @endif
                                    <div class="ml-3">
                                        <h5 class="mb-0 font-weight-600">{{ $user_name }}</h5>
                                        <small class="text-muted d-flex align-items-center">
                                            <span class="status-indicator"></span>
                                            Active now
                                        </small>
                                    </div>
                                </div>
                                <div class="messenger-header-actions">
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
                                
                                <div class="message-bubble-wrapper message-visible {{ $item->type == 'Admin' ? 'message-sent' : 'message-received' }}" data-comment-id="{{ $item->id }}">
                                    <div class="message-bubble">
                                        <div class="message-content">
                                            {!! $item->comment !!}
                                        </div>
                                        <div class="message-time">
                                            {{ $item->created_at->setTimezone('Asia/Manila')->format('M. j, Y h:i A') }}
                                        </div>
                                    </div>
                                    @if($item->type == 'Admin')
                                        <div class="message-avatar">
                                            @if($admin_photo != '' && $admin_photo != 'default.png')
                                                <img src="{{ asset('uploads/'.$admin_photo) }}" alt="{{ $admin_name }}">
                                            @else
                                                <img src="{{ asset('uploads/default.png') }}" alt="{{ $admin_name }}">
                                            @endif
                                        </div>
                                    @else
                                        <div class="message-avatar">
                                            @if($sender_data && $sender_data->photo != '' && $sender_data->photo != 'default.png')
                                                <img src="{{ asset('uploads/'.$sender_data->photo) }}" alt="{{ $sender_data->name }}">
                                            @else
                                                <img src="{{ asset('uploads/default.png') }}" alt="{{ $sender_data?->name ?? 'Customer' }}">
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
                                    @if($user_photo != 'default.png' && $user_photo != '')
                                        <img src="{{ asset('uploads/'.$user_photo) }}" alt="{{ $user_name }}" class="messenger-avatar">
                                    @else
                                        <img src="{{ asset('uploads/default.png') }}" alt="{{ $user_name }}" class="messenger-avatar">
                                    @endif
                                </div>
                            </div>
                            <small class="text-muted">{{ $user_name }} is typing...</small>
                        </div>
                        
                        <div class="messenger-input">
                            <form action="{{ route('admin_message_submit', $id) }}" method="post" id="messageForm">
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
            </div>
        </div>
    </section>
</div>

<script>
    // Admin photo for AJAX messages
    @php
        $admin_photo_path = ($admin_photo && $admin_photo != 'default.png') 
            ? asset('uploads/'.$admin_photo) 
            : asset('uploads/default.png');
        $last_comment_id = (isset($message_comments) && count($message_comments) > 0)
            ? (collect($message_comments)->max('id') ?? 0)
            : 0;
    @endphp
    const adminPhotoPath = @json($admin_photo_path);
    const adminName = @json($admin_name);
    let lastCommentId = {{ $last_comment_id }};
    const pollUrl = @json(route('admin_message_poll', $id));
    const typingUrl = @json(route('admin_typing', $id));
    const csrfToken = @json(csrf_token());

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
                <img src="${adminPhotoPath}" alt="${adminName}">
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

    // Auto-resize textarea
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

        // Typing indicator: send immediately on first keypress, then debounce
        let typingTimeout;
        let lastTypingSent = 0;
        function sendTyping() {
            if (!typingUrl) return;
            const now = Date.now();
            if (now - lastTypingSent < 300) return; // throttle to max once per 300ms
            lastTypingSent = now;
            const fd = new FormData();
            fd.append('_token', csrfToken);
            fetch(typingUrl, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: fd,
                credentials: 'same-origin'
            }).catch(() => {});
        }
        function onTyping() {
            sendTyping();
            clearTimeout(typingTimeout);
            typingTimeout = setTimeout(sendTyping, 350);
        }
        messageInput.addEventListener('input', onTyping);
        messageInput.addEventListener('keydown', onTyping);
    }

    // Real-time: poll for new customer messages every 3 seconds
    if (document.getElementById('messengerMessages')) {
        setInterval(function() {
            fetch(pollUrl + '?last_id=' + lastCommentId, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(r => r.json())
            .then(data => {
                const typingEl = document.getElementById('typingIndicator');
                if (typingEl) typingEl.style.display = (data.user_typing ? 'block' : 'none');
                if (data.comments && data.comments.length > 0) {
                    const container = document.getElementById('messengerMessages');
                    data.comments.forEach(function(msg) {
                        lastCommentId = Math.max(lastCommentId, msg.id);
                        if (container.querySelector('[data-comment-id="' + msg.id + '"]')) return;
                        const wrapper = document.createElement('div');
                        wrapper.className = 'message-bubble-wrapper message-received message-appear';
                        wrapper.setAttribute('data-comment-id', msg.id);
                        wrapper.innerHTML = `
                            <div class="message-bubble">
                                <div class="message-content">${msg.comment}</div>
                                <div class="message-time">${msg.created_at}</div>
                            </div>
                            <div class="message-avatar">
                                <img src="${msg.sender_photo || (window.location.origin + '/uploads/default.png')}" alt="${msg.sender_name || 'Customer'}">
                            </div>
                        `;
                        container.appendChild(wrapper);
                        setTimeout(() => wrapper.classList.add('message-visible'), 10);
                    });
                    scrollToBottom();
                }
            })
            .catch(() => {});
        }, 3000);
    }
</script>
@endsection
