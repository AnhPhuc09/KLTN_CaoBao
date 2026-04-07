import React, { useState, useEffect } from 'react';
import { useParams, Link } from 'react-router-dom';
import axios from 'axios';
import { 
  Calendar, 
  ExternalLink, 
  Share2, 
  Bookmark, 
  ArrowLeft,
  Sparkles,
  ChevronRight,
  TrendingUp,
  Clock,
  Loader2,
  Volume2,
  MoreHorizontal,
  ThumbsUp,
  MessageCircle,
  ArrowRight
} from 'lucide-react';
import NewsCard, { NewsItem } from '../components/NewsCard';
import { motion } from 'framer-motion';

const ArticleDetail = () => {
  const { id } = useParams();
  const [article, setArticle] = useState<any>(null);
  const [related, setRelated] = useState<NewsItem[]>([]);
  const [loading, setLoading] = useState(true);
  const [aiPending, setAiPending] = useState(false);

  useEffect(() => {
    const fetchArticle = async () => {
      try {
        setLoading(true);
        const host = window.location.hostname === 'localhost' ? 'http://localhost/KLTN_CaoBao' : '';
        const response = await axios.get(`${host}/BE/modules/api/news_article.php`, {
          params: { id }
        });

        if (response.data.status === 'success') {
          setRelated(response.data.related);
          setAiPending(response.data.aiPending);
        }
      } catch (error) {
        console.error('Error fetching article:', error);
      } finally {
        setLoading(false);
      }
    };

    fetchArticle();
    window.scrollTo(0, 0);
  }, [id]);

  if (loading) {
    return (
      <div className="flex flex-col items-center justify-center py-40">
        <Loader2 className="w-12 h-12 text-blue-600 animate-spin mb-6 opacity-20" />
      </div>
    );
  }

  return (
    <div className="max-w-7xl mx-auto px-6 py-12 font-inter bg-white">
      <nav className="flex items-center gap-3 text-[10px] font-black uppercase tracking-[0.2em] mb-12 text-slate-400">
        <span className="text-blue-600 hover:text-blue-700 cursor-pointer transition-colors">CÔNG NGHỆ</span>
        <span className="text-slate-200">/</span>
        <span className="flex items-center gap-1.5">
            <Clock size={12} className="text-blue-600" />
            9 PHÚT ĐỌC
        </span>
      </nav>

      <motion.div 
        initial={{ opacity: 0, y: 30 }}
        animate={{ opacity: 1, y: 0 }}
        className="max-w-4xl"
      >
        <h1 className="text-4xl md:text-6xl font-black text-slate-900 mb-10 leading-[1.1] tracking-tighter">
            Sự Phục Hưng của AI trong Kỷ nguyên Số 2026: Góc nhìn từ Gemini
        </h1>

        <div className="flex flex-wrap items-center justify-between gap-6 pb-12 border-b border-slate-100">
            <div className="flex items-center gap-4">
                <div className="w-12 h-12 bg-slate-900 rounded-full border-4 border-slate-50 flex items-center justify-center text-white font-bold">
                    PC
                </div>
                <div>
                    <h4 className="text-sm font-black text-slate-900 leading-none">Cao Bảo Phúc</h4>
                    <p className="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1.5">06 Tháng 4, 2026</p>
                </div>
            </div>

            <div className="flex items-center gap-3">
                <button className="flex items-center gap-2 px-4 py-2 bg-slate-50 hover:bg-blue-50 hover:text-blue-600 rounded-xl transition-all text-xs font-black text-slate-400 uppercase tracking-widest border border-slate-100 shadow-sm shadow-slate-100">
                    <Volume2 size={16} />
                    Nghe bài báo
                </button>
                <div className="h-6 w-px bg-slate-100 mx-2"></div>
                <div className="flex items-center gap-1">
                    <button className="p-2 text-slate-400 hover:text-blue-600 hover:bg-slate-50 rounded-lg transition-all"><Bookmark size={20} /></button>
                    <button className="p-2 text-slate-400 hover:text-blue-600 hover:bg-slate-50 rounded-lg transition-all"><Share2 size={20} /></button>
                    <button className="p-2 text-slate-400 hover:text-blue-600 hover:bg-slate-50 rounded-lg transition-all"><MoreHorizontal size={20} /></button>
                </div>
            </div>
        </div>
      </motion.div>

      <div className="grid grid-cols-1 lg:grid-cols-12 gap-16 mt-16 pb-20 border-b border-slate-100">
        <div className="lg:col-span-8 flex flex-col items-start">
            <div className="w-full aspect-[21/9] rounded-[3rem] overflow-hidden mb-16 bg-slate-100 border border-slate-50 shadow-2xl">
                <img 
                    src="https://images.unsplash.com/photo-1677442136019-21780ecad995?q=80&w=2070&auto=format&fit=crop" 
                    alt="Main Content" 
                    className="w-full h-full object-cover"
                />
            </div>

            <div className="grid grid-cols-1 md:grid-cols-12 gap-12 w-full">
                <div className="md:col-span-4 bg-[#F2F7FF] rounded-[2rem] p-8 border border-blue-50 shadow-lg shadow-blue-50 relative overflow-hidden group">
                    <div className="absolute top-0 right-0 w-24 h-24 bg-blue-600/20 rounded-full blur-3xl group-hover:scale-150 transition-transform"></div>
                    <div className="relative z-10 flex flex-col h-full">
                        <div className="flex items-center gap-2 mb-6">
                            <Sparkles size={18} className="text-blue-600 animate-pulse" />
                            <span className="text-[10px] font-black text-blue-600 uppercase tracking-[0.2em]">TÓM TẮT AI</span>
                        </div>
                        <ul className="space-y-6 text-sm font-bold text-slate-600 leading-relaxed list-none p-0 flex-1">
                            {['Sự phát triển vượt bậc của các mô hình đa phương thức.', 'Tác động sâu rộng tới thị trường lao động và chuyển đổi số Việt Nam.', 'Tương lai của ngành báo chí trong kỷ nguyên AI 2.0.'].map((text, i) => (
                                <li key={i} className="flex gap-4 items-start group/item">
                                    <div className="w-1.5 h-1.5 bg-blue-600 rounded-full mt-2 lg:mt-2.5 flex-shrink-0 group-hover/item:scale-150 transition-transform"></div>
                                    <span className="group-hover/item:text-slate-900 transition-colors uppercase text-[11px] tracking-tight">{text}</span>
                                </li>
                            ))}
                        </ul>
                        <div className="mt-8 pt-8 border-t border-blue-600/10">
                            <p className="text-[9px] font-black italic text-blue-600 uppercase tracking-widest leading-relaxed">Phân tích bởi Gemini 2.0 Flash</p>
                        </div>
                    </div>
                </div>

                <article className="md:col-span-8 prose prose-slate max-w-none prose-p:text-slate-500 prose-p:text-lg prose-p:leading-[1.8] prose-p:font-bold prose-headings:font-black prose-headings:tracking-tighter">
                   <p className="first-letter:text-7xl first-letter:font-black first-letter:mr-4 first-letter:float-left first-letter:text-blue-600">Trong thời đại công nghệ số bùng nổ, sự xuất hiện của các mô hình AI tiên tiến đã không chỉ dừng lại ở mức hỗ trợ cá nhân mà còn thay đổi hoàn toàn cách chúng ta tiếp nhận và xử lý thông tin hàng ngày. Đây được xem là một bước ngoặt lịch sử lớn hơn cả sự ra đời của Internet.</p>
                   <h2>Tại sao chúng ta cần AI trong báo chí?</h2>
                   <p>Khả năng tổng hợp và cá nhân hóa trải nghiệm của AI giúp người đọc tiết kiệm hàng giờ mỗi ngày. Bạn không còn phải đọc thủ công hàng trăm bài báo để hiểu một vấn đề phức tạp. Gemini AI làm điều đó trong chớp mắt, cung cấp những góc nhìn đa chiều, trung lập và chính xác tuyệt đối.</p>
                   <blockquote className="bg-slate-50 border-l-4 border-blue-600 p-8 rounded-2xl italic font-black text-slate-700 tracking-tight text-xl">
                    "AI sẽ không thay thế nhà báo, nhưng nhà báo biết sử dụng AI sẽ thay thế những người còn lại."
                   </blockquote>
                </article>
            </div>
        </div>

        <aside className="lg:col-span-4 space-y-12">
            <div>
                <h4 className="font-black text-slate-900 uppercase tracking-[0.2em] text-xs mb-8 flex items-center gap-3">
                    <TrendingUp size={16} className="text-blue-600" />
                    Có thể bạn quan tâm
                </h4>
                <div className="space-y-8">
                    {related.slice(0, 3).map((item) => (
                        <div key={item.id} className="group flex gap-4 items-center cursor-pointer">
                            <div className="w-20 h-20 bg-slate-100 rounded-2xl overflow-hidden flex-shrink-0 border border-slate-50 shadow-sm group-hover:shadow-lg transition-all">
                                <img src={item.image} className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" alt="" />
                            </div>
                            <div>
                                <span className="text-[9px] font-black text-blue-600 uppercase tracking-widest block mb-1">{item.category}</span>
                                <h5 className="text-sm font-bold text-slate-800 line-clamp-2 leading-snug group-hover:text-blue-600 transition-colors">{item.title}</h5>
                            </div>
                        </div>
                    ))}
                </div>
                <button className="w-full mt-8 py-4 border border-slate-100 rounded-2xl text-[10px] font-black text-slate-400 hover:text-blue-600 hover:border-blue-600/20 uppercase tracking-[0.2em] transition-all active:scale-95">
                    Xem thêm đề xuất AI
                </button>
            </div>

            <div className="bg-slate-900 rounded-[2.5rem] p-10 text-white relative overflow-hidden group shadow-2xl shadow-blue-200/20">
                <div className="absolute top-0 right-0 w-32 h-32 bg-blue-600 rounded-full blur-3xl opacity-20 group-hover:opacity-40 transition-opacity"></div>
                <div className="relative z-10">
                    <div className="w-12 h-12 bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center text-blue-600 mb-6 animate-pulse">
                        <Sparkles size={24} />
                    </div>
                    <h3 className="text-2xl font-black mb-4 tracking-tighter">Bạn muốn tóm tắt thêm nhiều bài viết?</h3>
                    <p className="text-slate-400 text-sm font-medium mb-8 leading-relaxed">Hãy hỏi Chatbot AI ở góc phải màn hình để được hỗ trợ tức thì.</p>
                    <button className="w-full bg-blue-600 hover:bg-blue-700 py-4 rounded-2xl font-black text-sm uppercase tracking-widest transition-all active:scale-95 flex items-center justify-center gap-2">
                        Chat với AI ngay
                        <ArrowRight size={18} />
                    </button>
                </div>
            </div>
        </aside>
      </div>

      <section className="mt-20 max-w-4xl">
        <h2 className="text-2xl font-black text-slate-900 tracking-tighter uppercase mb-10 flex items-center gap-3">
             <MessageCircle size={24} className="text-blue-600" />
             Thảo luận (24)
        </h2>
        <div className="space-y-12">
            {[1].map(i => (
                <div key={i} className="flex gap-6">
                    <div className="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600 font-bold">QA</div>
                    <div className="flex-1">
                        <div className="flex items-center gap-3 mb-2">
                            <span className="font-black text-sm text-slate-900">Quỳnh Anh</span>
                            <span className="text-[10px] font-bold text-slate-400 uppercase tracking-widest">3 giờ trước</span>
                        </div>
                        <p className="text-slate-600 text-sm leading-relaxed mb-4 font-medium">Bài báo phân tích rất xuất sắc về vai trò của AI Gemini. Tôi đặc biệt quan tâm đến phần tóm tắt AI bên trái, nó giúp tôi nắm bắt nội dung cực kỳ nhanh chóng!</p>
                        <div className="flex items-center gap-4 text-xs font-black text-slate-400 uppercase tracking-widest">
                            <button className="flex items-center gap-1.5 hover:text-blue-600 transition-colors"><ThumbsUp size={14} /> Thích (12)</button>
                            <button className="hover:text-blue-600 transition-colors">Phản hồi</button>
                        </div>
                    </div>
                </div>
            ))}
        </div>
      </section>
    </div>
  );
};

export default ArticleDetail;
