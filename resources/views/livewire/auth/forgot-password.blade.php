<div class="auth-page">
    <div class="auth-left">
        <a href="/" class="auth-logo">edu<span>me</span>x</a>
        <div class="auth-left-content">
            <div class="auth-quote">
                "The beautiful thing about learning is that no one can take it away from you."
            </div>
            <div class="auth-quote-author">— B.B. King</div>
        </div>
        <div class="auth-deco-circle"></div>
        <div class="auth-deco-sq"></div>
        <div class="auth-deco-dot"></div>
    </div>

    <div class="auth-right">
        <div class="auth-form-wrap">
            <div class="auth-form-header">
                <span class="auth-tag">Account recovery</span>
                <h1 class="auth-title">Forgot your password?</h1>
                <p class="auth-sub">Enter your email and we'll notify the admin to reset it for you.</p>
            </div>

            @if($sent)
                <div style="background: rgba(16,185,129,0.1); border: 1px solid #10b981; border-radius: 12px; padding: 1.5rem; text-align: center;">
                    <div style="font-size: 2rem; margin-bottom: 0.5rem;">✅</div>
                    <p style="font-weight: 700; margin-bottom: 0.5rem;">Request Sent!</p>
                    <p style="color: var(--c-muted); font-size: 0.9rem;">{{ $message }}</p>
                    <a href="/login" class="btn btn-primary" style="margin-top: 1rem; display: inline-block;">Back to Login</a>
                </div>
            @else
                <form class="auth-form" wire:submit="submit">
                    <div class="form-group">
                        <label for="email">Email address</label>
                        <input type="email" id="email" wire:model="email"
                            placeholder="you@example.com" autocomplete="email">
                        @error('email')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary btn-full">
                        <span wire:loading.remove wire:target="submit">Send Reset Request →</span>
                        <span wire:loading wire:target="submit">Sending...</span>
                    </button>
                </form>

                <div style="text-align: center; margin-top: 1.5rem;">
                    <a href="/login" style="color: var(--c-muted); font-size: 0.875rem;">← Back to login</a>
                </div>
            @endif
        </div>
    </div>
</div>
