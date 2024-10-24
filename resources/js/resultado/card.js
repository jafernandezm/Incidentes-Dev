document.addEventListener('DOMContentLoaded', () => {
    const cards = document.querySelectorAll('.card');

    cards.forEach(card => {
        card.addEventListener('click', () => {
            const details = card.querySelector('.details');
            if (details) {
                details.classList.toggle('hidden');
            }
        });
    });
});