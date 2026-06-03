# 🤖 Sobi — KI-Chatbot für Portfolio-Websites

> Ein persönlicher KI-Assistent der auf deiner Portfolio-Website läuft und Besuchern automatisch Fragen über dich, deine Skills und deine Projekte beantwortet.

<img width="450" height="203" alt="image" src="https://github.com/user-attachments/assets/e36027e9-7b8d-49dc-9b1d-54f7cd4dac68" />


![PHP](https://img.shields.io/badge/PHP-8.x-777BB4?style=flat-square&logo=php&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-ES6+-F7DF1E?style=flat-square&logo=javascript&logoColor=black)
![Gemini](https://img.shields.io/badge/Google_Gemini-2.0_Flash-4285F4?style=flat-square&logo=google&logoColor=white)
![CSS3](https://img.shields.io/badge/CSS3-Responsive-1572B6?style=flat-square&logo=css3&logoColor=white)

---

## ✨ Features

- 🧠 **KI-gestützte Antworten** via Google Gemini 2.0 Flash
- 💬 **Dynamische Suggestion Buttons** — KI schlägt nach jeder Antwort passende Folgefragen vor
- 🎨 **Lila Dark Theme** — elegant und modern
- 📱 **Vollständig responsiv** — auf kleinen Geräten öffnet sich Sobi fullscreen
- 🔒 **Sicher** — API Key wird niemals im Frontend exponiert
- ⚡ **Tipp-Animation** — zeigt an wenn Sobi tippt
- 🌍 **Mehrsprachig** — antwortet in der Sprache des Besuchers
- 🚀 **Einfache Integration** — nur 2 Zeilen HTML nötig

---

## 📁 Projektstruktur

```
portfolio-chatbot/
├── chat.php        ← Backend: liest .env, sendet Anfrage an Gemini API
├── chatbot.js      ← Frontend: Widget-Logik, Suggestions, Animationen
├── chatbot.css     ← Design: Lila Dark Theme, vollständig responsiv
├── preview.html    ← Vorschau-Seite zum lokalen Testen
├── .env            ← 🔒 API Key (NICHT auf GitHub!)
├── .gitignore      ← schützt .env vor GitHub
└── README.md
```

---

## 🚀 So funktioniert es

```
Besucher tippt Frage
        ↓
  chatbot.js (Frontend)
        ↓
  chat.php (Backend) ← API Key sicher versteckt
        ↓
  Google Gemini API
        ↓
  { "reply": "...", "suggestions": ["...", "..."] }
        ↓
  Antwort + neue Suggestion Buttons erscheinen
```

---

## ⚙️ Installation

### 1. Repository klonen

```bash
git clone https://github.com/Sobhanhaerizadeh/portfolio-chatbot.git
cd portfolio-chatbot
```

### 2. API Key holen

Gehe auf [aistudio.google.com](https://aistudio.google.com) → **Get API Key** → Key kopieren.

### 3. `.env` Datei erstellen

```env
GEMINI_API_KEY=dein-api-key-hier
```

> ⚠️ Die `.env` Datei wird durch `.gitignore` geschützt und nie auf GitHub hochgeladen!

### 4. Dateien auf Server hochladen

```
✅ chat.php
✅ chatbot.js
✅ chatbot.css
✅ .env  ← nur auf dem Server, nie auf GitHub!
```

### 5. In deine Website einbinden

Füge diese 2 Zeilen vor `</body>` in jede HTML-Seite ein:

```html
<link rel="stylesheet" href="/chatbot.css">
<script src="/chatbot.js" defer></script>
</body>
```

---

## 🎨 Vorschau

Der **Sobi-Button** erscheint fest unten rechts auf deiner Website:

<img width="1621" height="755" alt="image" src="https://github.com/user-attachments/assets/aec90b7b-6216-4f18-bf17-7f620dc018f5" />



Beim Klick öffnet sich das Chat-Fenster:

<img width="605" height="734" alt="image" src="https://github.com/user-attachments/assets/ac89b323-f4ba-4ef8-9001-182fa9a97fc4" />


---

## 🔒 Sicherheit

| Was | Wo | Sicher? |
|---|---|---|
| `chat.php` | GitHub & Server | ✅ Kein Key enthalten |
| `chatbot.js` | GitHub & Server | ✅ |
| `chatbot.css` | GitHub & Server | ✅ |
| `.env` | Nur Server | ✅ Durch `.gitignore` geschützt |

---

## 📦 Tech Stack

| Technologie | Verwendung |
|---|---|
| PHP 8.x | Backend, API-Kommunikation |
| JavaScript (ES6+) | Chat-Widget, DOM-Manipulation |
| CSS3 | Design, Animationen, Responsive |
| Google Gemini 2.0 Flash | KI-Antworten & Suggestions |

---

## 👤 Autor

**Sobhan Haerizadeh**
Webentwickler & Auszubildender Fachinformatiker für Anwendungsentwicklung

[![GitHub](https://img.shields.io/badge/GitHub-SobhanHaerizadeh-181717?style=flat-square&logo=github)](https://github.com/SobhanHaerizadeh)
[![LinkedIn](https://img.shields.io/badge/LinkedIn-sobhanhaerizadeh-0A66C2?style=flat-square&logo=linkedin)](https://www.linkedin.com/in/sobhanhaerizadeh)
[![Website](https://img.shields.io/badge/Website-sobhanhaerizadeh.de-7c3aed?style=flat-square&logo=globe)](https://sobhanhaerizadeh.de)

---