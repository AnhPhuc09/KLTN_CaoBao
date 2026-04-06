import RelatedNews from "./RelatedNews";

function ArticlePage() {
  const currentId = 123; // ID bài báo đang xem

  return (
    <div>
      {/* Nội dung bài báo chính */}
      <h1>Tiêu đề bài báo chính</h1>
      <p>Nội dung...</p>

      <hr />

      {/* THÊM Ở ĐÂY: Hiển thị phần liên quan từ Gemini */}
      <RelatedNews articleId={currentId} />
    </div>
  );
}