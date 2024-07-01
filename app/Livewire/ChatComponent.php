<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Message;
use App\Events\MessageSentEvent;
use Livewire\WithFileUploads;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ChatComponent extends Component
{
    use WithFileUploads;
    public $user;
    public $sender_id;
    public $receiver_id;
    public $message = '';
    public $messages = [];
    public $file;
    public function render()
    {
        return view('livewire.chat-component');
    }

    public function mount($user_id)
    {
        $this->sender_id = auth()->user()->id;
        $this->receiver_id = $user_id;

        $messages = Message::where(function ($query) {
            $query->where('sender_id', $this->sender_id)
                ->where('receiver_id', $this->receiver_id);
        })->orWhere(function ($query) {
            $query->where('sender_id', $this->receiver_id)
                ->where('receiver_id', $this->sender_id);
        })
            ->with('sender:id,name', 'receiver:id,name')
            ->get();

        // dd($messages->toArray());
        foreach ($messages as $message) {
            $this->appendChatMessage($message);
        }

        $this->user = User::where('id', $user_id)->first();
    }

    public function sendMessage()
    {
        $validator = Validator::make(['message' => $this->message, 'file' => $this->file], [
            'message' => ['nullable', 'regex:/^[a-zA-Z0-9\s]+$/'],
            'file' => ['nullable', 'mimes:jpeg,png,jpg,gif,mp3,wav,mp4,avi,doc,docx,pdf', 'max:10240']
        ], [
            'message.regex' => 'special characters is not accept',
        ]);
        if ($validator->fails()) {
            return;
        }

        try {
            $chatMessage = new Message();
            $chatMessage->sender_id = $this->sender_id;
            $chatMessage->receiver_id = $this->receiver_id;
            $chatMessage->message = $this->message;
            if ($this->file != "") {
                $filename = time() . "_" . $this->file->getClientOriginalName();
                $filePath = $this->file->storeAs('public/files', $filename);   // iss path main image store ho jayegi
                $chatMessage->file = $filePath;
                $chatMessage->save();
            }
            $chatMessage->save();

            $this->appendChatMessage($chatMessage);
            broadcast(new MessageSentEvent($chatMessage))->toOthers();

            $this->message = '';
            $this->file = null;
        } catch (\Exception $e) {
            Log::error('error saving message', ['error' => $e->getMessage()]);
        }
    }

    #[On('echo-private:chat-channel.{sender_id},MessageSentEvent')]
    public function listenForMessage($event)
    {
        // dd($event);
        $chatMessage = Message::whereId($event['message']['id'])
            ->with('sender:id,name', 'receiver:id,name')
            ->first();
        $this->appendChatMessage($chatMessage);
    }

    public function appendChatMessage($message)
    {


        $this->messages[] = [
            'id' => $message->id,
            'message' => $message->message,
            'sender' => $message->sender->name,
            'receiver' => $message->receiver->name,
            'file' => $message->file,
        ];
    }
}
