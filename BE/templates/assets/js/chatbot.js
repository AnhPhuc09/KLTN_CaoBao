document.addEventListener("DOMContentLoaded", () => {
  const chatBubble = document.getElementById("chat-bubble");
  const chatWindow = document.getElementById("chat-window");
  const closeBtn = document.getElementById("close-chat");
  const clearBtn = document.getElementById("clear-chat");
  const chatBox = document.getElementById("chat-box");
  const chatInput = document.getElementById("chat-input");
  const sendBtn = document.getElementById("send-btn");

  let history = [];
  function displayMessage(message, sender) {
    const messageEl = document.createElement("div");
    messageEl.className = `message ${sender}-message`;

    const bubble = document.createElement("div");
    bubble.className = "bubble markdown";

    if (sender === "bot") {
      bubble.innerHTML = marked.parse(message);
    } else {
      bubble.textContent = message;
    }
    
    messageEl.appendChild(bubble);
    chatBox.appendChild(messageEl);
    chatBox.scrollTop = chatBox.scrollHeight;
  }

  chatBubble.addEventListener("click", () => {
    chatWindow.classList.add("active", "expanded");
    chatBubble.style.display = "none";
    chatBox.scrollTop = chatBox.scrollHeight;
  });

  closeBtn.addEventListener("click", () => {
    chatWindow.classList.remove("active", "expanded");
    chatBubble.style.display = "flex";
  });

  clearBtn.addEventListener("click", async () => {
    if (confirm("Bạn có chắc muốn xóa lịch sử chat?")) {
      chatBox.innerHTML = "";
      history = [];
      try {
        await fetch("modules/api/news_chat_ai.php", {
          method: "POST",
          body: new URLSearchParams({ mode: "clear" }),
        });
      } catch (err) {
        console.error("Lỗi khi clear history:", err);
      }
      displayMessage("Xin chào! Tôi là AI tin tức. Bạn muốn hỏi gì?", "bot");
    }
  });

  async function sendMessage() {
    const prompt = chatInput.value.trim();
    if (prompt === "") return;
    displayMessage(prompt, "user");
    chatInput.value = "";
    const typingIndicator = document.createElement("div");
    typingIndicator.className = "typing-indicator";
    typingIndicator.textContent = "AI đang trả lời...";
    chatBox.appendChild(typingIndicator);
    chatBox.scrollTop = chatBox.scrollHeight;
    const formData = new FormData();
    formData.append("prompt", prompt);

    try {
      const res = await fetch("modules/api/news_chat_ai.php", {
        method: "POST",
        body: formData,
      });
      const data = await res.json();
      chatBox.removeChild(typingIndicator);

      if (data.error) {
        displayMessage(data.error, "bot");
        return;
      }

      displayMessage(data.message, "bot");
      const container = document.createElement("div");
      container.className = "related-articles";
      let html = `<h5>Bài báo liên quan:</h5>`;
      if (data.articles && data.articles.length > 0) {
        html += `<ul>`;
        data.articles.forEach((a) => {
          html += `
          <li>
            <a href="${a.link}" target="_blank" title="${a.title}">
              <i class="fa-regular fa-newspaper"></i> ${a.title}
            </a>
            <small>${a.source || "Nguồn tin"} • ${
            a.pubDate || "Không rõ thời gian"
          }</small>
          </li>
        `;
        });
        html += `</ul>`;
      } else {
        // Không có bài báo liên quan
        html += `<p style="color:#666; font-size:13px; margin:6px 0 0 4px;">
        Không tìm thấy bài báo liên quan nào.
      </p>`;
      }

      container.innerHTML = html;
      chatBox.appendChild(container);
      chatBox.scrollTop = chatBox.scrollHeight;
      history = data.history || [];
      if (history.length > 10) history = history.slice(-10);
    } catch (error) {
      console.error("Lỗi khi gửi tin nhắn:", error);
      chatBox.removeChild(typingIndicator);
      displayMessage("Đã xảy ra lỗi, vui lòng thử lại.", "bot");
    }
  }

  sendBtn.addEventListener("click", sendMessage);
  chatInput.addEventListener("keypress", (e) => {
    if (e.key === "Enter") sendMessage();
  });
  
  displayMessage("Xin chào! Tôi là AI tin tức. Bạn muốn hỏi gì?", "bot");
});
