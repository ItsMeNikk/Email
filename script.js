document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('emailForm');
    const statusBox = document.getElementById('statusBox');

    form.addEventListener('submit', function (event) {
        event.preventDefault();

        // Display "Sending..." status
        statusBox.textContent = 'Sending...';

        // Submit form data asynchronously
        const formData = new FormData(form);
        fetch(form.action, {
            method: form.method,
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            // Display response in status box
            statusBox.textContent = data;
        })
        .catch(error => {
            // Display error message in status box
            statusBox.textContent = 'Error: ' + error.message;
        });
    });
});