// Handle date range form submission
document.getElementById('submit-date-range').addEventListener('click', function() {
    const startDate = document.getElementById('start-date').value;
    const endDate = document.getElementById('end-date').value;

    if (!startDate || !endDate) {
        alert("Please select both start and end dates.");
        return;
    }

    fetch(`../php/get_transaction_history.php?start_date=${startDate}&end_date=${endDate}`)
        .then(response => response.json())
        .then(data => {
            const transactionHistoryList = document.getElementById('transaction-history-list');
            transactionHistoryList.innerHTML = ''; // Clear previous results

            if (data.transactions.length > 0) {
                data.transactions.forEach(transaction => {
                    const listItem = document.createElement('li');
                    listItem.classList.add('bg-neutral-800', 'p-4', 'rounded-lg');
                    listItem.innerHTML = `
                        <p><strong>Date:</strong> ${transaction.date}</p>
                        <p><strong>Time:</strong> ${transaction.time}</p>
                        <p><strong>Type:</strong> ${transaction.type}</p>
                        <p><strong>Amount:</strong> BDT ${transaction.amount}</p>
                        <p><strong>Comment:</strong> ${transaction.comment ? transaction.comment : 'N/A'}</p> <!-- Added Comment -->
                    `;
                    transactionHistoryList.appendChild(listItem);
                });
                transactionHistoryList.classList.remove('hidden');
            } else {
                transactionHistoryList.innerHTML = '<li class="text-white">No transactions found for this date range.</li>';
                transactionHistoryList.classList.remove('hidden');
            }
        })
        .catch(error => {
            console.error("Error fetching transaction history:", error);
        });
});

// Handle PDF generation
document.getElementById('generate-pdf').addEventListener('click', function() {
    const startDate = document.getElementById('start-date').value;
    const endDate = document.getElementById('end-date').value;

    if (!startDate || !endDate) {
        alert("Please select both start and end dates.");
        return;
    }

    window.open(`../php/get_transaction_history.php?start_date=${startDate}&end_date=${endDate}&generate_pdf=true`, '_blank');
});
