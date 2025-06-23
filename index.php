<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: /php/login.php");
    exit();
}
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Income & Expense Tracker</title>
    <script src="/js/tailwind.js"></script>
    <!-- Link to external CSS file -->
    <link rel="stylesheet" href="/css/scrollbar.css">

    <!-- Enhance Appearance -->
    <style>
        /* Smooth font rendering */
        body {
            font-smoothing: antialiased;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .grid-background {
            background-image: linear-gradient(to right, rgba(49, 49, 49, 0.1) 1px, transparent 1px),
                              linear-gradient(to bottom, rgba(49, 49, 49, 0.1) 1px, transparent 1px);
            background-size: 100px 100px; /* Adjust the grid size here for less density */
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 0; /* Place it behind other content */
        }
    </style>

    <!-- Start of Tawk.to Script -->
    <script type="text/javascript">
        var Tawk_API = Tawk_API || {}, Tawk_LoadStart = new Date();
        (function () {
            var s1 = document.createElement("script"), s0 = document.getElementsByTagName("script")[0];
            s1.async = true;
            s1.src = 'https://embed.tawk.to/67b0c5a3b09408190f0fb258/1ik5881og';
            s1.charset = 'UTF-8';
            s1.setAttribute('crossorigin', '*');
            s0.parentNode.insertBefore(s1, s0);
        })();
    </script>
    <!-- End of Tawk.to Script -->


</head>
<body class="bg-neutral-950 text-neutral-200 flex flex-col min-h-screen">
    <div class="grid-background"></div> <!-- Grid Background -->

    <!-- HEADER -->
    <header class="bg-neutral-950/90 text-white py-4 px-6 shadow-lg backdrop-blur-md border-b border-neutral-800">
        <div class="max-w-4xl mx-auto flex justify-between items-center">
            <h1 class="text-4xl font-extrabold">Income & Expense Tracker</h1>
            <div class="flex items-center space-x-4">
                <span class="text-lg font-semibold text-neutral-400">Welcome, <?php echo $_SESSION['user']; ?>!</span>
                <a href="/php/logout.php" class="bg-red-600 text-white px-4 py-2 rounded shadow-md hover:bg-red-500 transition-all transform active:scale-95 border border-red-400">Logout</a>
            </div>
        </div>
    </header>

    <!-- MAIN CONTENT -->
    <main class="flex-grow max-w-5xl mx-auto p-5 pt-16">
        <div class="flex flex-col md:flex-row gap-6 justify-center">
            
            <!-- Left: Transaction Form -->
            <form id="transaction-form" class="bg-neutral-900/80 p-6 rounded-lg shadow-2xl w-full max-w-lg min-h-[550px] flex-shrink-0 border border-neutral-700 backdrop-blur-lg">
                <h2 class="text-2xl font-bold text-white text-center mb-6">Income & Expense Tracker</h2>
                <div class="mb-2"> <!-- Changed from mb-4 to mb-2 -->
                    <label class="block font-bold text-neutral-300">Date:</label>
                    <input type="date" id="date" name="date" class="w-full p-2 border border-neutral-600 rounded bg-neutral-800/80 text-white focus:outline-none focus:ring-2 focus:ring-neutral-600 shadow-inner">
                </div>
                <div class="mb-2"> <!-- Changed from mb-4 to mb-2 -->
                    <label class="block font-bold text-neutral-300">Type:</label>
                    <select id="type" name="type" class="w-full p-2 border border-neutral-600 rounded bg-neutral-800/80 text-white focus:outline-none focus:ring-2 focus:ring-neutral-600">
                        <option value="income">Income</option>
                        <option value="expense">Expense</option>
                    </select>
                </div>
                <div class="mb-2">
                    <label class="block font-bold text-neutral-300">Amount (BDT):</label>
                    <input type="number" id="amount" name="amount" step="0.01" class="w-full p-2 border border-neutral-600 rounded bg-neutral-800/80 text-white focus:outline-none focus:ring-2 focus:ring-neutral-600 shadow-inner" placeholder="e.g., 100.23">
                </div>
                <div class="mb-2"> <!-- Changed from mb-4 to mb-2 -->
                    <label class="block font-bold text-neutral-300">Reason (Max 10 chars):</label>
                    <input type="text" id="comment" name="comment" maxlength="10" class="w-full p-2 border border-neutral-600 rounded bg-neutral-800/80 text-white focus:outline-none focus:ring-2 focus:ring-neutral-600 shadow-inner">
                </div>

                <div class="flex space-x-2 flex-wrap mt-4">
                    <button type="submit" class="bg-neutral-950 text-white px-6 py-3 rounded shadow-md hover:bg-neutral-900 transition-all transform active:scale-95 flex-1 min-w-[120px] border border-neutral-600">Add Entry</button>
                    <a href="history.php" class="bg-neutral-950 text-white px-6 py-3 rounded shadow-md hover:bg-neutral-900 transition-all flex-1 min-w-[120px] text-center border border-neutral-600">Check History</a>
                </div>

                <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-6 mt-6">
                    <div class="bg-neutral-800/80 p-4 rounded-lg shadow-inner border border-neutral-600 flex-1 backdrop-blur-md">
                        <h4 class="text-lg font-bold text-white" id="daily-title">Daily Totals</h4>
                        <div class="text-white mt-2">
                            <p>Income: BDT <span id="daily-income">0</span></p>
                            <p>Expense: BDT <span id="daily-expense">0</span></p>
                            <hr class="border-neutral-500 my-2">
                            <p id="daily-status">Savings: BDT 0</p>
                        </div>
                    </div>

                    <div class="bg-neutral-800/80 p-4 rounded-lg shadow-inner border border-neutral-600 flex-1 backdrop-blur-md">
                        <h4 class="text-lg font-bold text-white" id="monthly-title">Monthly Totals</h4>
                        <div class="text-white mt-2">
                            <p>Income: BDT <span id="monthly-income">0</span></p>
                            <p>Expense: BDT <span id="monthly-expense">0</span></p>
                            <hr class="border-neutral-500 my-2">
                            <p id="monthly-status">Savings/Loss: BDT 0</p>
                        </div>
                    </div>
                </div>
            </form>


            <!-- Right: Transactions List -->
            <div class="bg-neutral-900/80 p-6 rounded-lg shadow-2xl w-full max-w-lg min-h-[550px] flex-shrink-0 overflow-y-auto border border-neutral-700 backdrop-blur-md">
                <!-- Title and Filter in one row -->
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-bold text-white">Transactions</h3>

                    <!-- Clickable Filter Icon -->
                    <img id="filter-icon" src="../js/assets/filter.png" alt="Filter Icon" class="w-6 h-6 cursor-pointer">
                </div>

                <ul id="transaction-list" class="space-y-4 overflow-y-auto max-h-[570px]">
                    <!-- List items will be injected here -->
                </ul>
            </div>

            <!-- New Box Divided into Two Separate Boxes -->
            <div class="flex flex-col gap-4 w-full max-w-lg">
                <div class="bg-neutral-900/80 p-6 rounded-lg shadow-2xl flex-shrink-0 border border-neutral-700 backdrop-blur-md w-[275px] h-[130px]">
                    <div class="flex items-center justify-between">
                        <h4 class="text-md font-bold text-white">Total Balance</h4>
                        <img src="/js/assets/wallet.png" alt="Icon" class="w-4 h-4">
                    </div>
                    <div class="text-white mt-2">
                        <p class="text-2xl font-bold text-green-500">BDT <span id="current-balance">0.00</span></p>
                        <p id="balance-status" class="text-xs mt-1"></p> <!-- Status message -->
                    </div>
                </div>
                <div class="bg-neutral-900/80 p-6 rounded-lg shadow-2xl flex-shrink-0 border border-neutral-700 backdrop-blur-md w-[275px] h-[130px]">
                    <div class="flex items-center justify-between">
                        <h4 class="text-md font-bold text-white">Today is</h4>
                        <img src="/js/assets/calendar.png" alt="Calendar Icon" class="w-4 h-4"> <!-- Replace with your icon path -->
                    </div>
                    <div class="text-white mt-2">
                        <p id="current-date" class="text-white mt-1"></p> <!-- Date element -->
                    </div>
                </div>
                <div class="bg-neutral-900/80 p-6 rounded-lg shadow-2xl flex-shrink-0 border border-neutral-700 backdrop-blur-md w-[275px] h-[375px]">
                    <div class="flex justify-between items-center">
                        <h4 class="text-md font-bold text-white">Calculator</h4>
                        <img src="/js/assets/calculator.png" alt="Calculator Icon" class="w-4 h-4 ml-2"> <!-- Adjust path and size as needed -->
                    </div>
                    <div class="text-white mt-2">
                        <input type="text" id="calculator-display" class="w-full bg-neutral-800 p-2 rounded text-right" disabled />
                        <div class="grid grid-cols-4 gap-2 mt-4">
                            <!-- Row 1 -->
                            <button class="bg-neutral-700 p-2 rounded" data-value="7">7</button>
                            <button class="bg-neutral-700 p-2 rounded" data-value="8">8</button>
                            <button class="bg-neutral-700 p-2 rounded" data-value="9">9</button>
                            <button class="bg-neutral-700 p-2 rounded" data-value="/">/</button>

                            <!-- Row 2 -->
                            <button class="bg-neutral-700 p-2 rounded" data-value="4">4</button>
                            <button class="bg-neutral-700 p-2 rounded" data-value="5">5</button>
                            <button class="bg-neutral-700 p-2 rounded" data-value="6">6</button>
                            <button class="bg-neutral-700 p-2 rounded" data-value="*">*</button>

                            <!-- Row 3 -->
                            <button class="bg-neutral-700 p-2 rounded" data-value="1">1</button>
                            <button class="bg-neutral-700 p-2 rounded" data-value="2">2</button>
                            <button class="bg-neutral-700 p-2 rounded" data-value="3">3</button>
                            <button class="bg-neutral-700 p-2 rounded" data-value="-">-</button>

                            <!-- Row 4 -->
                            <button class="bg-red-400 p-2 rounded" data-value="C">C</button>
                            <button class="bg-neutral-700 p-2 rounded" data-value="0">0</button>
                            <button class="bg-neutral-700 p-2 rounded" data-value=".">.</button>
                            <button class="bg-neutral-700 p-2 rounded" data-value="+">+</button>

                            <!-- Row 5 -->
                            <button class="bg-neutral-700 p-2 rounded" data-value="(">(</button>
                            <button class="bg-neutral-700 p-2 rounded" data-value=")">)</button>
                            <button class="bg-neutral-700 p-2 rounded" data-value="←">←</button>
                            <button class="bg-neutral-700 p-2 rounded" data-value="=">=</button>
                        </div>
                    </div>
                </div>

            <!-- Support Modal -->
            <div id="support-modal" class="fixed inset-0 bg-neutral-800/80 bg-opacity-40 flex justify-center items-center hidden">
                <div class="bg-neutral-900 p-6 rounded-lg shadow-xl w-96">
                    <h2 class="text-2xl font-bold text-center text-white mb-4">Support</h2>

                    <form id="support-form" class="space-y-4">
                        <input type="text" id="name" name="name" placeholder="Your Username" required class="w-full p-3 border-2 border-neutral-600 rounded bg-neutral-700 text-white placeholder-neutral-300 focus:outline-none focus:ring-2 focus:ring-neutral-400">
                        <input type="text" id="subject" name="subject" placeholder="Subject" required class="w-full p-3 border-2 border-neutral-600 rounded bg-neutral-700 text-white placeholder-neutral-300 focus:outline-none focus:ring-2 focus:ring-neutral-400">
                        <input type="email" id="email" name="email" placeholder="Email" required class="w-full p-3 border-2 border-neutral-600 rounded bg-neutral-700 text-white placeholder-neutral-300 focus:outline-none focus:ring-2 focus:ring-neutral-400">
                        <textarea id="body" name="body" placeholder="Message" required class="w-full p-3 border-2 border-neutral-600 rounded bg-neutral-700 text-white placeholder-neutral-300 focus:outline-none focus:ring-2 focus:ring-neutral-400"></textarea>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded w-full hover:bg-blue-700 transition-colors">Send Message</button>
                    </form>

                    <button id="close-modal" class="mt-4 bg-red-500 text-white px-4 py-2 rounded w-full hover:bg-red-600 transition-colors">Close</button>
                </div>
            </div>

        </div>
    </main>

    <!-- FOOTER -->
    <footer class="bg-neutral-950/80 text-neutral-400 py-4 mt-10 flex justify-center items-center px-6 border-t border-neutral-800 shadow-inner backdrop-blur-md">
        <span class="flex-grow text-center">&copy; <?php echo date("Y"); ?> Income & Expense Tracker. All rights reserved.</span>
        <a href="javascript:void(0);" id="support-link" class="text-blue-400 hover:text-blue-300 transition-all">Support</a>
    </footer>

    <script src="/js/save_transaction.js"></script>
    <script src="/js/get_transactions.js"></script>
    <script src="/js/date_month_title.js"></script>
    <script src="/js/check_history.js"></script>
    <script src="/js/support.js"></script>
    <script src="/js/get_current_balance.js"></script>
    <script src="/js/current_date.js"></script>
    <script src="/js/calculator.js"></script>

</body>
</html>
