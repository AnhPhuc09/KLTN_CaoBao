import React from 'react';
import { Link, useLocation } from 'react-router-dom';
import { Search, Bell, User, Sparkles, MessageSquare } from 'lucide-react';
import Chatbot from './Chatbot';

const PublicLayout: React.FC<{ children: React.ReactNode }> = ({ children }) => {
  const location = useLocation();

  const categories = [
    { name: 'Thời Sự', to: '/?category=thoi-su' },
    { name: 'Pháp Luật', to: '/?category=phap-luat' },
    { name: 'Tin Trong Nước', to: '/?category=tin-trong-nuoc' },
    { name: 'Tin Thế Giới', to: '/?category=tin-the-gioi' },
    { name: 'Giải Trí', to: '/?category=giai-tri' },
    { name: 'Showbit', to: '/?category=show-bit' },
    { name: 'Ca Sỹ', to: '/?category=ca-sy' }
  ];

  return (
    <div className="min-h-screen bg-[#F9F9FC] flex flex-col font-inter">
      <header className="bg-white border-b border-slate-100 flex items-center sticky top-0 z-50 shadow-sm shadow-slate-100/50">
        <div className="max-w-7xl mx-auto w-full px-6 h-20 flex items-center justify-between">
          <Link to="/" className="flex items-center gap-2 group">
            <div className="text-2xl font-black tracking-tighter text-slate-900 group-hover:text-blue-600 transition-colors uppercase">
               KLTN <span className="text-blue-600 italic">NEWS</span>
            </div>
          </Link>

          <nav className="hidden lg:flex items-center gap-8 overflow-x-auto flex-1 mx-8 px-2" style={{ scrollbarWidth: 'none', msOverflowStyle: 'none' }}>
            <style>{`.hidden.lg\\:flex::-webkit-scrollbar { display: none; }`}</style>
            {categories.map((cat) => (
              <Link 
                key={cat.name} 
                to={cat.to}
                className="text-xs font-black text-slate-400 hover:text-blue-600 uppercase tracking-widest transition-all whitespace-nowrap flex-shrink-0"
              >
                {cat.name}
              </Link>
            ))}
          </nav>

          <div className="flex items-center gap-4 lg:gap-8">
            <div className="relative group hidden md:block">
              <Search className="absolute left-3 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-blue-600 transition-colors" size={16} />
              <input 
                type="text" 
                placeholder="Tìm kiếm..."
                className="bg-slate-50 border-none rounded-xl pl-10 pr-4 py-2 text-xs font-bold w-56 focus:w-80 transition-all focus:ring-2 focus:ring-blue-50 outline-none"
              />
            </div>

            <div className="flex items-center gap-4">
              {localStorage.getItem('auth_token') ? (
                <>
                  <Link to="/profile" title={localStorage.getItem('user_name') || 'Tài khoản'} className="w-10 h-10 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center font-black shadow-sm overflow-hidden hover:ring-2 hover:ring-blue-100 transition-all active:scale-95">
                    {localStorage.getItem('user_avatar') ? (
                      <img src={localStorage.getItem('user_avatar')!} alt="Avatar" className="w-full h-full object-cover" />
                    ) : (
                      localStorage.getItem('user_name') ? localStorage.getItem('user_name')!.charAt(0).toUpperCase() : 'U'
                    )}
                  </Link>
                  <button 
                    onClick={() => {
                        localStorage.removeItem('auth_token');
                        localStorage.removeItem('user_name');
                        localStorage.removeItem('user_avatar');
                        window.location.reload();
                    }}
                    className="ml-2 px-3 py-1.5 text-[10px] text-slate-500 font-black hover:text-white hover:bg-red-500 rounded-lg uppercase transition-all"
                  >
                    Thoát
                  </button>
                </>
              ) : (
                <>
                  <Link to="/login" className="text-xs font-black text-slate-900 bg-slate-100 hover:bg-blue-600 hover:text-white px-5 py-2.5 rounded-xl transition-all uppercase tracking-widest active:scale-95">
                      Đăng nhập
                  </Link>
                  <Link to="/register" className="hidden sm:block text-xs font-black text-white bg-blue-600 hover:bg-blue-700 px-5 py-2.5 rounded-xl shadow-lg shadow-blue-200 transition-all uppercase tracking-widest active:scale-95">
                      Đăng ký
                  </Link>
                </>
              )}
            </div>
          </div>
        </div>
      </header>

      <main className="flex-1">
        {children}
      </main>

      <footer className="bg-white border-t border-slate-100 py-16">
        <div className="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-12 gap-16">
          <div className="md:col-span-5">
            <div className="text-xl font-black text-slate-900 mb-6 uppercase tracking-tighter">
               KLTN<span className="text-blue-600 italic">NEWS</span>
            </div>
            <p className="text-slate-400 text-sm leading-relaxed max-w-sm font-medium mb-8">
                Hệ thống tin tức thông minh dựa trên nền tảng Gemini AI, cung cấp thông tin đa chiều và cá nhân hóa trải nghiệm người dùng mọi lúc mọi nơi.
            </p>
            <div className="flex items-center gap-4">
                {[1,2,3,4].map(i => <div key={i} className="w-10 h-10 bg-slate-50 text-slate-400 rounded-xl hover:bg-blue-600 hover:text-white transition-all cursor-pointer flex items-center justify-center border border-slate-100 shadow-sm shadow-slate-100"></div>)}
            </div>
          </div>

          <div className="md:col-span-3">
            <h4 className="font-black text-slate-900 mb-8 uppercase text-[10px] tracking-[0.2em]">KHÁM PHÁ</h4>
            <ul className="space-y-4 text-xs font-black text-slate-400 uppercase tracking-widest">
                <li className="hover:text-blue-600 cursor-pointer transition-colors">Về chúng tôi</li>
                <li className="hover:text-blue-600 cursor-pointer transition-colors">Đội ngũ biên tập</li>
                <li className="hover:text-blue-600 cursor-pointer transition-colors">Tuyển dụng</li>
                <li className="hover:text-blue-600 cursor-pointer transition-colors">Liên hệ</li>
            </ul>
          </div>

          <div className="md:col-span-4">
            <h4 className="font-black text-slate-900 mb-8 uppercase text-[10px] tracking-[0.2em]">PHÁP LÝ</h4>
            <ul className="space-y-4 text-xs font-black text-slate-400 uppercase tracking-widest">
                <li className="hover:text-blue-600 cursor-pointer transition-colors">Điều khoản bảo mật</li>
                <li className="hover:text-blue-600 cursor-pointer transition-colors">Bản quyền nội dung</li>
                <li className="hover:text-blue-600 cursor-pointer transition-colors">Quảng cáo</li>
            </ul>
          </div>
        </div>
        <div className="max-w-7xl mx-auto px-6 mt-16 pt-8 border-t border-slate-50 flex flex-col md:flex-row items-center justify-between gap-4">
            <p className="text-[10px] font-black text-slate-300 uppercase tracking-widest">© 2026 KLTN NEWS. ALL RIGHTS RESERVED.</p>
            <div className="flex items-center gap-2">
                <div className="w-2 h-2 bg-green-400 rounded-full animate-pulse shadow-[0_0_8px_rgba(74,222,128,0.5)]"></div>
                <p className="text-[10px] font-black text-slate-400 uppercase tracking-widest">Gemini 2.0 Connected</p>
            </div>
        </div>
      </footer>

      <Chatbot />
    </div>
  );
};

export default PublicLayout;
