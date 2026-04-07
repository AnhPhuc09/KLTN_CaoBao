import React, { useState } from 'react';
import { Link, useLocation } from 'react-router-dom';
import { 
  Home, 
  Newspaper, 
  User, 
  Settings, 
  LayoutDashboard, 
  MessageSquare, 
  Users, 
  Heart, 
  LogOut, 
  Search,
  Bell,
  Menu,
  X
} from 'lucide-react';

import Chatbot from './Chatbot';

interface SidebarItemProps {
  to: string;
  icon: React.ReactNode;
  label: string;
  active?: boolean;
}

const SidebarItem = ({ to, icon, label, active }: SidebarItemProps) => (
  <Link
    to={to}
    className={`flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group ${
      active 
        ? 'bg-blue-600 text-white shadow-lg shadow-blue-100' 
        : 'text-slate-500 hover:bg-blue-50 hover:text-blue-600'
    }`}
  >
    <span className={`${active ? 'text-white' : 'text-slate-400 group-hover:text-blue-600'}`}>
      {icon}
    </span>
    <span className="font-medium">{label}</span>
  </Link>
);

const AdminLayout: React.FC<{ children: React.ReactNode }> = ({ children }) => {
  const location = useLocation();
  const [isSidebarOpen, setIsSidebarOpen] = useState(false);

  const menuItems = [
    { to: '/', icon: <Home size={20} />, label: 'Trang chủ' },
    { to: '/favorites', icon: <Heart size={20} />, label: 'Tin yêu thích' },
    { to: '/profile', icon: <User size={20} />, label: 'Tài khoản' },
  ];

  const adminItems = [
    { to: '/admin', icon: <LayoutDashboard size={20} />, label: 'Dashboard' },
    { to: '/admin/news', icon: <Newspaper size={20} />, label: 'Quản lý tin' },
    { to: '/admin/users', icon: <Users size={20} />, label: 'Người dùng' },
  ];

  return (
    <div className="min-h-screen bg-background flex">
      <button 
        className="lg:hidden fixed top-4 left-4 z-50 p-2 bg-white rounded-lg shadow-md"
        onClick={() => setIsSidebarOpen(!isSidebarOpen)}
      >
        {isSidebarOpen ? <X size={24} /> : <Menu size={24} />}
      </button>

      <aside className={`fixed inset-y-0 left-0 z-40 w-64 bg-surface border-r border-slate-100 p-6 flex flex-col transition-transform duration-300 transform lg:translate-x-0 ${
        isSidebarOpen ? 'translate-x-0' : '-translate-x-full'
      }`}>
        <div className="mb-10 flex items-center gap-3 px-4">
          <div className="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center text-white font-bold">
            K
          </div>
          <span className="text-xl font-bold tracking-tight">KLTN News</span>
        </div>

        <nav className="flex-1 space-y-2">
          {menuItems.map((item) => (
            <SidebarItem 
              key={item.to} 
              {...item} 
              active={location.pathname === item.to} 
            />
          ))}

          <div className="pt-6 pb-2 px-4 uppercase text-[10px] font-bold text-slate-400 tracking-wider">
            Quản trị
          </div>
          {adminItems.map((item) => (
            <SidebarItem 
              key={item.to} 
              {...item} 
              active={location.pathname.startsWith(item.to)} 
            />
          ))}
        </nav>

        <div className="pt-6 border-t border-slate-100">
          <button className="flex items-center gap-3 px-4 py-3 text-slate-500 hover:text-red-500 w-full transition-colors">
            <LogOut size={20} />
            <span className="font-medium">Đăng xuất</span>
          </button>
        </div>
      </aside>

      <main className="flex-1 lg:ml-64 min-w-0 flex flex-col">
        <header className="h-16 bg-surface/80 backdrop-blur-md border-b border-slate-100 flex items-center justify-between px-6 sticky top-0 z-30">
          <div className="flex-1 max-w-md">
            <div className="relative group">
              <Search className="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-blue-600 transition-colors" size={18} />
              <input 
                type="text" 
                placeholder="Tìm kiếm tin tức..."
                className="w-full bg-slate-50 border-none rounded-xl pl-10 pr-4 py-2 focus:ring-2 focus:ring-blue-100 transition-all text-sm"
              />
            </div>
          </div>

          <div className="flex items-center gap-4">
            <button className="p-2 text-slate-500 hover:bg-slate-50 rounded-xl transition-colors relative">
              <Bell size={20} />
              <span className="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full border-2 border-white"></span>
            </button>
            <div className="h-8 w-px bg-slate-100 mx-2"></div>
            <div className="flex items-center gap-3 cursor-pointer hover:bg-slate-50 p-1 pr-3 rounded-xl transition-colors">
              <div className="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center font-bold">
                A
              </div>
              <div className="hidden sm:block text-left">
                <p className="text-xs font-bold leading-none">Phúc Cao</p>
                <p className="text-[10px] text-slate-400 leading-none mt-1">Quản trị viên</p>
              </div>
            </div>
          </div>
        </header>

        <div className="p-6 flex-1 overflow-auto">
          {children}
        </div>

        <Chatbot />
      </main>
    </div>
  );
};

export default AdminLayout;
