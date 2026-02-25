<div class="mt-8 border-t pt-6">
    <h3 class="text-lg font-semibold mb-4">Komentarze ({{ $task->comments->count() }})</h3>

    @if($task->comments->isEmpty())
        <p class="text-gray-500 dark:text-gray-400 text-sm">Brak komentarzy jeszcze.</p>
    @else
        <div class="space-y-4">
            @foreach($task->comments as $comment)
                <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg relative">
                    <div class="flex items-start justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center text-indigo-600 dark:text-indigo-300 font-medium">
                                {{ $comment->user->name[0] ?? '?' }}
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">
                                    {{ $comment->user->name ?? 'Anonim' }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $comment->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>

                        @if($comment->user_id === auth()->id() || $comment->task->project->user_id === auth()->id())
                            <form action="{{ route('comments.destroy', $comment) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm"
                                        onclick="return confirm('Usunąć ten komentarz?')">
                                    Usuń
                                </button>
                            </form>
                        @endif
                    </div>

                    <p class="mt-3 text-gray-700 dark:text-gray-300 whitespace-pre-wrap">
                        {{ $comment->body }}
                    </p>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Formularz dodawania nowego komentarza -->
    <form method="POST" action="{{ route('comments.store', $task) }}" class="mt-6">
        @csrf

        <textarea name="body" rows="3"
                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm"
                  placeholder="Dodaj komentarz..." required></textarea>

        @error('body')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror

        <div class="mt-3 flex justify-end">
            <button type="submit"
                    class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-sm font-medium">
                Dodaj komentarz
            </button>
        </div>
    </form>
</div>