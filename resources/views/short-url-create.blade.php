<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Create Short URL
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('short-urls.store') }}">
                        @csrf
                        <div class="form-group mb-4">
                            <label>Original URL</label>
                            <input type="url" name="original_url" class="w-full" value="{{ old('original_url') }}" required>
                        </div>
                        <button type="submit" class="btn px-4 py-2 rounded-md border
">Create Short URL</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


</x-app-layout>