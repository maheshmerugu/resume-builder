<x-guest-layout>
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-gray-900">Create your account</h1>
        <p class="mt-2 text-gray-500">Start building professional resumes in minutes — it's free.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <div>
            <label for="name" class="block text-sm font-semibold text-gray-700 mb-1.5">Full name</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                   class="w-full rounded-xl border-gray-300 bg-white px-4 py-3 text-gray-900 shadow-sm transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 placeholder:text-gray-400"
                   placeholder="John Doe">
            <x-input-error :messages="$errors->get('name')" class="mt-1.5" />
        </div>

        <div>
            <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">Email address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                   class="w-full rounded-xl border-gray-300 bg-white px-4 py-3 text-gray-900 shadow-sm transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 placeholder:text-gray-400"
                   placeholder="you@example.com">
            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="password" class="block text-sm font-semibold text-gray-700 mb-1.5">Password</label>
                <input id="password" type="password" name="password" required autocomplete="new-password"
                       class="w-full rounded-xl border-gray-300 bg-white px-4 py-3 text-gray-900 shadow-sm transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 placeholder:text-gray-400"
                       placeholder="Min 8 characters">
                <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-1.5">Confirm password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                       class="w-full rounded-xl border-gray-300 bg-white px-4 py-3 text-gray-900 shadow-sm transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 placeholder:text-gray-400"
                       placeholder="Repeat password">
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1.5" />
            </div>
        </div>

        <button type="submit"
                class="w-full rounded-xl bg-indigo-600 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-600/20 transition hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
            Create account
        </button>

        <p class="text-xs text-center text-gray-400">
            By signing up, you agree to our Terms of Service and Privacy Policy.
        </p>
    </form>

    <p class="mt-6 text-center text-sm text-gray-500">
        Already have an account?
        <a href="{{ route('login') }}" class="font-semibold text-indigo-600 hover:text-indigo-500">Sign in</a>
    </p>
</x-guest-layout>
