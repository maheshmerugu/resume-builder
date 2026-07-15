<x-guest-layout>
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-gray-900">Welcome back</h1>
        <p class="mt-2 text-gray-500">Sign in to your account to continue building your resume.</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">Email address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                   class="w-full rounded-xl border-gray-300 bg-white px-4 py-3 text-gray-900 shadow-sm transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 placeholder:text-gray-400"
                   placeholder="you@example.com">
            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
        </div>

        <div>
            <div class="flex items-center justify-between mb-1.5">
                <label for="password" class="block text-sm font-semibold text-gray-700">Password</label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">Forgot password?</a>
                @endif
            </div>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                   class="w-full rounded-xl border-gray-300 bg-white px-4 py-3 text-gray-900 shadow-sm transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 placeholder:text-gray-400"
                   placeholder="Enter your password">
            <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
        </div>

        <label for="remember_me" class="flex items-center gap-2 cursor-pointer">
            <input id="remember_me" type="checkbox" name="remember"
                   class="h-4 w-4 rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500/20">
            <span class="text-sm text-gray-600">Remember me for 30 days</span>
        </label>

        <button type="submit"
                class="w-full rounded-xl bg-indigo-600 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-600/20 transition hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
            Sign in
        </button>
    </form>

    <p class="mt-8 text-center text-sm text-gray-500">
        Don't have an account?
        <a href="{{ route('register') }}" class="font-semibold text-indigo-600 hover:text-indigo-500">Create one free</a>
    </p>

    <p class="mt-3 text-center text-sm text-gray-400">
        <a href="{{ route('admin.login') }}" class="font-medium text-gray-500 hover:text-gray-700">Admin login &rarr;</a>
    </p>
</x-guest-layout>
