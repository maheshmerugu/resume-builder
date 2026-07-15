<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-xl font-bold text-slate-900 sm:text-2xl">Profile</h1>
            <p class="text-sm text-slate-500">Manage your account settings</p>
        </div>
    </x-slot>

    <div class="space-y-6">
        @include('partials.alert')

        <div class="admin-card p-6 sm:p-8">
            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="admin-card p-6 sm:p-8">
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <div class="admin-card p-6 sm:p-8">
            <div class="max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-app-layout>
