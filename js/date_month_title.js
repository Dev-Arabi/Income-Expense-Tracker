document.addEventListener("DOMContentLoaded", function() {
    // Function to update titles and totals
    function updateTotals() {
        // Fetch daily and monthly total savings/lose from the server
        fetch('/php/get_transactions.php')
            .then(response => response.json())
            .then(data => {
                // Format date for daily totals (e.g., 15 Feb 2025)
                const today = new Date();
                const options = { day: 'numeric', month: 'short', year: 'numeric' };
                const formattedDate = today.toLocaleDateString('en-GB', options);
                document.getElementById('daily-title').textContent = `Daily Totals (${formattedDate})`;

                // Format month for monthly totals (e.g., February)
                const monthOptions = { month: 'long' };
                const formattedMonth = today.toLocaleDateString('en-GB', monthOptions);
                document.getElementById('monthly-title').textContent = `Monthly Totals (${formattedMonth})`;

                // Update Daily Totals box
                const dailyIncome = parseFloat(data.dailyTotal.income) || 0;
                const dailyExpense = parseFloat(data.dailyTotal.expense) || 0;
                const dailySavingsLose = data.dailySavingsLose;

                document.getElementById('daily-income').textContent = dailyIncome.toFixed(2);
                document.getElementById('daily-expense').textContent = dailyExpense.toFixed(2);

                let dailyStatus = dailySavingsLose >= 0 ? 'Savings' : 'Loss';
                document.getElementById('daily-status').textContent = `${dailyStatus}: BDT ${Math.abs(dailySavingsLose).toFixed(2)}`;

                // Update Monthly Totals box
                const monthlyIncome = parseFloat(data.monthlyTotal.income) || 0;
                const monthlyExpense = parseFloat(data.monthlyTotal.expense) || 0;
                const monthlySavingsLose = data.monthlySavingsLose;

                document.getElementById('monthly-income').textContent = monthlyIncome.toFixed(2);
                document.getElementById('monthly-expense').textContent = monthlyExpense.toFixed(2);

                let monthlyStatus = monthlySavingsLose >= 0 ? 'Savings' : 'Loss';
                document.getElementById('monthly-status').textContent = `${monthlyStatus}: BDT ${Math.abs(monthlySavingsLose).toFixed(2)}`;
            })
            .catch(error => console.error('Error:', error));
    }

    // Initial fetch to update totals
    updateTotals();

    // Set an interval to refresh totals every 5 seconds (optional)
    setInterval(updateTotals, 1000);
});
