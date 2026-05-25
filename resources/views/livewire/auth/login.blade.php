<div class="auth-page">

    <div class="auth-left">
        <a href="/" class="auth-logo">edu<span>me</span>x</a>
        <div class="auth-left-content">
            <div class="auth-quote">
                "The beautiful thing about learning is that no one can take it away from you."
            </div>
            <div class="auth-quote-author">— B.B. King</div>
            <div class="auth-left-stats">
                <div class="auth-stat">
                    <span class="auth-stat-num">120+</span>
                    <span class="auth-stat-label">Courses</span>
                </div>
                <div class="auth-stat">
                    <span class="auth-stat-num">3.4k</span>
                    <span class="auth-stat-label">Students</span>
                </div>
                <div class="auth-stat">
                    <span class="auth-stat-num">48</span>
                    <span class="auth-stat-label">Teachers</span>
                </div>
            </div>
        </div>
        <div class="auth-deco-circle"></div>
        <div class="auth-deco-sq"></div>
        <div class="auth-deco-dot"></div>
    </div>

    <div class="auth-right">
        <div class="auth-form-wrap">

            <div class="auth-form-header">
                <span class="auth-tag">Welcome back</span>
                <h1 class="auth-title">Log in to your account</h1>
                <p class="auth-sub">Don't have an account? <a href="/register">Sign up free →</a></p>
            </div>

            <form class="auth-form" wire:submit="login">
                @csrf

                <div class="form-group">
                    <label for="email">Email address</label>
                    <input type="email" id="email" wire:model="email" placeholder="you@example.com"
                        autocomplete="email">
                    @error('email')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <div class="form-label-row">
                        <label for="password">Password</label>
                        <a href="/forgot-password" class="form-forgot">Forgot password?</a>
                    </div>
                    <input type="password" id="password" wire:model="password" placeholder="••••••••"
                        autocomplete="current-password">
                    @error('password')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-check">
                    <input type="checkbox" id="remember" wire:model="remember">
                    <label for="remember">Remember me for 30 days</label>
                </div>

                <button type="submit" class="btn btn-primary btn-full">
                    <span wire:loading.remove wire:target="login">Log In →</span>
                    <span wire:loading wire:target="login">Logging in...</span>
                </button>

            </form>

        </div>
    </div>

</div>
