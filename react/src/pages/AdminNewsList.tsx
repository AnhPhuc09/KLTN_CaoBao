import React, { useState } from 'react';
import { 
  Search, 
  Filter, 
  Plus, 
  MoreHorizontal, 
  Eye, 
  Edit, 
  Trash2, 
  CheckCircle,
  AlertCircle,
  Link as LinkIcon
} from 'lucide-react';
import { motion } from 'framer-motion';

const AdminNewsList = () => {
  const [searchTerm, setSearchTerm] = useState('');

  const mockNews = [
    { id: 1, title: 'Giá xăng dầu hôm nay 06/04: Tiếp tục đà tăng nhẹ', category: 'Kinh doanh', source: 'VnExpress', date: '06/04/2026', status: 'Published' },
    { id: 2, title: 'U23 Việt Nam chốt danh sách dự VCK U23 Châu Á', category: 'Thể thao', source: 'Tuổi Trẻ', date: '05/04/2026', status: 'Draft' },
    { id: 3, title: 'Công nghệ AI bùng nổ tại thung lũng Silicon', category: 'Công nghệ', source: 'Zing News', date: '05/04/2026', status: 'Published' },
    { id: 4, title: 'Du lịch mùa hè 2026: Những điểm đến không thể bỏ qua', category: 'Đời sống', source: 'Thanh Niên', date: '04/04/2026', status: 'Published' },
    { id: 5, title: 'Xây dựng đường sắt tốc độ cao: Tầm nhìn 2045', category: 'Thời sự', source: 'VTV', date: '04/04/2026', status: 'Pending' },
  ];

  return (
    <div className="space-y-6 animate-in fade-in slide-in-from-bottom-2 duration-500">
      <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <h1 className="text-2xl font-bold text-slate-900 tracking-tight">Quản lý tin tức</h1>
          <p className="text-slate-400 text-sm font-medium mt-1">Tổng cộng {mockNews.length} bài báo trong hệ thống.</p>
        </div>
        <button className="btn-primary flex items-center gap-2 shadow-lg shadow-blue-200">
           <Plus size={20} />
           <span>Thêm bài mới</span>
        </button>
      </div>

      <div className="flex flex-col lg:flex-row gap-4">
        <div className="flex-1 relative group">
          <Search className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-blue-600 transition-colors" size={18} />
          <input 
            type="text" 
            placeholder="Tìm kiếm theo tiêu đề, nguồn hoặc ID..."
            value={searchTerm}
            onChange={(e) => setSearchTerm(e.target.value)}
            className="w-full bg-white border border-slate-200 rounded-2xl pl-12 pr-4 py-3 focus:ring-2 focus:ring-blue-100 focus:border-blue-600 transition-all text-sm outline-none"
          />
        </div>
        <div className="flex items-center gap-2">
            <button className="flex items-center gap-2 px-4 py-3 bg-white border border-slate-200 rounded-2xl text-slate-600 font-bold text-xs uppercase tracking-wider hover:bg-slate-50 transition-all">
                <Filter size={16} />
                Lọc
            </button>
            <button className="flex items-center gap-2 px-4 py-3 bg-white border border-slate-200 rounded-2xl text-slate-600 font-bold text-xs uppercase tracking-wider hover:bg-slate-50 transition-all">
                Xuất Excel
            </button>
        </div>
      </div>

      <div className="card overflow-hidden shadow-xl border-none">
        <div className="overflow-x-auto">
          <table className="w-full text-left border-collapse">
            <thead>
              <tr className="bg-slate-50/50 border-b border-slate-100">
                <th className="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest whitespace-nowrap">Bài báo</th>
                <th className="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest whitespace-nowrap text-center">Danh mục</th>
                <th className="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest whitespace-nowrap text-center">Trạng thái</th>
                <th className="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest whitespace-nowrap text-center">Ngày tạo</th>
                <th className="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest whitespace-nowrap text-right">Hành động</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-slate-50">
              {mockNews.map((news) => (
                <tr key={news.id} className="hover:bg-slate-50/50 transition-colors group">
                  <td className="px-6 py-5">
                    <div className="flex items-center gap-4">
                        <div className="w-10 h-10 rounded-xl bg-slate-100 overflow-hidden flex-shrink-0 border border-slate-200">
                            <img src={`https://picsum.photos/seed/${news.id}/100`} className="w-full h-full object-cover" alt="" />
                        </div>
                        <div className="max-w-xs xl:max-w-md">
                            <h4 className="text-sm font-bold text-slate-800 line-clamp-1 group-hover:text-blue-600 transition-colors">{news.title}</h4>
                            <div className="flex items-center gap-2 mt-1 text-[10px] text-slate-400 font-bold uppercase tracking-tight">
                                <LinkIcon size={10} />
                                <span>{news.source}</span>
                            </div>
                        </div>
                    </div>
                  </td>
                  <td className="px-6 py-5">
                    <div className="flex justify-center">
                        <span className="px-2 py-1 bg-slate-100 text-slate-500 rounded-lg text-[10px] font-bold uppercase tracking-tight">
                        {news.category}
                        </span>
                    </div>
                  </td>
                  <td className="px-6 py-5">
                    <div className="flex justify-center">
                        {news.status === 'Published' ? (
                            <span className="flex items-center gap-1.5 px-3 py-1 bg-green-50 text-green-600 rounded-full text-[10px] font-bold uppercase tracking-tight border border-green-100 shadow-sm">
                                <CheckCircle size={12} />
                                Đã duyệt
                            </span>
                        ) : news.status === 'Draft' ? (
                            <span className="flex items-center gap-1.5 px-3 py-1 bg-slate-100 text-slate-500 rounded-full text-[10px] font-bold uppercase tracking-tight border border-slate-200">
                                <AlertCircle size={12} />
                                Nháp
                            </span>
                        ) : (
                            <span className="flex items-center gap-1.5 px-3 py-1 bg-orange-50 text-orange-600 rounded-full text-[10px] font-bold uppercase tracking-tight border border-orange-100">
                                <AlertCircle size={12} />
                                Chờ duyệt
                            </span>
                        )}
                    </div>
                  </td>
                  <td className="px-6 py-5">
                    <div className="text-center text-[11px] font-bold text-slate-500">
                        {news.date}
                    </div>
                  </td>
                  <td className="px-6 py-5">
                    <div className="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                        <button className="p-2 text-slate-400 hover:text-blue-600 hover:bg-white rounded-lg transition-all shadow-sm border border-transparent hover:border-slate-100">
                            <Eye size={16} />
                        </button>
                        <button className="p-2 text-slate-400 hover:text-orange-500 hover:bg-white rounded-lg transition-all shadow-sm border border-transparent hover:border-slate-100">
                            <Edit size={16} />
                        </button>
                        <button className="p-2 text-slate-400 hover:text-red-500 hover:bg-white rounded-lg transition-all shadow-sm border border-transparent hover:border-slate-100">
                            <Trash2 size={16} />
                        </button>
                        <div className="w-px h-4 bg-slate-100 mx-1"></div>
                        <button className="p-2 text-slate-400 hover:text-slate-600 hover:bg-white rounded-lg transition-all shadow-sm">
                            <MoreHorizontal size={16} />
                        </button>
                    </div>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
        
        <div className="px-6 py-4 bg-slate-50/30 border-t border-slate-100 flex items-center justify-between">
            <span className="text-xs font-bold text-slate-400 uppercase tracking-wider">Hiển thị {mockNews.length} / 120 bài báo</span>
            <div className="flex items-center gap-1">
                <button className="px-3 py-1.5 text-xs font-bold text-slate-400 hover:text-blue-600 transition-colors disabled:opacity-50" disabled>Trướcc</button>
                <div className="flex gap-1 mx-2">
                    <button className="w-8 h-8 flex items-center justify-center bg-blue-600 text-white rounded-lg text-xs font-bold shadow-md shadow-blue-100">1</button>
                    <button className="w-8 h-8 flex items-center justify-center bg-white text-slate-500 hover:text-blue-600 rounded-lg text-xs font-bold border border-slate-100 transition-all">2</button>
                    <button className="w-8 h-8 flex items-center justify-center bg-white text-slate-500 hover:text-blue-600 rounded-lg text-xs font-bold border border-slate-100 transition-all text-[8px]">...</button>
                    <button className="w-8 h-8 flex items-center justify-center bg-white text-slate-500 hover:text-blue-600 rounded-lg text-xs font-bold border border-slate-100 transition-all">10</button>
                </div>
                <button className="px-3 py-1.5 text-xs font-bold text-slate-500 hover:text-blue-600 transition-colors">Tiếp</button>
            </div>
        </div>
      </div>
    </div>
  );
};

export default AdminNewsList;
