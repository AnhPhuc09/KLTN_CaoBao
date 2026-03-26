import React, { useState, useEffect } from 'react';

// Định nghĩa kiểu dữ liệu (Interface) cho bài báo
interface Article {
  id: number;
  title: string;
  content: string;
}

const ArticleDetail: React.FC = () => {
  const [article, setArticle] = useState<Article | null>(null);

  useEffect(() => {
    // Đây là nơi bạn fetch dữ liệu từ Backend PHP của mình
    // Ví dụ: fetch('http://localhost/KLTN_CaoBao/BE/modules/news/API_article.php?id=123')
    // .then(res => res.json())
    // .then(data => setArticle(data));
    
    // Tạm thời tạo dữ liệu mẫu
    setArticle({
      id: 123,
      title: "Chi tiết bài báo #123",
      content: "Nội dung bài báo sẽ được hiển thị ở đây..."
    });
  }, []);

  return (
    <div className="max-w-4xl mx-auto p-6 bg-white shadow-lg rounded-2xl mt-10">
      <h1 className="text-3xl font-extrabold text-gray-800 border-b pb-4">
        {article?.title}
      </h1>
      <div className="mt-6 text-gray-600 leading-relaxed">
        {article?.content}
      </div>
      <div className="mt-8 p-4 bg-blue-50 text-blue-700 rounded-lg italic">
        💡 Gợi ý từ Gemini: Không có bài liên quan.
      </div>
    </div>
  );
};

export default ArticleDetail;