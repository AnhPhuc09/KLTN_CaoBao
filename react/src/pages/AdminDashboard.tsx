import React from 'react';
import { 
  BarChart, 
  Bar, 
  XAxis, 
  YAxis, 
  CartesianGrid, 
  Tooltip, 
  ResponsiveContainer,
  PieChart,
  Pie,
  Cell,
  LineChart,
  Line
} from 'recharts';
import { 
  TrendingUp, 
  Users, 
  Newspaper, 
  MessageSquare, 
  Eye, 
  ArrowUpRight, 
  ArrowDownRight,
  Calendar,
  Search,
  Bell,
  MoreHorizontal,
  Clock
} from 'lucide-react';
import { motion } from 'framer-motion';

const dataBar = [
  { name: '01 T4', value: 400 },
  { name: '05 T4', value: 300 },
  { name: '10 T4', value: 600 },
  { name: '15 T4', value: 800 },
  { name: '20 T4', value: 500 },
  { name: '25 T4', value: 900 },
  { name: '30 T4', value: 700 },
];

const dataPie = [
  { name: 'Kinh tế', value: 35, color: '#2563EB' },
  { name: 'Công nghệ', value: 25, color: '#3B82F6' },
  { name: 'Giải trí', value: 20, color: '#93C5FD' },
  { name: 'Khác', value: 20, color: '#DBEAFE' },
];

const StatCard = ({ title, value, change, icon: Icon, color }: any) => (
  <div className="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all group">
    <div className="flex justify-between items-start mb-6">
      <div className={`p-4 rounded-2xl ${color} bg-opacity-10 text-opacity-100 ring-4 ring-slate-50 transition-all group-hover:ring-blue-600/10`}>
        <Icon size={24} />
      </div>
      <div className={`flex items-center gap-1.5 px-2 py-1 rounded-full text-[10px] font-black uppercase tracking-widest ${change >= 0 ? 'bg-green-50 text-green-600 border border-green-100' : 'bg-red-50 text-red-600 border border-red-100'}`}>
        {change >= 0 ? <ArrowUpRight size={14} /> : <ArrowDownRight size={14} />}
        {Math.abs(change)}%
      </div>
    </div>
    <h3 className="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] mb-2">{title}</h3>
    <p className="text-3xl font-black text-slate-900 tracking-tighter">{value}</p>
  </div>
);

