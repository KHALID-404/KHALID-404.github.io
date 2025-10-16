<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Set Content-Type to JSON
header('Content-Type: application/json');

// Get the question from URL parameter 'text'
$user_input = isset($_GET['text']) ? trim($_GET['text']) : '';

if ($user_input === '') {
    echo json_encode([
        "question" => "",
        "answer" => "I am KhalidGpt. I assist KHALID-404 with coding, electronics, IoT, and robotics. I provide clear, step-by-step instructions, practical solutions, and precise advice. I explain confidently, encourage creativity, and can generate summaries, project descriptions, and bios reflecting KHALID-404’s skills.",
        "developer" => "KHALID-404"
    ]);
    exit;
}

// API key (store safely in environment variables ideally)
$api_key = "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";

// AI request data
$data = [
    "model" => "openai/gpt-oss-120b",
    "messages" => [
        ["role" => "system", "content" => "You are KhalidGpt. You assist your owner/developer(KHALID-404) with coding, electronics, IoT, and robotics. I provide clear, step-by-step instructions, practical solutions, and precise advice. You explain confidently, encourage creativity, and can generate summaries, project descriptions, and bios reflecting KHALID-404’s skills.You answer different questions like chatgpt and gemini."],
        ["role" => "user", "content" => $user_input]
    ]
];

// Send request to Groq API
$ch = curl_init("https://api.groq.com/openai/v1/chat/completions");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $api_key",
    "Content-Type: application/json"
]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

$response = curl_exec($ch);
if ($response === false) {
    echo json_encode([
        "error" => "cURL Error: " . curl_error($ch),
        "developer" => "KHALID-404"
    ]);
    exit;
}
curl_close($ch);

// Decode API response
$result = json_decode($response, true);

// Return AI response in JSON with developer field
if (isset($result['choices'][0]['message']['content'])) {
    echo json_encode([
        "question" => $user_input,
        "answer" => $result['choices'][0]['message']['content'],
        "developer" => "KHALID-404"
    ]);
} elseif (isset($result['error'])) {
    echo json_encode([
        "error" => $result['error'],
        "developer" => "KHALID-404"
    ]);
} else {
    echo json_encode([
        "error" => "Unknown error",
        "raw_response" => $response,
        "developer" => "KHALID-404"
    ]);
}
?>
