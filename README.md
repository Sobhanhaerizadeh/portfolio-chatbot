# 🤖 Sobi — KI-Chatbot für Portfolio-Websites / Mein persönlicher KI-Chatbot

> Ein persönlicher KI-Assistent der auf deiner Portfolio-Website läuft und Besuchern automatisch Fragen über dich, deine Skills und deine Projekte beantwortet.

<img width="453" height="207" alt="image" src="https://github.com/user-attachments/assets/5ab4dd67-2856-44b4-b264-61a2684a798a" />


![PHP](https://img.shields.io/badge/PHP-8.x-777BB4?style=flat-square&logo=php&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-ES6+-F7DF1E?style=flat-square&logo=javascript&logoColor=black)
![Claude](https://img.shields.io/badge/Anthropic-Claude_Haiku-D97706?style=flat-square&logo=anthropic&logoColor=white)
![CSS3](https://img.shields.io/badge/CSS3-Responsive-1572B6?style=flat-square&logo=css3&logoColor=white)

---

## ✨ Features - Was kann Sobi?

- 🧠 **KI-gestützte Antworten** via Anthropic Claude Haiku
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
├── chat.php        ← Backend: liest .env, sendet Anfrage an Claude API
├── chatbot.js      ← Frontend: Widget-Logik, Suggestions, Animationen
├── chatbot.css     ← Design: Lila Dark Theme, vollständig responsiv
├── index.html    ← Vorschau-Seite zum lokalen Testen
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
  Anthropic Claude API
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

Gehe auf [console.anthropic.com](https://console.anthropic.com) → **API Keys** → Key erstellen & kopieren.

### 3. `.env` Datei erstellen

```env
ANTHROPIC_API_KEY=sk-ant-dein-key-hier
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

<img width="1522" height="752" alt="image" src="https://github.com/user-attachments/assets/693688aa-8a29-4dae-9925-df283b8a66ca" />



Beim Klick öffnet sich das Chat-Fenster:

<img width="456" height="627" alt="image" src="https://github.com/user-attachments/assets/5c16c365-4a3e-480a-bb7e-84dab9c9c1b5" />


<img width="456" height="627" alt="image" src="https://github.com/user-attachments/assets/25c56176-c13b-4086-84c5-1c337879c715" />


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
| Anthropic Claude Haiku | KI-Antworten & Suggestions |

---

## 👤 Autor

**Sobhan Haerizadeh**
Webentwickler & Auszubildender Fachinformatiker für Anwendungsentwicklung

[![GitHub](https://img.shields.io/badge/GitHub-SobhanHaerizadeh-181717?style=flat-square&logo=github)](https://github.com/SobhanHaerizadeh)
[![LinkedIn](https://img.shields.io/badge/LinkedIn-sobhanhaerizadeh-0A66C2?style=flat-square&logo=linkedin)](https://www.linkedin.com/in/sobhanhaerizadeh)
[![Website](https://img.shields.io/badge/Website-sobhanhaerizadeh.de-7c3aed?style=flat-square&logo=globe)](https://sobhanhaerizadeh.de)

---