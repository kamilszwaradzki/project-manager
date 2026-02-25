<x-app-layout>
    <x-slot name="title">
        {{ $project->title }}
    </x-slot>

    <x-slot name="header">

        @if (session('status') === 'project-created')
        <p
            x-data="{ show: true }"
            x-show="show"
            x-transition
            x-init="setTimeout(() => show = false, 3000)"
            class="p-4 bg-green-100 text-md text-gray-600 dark:text-gray-400"
        >Projekt został utworzony.</p>
        @endif

        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $project->title }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('projects.edit', $project) }}" 
                   class="inline-flex items-center px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white text-sm font-medium rounded-lg transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edytuj
                </a>
                <form action="{{ route('projects.destroy', $project) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition"
                            onclick="return confirm('Na pewno usunąć cały projekt wraz z zadaniami?')">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Usuń
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Opis projektu jako karta informacyjna -->
            @if($project->description)
            <div class="mb-6 bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6 border-l-4 border-indigo-500">
                <p class="text-gray-700 dark:text-gray-300 text-lg">{{ $project->description }}</p>
            </div>
            @endif

            <!-- Główna sekcja z zadaniami -->
            <div class="bg-white dark:bg-gray-800 shadow-md rounded-xl overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                        Zadania w projekcie
                    </h2>
                    <span class="px-3 py-1 bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200 text-sm font-medium rounded-full">
                        {{ $project->tasks->count() }} zadań
                    </span>
                </div>
                
                <div class="p-6">
                    <!-- Miejsce na tablicę Kanban / listę zadań – do zrobienia później -->
                    <div class="text-center py-12 text-gray-500 dark:text-gray-400 border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-lg">
                        <svg class="w-12 h-12 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <p class="text-lg">Widok zadań w budowie</p>
                        <p class="text-sm mt-2">Tutaj pojawi się tablica Kanban lub lista zadań</p>
                    </div>

                    <!-- Przykładowa lista zadań (do zastąpienia później) -->
                    @if($project->tasks->isNotEmpty())
                        <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($project->tasks as $task)
                                <li class="py-3 flex items-center justify-between">
                                    <div class="flex items-center">
                                        <input type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                        <span class="ml-3 text-gray-900 dark:text-white">{{ $task->title }}</span>
                                    </div>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">{{ $task->status }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-center text-gray-500 dark:text-gray-400 py-8">
                            Brak zadań w tym projekcie. 
                            <a href="#" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 font-medium">
                                Dodaj pierwsze zadanie
                            </a>
                        </p>
                    @endif
                </div>
            </div>

            <!-- Przycisk dodawania zadania (jeśli potrzebny) -->
            <div class="mt-6 flex justify-end">
                <a href="#" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Dodaj nowe zadanie
                </a>
            </div>
        </div>
    </div>
</x-app-layout>