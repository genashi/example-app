<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('messages.my_notes') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Create New Category Form -->
            <div class="mb-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4">
                    <form action="{{ route('notes.store') }}" method="POST" class="flex gap-2">
                        @csrf
                        <input 
                            type="text" 
                            name="category" 
                            placeholder="{{ __('messages.enter_new_category_name') }}"
                            required
                            class="flex-1 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-indigo-500 dark:focus:ring-indigo-400"
                        >
                        <input 
                            type="hidden" 
                            name="title" 
                            value=""
                        >
                        <input 
                            type="hidden" 
                            name="content" 
                            value=""
                        >
                        <button 
                            type="submit"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 dark:bg-indigo-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 transition ease-in-out duration-150"
                        >
                            {{ __('messages.create_category') }}
                        </button>
                    </form>
                </div>
            </div>

            <!-- Notes Columns Container -->
            <div class="flex gap-4 overflow-x-auto pb-4">
                @forelse($categories as $category)
                    @php
                        $categoryNotes = $notesByCategory->get($category, collect());
                        $activeNotes = $categoryNotes->where('is_completed', false)->sortByDesc('created_at');
                        $completedNotes = $categoryNotes->where('is_completed', true)->sortByDesc('created_at');
                    @endphp
                    <div class="min-w-[300px] flex-shrink-0 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 flex flex-col">
                        <!-- Category Header -->
                        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="font-semibold text-lg text-gray-900 dark:text-gray-100 mb-4">
                                {{ $category }}
                            </h3>
                            
                            <!-- Create Note Form with Description -->
                            <form action="{{ route('notes.store') }}" method="POST" class="space-y-3">
                                @csrf
                                <input type="hidden" name="category" value="{{ $category }}">
                                <input 
                                    type="text" 
                                    name="title" 
                                    placeholder="{{ __('messages.note_title') }}"
                                    required
                                    class="w-full text-sm rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-indigo-500 dark:focus:ring-indigo-400"
                                >
                                <textarea 
                                    name="content" 
                                    rows="3"
                                    placeholder="{{ __('messages.description_optional') }}"
                                    class="w-full text-sm rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-indigo-500 dark:focus:ring-indigo-400 resize-none"
                                ></textarea>
                                <button 
                                    type="submit"
                                    class="w-full px-3 py-2 bg-indigo-600 dark:bg-indigo-500 text-white text-sm font-medium rounded-md hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 transition"
                                >
                                    {{ __('messages.create_note') }}
                                </button>
                            </form>
                        </div>

                        <!-- Notes List -->
                        <div class="flex-1 p-4 space-y-3 overflow-y-auto max-h-[calc(100vh-400px)]">
                            <!-- Active Notes -->
                            @foreach($activeNotes as $note)
                                <div x-data="{ editing: false }" class="bg-white dark:bg-gray-700 rounded-lg p-3 border border-gray-200 dark:border-gray-600">
                                    <!-- Edit Form (Hidden by default) -->
                                    <div x-show="editing" x-cloak class="mb-3">
                                        <form action="{{ route('notes.update', $note) }}" method="POST" class="space-y-2">
                                            @csrf
                                            @method('PATCH')
                                            <input 
                                                type="text" 
                                                name="title" 
                                                value="{{ $note->title }}"
                                                required
                                                class="w-full text-sm rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-600 dark:text-gray-300 shadow-sm focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-indigo-500 dark:focus:ring-indigo-400"
                                            >
                                            <textarea 
                                                name="content" 
                                                rows="2"
                                                class="w-full text-sm rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-600 dark:text-gray-300 shadow-sm focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-indigo-500 dark:focus:ring-indigo-400 resize-none"
                                            >{{ $note->content }}</textarea>
                                            <div class="flex gap-2">
                                                <button 
                                                    type="submit"
                                                    class="flex-1 px-3 py-1.5 text-xs font-medium text-white bg-indigo-600 dark:bg-indigo-500 hover:bg-indigo-700 dark:hover:bg-indigo-600 rounded-md transition"
                                                >
                                                    {{ __('messages.save') }}
                                                </button>
                                                <button 
                                                    type="button"
                                                    @click="editing = false"
                                                    class="flex-1 px-3 py-1.5 text-xs font-medium text-gray-700 dark:text-gray-300 bg-gray-200 dark:bg-gray-600 hover:bg-gray-300 dark:hover:bg-gray-500 rounded-md transition"
                                                >
                                                    {{ __('messages.cancel') }}
                                                </button>
                                            </div>
                                        </form>
                                    </div>

                                    <!-- Note Content (Visible by default) -->
                                    <div x-show="!editing">
                                        <div class="mb-3">
                                            <h4 class="font-medium text-sm text-gray-900 dark:text-gray-100">
                                                {{ $note->title }}
                                            </h4>
                                            @if($note->content)
                                                <p class="text-xs text-gray-600 dark:text-gray-300 mt-1">
                                                    {{ $note->content }}
                                                </p>
                                            @endif
                                        </div>
                                        
                                        <!-- Done Button (Full Width) -->
                                        <form action="{{ route('notes.update', $note) }}" method="POST" class="mb-3">
                                            @csrf
                                            @method('PATCH')
                                            <input 
                                                type="hidden" 
                                                name="is_completed" 
                                                value="1"
                                            >
                                            <button 
                                                type="submit"
                                                class="w-full px-3 py-2 text-sm font-medium text-green-700 dark:text-green-400 bg-green-50 dark:bg-green-900/30 hover:bg-green-100 dark:hover:bg-green-900/50 rounded-md transition"
                                            >
                                                {{ __('messages.done') }}
                                            </button>
                                        </form>
                                        
                                        <!-- Edit/Delete Buttons Row (50/50 split) -->
                                        <div class="flex">
                                            <button 
                                                @click="editing = true"
                                                class="flex-1 px-3 py-1.5 text-xs font-medium text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30 hover:bg-blue-100 dark:hover:bg-blue-900/50 rounded-l-md transition"
                                            >
                                                {{ __('messages.edit') }}
                                            </button>
                                            <div class="border-r border-gray-200 dark:border-gray-600"></div>
                                            <form action="{{ route('notes.destroy', $note) }}" method="POST" class="flex-1">
                                                @csrf
                                                @method('DELETE')
                                                <button 
                                                    type="submit"
                                                    onclick="return confirm('{{ __('messages.are_you_sure_delete') }}')"
                                                    class="w-full px-3 py-1.5 text-xs font-medium text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/30 hover:bg-red-100 dark:hover:bg-red-900/50 rounded-r-md transition"
                                                >
                                                    {{ __('messages.delete') }}
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            <!-- Completed Notes -->
                            @foreach($completedNotes as $note)
                                @if($note->is_completed)
                                    <div x-data="{ editing: false }" class="bg-gray-100 dark:bg-gray-600 rounded-lg p-3 border border-gray-200 dark:border-gray-500 opacity-50">
                                        <!-- Edit Form (Hidden by default) -->
                                        <div x-show="editing" x-cloak class="mb-3">
                                            <form action="{{ route('notes.update', $note) }}" method="POST" class="space-y-2">
                                                @csrf
                                                @method('PATCH')
                                                <input 
                                                    type="text" 
                                                    name="title" 
                                                    value="{{ $note->title }}"
                                                    required
                                                    class="w-full text-sm rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-indigo-500 dark:focus:ring-indigo-400"
                                                >
                                                <textarea 
                                                    name="content" 
                                                    rows="2"
                                                    class="w-full text-sm rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-indigo-500 dark:focus:ring-indigo-400 resize-none"
                                                >{{ $note->content }}</textarea>
                                                <div class="flex gap-2">
                                                    <button 
                                                        type="submit"
                                                        class="flex-1 px-3 py-1.5 text-xs font-medium text-white bg-indigo-600 dark:bg-indigo-500 hover:bg-indigo-700 dark:hover:bg-indigo-600 rounded-md transition"
                                                    >
                                                        {{ __('messages.save') }}
                                                    </button>
                                                    <button 
                                                        type="button"
                                                        @click="editing = false"
                                                        class="flex-1 px-3 py-1.5 text-xs font-medium text-gray-700 dark:text-gray-300 bg-gray-200 dark:bg-gray-600 hover:bg-gray-300 dark:hover:bg-gray-500 rounded-md transition"
                                                    >
                                                        {{ __('messages.cancel') }}
                                                    </button>
                                                </div>
                                            </form>
                                        </div>

                                        <!-- Note Content (Visible by default) -->
                                        <div x-show="!editing">
                                            <div class="mb-3">
                                                <h4 class="font-medium text-sm text-gray-500 dark:text-gray-400 line-through">
                                                    {{ $note->title }}
                                                </h4>
                                                @if($note->content)
                                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 line-through">
                                                        {{ $note->content }}
                                                    </p>
                                                @endif
                                            </div>
                                            
                                            <!-- Restore Button (Full Width) -->
                                            <form action="{{ route('notes.update', $note) }}" method="POST" class="mb-3">
                                                @csrf
                                                @method('PATCH')
                                                <input 
                                                    type="hidden" 
                                                    name="is_completed" 
                                                    value="0"
                                                >
                                                <button 
                                                    type="submit"
                                                    class="w-full px-3 py-2 text-sm font-medium text-yellow-700 dark:text-yellow-400 bg-yellow-50 dark:bg-yellow-900/30 hover:bg-yellow-100 dark:hover:bg-yellow-900/50 rounded-md transition"
                                                >
                                                    {{ __('messages.restore') }}
                                                </button>
                                            </form>
                                            
                                            <!-- Edit/Delete Buttons Row (50/50 split) -->
                                            <div class="flex">
                                                <button 
                                                    @click="editing = true"
                                                    class="flex-1 px-3 py-1.5 text-xs font-medium text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30 hover:bg-blue-100 dark:hover:bg-blue-900/50 rounded-l-md transition"
                                                >
                                                    {{ __('messages.edit') }}
                                                </button>
                                                <div class="border-r border-gray-200 dark:border-gray-600"></div>
                                                <form action="{{ route('notes.destroy', $note) }}" method="POST" class="flex-1">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button 
                                                        type="submit"
                                                        onclick="return confirm('{{ __('messages.are_you_sure_delete') }}')"
                                                        class="w-full px-3 py-1.5 text-xs font-medium text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/30 hover:bg-red-100 dark:hover:bg-red-900/50 rounded-r-md transition"
                                                    >
                                                        {{ __('messages.delete') }}
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach

                            @if($activeNotes->isEmpty() && $completedNotes->isEmpty())
                                <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-4">
                                    {{ __('messages.no_notes_in_category') }}
                                </p>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="w-full bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                            <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="text-lg font-medium">{{ __('messages.no_categories_yet') }}</p>
                            <p class="mt-1">{{ __('messages.create_first_category') }}</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</x-app-layout>
