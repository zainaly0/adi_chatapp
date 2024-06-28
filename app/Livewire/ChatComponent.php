<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Message;
use App\Events\MessageSentEvent;
use Livewire\Attributes\On;

class ChatComponent extends Component
{
    public $user;
    public $sender_id;
    public $receiver_id;
    public $message = '';
    public $messages = []; 
    public function render()
    {
        return view('livewire.chat-component');
    }
    
    public function mount($user_id){
        $this->sender_id = auth()->user()->id;
        $this->receiver_id = $user_id;
        $this->user = User::where('id', $user_id)->first();

        $messages = Message::where(function($query){
            $query->where('sender_id', $this->sender_id)
                  ->where('receiver_id', $this->receiver_id);
        })->orWhere(function($query){
            $query->where('sender_id', $this->receiver_id)
                  ->where('receiver_id', $this->sender_id);
        })
        ->with('sender:id,name', 'receiver:id,name')
        ->get();

        // dd($messages->toArray());
        foreach($messages as $message){
            $this->appendChatMessage($message);
        }

        // dd($this->messages);
    }

    public function sendMessage(){
        $chatMessage = new Message();
        $chatMessage->sender_id = $this->sender_id;
        $chatMessage->receiver_id = $this->receiver_id;
        $chatMessage->message = $this->message;
        $chatMessage->save();

        $this->appendChatMessage($chatMessage); 
        
        broadcast(new MessageSentEvent($chatMessage))->toOthers();

        $this->message = '';
    }

    #[On('echo-private:chat-channel.{sender_id},MessageSentEvent')]
    public function listenForMessage($event){
        // dd($event);
        $chatMessage = Message::whereId($event['message']['id'])
        ->with('sender:id,name', 'receiver:id,name')
        ->first();
        $this->appendChatMessage($chatMessage); 
    }

    public function appendChatMessage($message){
        $this->messages[] =[
            'id' => $message->id,
            'message' => $message->message,
            'sender' => $message->sender->name,
            'receiver' => $message->receiver->name,
        ];

    }
}