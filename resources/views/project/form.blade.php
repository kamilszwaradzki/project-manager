<x-app-layout>
    <x-slot name="title">
        {{ isset($project) ? 'Edytuj projekt' : 'Nowy projekt' }}
    </x-slot>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ isset($project) ? 'Edytuj projekt' : 'Nowy projekt' }}
        </h2>
    </x-slot>

    @if (session('status') === 'project-updated')
    <p
        x-data="{ show: true }"
        x-show="show"
        x-transition
        x-init="setTimeout(() => show = false, 3000)"
        class="p-4 bg-green-100 text-md text-gray-600 dark:text-gray-400"
    >Projekt został zaaktualizowany.</p>
    @endif

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-md rounded-xl overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                        {{ isset($project) ? 'Edytuj projekt' : 'Dodaj nowy projekt' }}
                    </h2>
                </div>

                <form method="POST" action="{{ isset($project) ? route('projects.update', $project) : route('projects.store') }}"
                      class="p-6 space-y-6">
                    @csrf
                    @if (isset($project)) @method('PUT') @endif

                    <!-- Tytuł -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tytuł projektu *</label>
                        <input type="text" name="title" id="title" value="{{ old('title', $project->title ?? '') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm"
                               required>
                        @error('title') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Opis -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Opis</label>
                        <textarea name="description" id="description" rows="5"
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">{{ old('description', $project->description ?? '') }}</textarea>
                        @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                        <select name="status" id="status"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                            <!-- not-started,in-progress,cancelled,completed,on-hold -->
                            <option value="not-started" {{ old('status', $project->status ?? '') === 'not-started' ? 'selected' : '' }}>Nie rozpoczęty</option>
                            <option value="in-progress" {{ old('status', $project->status ?? '') === 'in-progress' ? 'selected' : '' }}>W trakcie</option>
                            <option value="cancelled" {{ old('status', $project->status ?? '') === 'cancelled' ? 'selected' : '' }}>Anulowany</option>
                            <option value="completed" {{ old('status', $project->status ?? '') === 'completed' ? 'selected' : '' }}>Ukończony</option>
                            <option value="on-hold" {{ old('status', $project->status ?? '') === 'on-hold' ? 'selected' : '' }}>Wstrzymany</option>
                        </select>
                    </div>

                    <div class="flex items-center justify-end pt-5 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('projects.index') }}" class="mr-4 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200">
                            Anuluj
                        </a>
                        <button type="submit"
                                class="inline-flex justify-center py-2 px-6 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ isset($project) ? 'Zapisz zmiany' : 'Utwórz projekt' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>