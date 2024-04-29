<?php

namespace App\Filament\Pages;

use Exception;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Livewire\Attributes\On;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class Chat extends Page
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static string $view = 'filament.pages.chat';
    public ?array $data = [];
    public bool $waitingForResponse = false;
    public string $reply = '';
    public string $lastMessage = '';
    public Collection $allMessages;
    public array $replies = [];
    public String $otherPartyName;
    public $refresh = false;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Textarea::make('message')
                    ->required()
            ])
            ->statePath('data');
    }
    public function mount()
    {
        $userId = Auth::id();
    
        $otherPartyMessage = Message::where(function ($query) use ($userId) {
            $query->where('sender_id', '!=', $userId)
                ->orWhere('receiver_id', '!=', $userId);
        })->first();
    
        if ($otherPartyMessage) {
            // Determine the other party's ID
            $otherPartyId = $otherPartyMessage->sender_id != $userId ? $otherPartyMessage->sender_id : $otherPartyMessage->receiver_id;
    
            // Fetch messages
            $this->allMessages = Message::where('sender_id', $userId)
                                        ->orWhere('receiver_id', $userId)
                                        ->get();
    
            // Fetch other party's name
            $this->otherPartyName = User::find($otherPartyId)->name;
        } else {
            // Handle case where there are no messages 
            $this->allMessages = collect(); 
            $this->otherPartyName = 'No other party'; 
        }
    }
    
    

    public function create(): void
    {
        $messageText = $this->form->getState()['message'];
        $senderId = Auth::id();
        $recipientId = $senderId == 2 ? 1 : 2;
    
        // Create a new message instance
        $message = new Message();
        $message->text = $messageText;
        $message->sender_id = $senderId;
        $message->receiver_id = $recipientId;
        $message->save();
        
        // Add the reply message to the replies array
        $this->replies[] = [
            'text' => $messageText,
            'time' => now()->format('M j, Y H:i A'), 
        ];
    }

}
