import './bootstrap';

import Alpine from 'alpinejs';
import Sortable from 'sortablejs';

window.Alpine = Alpine;

Alpine.start();

const showToast = (message) => {
    const body = document.body;

    if (! body) {
        return;
    }

    const existing = document.querySelector('.toast-notification');

    if (existing) {
        existing.remove();
    }

    const toast = document.createElement('div');
    toast.className = 'toast-notification';
    toast.textContent = message;

    body.appendChild(toast);

    requestAnimationFrame(() => {
        toast.classList.add('toast-notification--visible');
    });

    window.setTimeout(() => {
        toast.classList.remove('toast-notification--visible');

        const removeAfterTransition = () => {
            toast.removeEventListener('transitionend', removeAfterTransition);
            toast.remove();
        };

        toast.addEventListener('transitionend', removeAfterTransition, { once: true });

        window.setTimeout(() => {
            if (toast.isConnected) {
                toast.remove();
            }
        }, 350);
    }, 5000);
};

const initSortableLists = () => {
    const tokenElement = document.querySelector('meta[name="csrf-token"]');

    if (! tokenElement) {
        return;
    }

    const csrfToken = tokenElement.content;

    document.querySelectorAll('[data-sortable-list]').forEach((list) => {
        if (list.dataset.sortableInitialized) {
            return;
        }

        const endpoint = list.dataset.sortableEndpoint;

        if (! endpoint) {
            return;
        }

        Sortable.create(list, {
            animation: 150,
            handle: '[data-sortable-handle]',
            onEnd: async (event) => {
                if (event.oldIndex === event.newIndex) {
                    return;
                }

                const items = Array.from(list.querySelectorAll('[data-sortable-id]'));
                const ids = items
                    .map((row) => Number.parseInt(row.dataset.sortableId ?? '', 10))
                    .filter((id) => Number.isInteger(id) && id > 0);

                if (ids.length !== items.length) {
                    window.location.reload();
                    return;
                }

                try {
                    const response = await fetch(endpoint, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({ order: ids }),
                    });

                    if (! response.ok) {
                        throw new Error('Failed to update order');
                    }

                    showToast('Порядок успешно сохранён.');
                } catch (error) {
                    console.error(error);
                    window.alert('Не удалось сохранить порядок. Попробуйте ещё раз.');
                    window.location.reload();
                }
            },
        });

        list.dataset.sortableInitialized = '1';
    });
};

document.addEventListener('DOMContentLoaded', initSortableLists);
