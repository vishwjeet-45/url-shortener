<x-app-layout>

    <x-slot name="header">
        <div class="grid grid-cols-2 gap-4 items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Team Members
            </h2>

            <div class="text-right">
                <a href="{{ route('users.create') }}" class="btn">
                    Add New Team Member
                </a>
            </div>
        </div>

    </x-slot>

    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Total Generated URLs</th>
                <th>Total URL Hits</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->role->name }}</td>
                <td>{{ $user->total_generated_urls }}</td>
                <td>{{ $user->total_url_hits }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $users->links() }}
</x-app-layout>