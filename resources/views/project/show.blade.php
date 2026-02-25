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
                    <div class="text-center py-12 text-gray-500 dark:text-gray-400 border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-lg">
                        <div>
                            <h2 class="text-2xl font-bold mb-6 text-gray-900 dark:text-white">Tablica Kanban</h2>

                            <div class="overflow-x-auto pb-4">
                                <div class="inline-flex gap-6 min-w-full">
                                    <!-- Kolumna: To Do -->
                                    <div class="min-w-[320px] max-w-[380px] bg-gray-50 dark:bg-gray-800 rounded-xl shadow-md p-4 border border-gray-200 dark:border-gray-700">
                                        <div class="flex items-center justify-between mb-4">
                                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Do zrobienia</h3>
                                            <span class="px-3 py-1 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm rounded-full">
                                                {{ $project->tasks->where('status', 'todo')->count() }}
                                            </span>
                                        </div>

                                        <div class="space-y-4 min-h-[200px]">
                                            @forelse ($project->tasks->where('status', 'todo') as $task)
                                                <div class="bg-white dark:bg-gray-900 rounded-lg shadow p-4 border-l-4 
                                                    {{ $task->priority->color() }} hover:shadow-lg transition-shadow">
                                                    <h4 class="font-medium text-gray-900 dark:text-white mb-1">{{ $task->title }}</h4>
                                                    <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2 mb-3">
                                                        {{ $task->description ?? 'Brak opisu' }}
                                                    </p>

                                                    <div class="flex flex-wrap gap-2 text-xs">
                                                        <span class="px-2.5 py-0.5 rounded-full font-medium
                                                            {{ $task->priority->color() }}">
                                                            {{ $task->priority->label() ?? ucfirst($task->priority ?? '—') }}
                                                        </span>

                                                        @if($task->due_date)
                                                            <span class="px-2.5 py-0.5 rounded-full 
                                                                {{ $task->due_date->isPast() ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                                                                Termin: {{ $task->due_date->format('d.m.Y') }}
                                                            </span>
                                                        @endif

                                                        <span class="px-2.5 py-0.5 rounded-full bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200">
                                                            {{ $task->assignedTo?->name ?? 'Nieprzypisane' }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @empty
                                                <div class="text-center text-gray-500 dark:text-gray-400 py-8 text-sm">
                                                    Brak zadań w tej kolumnie
                                                </div>
                                            @endforelse
                                        </div>

                                        <a href="{{ route('projects.tasks.create', [$project, 'status' => 'todo']) }}"
                                        class="mt-4 block text-center py-2 px-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium">
                                            + Dodaj zadanie
                                        </a>
                                    </div>

                                    <!-- Kolumna: In Progress → analogicznie jak wyżej, tylko status 'in-progress' -->
                                    <div class="min-w-[320px] max-w-[380px] bg-gray-50 dark:bg-gray-800 rounded-xl shadow-md p-4 border border-gray-200 dark:border-gray-700">
                                        <!-- ... identyczna struktura ... -->
                                        <div class="flex items-center justify-between mb-4">
                                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">W trakcie</h3>
                                            <span class="px-3 py-1 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm rounded-full">
                                                {{ $project->tasks->where('status', 'in-progress')->count() }}
                                            </span>
                                        </div>

                                        <div class="space-y-4 min-h-[200px]">
                                            @forelse ($project->tasks->where('status', 'in-progress') as $task)
                                                <div class="bg-white dark:bg-gray-900 rounded-lg shadow p-4 border-l-4 
                                                    {{ $task->priority->color() }} hover:shadow-lg transition-shadow">
                                                    <h4 class="font-medium text-gray-900 dark:text-white mb-1">{{ $task->title }}</h4>
                                                    <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2 mb-3">
                                                        {{ $task->description ?? 'Brak opisu' }}
                                                    </p>

                                                    <div class="flex flex-wrap gap-2 text-xs">
                                                        <span class="px-2.5 py-0.5 rounded-full font-medium
                                                            {{ $task->priority->color() }}">
                                                            {{ $task->priority->label() ?? ucfirst($task->priority ?? '—') }}
                                                        </span>

                                                        @if($task->due_date)
                                                            <span class="px-2.5 py-0.5 rounded-full 
                                                                {{ $task->due_date->isPast() ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                                                                Termin: {{ $task->due_date->format('d.m.Y') }}
                                                            </span>
                                                        @endif

                                                        <span class="px-2.5 py-0.5 rounded-full bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200">
                                                            {{ $task->assignedTo?->name ?? 'Nieprzypisane' }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @empty
                                                <div class="text-center text-gray-500 dark:text-gray-400 py-8 text-sm">
                                                    Brak zadań w tej kolumnie
                                                </div>
                                            @endforelse
                                        </div>

                                        <a href="{{ route('projects.tasks.create', [$project, 'status' => 'in-progress']) }}"
                                        class="mt-4 block text-center py-2 px-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium">
                                            + Dodaj zadanie
                                        </a>
                                    </div>

                                    <!-- Kolumna: Review → analogicznie -->
                                    <div class="min-w-[320px] max-w-[380px] bg-gray-50 dark:bg-gray-800 rounded-xl shadow-md p-4 border border-gray-200 dark:border-gray-700">

                                        <div class="flex items-center justify-between mb-4">
                                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Do weryfikacji</h3>
                                            <span class="px-3 py-1 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm rounded-full">
                                                {{ $project->tasks->where('status', 'review')->count() }}
                                            </span>
                                        </div>

                                        <div class="space-y-4 min-h-[200px]">
                                            @forelse ($project->tasks->where('status', 'review') as $task)
                                                <div class="bg-white dark:bg-gray-900 rounded-lg shadow p-4 border-l-4 
                                                    {{ $task->priority->color() }} hover:shadow-lg transition-shadow">
                                                    <h4 class="font-medium text-gray-900 dark:text-white mb-1">{{ $task->title }}</h4>
                                                    <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2 mb-3">
                                                        {{ $task->description ?? 'Brak opisu' }}
                                                    </p>

                                                    <div class="flex flex-wrap gap-2 text-xs">
                                                        <span class="px-2.5 py-0.5 rounded-full font-medium
                                                            {{ $task->priority->color() }}">
                                                            {{ $task->priority->label() ?? ucfirst($task->priority ?? '—') }}
                                                        </span>

                                                        @if($task->due_date)
                                                            <span class="px-2.5 py-0.5 rounded-full 
                                                                {{ $task->due_date->isPast() ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                                                                Termin: {{ $task->due_date->format('d.m.Y') }}
                                                            </span>
                                                        @endif

                                                        <span class="px-2.5 py-0.5 rounded-full bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200">
                                                            {{ $task->assignedTo?->name ?? 'Nieprzypisane' }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @empty
                                                <div class="text-center text-gray-500 dark:text-gray-400 py-8 text-sm">
                                                    Brak zadań w tej kolumnie
                                                </div>
                                            @endforelse
                                        </div>

                                        <a href="{{ route('projects.tasks.create', [$project, 'status' => 'review']) }}"
                                        class="mt-4 block text-center py-2 px-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium">
                                            + Dodaj zadanie
                                        </a>
                                    </div>

                                    <!-- Kolumna: Done → analogicznie -->
                                    <div class="min-w-[320px] max-w-[380px] bg-gray-50 dark:bg-gray-800 rounded-xl shadow-md p-4 border border-gray-200 dark:border-gray-700">

                                        <div class="flex items-center justify-between mb-4">
                                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Zrobione</h3>
                                            <span class="px-3 py-1 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm rounded-full">
                                                {{ $project->tasks->where('status', 'done')->count() }}
                                            </span>
                                        </div>

                                        <div class="space-y-4 min-h-[200px]">
                                            @forelse ($project->tasks->where('status', 'done') as $task)
                                                <div class="bg-white dark:bg-gray-900 rounded-lg shadow p-4 border-l-4 
                                                    {{ $task->priority->color() }} hover:shadow-lg transition-shadow">
                                                    <h4 class="font-medium text-gray-900 dark:text-white mb-1">{{ $task->title }}</h4>
                                                    <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2 mb-3">
                                                        {{ $task->description ?? 'Brak opisu' }}
                                                    </p>

                                                    <div class="flex flex-wrap gap-2 text-xs">
                                                        <span class="px-2.5 py-0.5 rounded-full font-medium
                                                            {{ $task->priority->color() }}">
                                                            {{ $task->priority->label() ?? ucfirst($task->priority ?? '—') }}
                                                        </span>

                                                        @if($task->due_date)
                                                            <span class="px-2.5 py-0.5 rounded-full 
                                                                {{ $task->due_date->isPast() ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                                                                Termin: {{ $task->due_date->format('d.m.Y') }}
                                                            </span>
                                                        @endif

                                                        <span class="px-2.5 py-0.5 rounded-full bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200">
                                                            {{ $task->assignedTo?->name ?? 'Nieprzypisane' }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @empty
                                                <div class="text-center text-gray-500 dark:text-gray-400 py-8 text-sm">
                                                    Brak zadań w tej kolumnie
                                                </div>
                                            @endforelse
                                        </div>

                                        <a href="{{ route('projects.tasks.create', [$project, 'status' => 'done']) }}"
                                        class="mt-4 block text-center py-2 px-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium">
                                            + Dodaj zadanie
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-10 bg-white dark:bg-gray-800 shadow rounded-xl p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-xl font-semibold">Zadania</h2>
                            <a href="{{ route('projects.tasks.create', $project) }}"
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                                + Nowe zadanie
                            </a>
                        </div>

                        @if($project->tasks->isEmpty())
                            <p class="text-gray-500 text-center py-8">Brak zadań w tym projekcie.</p>
                        @else
                            <div class="space-y-4">
                                @foreach($project->tasks as $task)
                                    <div class="border-l-4 pl-4 {{ $task->priority->color() }} flex justify-between items-start">
                                        <div>
                                            <h3 class="font-medium">{{ $task->title }}</h3>
                                            <p class="text-sm text-gray-600">{{ Str::limit($task->description, 80) }}</p>
                                            <div class="text-xs text-gray-500 mt-1">
                                                Priorytet: {{ $task->priority->label() ?? $task->priority }}
                                                • Status: {{ ucfirst($task->status->value) }}
                                                • Termin: {{ $task->due_date ?? '—' }}
                                            </div>
                                        </div>
                                        <div class="flex space-x-3 text-sm">
                                            <a href="{{ route('projects.tasks.edit', [$project, $task]) }}" class="text-amber-600 hover:underline">Edytuj</a>
                                            <form action="{{ route('projects.tasks.destroy', [$project, $task]) }}" method="POST" class="inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:underline"
                                                        onclick="return confirm('Usunąć to zadanie?')">Usuń</button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>