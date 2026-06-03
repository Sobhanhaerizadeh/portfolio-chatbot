<?php
session_start();

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

// ===== RATE LIMITING =====
$limit = 10;
$window = 86400;
$now = time();

if (!isset($_SESSION['sobi_count'])) {
    $_SESSION['sobi_count'] = 0;
    $_SESSION['sobi_time'] = $now;
}

if ($now - $_SESSION['sobi_time'] > $window) {
    $_SESSION['sobi_count'] = 0;
    $_SESSION['sobi_time'] = $now;
}

if ($_SESSION['sobi_count'] >= $limit) {
    $remaining = $window - ($now - $_SESSION['sobi_time']);
    $hours = ceil($remaining / 3600);
    echo json_encode([
        "error" => "limit",
        "reply" => "⏳ Du hast deine 10 Anfragen für heute aufgebraucht! 😊 Komm in {$hours} Stunden wieder! 🟣"
    ]);
    exit;
}

$_SESSION['sobi_count']++;
// ===== RATE LIMITING END =====

$dotenv = file(__DIR__ . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
foreach ($dotenv as $line) {
    if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
        [$key, $value] = explode('=', $line, 2);
        putenv(trim($key) . '=' . trim($value));
    }
}
$api_key = getenv('ANTHROPIC_API_KEY');

$input = json_decode(file_get_contents("php://input"), true);
$user_message = $input["message"] ?? "";

if (empty($user_message)) {
    echo json_encode(["error" => "No message provided"]);
    exit;
}

$system_prompt = "Du bist 'Sobi', der persönliche KI-Assistent von Sobhan Haerizadeh.
Du sprichst freundlich, professionell und antwortest in der Sprache des Besuchers.

=== ÜBER SOBHAN ===
Name:     Sobhan Haerizadeh
Beruf:    Webentwickler & Auszubildender Fachinformatiker für Anwendungsentwicklung
Website:  https://sobhanhaerizadeh.de
GitHub:   https://github.com/SobhanHaerizadeh
LinkedIn: https://www.linkedin.com/in/sobhanhaerizadeh

=== ANTWORTFORMAT ===
Antworte IMMER nur mit diesem JSON-Format, nichts anderes:
{
  \"reply\": \"Deine Antwort hier\",
  \"suggestions\": [\"Frage 1?\", \"Frage 2?\", \"Frage 3?\"]
}
Maximal 3 Suggestions, kurz und auf Deutsch.

=== REGELN ===
- Beantworte NUR Fragen über Sobhan, seine Skills, Projekte oder seine Arbeit
- Bei anderen Themen: höflich ablehnen
- Sei freundlich, kurz und hilfreich";

$request_data = [
    "model" => "claude-haiku-4-5-20251001",
    "max_tokens" => 512,
    "system" => $system_prompt,
    "messages" => [["role" => "user", "content" => $user_message]]
];

$ch = curl_init("https://api.anthropic.com/v1/messages");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request_data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "x-api-key: " . $api_key,
    "anthropic-version: 2023-06-01"
]);

$response = curl_exec($ch);
curl_close($ch);

$result = json_decode($response, true);

if (isset($result["content"][0]["text"])) {
    $raw = $result["content"][0]["text"];
    $clean = preg_replace('/```json|```/', '', $raw);
    $parsed = json_decode(trim($clean), true);
    if (isset($parsed["reply"])) {
        echo json_encode([
            "reply" => $parsed["reply"],
            "suggestions" => $parsed["suggestions"] ?? []
        ]);
    } else {
        echo json_encode(["reply" => $raw, "suggestions" => []]);
    }
} else {
    echo json_encode(["error" => "Fehler", "debug" => $result]);
}
?>