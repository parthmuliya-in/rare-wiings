const chatIcon = document.getElementById('chatIcon');
const chatBox = document.getElementById('chatBox');
const chatMessages = document.getElementById('chatMessages');
const userInput = document.getElementById('userInput');
const sendBtn = document.getElementById('sendBtn');
const typing = document.getElementById('typing');

// Append messages
function appendMessage(text, sender) {
    const div = document.createElement('div');
    div.classList.add('message', sender === 'user' ? 'user-message' : 'bot-message');
    //div.textContent = text;
    div.innerHTML = text;
    chatMessages.appendChild(div);
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

// Toggle chat open/close
chatIcon.addEventListener('click', () => {
    if (chatBox.style.display === 'flex') {
        chatBox.style.display = 'none';
    } else {
        chatBox.style.display = 'flex';
        if (!chatBox.dataset.greeted) {
            appendMessage("Hello! How can I help you?", 'bot');
            chatBox.dataset.greeted = "true";
        }
    }
});

// Send message
function handleMessage() {
    const input = userInput.value.trim();
    if (!input) return;
    appendMessage(input, 'user');
    userInput.value = '';
    typing.style.display = 'block';

    const formData = new FormData();
    formData.append('message', input);

    fetch('chatbot.php', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            typing.style.display = 'none';
            appendMessage(data.answer, 'bot');
        });
}

sendBtn.addEventListener('click', handleMessage);
userInput.addEventListener('keydown', (e) => { if (e.key === 'Enter') handleMessage(); });