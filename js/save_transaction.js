document.getElementById('transaction-form').addEventListener('submit', function(event) {
    event.preventDefault();
    
    let formData = new FormData(this);

    // Get user's local time in HH:MM:SS (24-hour format)
    let now = new Date();
    let userTime = now.toLocaleTimeString('en-GB', { hour12: false }); // Ensure 24-hour format

    console.log("User Time Captured: ", userTime);  // Debugging: check if user time is captured

    // Append user time to the form data
    formData.append("time", userTime);

    fetch('../php/save_transaction.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            let transactionList = document.getElementById('transaction-list');
            let listItem = document.createElement('li');
            listItem.className = `flex items-center justify-between p-2 rounded-lg shadow-md border border-neutral-600 bg-neutral-800/80 text-white`;

            // Format the date and time
            let transactionDateTime = `${data.date} ${userTime}`;
            
            // Create inner content
            listItem.innerHTML = `
    <div class="flex items-center justify-between w-full">
        <div class="flex items-center">
            <img src="js/assets/income.png" 
                alt="Income icon" 
                class="w-10 h-10 mr-4" style="display: ${data.type === 'income' ? 'block' : 'none'}"> 
            <img src="js/assets/expense.png" 
                alt="Expense icon" 
                class="w-10 h-10 mr-4" style="display: ${data.type === 'expense' ? 'block' : 'none'}"> 
            <div>
                <p class="text-sm text-gray-400">${transactionDateTime}</p>
                <p class="text-base font-semibold">${data.type.toUpperCase()}</p>
            </div>
        </div>
        <div class="flex flex-col items-end">
            <p class="text-sm text-gray-400">${data.comment ? `Reason: ${data.comment}` : ''}</p>
            <span class="text-base font-semibold ${data.type === 'income' ? 'text-green-500' : 'text-red-500'}">
                ${data.type === 'income' ? '+' : '-'} BDT ${data.amount}
            </span>
        </div>
    </div>
`;

            transactionList.appendChild(listItem);
        } else {
            alert('Error saving transaction');
        }
    })
    .catch(error => console.error("Fetch error:", error));
});
