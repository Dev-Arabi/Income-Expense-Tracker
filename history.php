<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: /php/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction History</title>
    <script src="/js/tailwind.js"></script>

    <!--Start of Tawk.to Script-->
    <script type="text/javascript">
    var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
    (function(){
    var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
    s1.async=true;
    s1.src='https://embed.tawk.to/67b0c5a3b09408190f0fb258/1ik5881og';
    s1.charset='UTF-8';
    s1.setAttribute('crossorigin','*');
    s0.parentNode.insertBefore(s1,s0);
    })();
    </script>
    <!--End of Tawk.to Script-->

</head>
<body class="bg-neutral-950 text-neutral-100">
    <div class="max-w-2xl mx-auto p-5">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-3xl font-bold text-white">Transaction History</h2>
            <a href="index.php" class="ml-4 bg-red-700 text-white px-4 py-2 rounded hover:bg-red-800 transition-colors transform active:scale-95">Back to Dashboard</a>
        </div>

        <!-- Date Range Form -->
        <div id="date-range-form" class="bg-neutral-900 p-6 rounded-lg shadow-xl mt-4">
            <h3 class="text-xl font-bold text-white mb-4">Select Date Range</h3>
            <div class="mb-4">
                <label class="block font-bold text-neutral-300">Start Date:</label>
                <input type="date" id="start-date" class="w-full p-3 border-2 border-neutral-700 rounded bg-neutral-800 text-white placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>
            <div class="mb-4">
                <label class="block font-bold text-neutral-300">End Date:</label>
                <input type="date" id="end-date" class="w-full p-3 border-2 border-neutral-700 rounded bg-neutral-800 text-white placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>
            <div class="flex space-x-4">
                <button id="submit-date-range" class="bg-blue-800 text-white px-6 py-3 rounded hover:bg-blue-600 transition-colors transform active:scale-95 w-full sm:w-auto">Submit</button>
                <button id="generate-pdf" class="bg-blue-700 text-white px-6 py-3 rounded hover:bg-blue-600 transition-colors transform active:scale-95 w-full sm:w-auto mt-4 sm:mt-0">Generate PDF</button>
            </div>
        </div>

        <!-- Transaction History List -->
        <ul id="transaction-history-list" class="bg-neutral-900 p-6 rounded-lg shadow-xl mt-4 space-y-4 hidden">
            <!-- Transaction items will be injected here -->
        </ul>
    </div>

    <script src="/js/date_range.js"></script>
</body>
</html>

