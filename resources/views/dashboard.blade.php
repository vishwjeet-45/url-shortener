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

    <table class="table">
        <thead>
            <tr>
                <th>client Name</th>
                <th>Users</th>
                <th>Total Generated URLs</th>
                <th>Total URL Hits</th>
            </tr>
        </thead>
        <tbody>
            @foreach($companies as $company)
            <tr>
                <td>{{ $company->name }}
                    <br>
                    <span class="text-sm text-gray-500">{{ $company->email }}</span>
                </td>
                <td>{{ $company->total_users }}</td>
                <td>{{ $company->total_genrated_urls }}</td>
                <td>{{ $company->total_url_hits }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $companies->links() }}
</x-app-layout>