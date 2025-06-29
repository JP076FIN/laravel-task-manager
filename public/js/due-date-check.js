document.addEventListener('DOMContentLoaded', function () {
    const checkbox = document.querySelector('input[name="completed"]');
    const dueDate = document.getElementById('due_date');

    function toggleDueDateMin() {
        if (checkbox.checked) {
            dueDate.removeAttribute('min'); // allow past dates
        } else {
            const today = new Date().toISOString().split('T')[0];
            dueDate.setAttribute('min', today); // only today or future
        }
    }

    if (checkbox && dueDate) {
        toggleDueDateMin();
        checkbox.addEventListener('change', toggleDueDateMin);
    }
});
