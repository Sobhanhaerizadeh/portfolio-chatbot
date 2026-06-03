const CHAT_API_URL = "/chat.php";

function t(key) {
  return window.i18n ? window.i18n.t('chatbot.' + key) : key;
}

function buildInitialSuggestions(container) {
  const suggestions = t('suggestions');
  if (!Array.isArray(suggestions)) return;
  suggestions.forEach(text => {
    const btn = document.createElement('button');
    btn.classList.add('sobi-suggestion');
    btn.textContent = text;
    btn.addEventListener('click', () => {
      document.getElementById('sobi-input').value = text;
      container.remove();
      sendMessage();
    });
    container.appendChild(btn);
  });
}

function createChatWidget() {
  const widget = document.createElement("div");
  widget.innerHTML = `
  <div id="sobi-toggle" data-i18n-title="chatbot.toggle_title" title="${t('toggle_title')}">
    <img src="https://sobhanhaerizadeh.de/assets/images/sobidev.png" alt="Sobidev" />
  </div>

  <div id="sobi-box">
    <div id="sobi-header">
      <img src="https://sobhanhaerizadeh.de/assets/images/sobidev.png" alt="Sobidev Bot" />
      <div>
        <strong>Sobi</strong>
        <span data-i18n="chatbot.subtitle">${t('subtitle')}</span>
      </div>
      <button id="sobi-close">✕</button>
    </div>

    <div id="sobi-messages">
      <div class="sobi-msg sobi-bot" id="sobi-greeting" data-i18n-html="chatbot.greeting">
        ${t('greeting')}
      </div>
      <div id="sobi-suggestions" data-initial="true"></div>
    </div>

    <div id="sobi-input-area">
      <input id="sobi-input" type="text" data-i18n-placeholder="chatbot.placeholder" placeholder="${t('placeholder')}" />
      <button id="sobi-send">➤</button>
    </div>
  </div>
`;
  document.body.appendChild(widget);

  const suggestionsContainer = widget.querySelector('#sobi-suggestions');
  buildInitialSuggestions(suggestionsContainer);
}

function appendMessage(text, sender) {
  const messages = document.getElementById("sobi-messages");
  const div = document.createElement("div");
  div.classList.add("sobi-msg", sender === "user" ? "sobi-user" : "sobi-bot");
  div.innerHTML = text;
  messages.appendChild(div);
  messages.scrollTop = messages.scrollHeight;
}

function showTyping() {
  const messages = document.getElementById("sobi-messages");
  const div = document.createElement("div");
  div.classList.add("sobi-msg", "sobi-bot", "sobi-typing");
  div.id = "sobi-typing";
  div.innerHTML = `<span></span><span></span><span></span>`;
  messages.appendChild(div);
  messages.scrollTop = messages.scrollHeight;
}

function hideTyping() {
  const el = document.getElementById("sobi-typing");
  if (el) el.remove();
}

function showSuggestions(suggestions) {
  const old = document.getElementById("sobi-suggestions");
  if (old) old.remove();

  const messages = document.getElementById("sobi-messages");
  const div = document.createElement("div");
  div.id = "sobi-suggestions";

  suggestions.forEach(text => {
    const btn = document.createElement("button");
    btn.classList.add("sobi-suggestion");
    btn.textContent = text;
    btn.addEventListener("click", () => {
      document.getElementById("sobi-input").value = text;
      div.remove();
      sendMessage();
    });
    div.appendChild(btn);
  });

  messages.appendChild(div);
  messages.scrollTop = messages.scrollHeight;
}

async function sendMessage() {
  const input = document.getElementById("sobi-input");
  const message = input.value.trim();
  if (!message) return;

  appendMessage(message, "user");
  input.value = "";
  showTyping();

  try {
    const response = await fetch(CHAT_API_URL, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ message })
    });

    const data = await response.json();
    hideTyping();

    if (data.reply) {
      appendMessage(data.reply, "bot");
      if (data.suggestions && data.suggestions.length > 0) {
        showSuggestions(data.suggestions);
      }
    } else {
      appendMessage(t('err_no_reply'), "bot");
    }
  } catch (err) {
    hideTyping();
    appendMessage(t('err_connection'), "bot");
  }
}

function initSobi() {
  createChatWidget();

  if (window.i18n) window.i18n.applyTranslations();

  const toggle = document.getElementById("sobi-toggle");
  const box = document.getElementById("sobi-box");
  const close = document.getElementById("sobi-close");
  const sendBtn = document.getElementById("sobi-send");
  const input = document.getElementById("sobi-input");

  toggle.addEventListener("click", () => {
    box.classList.toggle("sobi-open");
    toggle.classList.toggle("sobi-hidden");
  });

  close.addEventListener("click", () => {
    box.classList.remove("sobi-open");
    toggle.classList.remove("sobi-hidden");
  });

  sendBtn.addEventListener("click", sendMessage);

  input.addEventListener("keydown", (e) => {
    if (e.key === "Enter") sendMessage();
  });

  // Rebuild initial suggestions when language changes (only if they haven't been replaced by backend suggestions)
  document.addEventListener('langchange', () => {
    const suggestions = document.getElementById('sobi-suggestions');
    if (suggestions && suggestions.dataset.initial) {
      suggestions.innerHTML = '';
      buildInitialSuggestions(suggestions);
    }
  });
}

document.addEventListener("DOMContentLoaded", initSobi);
