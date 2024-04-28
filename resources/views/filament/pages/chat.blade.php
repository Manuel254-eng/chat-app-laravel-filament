  <x-filament-panels::page>
  <style>
    .main-container{
        width: 100%;
        aspect-ratio: 5/2;
        margin: auto;
        overflow-x: hidden;
overflow-y: scroll;
scroll-snap-type: y mandatory;
    }
    .message-container {
        display: flex;
        flex-direction: column; 
        width: 100%;
    }

    .left-message {
        background-color: #e1e1e1;
        align-self: flex-start; 
        border-radius: 8px;
        padding: 10px;
        margin-bottom: 10px;
    }

    .right-message {
        background-color: #13B5EA;
        align-self: flex-end; 
        border-radius: 8px;
        padding: 10px;
        margin-bottom: 10px;
    }
    .message {
         padding: 10px;
         border-radius: 8px;
         
         width: 40% ;
        }
    
</style> 
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
    <script>
    // Scroll to the bottom of the message container
    function scrollToBottom() {
        var messageContainer = document.getElementById('message-container');
        messageContainer.scrollTop = messageContainer.scrollHeight;
    }

    // Call scrollToBottom function when the page is loaded
    window.addEventListener('load', function() {
        scrollToBottom();
    });

    // Call scrollToBottom function after Livewire updates the DOM
    Livewire.hook('message.processed', function() {
        scrollToBottom();
    });
</script>
</x-filament-panels::page>
