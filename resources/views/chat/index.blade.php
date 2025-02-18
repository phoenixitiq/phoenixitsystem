@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-12">
    <div class="flex h-[600px] bg-white rounded-lg shadow-lg overflow-hidden">
        <!-- قائمة المحادثات -->
        <div class="w-1/4 border-r">
            <div class="p-4">
                <h2 class="text-lg font-bold mb-4">{{ __('chat.conversations') }}</h2>
                <div class="space-y-4">
                    @foreach($conversations as $conversation)
                        <div class="flex items-center p-3 hover:bg-gray-50 rounded-lg cursor-pointer">
                            <img src="{{ $conversation->other_user->avatar }}" class="w-10 h-10 rounded-full">
                            <div class="ml-3">
                                <div class="font-medium">{{ $conversation->other_user->name }}</div>
                                <div class="text-sm text-gray-500">{{ $conversation->last_message->preview }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- نافذة المحادثة -->
        <div class="flex-1 flex flex-col">
            <!-- رأس المحادثة -->
            <div class="p-4 border-b">
                <div class="flex items-center">
                    <img src="{{ $activeConversation->other_user->avatar }}" class="w-10 h-10 rounded-full">
                    <div class="ml-3">
                        <div class="font-medium">{{ $activeConversation->other_user->name }}</div>
                        <div class="text-sm text-gray-500">
                            @if($activeConversation->other_user->is_online)
                                <span class="text-green-500">{{ __('chat.online') }}</span>
                            @else
                                {{ __('chat.last_seen') }} {{ $activeConversation->other_user->last_seen_at->diffForHumans() }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- الرسائل -->
            <div class="flex-1 overflow-y-auto p-4">
                @foreach($messages as $message)
                    <div class="mb-4 flex {{ $message->user_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-[70%] {{ $message->user_id === auth()->id() ? 'bg-emerald-500 text-white' : 'bg-gray-100' }} rounded-lg p-3">
                            {{ $message->content }}
                            <div class="text-xs {{ $message->user_id === auth()->id() ? 'text-emerald-100' : 'text-gray-500' }} mt-1">
                                {{ $message->created_at->format('H:i') }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- مربع إدخال الرسالة -->
            <div class="p-4 border-t">
                <form class="flex gap-4">
                    <input type="text" class="flex-1 rounded-lg border-gray-300" placeholder="{{ __('chat.type_message') }}">
                    <button type="submit" class="btn-primary">
                        {{ __('chat.send') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 