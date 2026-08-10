<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Project Manager') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            <div class="max-w-3xl mx-auto px-6 pt-20 pb-16 text-center">
                <x-application-logo class="w-16 h-16 mx-auto fill-current text-indigo-500" />

                <h1 class="mt-6 text-4xl font-bold text-gray-900 dark:text-gray-100">
                    {{ config('app.name', 'Project Manager') }}
                </h1>
                <p class="mt-4 text-lg text-gray-600 dark:text-gray-400">
                    Prosta tablica Kanban do zarządzania projektami i zadaniami zespołu —
                    twórz projekty, przeciągaj zadania między kolumnami To&nbsp;Do / In&nbsp;Progress / Done
                    i komentuj postępy w jednym miejscu.
                </p>

                <div class="mt-8 flex items-center justify-center gap-4">
                    @auth
                        <a href="{{ route('projects.index') }}"
                           class="inline-block px-6 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-md hover:bg-indigo-500 transition">
                            Przejdź do projektów
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                           class="inline-block px-6 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-md hover:bg-indigo-500 transition">
                            Zaloguj się
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                               class="inline-block px-6 py-2.5 border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-300 text-sm font-semibold rounded-md hover:border-gray-400 dark:hover:border-gray-500 transition">
                                Zarejestruj się
                            </a>
                        @endif
                    @endauth
                </div>
            </div>

            <div class="max-w-5xl mx-auto px-6 pb-20 grid gap-6 sm:grid-cols-3">
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
                    <h2 class="font-semibold text-gray-900 dark:text-gray-100">Projekty</h2>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                        Twórz i organizuj projekty, przypisuj do nich zadania i śledź ich status.
                    </p>
                </div>
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
                    <h2 class="font-semibold text-gray-900 dark:text-gray-100">Tablica Kanban</h2>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                        Przeciągaj zadania między kolumnami To Do, In Progress i Done.
                    </p>
                </div>
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
                    <h2 class="font-semibold text-gray-900 dark:text-gray-100">Komentarze</h2>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                        Dyskutuj postępy zadań bezpośrednio przy szczegółach zadania.
                    </p>
                </div>
            </div>
        </div>
    </body>
</html>
