import React, { useState, useEffect } from 'react';

interface RelatedArticle {
  id: number;
  title: string;
  link: string;
  image: string;
  source: string;
  pubDate: string;
}

interface ApiResponse {
  related: RelatedArticle[];
  total: number;
  aiPending: boolean;
}

const RelatedNews: React.FC<{ articleId: number }> = ({ articleId }) => {
  const [data, setData] = useState<ApiResponse | null>(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    // Truyền ID bài báo hiện tại vào API
    const apiUrl = `http://localhost/KLTN_CaoBao/BE/modules/news/API_article.php?id=${articleId}&page=1&perPage=5`;

    setLoading(true);
    fetch(apiUrl)
      .then(res => res.json())
      .then(json => {
        setData(json);
        setLoading(false);
      })
      .catch(err => {
        console.error("Lỗi:", err);
        setLoading(false);
      });
  }, [articleId]);

  if (loading) return <p className="text-gray-500">Đang tìm bài viết liên quan...</p>;
  if (!data || data.related.length === 0) return <p className="text-gray-400 italic">Không có bài liên quan.</p>;

  return (
    <div className="mt-10">
      <h2 className="text-xl font-bold mb-4 flex items-center">
        Bài viết liên quan 
        {data.aiPending && (
          <span className="ml-2 text-xs bg-yellow-100 text-yellow-600 px-2 py-1 rounded-full animate-pulse">
            AI đang xử lý thêm...
          </span>
        )}
      </h2>
      
      <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
        {data.related.map((item) => (
          <a 
            key={item.id} 
            href={item.link} 
            className="flex gap-4 p-3 border rounded-lg hover:shadow-md transition bg-white"
          >
            {item.image && (
              <img src={item.image} alt={item.title} className="w-20 h-20 object-cover rounded" />
            )}
            <div>
              <h3 className="text-sm font-semibold line-clamp-2">{item.title}</h3>
              <p className="text-xs text-gray-500 mt-1">{item.source} • {new Date(item.pubDate).toLocaleDateString('vi-VN')}</p>
            </div>
          </a>
        ))}
      </div>
    </div>
  );
};

export default RelatedNews;