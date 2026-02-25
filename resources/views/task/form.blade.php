<x-app-layout>
    <x-slot name="title">
        {{ $task->exists ? 'Edytuj zadanie' : 'Nowe zadanie' }}
    </x-slot>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $task->exists ? 'Edytuj zadanie' : 'Nowe zadanie' }}
            <span class="text-sm text-gray-500 dark:text-gray-400 font-normal ml-2">
                w projekcie: {{ $project->title }}
            </span>
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-md rounded-xl overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                        {{ $task->exists ? 'Edytuj zadanie' : 'Dodaj nowe zadanie' }}
                    </h2>
                </div>

                <form method="POST" action="{{ $task->exists ? route('projects.tasks.update', [$project, $task]) : route('projects.tasks.store', $project) }}" 
                      class="p-6 space-y-6">
                    @csrf
                    @if($task->exists) @method('PUT') @endif

                    <!-- Tytuł zadania -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tytuł zadania *</label>
                        <input type="text" name="title" id="title" value="{{ old('title', $task->title ?? '') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm"
                               required>
                        @error('title') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Opis zadania -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Opis</label>
                        <textarea name="description" id="description" rows="4"
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">{{ old('description', $task->description ?? '') }}</textarea>
                        @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Priorytet -->
                        <div>
                            <label for="priority" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Priorytet</label>
                            <select name="priority" id="priority"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                                <option value="low" {{ old('priority', $task->priority->value ?? '') === 'low' ? 'selected' : '' }}>Niski</option>
                                <option value="medium" {{ old('priority', $task->priority->value ?? '') === 'medium' ? 'selected' : '' }}>Średni</option>
                                <option value="high" {{ old('priority', $task->priority->value ?? '') === 'high' ? 'selected' : '' }}>Wysoki</option>
                                <option value="urgent" {{ old('priority', $task->priority->value ?? '') === 'urgent' ? 'selected' : '' }}>Pilny</option>
                            </select>
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                            <select name="status" id="status"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                                <option value="todo" {{ old('status', $task->status->value ?? '') === 'todo' ? 'selected' : '' }}>Do zrobienia</option>
                                <option value="in_progress" {{ old('status', $task->status->value ?? '') === 'in_progress' ? 'selected' : '' }}>W trakcie</option>
                                <option value="review" {{ old('status', $task->status->value ?? '') === 'review' ? 'selected' : '' }}>Do weryfikacji</option>
                                <option value="done" {{ old('status', $task->status->value ?? '') === 'done' ? 'selected' : '' }}>Zakończone</option>
                            </select>
                        </div>

                        <!-- Termin wykonania -->
                        <div>
                            <label for="due_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Termin wykonania</label>
                            <input type="date" name="due_date" id="due_date" 
                                   value="{{ old('due_date', isset($task->due_date) ? $task->due_date->format('Y-m-d') : '') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                            @error('due_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Przypisanie do użytkownika (opcjonalne) -->
                    @if(isset($users) && $users->isNotEmpty())
                    <div>
                        <label for="assigned_to" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Przypisz do</label>
                        <select name="assigned_to" id="assigned_to"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                            <option value="">Nieprzypisane</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('assigned_to', $task->assigned_to ?? '') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    <div class="flex items-center justify-end pt-5 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('projects.show', $project) }}" 
                           class="mr-4 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200">
                            Anuluj
                        </a>
                        <button type="submit"
                                class="inline-flex justify-center py-2 px-6 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ $task->exists ? 'Zapisz zmiany' : 'Utwórz zadanie' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>