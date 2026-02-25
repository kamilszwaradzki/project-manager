import './bootstrap';

import Alpine from 'alpinejs';
import Sortable from 'sortablejs';

window.Alpine = Alpine;

Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    const columns = document.querySelectorAll('.kanban-column');

    columns.forEach(column => {
        new Sortable(column, {
            group: 'tasks',              // wszystkie kolumny w jednej grupie → można przenosić między nimi
            animation: 150,              // animacja płynna
            ghostClass: 'sortable-ghost',    // styl podczas drag
            chosenClass: 'sortable-chosen',
            dragClass: 'sortable-dragging',
            handle: '.task-handle',      // opcjonalnie – tylko za uchwyt (jeśli dodasz <div class="task-handle cursor-move">☰</div> na karcie)

            onEnd: function (evt) {
                const taskId = evt.item.dataset.taskId;
                const newStatus = evt.to.dataset.status;

                if (!taskId || !newStatus) return;

                // Zapisz zmianę statusu przez AJAX
                fetch('/tasks/' + taskId + '/status', {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({ status: newStatus })
                })
                .then(response => {
                    if (!response.ok) {
                        alert('Błąd podczas aktualizacji statusu');
                        // Opcjonalnie: cofnij kartę wizualnie (evt.item -> evt.from)
                        evt.from.insertBefore(evt.item, evt.from.children[evt.oldIndex]);
                    } else {
                        // Sukces – możesz dodać toast / odśwież kolumny jeśli chcesz
                        console.log('Status zaktualizowany:', newStatus);
                    }
                })
                .catch(error => {
                    console.error('Błąd:', error);
                    // Cofnij wizualnie
                    evt.from.insertBefore(evt.item, evt.from.children[evt.oldIndex]);
                });
            }
        });
    });
});