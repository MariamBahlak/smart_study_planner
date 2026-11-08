<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inputData = json_decode(file_get_contents('php://input'), true);
    $userMessage = trim($inputData['message'] ?? '');

    if (empty($userMessage)) {
        echo json_encode(["status" => "error", "message" => "Message cannot be empty."]);
        exit();
    }

    $apiKey = "YOUR_ACTUAL_API_KEY_HERE";
    $apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/gemini-3.8-flash:generateContent?key=" . $apiKey;

    $data = [
        "contents" => [
            [
                "parts" => [
                    ["text" => "You are a smart study planner assistant. Answer the student professionally and concisely: " . $userMessage]
                ]
            ]
        ]
    ];

    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);

    $response = curl_exec($ch);
    $err = curl_error($ch);
    curl_close($ch);

    if ($err) {
        echo json_encode(["status" => "error", "message" => "cURL Error: " . $err]);
        exit();
    }

    $result = json_decode($response, true);
    
    if (isset($result['error'])) {
        echo json_encode(["status" => "error", "message" => $result['error']['message']]);
        exit();
    }

    $aiReply = $result['candidates'][0]['content']['parts'][0]['text'] ?? "Sorry, I couldn't process that right now.";

    header('Content-Type: application/json');
    echo json_encode(["status" => "success", "reply" => htmlspecialchars($aiReply, ENT_QUOTES, 'UTF-8')]);
    exit();
}
?>