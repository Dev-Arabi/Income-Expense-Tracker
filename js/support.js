// Get modal and buttons
const supportLink = document.getElementById('support-link');
const modal = document.getElementById('support-modal');
const closeModal = document.getElementById('close-modal');

// Show modal when "Support" is clicked
supportLink.addEventListener('click', function() {
    modal.classList.remove('hidden');
});

// Close modal when "Close" is clicked
closeModal.addEventListener('click', function() {
    modal.classList.add('hidden');
});


const form = document.getElementById('support-form');

    form.addEventListener('submit', function(event) {
        event.preventDefault();

        const formData = new FormData(form);

        fetch('../php/send_support_message.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            alert("Something went wrong. Please try again later.");
            modal.classList.add('hidden');  // Close the modal after submission
        })
        .catch(error => {
            alert("Your message has been sent successfully!");
        });
    });