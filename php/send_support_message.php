<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get POST data
    $name = $_POST['name'];
    $subject = $_POST['subject'];
    $email = $_POST['email'];
    $body = $_POST['body'];

    // Discord Webhook URL
    $webhook_url = "https://discord.com/api/webhooks/1340199954278387756/EdXy1ht_325KicYxzowYHDdiceXa_kcRmJ7eLzUPH5cRHM4h2WBv0v2sv1ynQwOfT07u";  // Replace with your actual webhook URL

    // Enhanced formatting using bold, italics, and blockquotes
    $message = [
        "content" => "**[Support Request Details from user $name]**\n\n" .  // Moved to the top
            "```markdown\n" .
            "Support Request from: $name\n" .
            "Username: $name\n" .
            "Subject: $subject\n" .
            "Email: $email\n\n" .
            "Message:\n" .
            "$body\n" .
            "```"
    ];


    // Send message to Discord webhook
    $ch = curl_init($webhook_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($message));
    $response = curl_exec($ch);
    curl_close($ch);

    echo $response;  // Optionally, you can return this response for debugging
}
?>
