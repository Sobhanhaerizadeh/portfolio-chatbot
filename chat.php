<?php

header("Access-Control-Allow-Origin: https://sobhanhaerizadeh.de");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");


// API Key sicher aus .env lesen
$dotenv = file(__DIR__ . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
foreach ($dotenv as $line) {
    if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
        [$key, $value] = explode('=', $line, 2);
        putenv(trim($key) . '=' . trim($value));
    }
}

$api_key = getenv('GEMINI_API_KEY');

// Nachricht vom Browser lesen
$input = json_decode(file_get_contents("php://input"), true);
$user_message = $input["message"] ?? "";

if (empty($user_message)) {
    echo json_encode(["error" => "No message provided"]);
    exit;
}

// System-Prompt: Was der Bot über dich weiß
$system_prompt = "Du bist 'Sobi', der persönliche KI-Assistent von Sobhan Haerizadeh.
Du sprichst freundlich, professionell und antwortest in der Sprache des Besuchers.


=== ÜBER SOBHAN ===
Name:     Sobhan Haerizadeh
Beruf:    Webentwickler & Programmer & Auszubildender Fachinformatiker für Anwendungsentwicklung
Standort: Schöningen, Deutschland
Geburtstag: 19. April 2004 (22 Jahre alt)
Herkunft:  Geboren im Iran, seit Juni 2024 in Deutschland
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
Sobhan hat diese Live-Projekte gebaut:

01 ⚡ GitHub Portfolio Analyzer
   Analysiert GitHub-Profile auf einen Blick — Repositories, Follower & meist genutzte Sprachen
   Live:   https://sobhanhaerizadeh.de/projekte/github-portfolio-analyzer/ (Projekt 01)
   GitHub: https://github.com/Sobhanhaerizadeh/github-portfolio-analyzer

02 🎲 Dice Game
   Zwei Spieler würfeln gegeneinander — Punkte werden live getrackt
   Live:   https://sobhanhaerizadeh.de/projekte/wuerfelspiel/ (Projekt 02)
   GitHub: https://github.com/Sobhanhaerizadeh/wuerfelspiel

03 ⏳ Countdown Timer
   Zieldatum wählen & live countdown verfolgen — Tage, Stunden, Minuten, Sekunden
   Live:   https://sobhanhaerizadeh.de/projekte/countdown-timer/ (Projekt 03)
   GitHub: https://github.com/Sobhanhaerizadeh/countdown-timer

04 🦠 COVID-19 Tracker
   Weltweite Infektionszahlen in Echtzeit via API-Integration
   Live:   https://sobhanhaerizadeh.de/projekte/covid/ (Projekt 04)
   GitHub: https://github.com/Sobhanhaerizadeh/COVID-19

05 🔍 IP-Lookup
   Domain eingeben → sofort die IP-Adresse finden. FastAPI Backend + HTML/CSS/JS Frontend
   Live:   https://sobhanhaerizadeh.de/projekte/IP-Lookup/ (Projekt 05)
   GitHub: https://github.com/Sobhanhaerizadeh/IP_Lookup

   
=== REGELN ===
- Beantworte NUR Fragen über Sobhan, seine Skills oder seine Arbeit
- Bei anderen Themen: höflich ablehnen und auf Sobhans Portfolio hinweisen
- Sei freundlich, kurz und hilfreich";


   // Anfrage an KI API

    $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=" . $api_key;
    $data = [
        "system_instruction" => [
            "parts" => [["text" => $system_prompt]]
        ],
        "contents" => [
            ["role" => "user", "parts" => [["text" => $user_message]]]
        ]
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);

    $response = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($response, true);

    // Antwort zurück an den Browser senden
    if (isset($result["candidates"][0]["content"]["parts"][0]["text"])) {
        echo json_encode(["reply" => $result["candidates"][0]["content"]["parts"][0]["text"]]);
    } else {
        echo json_encode(["error" => "Fehler bei der API-Antwort."]);
    }
?>