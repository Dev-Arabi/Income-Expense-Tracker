document.addEventListener("DOMContentLoaded", function () {
    displayCurrentDate();

    function displayCurrentDate() {
        const dateElement = document.getElementById('current-date');
        const optionsDay = { weekday: 'long' };
        const optionsDate = { year: 'numeric', month: 'long', day: 'numeric' };
        const today = new Date();
        
        const day = today.toLocaleDateString('en-US', optionsDay); // Get the day
        const date = today.toLocaleDateString('en-US', optionsDate); // Get the formatted date
        
        dateElement.innerHTML = `${day}<br>${date}`; // Set the content with a line break
    }
});