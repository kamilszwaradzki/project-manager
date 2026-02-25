<x-app-layout>
    <x-slot name="title">
        Moje projekty
    </x-slot>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Moje projekty
            </h2>
            <a href="{{ route('projects.create') }}"
               class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-md transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nowy projekt
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        @if (session('status') === 'project-deleted')
        <p
            x-data="{ show: true }"
            x-show="show"
            x-transition
            x-init="setTimeout(() => show = false, 3000)"
            class="p-4 bg-green-100 text-md text-gray-600 dark:text-gray-400"
        >Projekt {{ session('p_name') }} został usunięty.</p>
        @endif

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if ($projects->isEmpty())
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 text-center text-gray-500 dark:text-gray-400">
                    Nie masz jeszcze żadnych projektów. Stwórz pierwszy!
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($projects as $project)
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-md rounded-xl hover:shadow-lg transition-shadow duration-300">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2 truncate">
                                    {{ $project->title }}
                                </h3>
                                <p class="text-gray-600 dark:text-gray-300 text-sm mb-4 line-clamp-3">
                                    {{ $project->description ?? 'Brak opisu' }}
                                </p>

                                <div class="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400">
                                    <span>Zadań: {{ count($project->tasks) ?? 0 }}</span>
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $project->status->value === 'in-progress' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                                        {{ ucfirst($project->status->value) }}
                                    </span>
                                </div>
                            </div>

                            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900 border-t border-gray-100 dark:border-gray-700 flex justify-end space-x-3">
                                <a href="{{ route('projects.show', $project) }}"
                                   class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 text-sm font-medium">
                                    Szczegóły
                                </a>
                                <a href="{{ route('projects.edit', $project) }}"
                                   class="text-amber-600 hover:text-amber-900 dark:text-amber-400 dark:hover:text-amber-300 text-sm font-medium">
                                    Edytuj
                                </a>
                                <form action="{{ route('projects.destroy', $project) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 text-sm font-medium"
                                            onclick="return confirm('Na pewno chcesz usunąć projekt?')">
                                        Usuń
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $projects->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>