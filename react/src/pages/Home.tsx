import React, { useState, useEffect } from 'react';
import axios from 'axios';
import NewsCard, { NewsItem } from '../components/NewsCard';
import { Newspaper, LayoutGrid, List, SlidersHorizontal, Loader2, TrendingUp, ChevronRight, ArrowRight, Clock } from 'lucide-react';
import { motion } from 'framer-motion';

const categories = [
  { id: '', name: 'Tất cả' },
  { id: 'doi-song', name: 'Đời Sống' },
  { id: 'kinh-doanh', name: 'Kinh Doanh' },
  { id: 'giao-duc', name: 'Giáo Dục' },
  { id: 'the-gioi', name: 'Thế Giới' },
  { id: 'phap-luat', name: 'Pháp Luật' },
  { id: 'thoi-su', name: 'Thời Sự' },
  { id: 'giai-tri', name: 'Giải Trí' },
  { id: 'suc-khoe', name: 'Sức Khỏe' },
  { id: 'cong-nghe', name: 'Công Nghệ' },
  { id: 'the-thao', name: 'Thể Thao' }
];

const Home = () => {
  const [news, setNews] = useState<NewsItem[]>([]);
  const [loading, setLoading] = useState(true);
  const [selectedCategory, setSelectedCategory] = useState('');
  const [page, setPage] = useState(1);
  const [hasMore, setHasMore] = useState(true);

  const fetchNews = async (pageNum: number, category: string, reset = false) => {
    try {
      setLoading(true);
      const host = window.location.hostname === 'localhost' ? 'http://localhost/KLTN_CaoBao' : '';
      const response = await axios.get(`${host}/BE/modules/api/news_load.php`, {
        params: {
          page: pageNum,
          category: category,
          perPage: 12
        }
      });

      if (response.data.status === 'success') {
        const fetchedNews = response.data.data;
        if (reset) {
          setNews(fetchedNews);
        } else {
          setNews(prev => [...prev, ...fetchedNews]);
        }
        setHasMore(pageNum < response.data.pagination.totalPages);
      }
    } catch (error) {
      console.error('Error fetching news:', error);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchNews(1, selectedCategory, true);
    setPage(1);
  }, [selectedCategory]);

  const featuredItem = news.length > 0 ? news[0] : null;
  const trendingItems = news.slice(1, 6);
  const otherItems = news.slice(6);

  return (
    <div className="max-w-7xl mx-auto px-6 pb-20">
      <div className="flex items-center gap-6 py-6 overflow-x-auto no-scrollbar mb-8 border-b border-slate-50">
        {categories.map((cat) => (
          <button
            key={cat.id}
            onClick={() => setSelectedCategory(cat.id)}
            className={`whitespace-nowrap text-sm font-bold transition-all uppercase tracking-widest pb-2 border-b-2 ${
              selectedCategory === cat.id 
                ? 'text-blue-600 border-blue-600' 
                : 'text-slate-400 border-transparent hover:text-slate-600'
            }`}
          >
            {cat.name}
          </button>
        ))}
      </div>

      {!selectedCategory && featuredItem && page === 1 && (
        <div className="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-16">
          <motion.div 
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            className="lg:col-span-8 group relative aspect-[16/9] md:aspect-[21/9] lg:aspect-auto lg:h-[500px] rounded-[2rem] overflow-hidden bg-slate-900 border border-slate-100 shadow-2xl"
          >
            <img 
              src={featuredItem.image || 'https://images.unsplash.com/photo-1504711434969-e33886168f5a?q=80&w=2070&auto=format&fit=crop'} 
              alt={featuredItem.title}
              className="absolute inset-0 w-full h-full object-cover opacity-70 group-hover:scale-105 transition-transform duration-1000"
            />
            <div className="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/20 to-transparent"></div>
            
            <div className="absolute bottom-0 left-0 p-8 md:p-12 w-full max-w-3xl">
                <span className="inline-block px-3 py-1 bg-blue-600 rounded-lg text-white text-[10px] font-black uppercase tracking-widest mb-6">
                    TIÊU ĐIỂM
                </span>
                <h1 className="text-3xl md:text-5xl font-black text-white mb-6 leading-[1.1] tracking-tighter line-clamp-2">
                    {featuredItem.title}
                </h1>
                <div className="flex items-center gap-6 text-slate-300 text-xs font-bold uppercase tracking-wider mb-8">
                    <span className="flex items-center gap-2">
                        <Clock size={14} className="text-blue-600" />
                        {featuredItem.source}
                    </span>
                    <span className="w-1.5 h-1.5 bg-slate-600 rounded-full"></span>
                    <span>{featuredItem.pubDate}</span>
                </div>
                <button className="px-8 py-4 bg-white text-slate-900 rounded-2xl font-black text-sm uppercase tracking-widest hover:bg-blue-600 hover:text-white transition-all shadow-xl shadow-white/5 active:scale-95 flex items-center gap-3">
                    Đọc toàn bộ bài viết
                    <ArrowRight size={18} />
                </button>
            </div>
          </motion.div>

          <div className="lg:col-span-4 flex flex-col">
            <div className="flex items-center justify-between mb-8 pb-4 border-b border-slate-100">
                <h3 className="font-black text-slate-900 uppercase tracking-[0.2em] text-xs flex items-center gap-2">
                    <TrendingUp size={16} className="text-blue-600" />
                    XU HƯỚNG
                </h3>
            </div>
            <div className="space-y-6">
                {trendingItems.map((item, idx) => (
                    <div key={item.id} className="flex gap-4 group cursor-pointer">
                        <span className="text-3xl font-black text-slate-100 group-hover:text-blue-600/10 transition-colors leading-none pt-1">
                            0{idx + 1}
                        </span>
                        <div className="flex-1">
                            <span className="text-[10px] font-black text-blue-600 uppercase tracking-widest mb-2 block">
                                {item.category}
                            </span>
                            <h4 className="font-bold text-slate-800 line-clamp-2 group-hover:text-blue-600 transition-colors leading-snug">
                                {item.title}
                            </h4>
                        </div>
                    </div>
                ))}
            </div>
            <button className="mt-auto pt-6 flex items-center gap-2 text-xs font-black text-slate-400 hover:text-blue-600 transition-colors group">
                Xem bảng xếp hạng đầy đủ
                <ChevronRight size={14} className="group-hover:translate-x-1 transition-transform" />
            </button>
          </div>
        </div>
      )}

      <div className="flex items-center justify-between mb-10">
        <h2 className="text-2xl font-black text-slate-900 tracking-tighter uppercase flex items-center gap-3">
          <div className="w-2 h-8 bg-blue-600 rounded-full"></div>
          {selectedCategory ? categories.find(c => c.id === selectedCategory)?.name : 'TIN MỚI NHẤT'}
        </h2>
        <div className="flex items-center gap-2 text-slate-400 font-bold text-[10px] uppercase tracking-widest hover:text-blue-600 cursor-pointer transition-colors">
            Xem tất cả bài báo ({news.length})
            <ChevronRight size={14} />
        </div>
      </div>

      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-10">
        {(selectedCategory ? news : otherItems).map((item) => (
          <NewsCard key={item.id} item={item} />
        ))}
      </div>

      {loading && (
        <div className="flex flex-col items-center justify-center py-20">
           <Loader2 className="w-10 h-10 text-blue-600 animate-spin mb-4 opacity-20" />
        </div>
      )}

      {!loading && hasMore && (
        <div className="mt-20 text-center">
          <button 
            onClick={() => {
                const next = page + 1;
                setPage(next);
                fetchNews(next, selectedCategory);
            }}
            className="px-12 py-5 bg-white border border-slate-100 rounded-[1.25rem] font-black text-sm uppercase tracking-widest text-slate-500 hover:text-blue-600 hover:border-blue-600/20 hover:shadow-2xl hover:shadow-blue-600/10 transition-all active:scale-95"
          >
            Tải thêm tin tức mới
          </button>
        </div>
      )}
    </div>
  );
};

export default Home;
