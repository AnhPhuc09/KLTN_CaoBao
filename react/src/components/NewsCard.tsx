import React from 'react';
import { Bookmark, Share2, ArrowRight, MessageSquare, Clock } from 'lucide-react';
import { Link } from 'react-router-dom';

export interface NewsItem {
  id: number;
  title: string;
  source: string;
  pubDate: string;
  image?: string;
  category: string;
  link: string;
}

interface NewsCardProps {
  item: NewsItem;
  featured?: boolean;
}

const NewsCard: React.FC<NewsCardProps> = ({ item, featured = false }) => {
  if (featured) {
    return (
      <div className="group relative aspect-[16/10] rounded-[2rem] overflow-hidden bg-slate-900 border border-slate-100 shadow-xl">
        <img 
          src={item.image || 'https://images.unsplash.com/photo-1504711434969-e33886168f5a?q=80&w=2070&auto=format&fit=crop'} 
          alt={item.title}
          className="absolute inset-0 w-full h-full object-cover opacity-60 group-hover:scale-105 transition-transform duration-1000"
        />
        <div className="absolute inset-0 bg-gradient-to-t from-slate-900/90 to-transparent"></div>
        <div className="absolute bottom-0 left-0 p-8">
            <span className="inline-block px-2 py-1 bg-blue-600 rounded-lg text-white text-[9px] font-black uppercase tracking-widest mb-3">
                {item.category}
            </span>
            <h3 className="text-xl font-bold text-white mb-2 line-clamp-2 leading-tight group-hover:text-blue-600 transition-colors">
                <Link to={`/article/${item.id}`}>{item.title}</Link>
            </h3>
            <p className="text-[10px] text-slate-400 font-bold uppercase tracking-widest">{item.source} • {item.pubDate}</p>
        </div>
      </div>
    );
  }

  return (
    <div className="flex flex-col h-full group bg-transparent">
      <div className="relative aspect-[16/10] rounded-[2rem] overflow-hidden mb-6 bg-slate-100 border border-slate-50 shadow-sm group-hover:shadow-xl transition-all duration-500">
        <img 
          src={item.image || 'https://images.unsplash.com/photo-1504711434969-e33886168f5a?q=80&w=2070&auto=format&fit=crop'} 
          alt={item.title} 
          className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" 
        />
        <div className="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors"></div>
        
        <div className="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300">
            <Link to={`/article/${item.id}`} className="p-4 bg-white/90 backdrop-blur-md rounded-full text-slate-900 shadow-2xl hover:bg-blue-600 hover:text-white transition-all transform translate-y-4 group-hover:translate-y-0">
                <ArrowRight size={24} />
            </Link>
        </div>

        <div className="absolute top-4 left-4">
            <span className="px-3 py-1 bg-white/90 backdrop-blur-md rounded-xl text-[9px] font-black text-blue-600 uppercase shadow-sm border border-white">
                {item.category}
            </span>
        </div>
        <button className="absolute bottom-4 right-4 p-2 bg-white/90 backdrop-blur-md rounded-xl text-slate-400 hover:text-blue-600 transition-all shadow-sm border border-white transform translate-x-12 opacity-0 group-hover:translate-x-0 group-hover:opacity-100 delay-100">
          <Bookmark size={16} />
        </button>
      </div>

      <div className="flex-1 flex flex-col items-start px-2">
        <div className="flex items-center gap-3 mb-3 text-slate-400">
            <span className="text-[10px] font-black uppercase tracking-[0.1em]">{item.source}</span>
            <span className="w-1 h-1 bg-slate-200 rounded-full"></span>
            <div className="flex items-center gap-1.5 text-[10px] font-bold">
                <Clock size={12} className="text-slate-300" />
                {item.pubDate}
            </div>
        </div>
        
        <h3 className="font-black text-lg text-slate-800 mb-4 line-clamp-2 leading-[1.3] group-hover:text-blue-600 transition-colors tracking-tight">
          <Link to={`/article/${item.id}`}>
            {item.title}
          </Link>
        </h3>

        <div className="mt-auto pt-4 flex items-center justify-between w-full border-t border-slate-100">
           <div className="flex items-center gap-2">
                <button className="p-2 -ml-2 text-slate-300 hover:text-blue-600 transition-colors">
                    <Share2 size={16} />
                </button>
                <button className="p-2 text-slate-300 hover:text-blue-600 transition-colors">
                    <MessageSquare size={16} />
                </button>
           </div>
           <Link 
            to={`/article/${item.id}`} 
            className="text-[10px] font-black text-slate-400 hover:text-blue-600 uppercase tracking-[0.2em] transition-colors flex items-center gap-1"
           >
              Đọc ngay
              <ArrowRight size={12} />
           </Link>
        </div>
      </div>
    </div>
  );
};

export default NewsCard;
