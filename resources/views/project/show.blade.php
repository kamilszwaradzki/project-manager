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

                                        <div class="kanban-column space-y-4 min-h-[200px]"  data-status="todo" id="column-todo">
                                            @forelse ($project->tasks->where('status', 'todo') as $task)
                                                <div class="task-card bg-white dark:bg-gray-900 rounded-lg shadow p-4 border-l-4 
                                                    {{ $task->priority->color() }} hover:shadow-lg transition-shadow" 
                                                    x-data=""
                                                    @click="$dispatch('open-task-modal', { taskId: '{{ $task->id }}' })"
                                                    data-task-id="{{ $task->id }}" draggable="true">
                                                    <div class="task-handle cursor-move text-gray-400 mb-2">☰</div>
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

                                        <div class="kanban-column space-y-4 min-h-[200px]" data-status="in-progress" id="column-in-progress">
                                            @forelse ($project->tasks->where('status', 'in-progress') as $task)
                                                <div class="task-card bg-white dark:bg-gray-900 rounded-lg shadow p-4 border-l-4 
                                                    {{ $task->priority->color() }} hover:shadow-lg transition-shadow" 
                                                    x-data=""
                                                    @click="$dispatch('open-task-modal', { taskId: '{{ $task->id }}' })"
                                                    data-task-id="{{ $task->id }}" draggable="true">
                                                    <div class="task-handle cursor-move text-gray-400 mb-2">☰</div>
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

                                        <div class="kanban-column space-y-4 min-h-[200px]" data-status="review" id="column-review">
                                            @forelse ($project->tasks->where('status', 'review') as $task)
                                                <div class="task-card bg-white dark:bg-gray-900 rounded-lg shadow p-4 border-l-4 
                                                    {{ $task->priority->color() }} hover:shadow-lg transition-shadow" 
                                                    x-data=""
                                                    @click="$dispatch('open-task-modal', { taskId: '{{ $task->id }}' })"
                                                    data-task-id="{{ $task->id }}" draggable="true">
                                                    <div class="task-handle cursor-move text-gray-400 mb-2">☰</div>
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

                                        <div class="kanban-column space-y-4 min-h-[200px]" data-status="done" id="column-done">
                                            @forelse ($project->tasks->where('status', 'done') as $task)
                                                <div class="task-card bg-white dark:bg-gray-900 rounded-lg shadow p-4 border-l-4 
                                                    {{ $task->priority->color() }} hover:shadow-lg transition-shadow" 
                                                    x-data=""
                                                    @click="$dispatch('open-task-modal', { taskId: '{{ $task->id }}' })"
                                                    data-task-id="{{ $task->id }}" draggable="true">
                                                    <div class="task-handle cursor-move text-gray-400 mb-2">☰</div>
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

                    <!-- Modal -->
                    <div 
                        x-data="taskModal"
                        x-show="open"
                        x-transition
                        @open-task-modal.window="open = true; loading = true; fetchTask($event.detail.taskId)"
                        class="fixed inset-0 z-50 overflow-y-auto"
                        aria-labelledby="modal-title"
                        role="dialog"
                        aria-modal="true">

                        <!-- tło przyciemnione -->
                        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" @click="open = false"></div>

                        <!-- okno modalne -->
                        <div class="relative min-h-screen flex items-center justify-center p-4">
                            <div 
                                @click.away="open = false"
                                class="relative bg-white dark:bg-gray-800 rounded-xl shadow-2xl max-w-3xl w-full max-h-[90vh] overflow-y-auto">

                                <!-- nagłówek -->
                                <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center sticky top-0 bg-white dark:bg-gray-800 z-10">
                                    <h2 class="text-xl font-bold text-gray-900 dark:text-white" x-text="task ? task.title : 'Ładowanie...'"></h2>
                                    <button @click="open = false" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>

                                <!-- treść -->
                                <div class="p-6" x-show="!loading" x-transition>
                                    <template x-if="task">
                                        <div>
                                            <!-- opis zadania -->
                                            <p class="text-gray-700 dark:text-gray-300 mb-6" x-text="task.description || 'Brak opisu'"></p>

                                            <!-- badge z priorytetem, statusem, terminem -->
                                            <div class="flex flex-wrap gap-3 mb-6">
                                                <span x-bind:class="task.priorityClass" class="px-3 py-1 rounded-full text-sm font-medium" x-text="task.priorityLabel"></span>
                                                <span x-bind:class="task.statusClass" class="px-3 py-1 rounded-full text-sm font-medium" x-text="task.statusLabel"></span>
                                                <span x-show="task.due_date" :class="task.dueDateClass" class="px-3 py-1 rounded-full text-sm font-medium" x-text="'Termin: ' + task.due_date"></span>
                                            </div>

                                            <!-- sekcja komentarzy (wklej partial _list.blade.php lub uproszczoną wersję) -->
                                            <div class="mt-8">
                                                <h3 class="text-lg font-semibold mb-4">Komentarze</h3>

                                                <template x-if="task.comments && task.comments.length > 0">
                                                    <div class="space-y-4">
                                                        <template x-for="comment in task.comments" :key="comment.id">
                                                            <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg">
                                                                <div class="flex justify-between">
                                                                    <div class="flex items-center space-x-3">
                                                                        <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-medium">
                                                                            <span x-text="comment.user?.name?.charAt(0) || '?'"></span>
                                                                        </div>
                                                                        <div>
                                                                            <p class="font-medium" x-text="comment.user?.name || 'Użytkownik'"></p>
                                                                            <p class="text-xs text-gray-500" x-text="comment.created_at"></p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <p class="mt-2 text-gray-700 dark:text-gray-300" x-text="comment.body"></p>
                                                            </div>
                                                        </template>
                                                    </div>
                                                </template>

                                                <template x-if="!task.comments || task.comments.length === 0">
                                                    <p class="text-gray-500 text-sm">Brak komentarzy</p>
                                                </template>

                                                <!-- formularz dodawania komentarza -->
                                                <form @submit.prevent="addComment" class="mt-6">
                                                    <textarea 
                                                        x-model="newComment"
                                                        rows="3"
                                                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                                        placeholder="Napisz komentarz..."></textarea>
                                                    <div class="mt-3 flex justify-end">
                                                        <button 
                                                            type="submit"
                                                            class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 disabled:opacity-50"
                                                            :disabled="!newComment.trim()">
                                                            Dodaj
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </template>
                                </div>

                                <!-- loader -->
                                <div class="p-12 text-center" x-show="loading">
                                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600 mx-auto"></div>
                                    <p class="mt-4 text-gray-600 dark:text-gray-400">Ładowanie szczegółów zadania...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <script>
                    document.addEventListener('alpine:init', () => {
    Alpine.data('taskModal', () => ({
        open: false,
        task: null,
        loading: false,
        newComment: '',

        async fetchTask(taskId) {
            this.loading = true;
            try {
                const response = await fetch(`/tasks/${taskId}/details`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) throw new Error('Błąd ładowania');

                const data = await response.json();
                this.task = {
                    ...data,
                    priorityClass: this.getPriorityClass(data.priority),
                    priorityLabel: this.getPriorityLabel(data.priority),
                    statusClass: this.getStatusClass(data.status),
                    statusLabel: this.getStatusLabel(data.status),
                    dueDateClass: data.due_date && new Date(data.due_date) < new Date() 
                        ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' 
                        : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'
                };
            } catch (error) {
                console.error(error);
                alert('Nie udało się załadować szczegółów zadania');
            } finally {
                this.loading = false;
            }
        },

        async addComment() {
            if (!this.newComment.trim()) return;

            try {
                const response = await fetch(`/tasks/${this.task.id}/comments`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ body: this.newComment })
                });

                if (!response.ok) throw new Error('Błąd dodawania komentarza');

                const newComment = await response.json();
                this.task.comments.unshift(newComment); // dodaj na górę listy
                this.newComment = '';
            } catch (error) {
                console.error(error);
                alert('Nie udało się dodać komentarza');
            }
        },

        // Pomocnicze metody do kolorów i etykiet (dostosuj do swoich enumów)
        getPriorityClass(p) {
            const map = {
                'low': 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                'medium': 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                'high': 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200',
                'urgent': 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'
            };
            return map[p] || 'bg-gray-100 text-gray-800';
        },

        getPriorityLabel(p) {
            const map = { 'low': 'Niski', 'medium': 'Średni', 'high': 'Wysoki', 'urgent': 'Pilny' };
            return map[p] || p;
        },

        getStatusClass(s) {
            const map = {
                'todo': 'bg-gray-100 text-gray-800',
                'in_progress': 'bg-yellow-100 text-yellow-800',
                'done': 'bg-green-100 text-green-800'
            };
            return map[s] || 'bg-gray-100 text-gray-800';
        },

        getStatusLabel(s) {
            const map = { 'todo': 'Do zrobienia', 'in_progress': 'W trakcie', 'done': 'Zrobione' };
            return map[s] || s;
        }
    }));
});
                    </script>

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