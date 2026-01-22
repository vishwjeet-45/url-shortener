<x-app-layout>



    <x-slot name="header">
        <div class="grid grid-cols-2 gap-4 items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Short URLs
            </h2>

            <div class="text-right">
                @if(!auth()->user()->hasRole('SuperAdmin'))
                <a href="{{ route('short-urls.create') }}" class="btn">Create Short URL</a>
                @endif
            </div>
        </div>
    </x-slot>

    <form method="GET" action="{{ route('short-urls.download') }}" class="flex gap-4 mb-4">
    
    <select name="filter" class="border px-2 py-1">
        <option value="">-- Select Date --</option>
        <option value="today" >Today</option>
        <option value="last_week">Last Week</option>
        <option value="last_month">Last Month</option>
    </select>

    <button type="submit" class="px-3 py-1 border bg-green-200">
        Download
    </button>

</form>

    <table class="table mt-4">
        <thead>
            <tr>
                <th>Short Code</th>
                <th>Original URL</th>
                <th>Clicks</th>
                @if(auth()->user()->hasRole('SuperAdmin'))
                <th>Company</th>
                @endif
                @if(auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Member'))
                <th>Created By</th>
                @endif
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($shortUrls as $url)
            <tr>
                <td>
                    <a href="{{ route('short-urls.show',$url->short_code) }}" target="_blank">
                        {{ url($url->short_code) }}
                    </a>
                </td>
                <td>{{ Str::limit($url->original_url, 50) }}</td>
                <td>{{ $url->click_count }}</td>
                @if(auth()->user()->hasRole('SuperAdmin'))
                <td>{{ $url->company->name }}</td>
                @endif
                @if(auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Member'))
                <td>{{ $url->user->name }}</td>
                @endif
                <td>{{ $url->created_at->format('d M Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $shortUrls->links() }}
</x-app-layout>