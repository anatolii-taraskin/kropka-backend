import './bootstrap';

import Alpine from 'alpinejs';
import Sortable from 'sortablejs';

window.Alpine = Alpine;

Alpine.start();

const MAX_VISIBLE_TOASTS = 3;

const getToastContainer = (body) => {
    let container = document.querySelector('.toast-container');

    if (! container) {
        container = document.createElement('div');
        container.className = 'toast-container';
        body.appendChild(container);
    }

    return container;
};

const removeToast = (toast, { force = false } = {}) => {
    if (! toast) {
        return;
    }

    if (force) {
        toast.remove();
        return;
    }

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
};

const TOAST_TYPES = new Set(['success', 'info', 'danger']);

const normalizeToastType = (type) => {
    if (typeof type !== 'string') {
        return 'success';
    }

    const normalized = type.toLowerCase();

    return TOAST_TYPES.has(normalized) ? normalized : 'success';
};

const showToast = (message, type = 'success') => {
    const body = document.body;

    if (! body) {
        return;
    }

    const container = getToastContainer(body);

    if (container.children.length >= MAX_VISIBLE_TOASTS) {
        removeToast(container.firstElementChild, { force: true });
    }

    const toast = document.createElement('div');
    const toastType = normalizeToastType(type);
    toast.className = `toast-notification toast-notification--${toastType}`;
    toast.textContent = message;

    container.appendChild(toast);

    requestAnimationFrame(() => {
        toast.classList.add('toast-notification--visible');
    });

    window.setTimeout(() => {
        removeToast(toast);
    }, 5000);
};

window.addEventListener('app:toast', (event) => {
    const detail = event.detail;
    const isStringDetail = typeof detail === 'string';
    const message = isStringDetail ? detail : detail?.message;
    const type = isStringDetail ? 'success' : detail?.type;

    if (message) {
        showToast(message, type);
    }
});

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

const initFlashToasts = () => {
    document.querySelectorAll('[data-toast-message]').forEach((element) => {
        const { toastMessage, toastType } = element.dataset;

        if (toastMessage) {
            showToast(toastMessage, toastType);
        }

        element.remove();
    });
};

document.addEventListener('DOMContentLoaded', () => {
    initSortableLists();
    initFlashToasts();
});
