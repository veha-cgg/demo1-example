<?php

namespace App\Livewire;

use App\Models\ChatMessage;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

#[Layout('layouts.livewire')]
class Chat extends Component
{
    use WithPagination;

    public $users;
    public $selectedUserId = null;
    public $message = '';

    public function mount()
    {
        $this->users = User::whereNot('id', Auth::id())->get();
    }

    public function selectUser($userId)
    {
        $this->selectedUserId = $userId;
        $this->resetPage();
    }

    public function sendMessage()
    {
        if (empty($this->message) || !$this->selectedUserId) {
            return;
        }

        ChatMessage::create([
            'send_id' => Auth::id(),
            'receiver_id' => $this->selectedUserId,
            'message' => $this->message,
        ]);

        $this->message = '';
        $this->dispatch('message-sent');
    }

    public function render()
    {
        $messages = collect();

        if ($this->selectedUserId) {
            $messages = ChatMessage::with(['sender', 'receiver'])
                ->where(function ($query) {
                    $query->where('send_id', Auth::id())
                        ->where('receiver_id', $this->selectedUserId);
                })
                ->orWhere(function ($query) {
                    $query->where('send_id', $this->selectedUserId)
                        ->where('receiver_id', Auth::id());
                })
                ->orderBy('created_at', 'asc')
                ->get();
        }

        return view('livewire.chat', [
            'messages' => $messages,
            'selectedUser' => $this->selectedUserId ? User::find($this->selectedUserId) : null,
        ]);
    }
}
