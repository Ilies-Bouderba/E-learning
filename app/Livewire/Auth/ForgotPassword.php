<?php

namespace App\Livewire\Auth;

use App\Models\PasswordResetRequest;
use App\Models\User;
use Livewire\Component;

class ForgotPassword extends Component
{
    public string $email   = '';
    public bool   $sent    = false;
    public string $message = '';

    protected array $rules = [
        'email' => 'required|email|exists:users,email',
    ];

    protected array $messages = [
        'email.exists' => 'No account found with that email address.',
    ];

    public function submit(): void
    {
        $this->validate();

        $user = User::where('email', $this->email)->first();

        if ($user->isAdmin()) {
            $this->addError('email', 'Admin accounts cannot use this reset flow.');
            return;
        }

        $existing = PasswordResetRequest::where('user_id', $user->id)
            ->where('status', 'pending')
            ->first();

        if ($existing) {
            $this->sent    = true;
            $this->message = 'You already have a pending reset request. The admin will contact you soon.';
            return;
        }

        PasswordResetRequest::create([
            'user_id' => $user->id,
            'status'  => 'pending',
            'message' => null,
        ]);

        $this->sent    = true;
        $this->message = 'Your request has been sent. The admin will reset your password and email it to you shortly.';
    }

    public function render()
    {
        return view('livewire.auth.forgot-password');
    }
}
