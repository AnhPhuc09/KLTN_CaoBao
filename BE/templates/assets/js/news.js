document.addEventListener("DOMContentLoaded", () => {
  let page = 1;
  let loading = false;
  let allNews = [];
  let isFirstLoad = true;

  let relatedArticles = [];
  let currentIndex = 0;
  let carouselInterval = null;

  const params = new URLSearchParams(window.location.search);
  const keyword = params.get("keyword") || "";
  const category = params.get("category") || "";

  async function loadNews() {
    if (loading) return;
    loading = true;

    const loadingEl = document.getElementById("loading");
    loadingEl.style.display = "block";
    loadingEl.innerText = "Đang tải...";

    try {
      const res = await fetch(
        `modules/api/news_load.php?page=${page}&keyword=${encodeURIComponent(
          keyword
        )}&category=${encodeURIComponent(category)}`
      );
      const responseJson = await res.json();
      const data = responseJson.data || [];

      if (data.length > 0) {
        const newsList = document.getElementById("newsList");
        const topNews = document.getElementById("topNews");
        const hotNewsSide = document.getElementById("hotNewsSide");

        data.forEach((item, index) => {
          const globalIndex = allNews.length;
          allNews.push(item);
          if (isFirstLoad && globalIndex === 0) {
            topNews.innerHTML = `
            <div class="top-wrapper">
              <div class="action-buttons">
                <button class="fav-btn ${
                  Number(item.is_favourite) === 1 ? "saved" : ""
                }">
                  <i class="fa-heart ${
                    Number(item.is_favourite) === 1 ? "fa-solid" : "fa-regular"
                  }"></i>
                </button>
                <button class="cmt-btn">
                  <i class="fa-regular fa-comment"></i>
                </button>
              </div>

              <a href="${item.link}" target="_blank">
                <img src="${item.image}" alt="${item.title}">
              </a>

              <h2>
                <a href="${item.link}" target="_blank">${item.title}</a>
              </h2>
            </div>
          `;

            topNews.querySelector(".fav-btn").onclick = () =>
              toggleFavorite(
                topNews.querySelector(".fav-btn"),
                item.id,
                item.title,
                item.image,
                item.link
              );

            topNews.querySelector(".cmt-btn").onclick = () => {
              window.location.href = `?module=news&action=comment&id=${item.id}`;
            };

            markAsViewedAndLoadAI(item);
            return;
          }
          if (isFirstLoad && globalIndex > 0 && globalIndex <= 4) {
            const hotItem = document.createElement("article");
            hotItem.className = "side-news__item";
            hotItem.innerHTML = `
            <a href="${item.link}" target="_blank">
              ${item.title}
            </a>
          `;
            hotNewsSide.appendChild(hotItem);
            return;
          }
          const article = document.createElement("article");
          article.className = "news-item";

          const actionBox = document.createElement("div");
          actionBox.className = "action-buttons action-card";

          const favBtn = document.createElement("button");
          const isSaved = Number(item.is_favourite) === 1;
          favBtn.className = `fav-btn ${isSaved ? "saved" : ""}`;
          favBtn.innerHTML = `<i class="fa-heart ${
            isSaved ? "fa-solid" : "fa-regular"
          }"></i>`;
          favBtn.onclick = () =>
            toggleFavorite(favBtn, item.id, item.title, item.image, item.link);

          const cmtBtn = document.createElement("button");
          cmtBtn.className = "cmt-btn";
          cmtBtn.innerHTML = `<i class="fa-regular fa-comment"></i>`;
          cmtBtn.onclick = () => {
            window.location.href = `?module=news&action=comment&id=${item.id}`;
          };

          actionBox.appendChild(favBtn);
          actionBox.appendChild(cmtBtn);
          article.appendChild(actionBox);

          const aImg = document.createElement("a");
          aImg.href = item.link;
          aImg.target = "_blank";
          aImg.innerHTML = `<img src="${item.image}" alt="${item.title}" loading="lazy">`;
          article.appendChild(aImg);

          const info = document.createElement("div");
          info.className = "news-info";

          info.innerHTML = `
          <h4>
            <a href="${item.link}" target="_blank">${item.title}</a>
          </h4>
          <div class="meta">
            <span>${item.source}</span> • <span>${item.pubDate}</span>
          </div>
        `;

          article.appendChild(info);
          newsList.appendChild(article);
        });

        isFirstLoad = false;
        page++;
        loadingEl.innerText = "Kéo xuống để tải thêm...";
      } else {
        observer.disconnect();
        loadingEl.innerText = "Hết tin tức";
      }
    } catch (err) {
      console.error("Lỗi load news:", err);
      loadingEl.innerText = "Lỗi khi tải tin";
    }

    loading = false;
  }

  function markAsViewedAndLoadAI(item) {
    let viewed = JSON.parse(sessionStorage.getItem("viewedNews") || "[]");
    if (!viewed.includes(item.id)) {
      viewed.push(item.id);
      sessionStorage.setItem("viewedNews", JSON.stringify(viewed));
    }
    loadAIRelated(item.id);
  }
  async function loadAIRelated(articleId) {
    try {
      const res = await fetch(
        `modules/api/news_article.php?id=${articleId}&perPage=10`
      );
      const data = await res.json();

      if (data.related && data.related.length > 0) {
        renderRelatedNews(data.related, true);
      }
    } catch (err) {
      console.error("Lỗi load fallback related:", err);
    }

    let attempts = 0;
    const interval = setInterval(async () => {
      try {
        const res = await fetch(
          `modules/api/news_article.php?id=${articleId}&perPage=10&_=${Date.now()}`
        );
        if (res.ok) {
          const data = await res.json();
          if (data && data.status === 'success' && data.aiPending === false && data.related && data.related.length > 0) {
            renderRelatedNews(data.related, false);
            clearInterval(interval);
          }
        }
      } catch (err) {
        console.error("Lỗi load AI related:", err);
      }
      if (++attempts > 5) clearInterval(interval);
    }, 4000);
  }
  function renderRelatedNews(articles, isFallback = false) {
    const box = document.getElementById("rotateNews");
    if (!box) return;

    const relatedArticles = articles.slice(0, 10);
    currentIndex = 0;
    box.innerHTML = "";

    const wrapper = document.createElement("div");
    wrapper.className = "related-wrapper";

    relatedArticles.forEach((item) => {
      const article = document.createElement("article");
      article.className = "related-item";
      article.style.opacity = isFallback ? "1" : "0";

      article.innerHTML = `
      <a href="${item.link}" target="_blank" class="img-link">
        <img src="${item.image}" alt="${item.title}" loading="lazy">
      </a>
      <div class="related-info">
        <h5>
          <a href="${item.link}" target="_blank" class="title-link">${item.title}</a>
        </h5>
        <div class="meta">
          <span>${item.source}</span> • <span>${item.pubDate}</span>
        </div>
      </div>
    `;
      article.querySelector(".title-link").addEventListener("click", (e) => {
        e.preventDefault();
        markAsViewedAndLoadAI(item);
        setTimeout(() => window.open(item.link, "_blank"), 300);
      });

      wrapper.appendChild(article);
    });
    box.appendChild(wrapper);

    if (!isFallback) {
      requestAnimationFrame(() => {
        wrapper.querySelectorAll(".related-item").forEach((el) => {
          el.style.transition = "opacity 0.6s ease, transform 0.4s ease";
          el.style.opacity = "1";
          el.style.transform = "translateY(0)";
        });
      });
    }
    function updateSlide() {
      if (!wrapper.children.length) return;
      const childWidth = wrapper.children[0].offsetWidth + 15;
      const maxIndex =
        relatedArticles.length - Math.floor(box.offsetWidth / childWidth);
      const offset = -Math.min(currentIndex, maxIndex) * childWidth;
      wrapper.style.transform = `translateX(${offset}px)`;
      wrapper.style.transition = "transform 0.6s ease";
    }
    if (carouselInterval) clearInterval(carouselInterval);
    carouselInterval = setInterval(() => {
      currentIndex += 1;
      const childWidth = wrapper.children[0].offsetWidth + 15;
      const maxIndex =
        relatedArticles.length - Math.floor(box.offsetWidth / childWidth);
      if (currentIndex > maxIndex) currentIndex = 0;
      updateSlide();
    }, 4000);

    updateSlide();
  }

  async function toggleFavorite(
    btn,
    news_id,
    title = "",
    image = "",
    link = ""
  ) {
    const icon = btn.querySelector("i");
    const isAdding = !btn.classList.contains("saved");
    btn.classList.toggle("saved", isAdding);
    icon.classList.toggle("fa-solid", isAdding);
    icon.classList.toggle("fa-regular", !isAdding);
    try {
      const formData = new FormData();
      formData.append("news_id", news_id);
      formData.append("title", title);
      formData.append("image", image);
      formData.append("link", link);
      const res = await fetch("modules/api/favourite_toggle.php", {
        method: "POST",
        body: formData,
      });
      const data = await res.json();
      console.log(data.message);
    } catch (error) {
      console.error("Lỗi khi lưu yêu thích:", error);
    }
  }

  const suggestionBox = document.getElementById("suggestionBox");
  const searchBox = document.getElementById("searchBox");
  searchBox.addEventListener("blur", () =>
    setTimeout(() => (suggestionBox.style.display = "none"), 200)
  );
  searchBox.addEventListener("focus", () => {
    if (suggestionBox.innerHTML.trim() !== "")
      suggestionBox.style.display = "block";
  });
  window.suggestSearch = async function () {
    const query = searchBox.value.trim();

    if (query.length < 2) {
      suggestionBox.style.display = "none";
      return;
    }

    const formData = new FormData();
    formData.append("query", query);

    try {
      const res = await fetch("modules/api/news_suggest.php", {
        method: "POST",
        body: formData,
      });
      const data = await res.json();
      suggestionBox.innerHTML = "";

      if (data.suggestions && data.suggestions.length > 0) {
        data.suggestions.forEach((s) => {
          const div = document.createElement("div");

          div.textContent = s;
          div.classList.add("suggestion-item");
          if (s === "Không có từ khóa bạn đang tìm kiếm!") {
            div.style.color = "red";
            div.classList.add("no-suggestion");
          } else {
            div.addEventListener("click", () => {
              window.location.href =
                "index.php?module=news&action=list&keyword=" +
                encodeURIComponent(s);
            });
          }
          suggestionBox.appendChild(div);
        });
        suggestionBox.style.display = "block";
      } else {
        suggestionBox.innerHTML =
          "<div style='padding:8px; color:red'>Không có từ khóa bạn đang tìm kiếm!</div>";
        suggestionBox.style.display = "block";
      }
    } catch (error) {
      console.error("Lỗi khi gọi API suggest:", error);
      suggestionBox.innerHTML =
        "<div style='padding:8px; color:red'>Lỗi kết nối. Vui lòng thử lại.</div>";
      suggestionBox.style.display = "block";
    }
  };

  const observer = new IntersectionObserver(
    (entries) => {
      if (entries[0].isIntersecting) {
        loadNews();
      }
    },
    {
      rootMargin: "0px 0px 200px 0px",
      threshold: 0.1,
    }
  );

  observer.observe(document.getElementById("loading"));
  loadNews();
});
