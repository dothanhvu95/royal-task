@extends('layouts.dashboard')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Add New Book</h1>
    </div>

    @if($errors->any())
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white shadow-sm rounded-lg">
        <form action="{{ route('books.store') }}" method="POST" class="space-y-6 p-6">
            @csrf

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700">Title *</label>
                    <input type="text" 
                           name="title" 
                           id="title" 
                           value="{{ old('title') }}"
                           required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <!-- Author -->
                <div>
                    <label for="author_id" class="block text-sm font-medium text-gray-700">Author *</label>
                    <select name="author[id]"
                            id="author_id" 
                            required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Select Author</option>
                        @foreach($authors as $author)
                            <option value="{{ $author['id'] }}" {{ old('author_id') == $author['id'] ? 'selected' : '' }}>
                                {{ $author['first_name'] }} {{ $author['last_name'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Release Date -->
                <div>
                    <label for="release_date" class="block text-sm font-medium text-gray-700">Release Date</label>
                    <input type="date" 
                           name="release_date" 
                           id="release_date" 
                           value="{{ old('release_date') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <!-- Format -->
                <div>
                    <label for="format" class="block text-sm font-medium text-gray-700">Format *</label>
                    <select name="format" 
                            id="format" 
                            required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Select Format</option>
                        <option value="hardcover" {{ old('format') == 'hardcover' ? 'selected' : '' }}>Hardcover</option>
                        <option value="paperback" {{ old('format') == 'paperback' ? 'selected' : '' }}>Paperback</option>
                        <option value="ebook" {{ old('format') == 'ebook' ? 'selected' : '' }}>E-Book</option>
                    </select>
                </div>

                <!-- ISBN -->
                <div>
                    <label for="isbn" class="block text-sm font-medium text-gray-700">ISBN</label>
                    <input type="text" 
                           name="isbn" 
                           id="isbn" 
                           value="{{ old('isbn') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <!-- Number of Pages -->
                <div>
                    <label for="number_of_pages" class="block text-sm font-medium text-gray-700">Number of Pages</label>
                    <input type="number" 
                           name="number_of_pages" 
                           id="number_of_pages" 
                           value="{{ old('number_of_pages') }}"
                           min="1"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <!-- Description -->
                <div class="sm:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" 
                              id="description" 
                              rows="3"
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('description') }}</textarea>
                </div>
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('authors') }}" 
                   class="inline-flex justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit"
                        class="inline-flex justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700">
                    Create Book
                </button>
            </div>
        </form>
    </div>
</div>
@endsection