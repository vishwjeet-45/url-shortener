<x-app-layout>

    <x-slot name="header">
        <div class="grid grid-cols-2 gap-4 items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Companies
            </h2>

            <div class="text-right">
                <a href="{{ route('companies.create') }}" class="btn">
                    Add New Company
                </a>
            </div>
        </div>

    </x-slot>

    <form method="POST" action="{{ route('companies.store') }}" class="grid grid-cols-2 gap-4">
        @csrf
        <div class="form-group">
            <label>Company Name</label>
            <input type="text" name="name" class="w-full" value="{{ old('name') }}" required>
        </div>
        <div class="form-group">
            <label>Company Email</label>
            <input type="email" name="email" class="w-full" value="{{ old('email') }}" required>
        </div>
        <button type="submit" class="btn px-4 py-2 rounded-md border">Create Company</button>
    </form>
</x-app-layout>