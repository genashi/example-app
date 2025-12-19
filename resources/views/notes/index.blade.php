<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('My Notes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Create Note Form -->
            <div class="mb-8 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Create New Note</h3>
                    <form action="{{ route('notes.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Title
                            </label>
                            <input 
                                type="text" 
                                name="title" 
                                id="title" 
                                required
                                value="{{ old('title') }}"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-indigo-500 dark:focus:ring-indigo-400"
                                placeholder="Enter note title"
                            >
                            @error('title')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Category
                            </label>
                            <input 
                                type="text" 
                                name="category" 
                                id="category"
                                value="{{ old('category') }}"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-indigo-500 dark:focus:ring-indigo-400"
                                placeholder="Enter category (optional)"
                            >
                            @error('category')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Content
                            </label>
                            <textarea 
                                name="content" 
                                id="content" 
                                rows="4"
                                required
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-indigo-500 dark:focus:ring-indigo-400"
                                placeholder="Enter note content"
                            >{{ old('content') }}</textarea>
                            @error('content')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <button 
                                type="submit"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 dark:bg-indigo-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:bg-indigo-700 dark:focus:bg-indigo-600 active:bg-indigo-900 dark:active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
                            >
                                Create Note
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Notes Grid -->
            @if($notes->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($notes as $note)
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md dark:hover:shadow-gray-700 transition-shadow duration-200">
                            <div class="p-6">
                                <div class="flex justify-between items-start mb-3">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-1">
                                            {{ $note->title }}
                                        </h3>
                                        @if($note->category)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200">
                                                {{ $note->category }}
                                            </span>
                                        @endif
                                    </div>
                                    <form action="{{ route('notes.destroy', $note) }}" method="POST" class="ml-2">
                                        @csrf
                                        @method('DELETE')
                                        <button 
                                            type="submit"
                                            onclick="return confirm('Are you sure you want to delete this note?')"
                                            class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 focus:outline-none transition-colors duration-150"
                                            title="Delete note"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                                
                                <p class="text-gray-600 dark:text-gray-300 text-sm mb-4 line-clamp-3">
                                    {{ $note->content }}
                                </p>
                                
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $note->created_at->format('M d, Y') }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                        <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="text-lg font-medium">No notes yet</p>
                        <p class="mt-1">Create your first note using the form above!</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

