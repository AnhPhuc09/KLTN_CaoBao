import React, { useState } from 'react';
import { 
  User, Mail, Phone, Lock, Camera, Clock, Bookmark, 
  ChevronRight, X, LogOut, CheckCircle, Settings, Trash2,
  ListTodo, Heart, Bell, Shield, Download, Trash
} from 'lucide-react';
import { motion } from 'framer-motion';

const Profile = () => {
  const [activeTab, setActiveTab] = useState('overview');
  const userName = localStorage.getItem('user_name') || 'Người dùng';
  const userEmail = 'user@example.com';
  const userAvatar = localStorage.getItem('user_avatar');

  const categories = [
    { id: 'thoi-su', name: 'Thời Sự', checked: true },
    { id: 'kinh-doanh', name: 'Kinh Doanh', checked: true },
    { id: 'cong-nghe', name: 'Công Nghệ', checked: false },
    { id: 'the-thao', name: 'Thể Thao', checked: true },
    { id: 'giai-tri', name: 'Giải Trí', checked: false },
  ];

  const savedArticles = [
    {
      id: 1,
      title: "Bước nhảy vọt lượng tử: Định hướng kỷ nguyên tiếp theo",
      category: "CÔNG NGHỆ",
      image: "https://images.unsplash.com/photo-1635070041078-e363dbe005cb?q=80&w=2070&auto=format&fit=crop",
      time: "4 phút đọc",
    },
    {
      id: 2,
      title: "Chủ nghĩa tối giản như một hình thức phản kháng",
      category: "VĂN HOÁ",
      image: "https://images.unsplash.com/photo-1494438639946-1ebd1d20bf85?q=80&w=2067&auto=format&fit=crop",
      time: "6 phút đọc",
    }
  ];

  const history = [
      { id: 101, title: "Địa chính trị của chất bán dẫn", time: "HÔM NAY", info: "4 phút đọc • Thể thao & Kinh tế" },
      { id: 102, title: "Vì sao sàn diễn thời trang lại chuyển sang Metaverse?", time: "HÔM QUA", info: "4 phút đọc • Thể thao & Kinh tế" },
      { id: 103, title: "Sự hồi sinh của các khách sạn boutique", time: "24 Thẳng 10", info: "4 phút đọc • Thể thao & Kinh tế" },
      { id: 104, title: "Mười nghệ sĩ đáng chú ý trong năm 2025", time: "22 Thẳng 10", info: "4 phút đọc • Thể thao & Kinh tế" }
  ];

  return (
    <div className="max-w-7xl mx-auto px-6 py-12">
      <div className="flex flex-col lg:flex-row gap-8">
        
        <div className="w-full lg:w-1/3 space-y-6">
          <motion.div 
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            className="bg-white rounded-[2.5rem] p-10 border border-slate-100 shadow-sm text-center relative overflow-hidden group"
          >
            <div className="absolute top-0 left-0 w-full h-2 bg-blue-600"></div>
            
            <div className="relative inline-block mb-6">
              <div className="w-32 h-32 rounded-full border-4 border-slate-50 overflow-hidden mx-auto shadow-xl">
                {userAvatar ? (
                  <img src={userAvatar} alt="Profile" className="w-full h-full object-cover" />
                ) : (
                  <div className="w-full h-full bg-blue-600/10 flex items-center justify-center text-blue-600 text-4xl font-black">
                    {userName.charAt(0).toUpperCase()}
                  </div>
                )}
              </div>
              <button className="absolute bottom-1 right-1 w-10 h-10 bg-white shadow-lg rounded-full flex items-center justify-center text-slate-400 hover:text-blue-600 transition-colors border border-slate-100">
                <Camera size={18} />
              </button>
            </div>

            <h2 className="text-2xl font-black text-slate-900 mb-1 tracking-tight">{userName}</h2>
            <p className="text-slate-400 font-bold text-xs uppercase tracking-widest mb-8">{userEmail}</p>

            <button className="w-full bg-blue-600 text-white py-4 rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl shadow-blue-100 hover:bg-blue-700 transition-all active:scale-95">
              Chỉnh sửa sở thích
            </button>
          </motion.div>

          <motion.div 
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ delay: 0.1 }}
            className="bg-white rounded-[2.5rem] p-10 border border-slate-100 shadow-sm"
          >
            <h3 className="text-sm font-black text-slate-900 uppercase tracking-widest mb-8 border-b border-slate-50 pb-4">Sở thích chuyên mục</h3>
            <div className="space-y-4">
              {categories.map((cat) => (
                <label key={cat.id} className="flex items-center justify-between cursor-pointer group">
                  <span className="text-sm font-bold text-slate-500 group-hover:text-slate-900 transition-colors">{cat.name}</span>
                  <div className="relative">
                    <input type="checkbox" defaultChecked={cat.checked} className="peer hidden" />
                    <div className="w-5 h-5 border-2 border-slate-200 rounded-md peer-checked:bg-blue-600 peer-checked:border-blue-600 transition-all flex items-center justify-center">
                        <CheckCircle size={14} className="text-white opacity-0 peer-checked:opacity-100 transition-opacity" />
                    </div>
                  </div>
                </label>
              ))}
            </div>
          </motion.div>
        </div>

        <div className="flex-1 space-y-8">
          
          <section>
            <div className="flex items-center justify-between mb-6">
              <h3 className="text-xl font-black text-slate-900 tracking-tight">Bài viết đã lưu</h3>
              <button className="text-[10px] font-black text-blue-600 uppercase tracking-widest hover:underline">Xem tất cả</button>
            </div>
            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
              {savedArticles.map((article) => (
                <motion.div 
                   key={article.id}
                   whileHover={{ y: -5 }}
                   className="bg-white rounded-[2rem] overflow-hidden border border-slate-100 shadow-sm group cursor-pointer"
                >
                  <div className="h-48 overflow-hidden relative">
                    <img src={article.image} alt={article.title} className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" />
                    <div className="absolute top-4 left-4 bg-white/90 backdrop-blur-md px-3 py-1 rounded-lg text-[9px] font-black tracking-widest uppercase text-slate-900">
                      {article.category}
                    </div>
                  </div>
                  <div className="p-6">
                    <h4 className="text-md font-black text-slate-900 leading-snug mb-3 group-hover:text-blue-600 transition-colors line-clamp-2">
                       {article.title}
                    </h4>
                    <div className="flex items-center gap-2 text-[10px] font-bold text-slate-400">
                      <Clock size={12} />
                      {article.time}
                    </div>
                  </div>
                </motion.div>
              ))}
            </div>
          </section>

          <section>
            <div className="flex items-center justify-between mb-6">
              <h3 className="text-xl font-black text-slate-900 tracking-tight">Lịch sử đã xem</h3>
            </div>
            <div className="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
              <div className="divide-y divide-slate-50">
                {history.map((record) => (
                  <div key={record.id} className="p-6 flex items-center justify-between hover:bg-slate-50 transition-colors group">
                    <div className="flex items-start gap-6">
                      <div className="text-[10px] font-black text-slate-300 uppercase tracking-widest w-20 pt-1">
                        {record.time}
                      </div>
                      <div>
                        <h4 className="font-bold text-slate-900 mb-1 group-hover:text-blue-600 transition-colors">{record.title}</h4>
                        <p className="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{record.info}</p>
                      </div>
                    </div>
                    <button className="text-slate-300 hover:text-red-500 transition-colors">
                      <Trash2 size={16} />
                    </button>
                  </div>
                ))}
              </div>
              <button className="w-full py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-t border-slate-50 hover:bg-slate-50 transition-all">
                Tải thêm lịch sử
              </button>
            </div>
          </section>

          <section className="bg-blue-600/5 rounded-[2.5rem] p-8 border border-blue-600/10 flex flex-col md:flex-row items-center justify-between gap-6">
              <div className="flex items-center gap-4">
                  <div className="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-blue-600 shadow-sm border border-blue-600/10">
                      <Shield size={24} />
                  </div>
                  <div>
                      <h4 className="font-black text-slate-900 tracking-tight">Cài đặt bảo mật</h4>
                      <p className="text-xs font-medium text-slate-500">Quản lý mật khẩu và quyền riêng tư của bạn</p>
                  </div>
              </div>
              <button className="px-6 py-3 bg-white text-blue-600 rounded-xl font-black text-[10px] uppercase tracking-widest shadow-sm hover:shadow-md transition-all">Cập nhật ngay</button>
          </section>

        </div>
      </div>
    </div>
  );
};

export default Profile;
