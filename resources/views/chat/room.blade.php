@extends('layouts.app')

@section('content')
<div class="chat-container">
    <div class="chat-messages" id="chat-messages">
        @foreach($messages as $message)
            <div class="message {{ $message->sender_id === auth()->id() ? 'sent' : 'received' }}">
                <div class="message-content">
                    @if($message->message_type === 'text')
                        {{ $message->message }}
                    @elseif($message->message_type === 'image')
                        <img src="{{ $message->file_url }}" alt="صورة">
                    @endif
                </div>
                <div class="message-meta">
                    {{ $message->sender->name }} - {{ $message->created_at->diffForHumans() }}
                </div>
            </div>
        @endforeach
    </div>

    <form class="chat-input" id="message-form">
        <input type="text" name="message" placeholder="اكتب رسالتك هنا...">
        <button type="submit">إرسال</button>
    </form>
</div>

@push('scripts')
<script>
    // إعداد Pusher
    const pusher = new Pusher('{{ config("broadcasting.connections.pusher.key") }}', {
        cluster: '{{ config("broadcasting.connections.pusher.options.cluster") }}'
    });

    // الاستماع للرسائل الجديدة
    const channel = pusher.subscribe('chat.{{ $room->id }}');
    channel.bind('new-message', function(data) {
        appendMessage(data.message);
    });

    function appendMessage(message) {
        const messagesDiv = document.getElementById('chat-messages');
        // إضافة الرسالة الجديدة
        // ...
    }
</script>
@endpush 