const AdminDashboard = () => {
  return (
    <div className="space-y-10 animate-in fade-in duration-700 max-w-[1600px] mx-auto pb-20">
      <div className="flex flex-col md:flex-row md:items-center justify-between gap-6 px-2">
        <div>
          <h1 className="text-3xl font-black text-slate-900 tracking-tighter uppercase flex items-center gap-3">
             Thống kê & Phân tích
             <span className="inline-block w-2.5 h-2.5 bg-green-400 rounded-full animate-pulse shadow-[0_0_10px_rgba(74,222,128,0.5)]"></span>
          </h1>
          <p className="text-slate-400 text-xs font-black uppercase tracking-[0.2em] mt-2">Chào mừng Linh Chi (Quản trị viên Cao Cấp)</p>
        </div>
        <div className="flex items-center gap-4">
            <div className="bg-white px-5 py-3 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-3 text-xs font-black text-slate-600 uppercase tracking-widest">
                <Calendar size={18} className="text-blue-600" />
                <span>Tháng 04, 2026</span>
            </div>
            <button className="bg-blue-600 hover:bg-blue-700 text-white p-3.5 rounded-2xl shadow-xl shadow-blue-200 transition-all active:scale-90">
                <Bell size={20} />
            </button>
        </div>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
        <StatCard title="Tổng lượt truy cập" value="1,240,500" change={15.2} icon={Eye} color="text-blue-600 bg-blue-600" />
        <StatCard title="Tổng số bài báo" value="15,842" change={8.5} icon={Newspaper} color="text-purple-600 bg-purple-600" />
        <StatCard title="Thời gian đọc tb" value="4m 32s" change={-2.4} icon={Clock} color="text-orange-600 bg-orange-600" />
        <StatCard title="Tỷ lệ tương tác" value="18.5%" change={24.1} icon={TrendingUp} color="text-green-600 bg-green-600" />
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <div className="lg:col-span-8 bg-white p-10 rounded-[3rem] border border-slate-100 shadow-sm relative overflow-hidden group hover:shadow-2xl hover:shadow-slate-200/50 transition-all">
          <div className="flex items-center justify-between mb-12">
            <div>
              <h3 className="font-black text-slate-900 uppercase tracking-[0.22em] text-xs mb-1">Xu hướng lượt truy cập</h3>
              <p className="text-slate-300 text-[10px] font-bold uppercase tracking-widest">Phân tích dữ liệu trong 30 ngày qua</p>
            </div>
            <div className="flex gap-2 bg-slate-50 p-1.5 rounded-xl border border-slate-100">
                <button className="px-4 py-1.5 bg-white text-blue-600 text-[10px] font-black uppercase tracking-widest rounded-lg shadow-sm">30 Ngày</button>
                <button className="px-4 py-1.5 text-slate-400 text-[10px] font-black uppercase tracking-widest rounded-lg hover:text-slate-600">7 Ngày</button>
            </div>
          </div>
          
          <div className="h-[350px] w-full">
            <ResponsiveContainer width="100%" height="100%">
              <BarChart data={dataBar}>
                <CartesianGrid strokeDasharray="3 3" vertical={false} stroke="#F1F5F9" />
                <XAxis 
                    dataKey="name" 
                    axisLine={false} 
                    tickLine={false} 
                    tick={{ fontSize: 10, fontWeight: 900, fill: '#CBD5E1' }}
                    dy={15}
                />
                <YAxis 
                    axisLine={false} 
                    tickLine={false} 
                    tick={{ fontSize: 10, fontWeight: 900, fill: '#CBD5E1' }}
                />
                <Tooltip 
                    cursor={{ fill: 'rgba(37, 99, 235, 0.05)' }} 
                    contentStyle={{ borderRadius: '16px', border: 'none', boxShadow: '0 20px 40px rgba(15, 23, 42, 0.1)', fontWeight: 900, textTransform: 'uppercase', fontSize: '10px' }}
                />
                <Bar 
                    dataKey="value" 
                    fill="#2563EB" 
                    radius={[12, 12, 4, 4]} 
                    barSize={40}
                    className="opacity-90 hover:opacity-100 transition-opacity"
                />
              </BarChart>
            </ResponsiveContainer>
          </div>
        </div>

        <div className="lg:col-span-4 bg-white p-10 rounded-[3rem] border border-slate-100 shadow-sm hover:shadow-2xl transition-all">
          <h3 className="font-black text-slate-900 uppercase tracking-[0.22em] text-xs mb-10 text-center">Chuyên mục phổ biến</h3>
          <div className="h-[300px] w-full flex items-center justify-center relative">
            <ResponsiveContainer width="100%" height="100%">
              <PieChart>
                <Pie
                  data={dataPie}
                  innerRadius={80}
                  outerRadius={110}
                  paddingAngle={8}
                  dataKey="value"
                >
                  {dataPie.map((entry, index) => (
                    <Cell key={`cell-${index}`} fill={entry.color} stroke="none" />
                  ))}
                </Pie>
                <Tooltip />
              </PieChart>
            </ResponsiveContainer>
            <div className="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                <span className="text-3xl font-black text-slate-900 tracking-tighter">852</span>
                <span className="text-[10px] font-black text-slate-400 uppercase tracking-widest">BÀI VIẾT</span>
            </div>
          </div>
          <div className="grid grid-cols-2 gap-4 mt-8">
            {dataPie.map((item) => (
                <div key={item.name} className="flex flex-col gap-1.5 p-3 rounded-2xl bg-slate-50/50 border border-slate-50">
                    <div className="flex items-center gap-2">
                        <div className="w-2.5 h-2.5 rounded-full" style={{ backgroundColor: item.color }}></div>
                        <span className="text-[10px] font-black text-slate-500 uppercase tracking-widest">{item.name}</span>
                    </div>
                    <span className="text-sm font-black text-slate-900 pl-4">{item.value}%</span>
                </div>
            ))}
          </div>
        </div>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <div className="lg:col-span-8 bg-white p-10 rounded-[3rem] border border-slate-100 shadow-sm">
            <div className="flex items-center justify-between mb-10">
                <h3 className="font-black text-slate-900 uppercase tracking-[0.22em] text-xs">Top 5 bài báo nổi bật trong tuần</h3>
                <button className="text-[10px] font-black text-blue-600 hover:underline uppercase tracking-tighter">Xem tất cả</button>
            </div>
            <div className="space-y-6">
                {[1, 2, 3, 4, 5].map((i) => (
                    <div key={i} className="flex items-center gap-6 p-4 hover:bg-slate-50 rounded-[1.5rem] transition-all group">
                        <div className="w-14 h-14 bg-slate-100 rounded-2xl overflow-hidden flex-shrink-0 border border-slate-200">
                             <img src={`https://picsum.photos/seed/${i+100}/100`} className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" alt="" />
                        </div>
                        <div className="flex-1 min-w-0">
                            <h4 className="text-sm font-black text-slate-800 line-clamp-1 group-hover:text-blue-600 transition-colors mb-2 uppercase tracking-tight">Tương lai của AI trong báo chí Việt Nam: Thách thức và cơ hội mới</h4>
                            <div className="flex items-center gap-5">
                                <span className="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Cao Bảo Phúc</span>
                                <div className="px-2 py-0.5 bg-blue-100 text-blue-600 rounded-lg text-[9px] font-black uppercase tracking-tighter">Công Nghệ</div>
                            </div>
                        </div>
                        <div className="text-right flex flex-col gap-1 items-end pr-4">
                            <span className="text-sm font-black text-slate-900 tracking-tighter">12,502</span>
                            <span className="text-[9px] font-black text-green-500 uppercase tracking-tighter">+18.5%</span>
                        </div>
                        <button className="p-3 text-slate-300 hover:text-blue-600 hover:bg-white rounded-xl transition-all shadow-sm group-hover:border-slate-100 border border-transparent">
                            <ArrowUpRight size={18} />
                        </button>
                    </div>
                ))}
            </div>
        </div>

        <div className="lg:col-span-4 bg-white p-10 rounded-[3rem] border border-slate-100 shadow-sm">
            <h3 className="font-black text-slate-900 uppercase tracking-[0.22em] text-xs mb-10">Nguồn truy cập</h3>
            <div className="space-y-8">
                {[
                    { name: 'Truy cập trực tiếp', value: 45, color: 'bg-blue-600' },
                    { name: 'Tìm kiếm tự nhiên', value: 32, color: 'bg-blue-400' },
                    { name: 'Mạng xã hội', value: 23, color: 'bg-blue-200' },
                ].map((item) => (
                    <div key={item.name} className="space-y-3">
                        <div className="flex justify-between items-center text-[10px] font-black uppercase tracking-widest">
                            <span className="text-slate-500">{item.name}</span>
                            <span className="text-slate-900">{item.value}%</span>
                        </div>
                        <div className="h-2 w-full bg-slate-50 rounded-full overflow-hidden border border-slate-100/50 p-0.5">
                            <div className={`h-full ${item.color} rounded-full`} style={{ width: `${item.value}%` }}></div>
                        </div>
                    </div>
                ))}
            </div>
            
            <div className="mt-14 p-8 bg-[#F9F9FC] rounded-[2rem] border border-slate-100 relative overflow-hidden group">
                <div className="absolute -top-10 -right-10 w-32 h-32 bg-blue-600/10 rounded-full blur-3xl transition-transform group-hover:scale-150"></div>
                <div className="relative z-10 flex flex-col items-center text-center">
                    <TrendingUp className="text-blue-600 mb-6 animate-bounce" size={32} />
                    <h4 className="text-base font-black text-slate-900 mb-4 tracking-tight">KẾT LUẬN PHÂN TÍCH</h4>
                    <p className="text-xs font-bold text-slate-400 leading-relaxed uppercase tracking-wider">Lượt truy cập trực tiếp đang tăng ổn định nhờ vào các chiến dịch Gemini AI.</p>
                </div>
            </div>
        </div>
      </div>
    </div>
  );
};

export default AdminDashboard;
