document.addEventListener("DOMContentLoaded", function () {
    const filterIcon = document.getElementById('filter-icon');
    let filterState = "both"; // Default state

    filterIcon.addEventListener("click", function () {
        // Cycle through filter states: both → income → expense → both...
        if (filterState === "both") {
            filterState = "income";
            filterIcon.src = "js/assets/filter1.png";
        } else if (filterState === "income") {
            filterState = "expense";
            filterIcon.src = "js/assets/filter2.png";
        } else {
            filterState = "both";
            filterIcon.src = "js/assets/filter.png";
        }

        // Fetch filtered transactions
        fetchTransactions(filterState);
    });

    function fetchTransactions(filter) {
        fetch(`../php/get_transactions.php?filter=${filter}`)
            .then(response => response.json())
            .then(data => {
                console.log("Filtered data:", data); // Debugging
    
                if (!Array.isArray(data.transactions)) {
                    console.error("Error: Expected an array but received:", data);
                    return;
                }
    
                let transactionList = document.getElementById('transaction-list');
                transactionList.innerHTML = ''; // Clear previous list
    
                data.transactions.forEach(transaction => {
                    let listItem = document.createElement('li');
                    listItem.className = `flex items-center justify-between p-2 rounded-lg shadow-md border border-neutral-600 bg-neutral-800/80 text-white`;
    
                    let transactionDateTime = `${transaction.date} ${transaction.time}`;
    
                    listItem.innerHTML = `
                        <div class="flex items-center justify-between w-full">
                            <div class="flex items-center">
                                <img src="js/assets/income.png" 
                                    alt="Income icon" 
                                    class="w-10 h-10 mr-4" style="display: ${transaction.type === 'income' ? 'block' : 'none'}"> 
                                <img src="js/assets/expense.png" 
                                    alt="Expense icon" 
                                    class="w-10 h-10 mr-4" style="display: ${transaction.type === 'expense' ? 'block' : 'none'}"> 
                                <div>
                                    <p class="text-sm text-gray-400">${transactionDateTime}</p>
                                    <p class="text-base font-semibold">${transaction.type.toUpperCase()}</p>
                                </div>
                            </div>
                            <div class="flex flex-col items-end">
                                <p class="text-sm text-gray-400">${transaction.comment ? `Reason: ${transaction.comment}` : ''}</p>
                                <span class="text-base font-semibold ${transaction.type === 'income' ? 'text-green-500' : 'text-red-500'}">
                                    ${transaction.type === 'income' ? '+' : '-'} BDT ${transaction.amount}
                                </span>
                            </div>
                        </div>
                    `;
    
                    transactionList.appendChild(listItem);
                });
    
            })
            .catch(error => console.error("Fetch error:", error));
    }

    // Initial fetch
    fetchTransactions(filterState);

    // Auto-refresh every 5 seconds
    setInterval(() => fetchTransactions(filterState), 1000);
});
