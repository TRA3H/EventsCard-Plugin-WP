document.addEventListener('DOMContentLoaded', function() {
    const imageLinks = document.querySelectorAll('.cb-event-image a');
    imageLinks.forEach(link => {
        link.addEventListener('click', function(event) {
            event.preventDefault();
        });
    });
});