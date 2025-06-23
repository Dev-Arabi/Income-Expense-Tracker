document.addEventListener("DOMContentLoaded", function () {
    fetchCurrentBalance();

    function fetchCurrentBalance() {
        fetch('../php/get_current_balance.php')
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    console.error("Error fetching balance:", data.error);
                    return;
                }

                // Select elements
                const balanceElement = document.getElementById('current-balance');
                const balanceContainer = balanceElement.parentElement; // Parent <p> to apply color
                const balanceStatusElement = document.getElementById('balance-status');

                let currentBalance = parseFloat(data.currentBalance); // Ensure it's a number
                balanceElement.textContent = currentBalance.toFixed(2); // Format to 2 decimal places

                // Determine balance status and color
                if (currentBalance < 0) {
                    balanceStatusElement.textContent = "You are in Loan";
                    balanceContainer.className = "text-2xl font-bold text-red-500"; // Red color
                    balanceStatusElement.className = "text-xs text-red-500 mt-1"; // Small red text
                } else if (currentBalance === 0.00) {
                    balanceStatusElement.textContent = "Are you broke?";
                    balanceContainer.className = "text-2xl font-bold text-white"; // White color
                    balanceStatusElement.className = "text-xs text-white mt-1"; // Small white text
                } else if (currentBalance < 1000) {
                    balanceStatusElement.textContent = "Balance is Low";
                    balanceContainer.className = "text-2xl font-bold text-yellow-500"; // Yellow color
                    balanceStatusElement.className = "text-xs text-yellow-500 mt-1"; // Small yellow text
                } else {
                    balanceStatusElement.textContent = "Balance is Perfect";
                    balanceContainer.className = "text-2xl font-bold text-green-500"; // Green color
                    balanceStatusElement.className = "text-xs text-green-500 mt-1"; // Small green text
                }
            })
            .catch(error => console.error("Fetch error:", error));
    }

    // Refresh balance every second
    setInterval(fetchCurrentBalance, 1000);
});
