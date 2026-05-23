const logButtons = document.querySelectorAll('[data-log-exercise]');
const logSelect = document.querySelector('[data-log-select="exercise"]');

if (logButtons.length && logSelect) {
    logButtons.forEach((button) => {
        button.addEventListener('click', () => {
            const exerciseId = button.getAttribute('data-exercise-id');
            if (exerciseId) {
                logSelect.value = exerciseId;
                logSelect.dispatchEvent(new Event('change'));
            }

            const logFormSection = document.querySelector('#log-form');
            if (logFormSection) {
                logFormSection.scrollIntoView({ behavior: 'smooth' });
            }
        });
    });
}
