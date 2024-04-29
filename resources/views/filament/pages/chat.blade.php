  <x-filament-panels::page>
    <form wire:submit="create">
    <p class="conversation-heading">Conversation with {{ $otherPartyName }}</p>
        <div class="main-container mt-6" id="message-container">
        @if($allMessages->count() > 0)
            <div class="mt-6">
                @foreach($allMessages as $message)
                <div class="message-container">
                @if($message->sender_id == Auth::id())
                    <div class="message left-message">
                        <p class="font-bold">You</p>
                        <p>{{ $message->text }}</p>
                        <p class="message-time">{{ $message->created_at->format('M j, Y H:i A') }}</p>
                    </div>
                @else
                    <div class="message right-message">
                        <p class="font-bold">{{ $otherPartyName }}</p>
                        <p>{{ $message->text }}</p>
                        <p class="message-time">{{ $message->created_at->format('M j, Y H:i A') }}</p>
                    </div>
                @endif
            </div>
            @endforeach
        </div>
        @endif
        @if($replies)
         <div class="mt-6">
        @foreach($replies as $reply)
            <div class="message mt-6 left-message">
                <p class="font-bold">You </p>
                <p>{{ $reply['text'] }}</p>
                <p class="message-time">{{ $reply['time'] }}</p>
            </div>
         @endforeach
        </div>
        @endif
       </div>
        {{ $this->form }}
        @if(!$waitingForResponse)
            <x-filament::button
                    class="mt-6"
                    type="submit"
                    wire:target="submit">
                Send Message
            </x-filament::button>
        @else
            <div class="mt-6">
                <p>Waiting ..</p>
            </div>
        @endif
    </form>
</x-filament-panels::page>
