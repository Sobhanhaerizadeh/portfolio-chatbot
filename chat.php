<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");


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


// ===== RATE LIMITING =====
$ip = $_SERVER['REMOTE_ADDR'];
$limit = 10;
$window = 86400; // 24 Stunden in Sekunden
$storage_file = __DIR__ . '/rate_limit.json';

// Datei laden oder neu erstellen
$data = [];
if (file_exists($storage_file)) {
    $data = json_decode(file_get_contents($storage_file), true) ?? [];
}

$now = time();

// Alte Einträge aufräumen (älter als 24h)
foreach ($data as $stored_ip => $info) {
    if ($now - $info['time'] > $window) {
        unset($data[$stored_ip]);
    }
}

// Aktuelle IP prüfen
if (isset($data[$ip])) {
    if ($data[$ip]['count'] >= $limit) {
        $remaining = $window - ($now - $data[$ip]['time']);
        $hours = ceil($remaining / 3600);
        echo json_encode([
        "error" => "limit",
        "reply" => "⏳ Du hast deine 10 Anfragen für heute aufgebraucht! 😊\n\nKomm in {$hours} Stunden wieder — ich freue mich auf deine nächsten Fragen! 🟣"
    ]);
        exit;
    }
    $data[$ip]['count']++;
} else {
    $data[$ip] = ['count' => 1, 'time' => $now];
}

// Speichern
file_put_contents($storage_file, json_encode($data));
// ===== RATE LIMITING END =====


$system_prompt = "Du bist 'Sobi', der persönliche KI-Assistent von Sobhan Haerizadeh.
Du sprichst freundlich, professionell und antwortest in der Sprache des Besuchers.

=== ÜBER SOBHAN ===
Name:     Sobhan Haerizadeh
Beruf:    Webentwickler & Programmer & Auszubildender Fachinformatiker für Anwendungsentwicklung
Interessen: Programmierung, moderne Webtechnologien, sauberer Code & gute Softwarelösungen

=== SKILLS ===
Frontend:  HTML & CSS, Tailwind CSS, Bootstrap, JavaScript, React
Backend:   PHP, C#/.NET, Entity Framework Core, REST API
CMS:       WordPress
Datenbank: SQL / MySQL
Tools:     Git & GitHub

=== SPRACHEN ===
Persisch  → Muttersprache
Deutsch   → C1
Englisch  → B2

=== WERDEGANG ===
2019 → Erste Schritte mit HTML & CSS
2022 → JavaScript, PHP & MySQL
Heute → Ausbildung zum Fachinformatiker, C#/.NET & moderne Webtechnologien

=== LINKS ===
GitHub:   https://github.com/SobhanHaerizadeh
LinkedIn: https://www.linkedin.com/in/sobhanhaerizadeh

=== PROJEKTE ===
01 ⚡ GitHub Portfolio Analyzer
   Live:   https://sobhanhaerizadeh.de/projekte/github-portfolio-analyzer/
   GitHub: https://github.com/Sobhanhaerizadeh/github-portfolio-analyzer

02 🎲 Dice Game
   Live:   https://sobhanhaerizadeh.de/projekte/wuerfelspiel/
   GitHub: https://github.com/Sobhanhaerizadeh/wuerfelspiel

03 ⏳ Countdown Timer
   Live:   https://sobhanhaerizadeh.de/projekte/countdown-timer/
   GitHub: https://github.com/Sobhanhaerizadeh/countdown-timer

04 🦠 COVID-19 Tracker
   Live:   https://sobhanhaerizadeh.de/projekte/covid/
   GitHub: https://github.com/Sobhanhaerizadeh/COVID-19

05 🔍 IP-Lookup
   Live:   https://sobhanhaerizadeh.de/projekte/IP-Lookup/
   GitHub: https://github.com/Sobhanhaerizadeh/IP_Lookup

=== ANTWORTFORMAT ===
Antworte IMMER nur mit diesem JSON-Format, nichts anderes:
{
  \"reply\": \"Deine Antwort hier\",
  \"suggestions\": [\"Frage 1?\", \"Frage 2?\", \"Frage 3?\"]
}
Suggestions passend zur Antwort, maximal 3 Stück, kurz und auf Deutsch.

=== REGELN ===
- Beantworte NUR Fragen über Sobhan, seine Skills oder seine Arbeit
- Bei anderen Themen: höflich ablehnen und auf Sobhans Portfolio hinweisen
- Sei freundlich, kurz und hilfreich";

$url = "https://api.anthropic.com/v1/messages";

$request_data  = [
    "model" => "claude-haiku-4-5-20251001",
    "max_tokens" => 1024,
    "system" => $system_prompt,
    "messages" => [
        ["role" => "user", "content" => $user_message]
    ]
];

$ch = curl_init($url);
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
    echo json_encode(["error" => "Fehler bei der API-Antwort.", "debug" => $result]);
}
?>