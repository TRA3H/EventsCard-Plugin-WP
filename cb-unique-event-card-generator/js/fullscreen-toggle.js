document.addEventListener('DOMContentLoaded', function() {
    const eventCards = document.querySelectorAll('.cb-event-card');
    eventCards.forEach(card => {
        card.addEventListener('click', function() {
            this.classList.toggle('fullscreen');
        });
    });
});