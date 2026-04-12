document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('#contact-form');
    if (!form) return;

    const validators = {
        name: (value) => value.trim().length >= 3 ? '' : 'A név legalább 3 karakter legyen.',
        email: (value) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value.trim()) ? '' : 'Adj meg egy érvényes e-mail címet.',
        subject: (value) => value.trim().length >= 5 ? '' : 'A tárgy legalább 5 karakter legyen.',
        message: (value) => value.trim().length >= 10 ? '' : 'Az üzenet legalább 10 karakter legyen.'
    };

    const showError = (field, message) => {
        const container = field.closest('label');
        if (!container) return;
        const error = container.querySelector('.error-text');
        if (error) error.textContent = message;
        field.classList.toggle('input-error', !!message);
    };

    form.querySelectorAll('[data-rule]').forEach((field) => {
        field.addEventListener('input', () => {
            const rule = field.dataset.rule;
            const message = validators[rule](field.value);
            showError(field, message);
        });
    });

    form.addEventListener('submit', (event) => {
        let hasError = false;
        form.querySelectorAll('[data-rule]').forEach((field) => {
            const rule = field.dataset.rule;
            const message = validators[rule](field.value);
            showError(field, message);
            if (message) hasError = true;
        });

        if (hasError) {
            event.preventDefault();
        }
    });
});
