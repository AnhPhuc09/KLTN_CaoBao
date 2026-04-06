import React, { useState, useEffect } from 'react';

const RelatedNews = ({ articleId }: { articleId: number }) => {
  const [related, setRelated] = useState([]);
  const [isAiProcessing, setIsAiProcessing] = useState(false);

  useEffect(() => {
    // GỌI CODE Ở ĐÂY:
    fetch(`http://localhost/KLTN_CaoBao/BE/modules/news/API_keyGemini.php?id=${articleId}`)
      .then(res => res.json())
      .then(data => {
        setRelated(data.related); // Lưu danh sách bài báo vào state
        setIsAiProcessing(data.aiPending); // Kiểm tra xem AI đã xong chưa
      })
      .catch(err => console.error("Lỗi gọi API:", err));
  }, [articleId]);

  return (
    <div className="mt-8">
      <h3 className="text-xl font-bold">Bài viết liên quan 
        {isAiProcessing && <span className="text-sm font-normal text-orange-500 animate-pulse ml-2">(AI đang phân tích...)</span>}
      </h3>
      
      <div className="grid gap-4 mt-4">
        {related.map((item: any) => (
          <div key={item.id} className="p-4 border rounded-lg shadow-sm hover:bg-gray-50">
            <h4 className="font-semibold text-blue-600">{item.title}</h4>
            <p className="text-sm text-gray-500">{item.source} - {item.pubDate}</p>
          </div>
        ))}
      </div>
    </div>
  );
};

export default RelatedNews;