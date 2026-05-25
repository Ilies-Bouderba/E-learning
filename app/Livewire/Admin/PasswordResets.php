<?php

namespace App\Livewire\Admin;

use App\Models\PasswordResetRequest;
use App\Notifications\PasswordResetByAdmin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class PasswordResets extends Component
{
    public ?int    $resolvingId = null;
    public string  $newPassword = '';

    public function openResolve(int $id): void
    {
        $this->resolvingId = $id;
        $this->newPassword = Str::random(10);
    }

    public function cancelResolve(): void
    {
        $this->resolvingId = null;
        $this->newPassword = '';
    }

    public function resolve(): void
    {
        $this->validate([
            'newPassword' => 'required|string|min:8',
        ]);

        $request = PasswordResetRequest::with('user')->findOrFail($this->resolvingId);

        $request->user->update([
            'password' => Hash::make($this->newPassword),
        ]);

        $request->user->notify(new PasswordResetByAdmin($this->newPassword));

        $request->update([
            'status'      => 'resolved',
            'resolved_at' => now(),
        ]);

        $this->resolvingId = null;
        $this->newPassword = '';

        session()->flash('success', 'Password reset and email sent to ' . $request->user->email);
    }

    public function dismiss(int $id): void
    {
        PasswordResetRequest::findOrFail($id)->update([
            'status'      => 'resolved',
            'resolved_at' => now(),
        ]);

        session()->flash('success', 'Request dismissed.');
    }

    public function render()
    {
        return view('livewire.admin.password-resets', [
            'pending'  => PasswordResetRequest::with('user')->where('status', 'pending')->latest()->get(),
            'resolved' => PasswordResetRequest::with('user')->where('status', 'resolved')->latest()->take(20)->get(),
            'pendingCount' => PasswordResetRequest::where('status', 'pending')->count(),
        ]);
    }
}
