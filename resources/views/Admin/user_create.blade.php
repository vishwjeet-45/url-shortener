<x-app-layout>
    <x-slot name="header">
        <div class="grid grid-cols-2 gap-4 items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Invite New User
            </h2>
        </div>
    </x-slot>

<form method="POST" class="flex items-center gap-3" action="{{ route('users.store') }}">
    @csrf
    <div class="form-group">
        <label>Name</label>
        <input type="text" name="name" class="w-full" value="{{ old('name') }}" required>
    </div>
    <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" class="w-full" value="{{ old('email') }}" required>
    </div>
    <div class="form-group">
        <label>Role</label>
        <select name="role_id" class="w-full" required>
            <option value="">Select Role</option>
            @foreach($roles as $role)
            <option value="{{ $role->id }}">{{ $role->name }}</option>
            @endforeach
        </select>
    </div>
    <input type="hidden" name="company_id" value="{{ auth()->user()->company_id }}">
    <button type="submit" class="btn btn-secondary mt-3">Send Invitation</button>
</form>
</x-app-layout>