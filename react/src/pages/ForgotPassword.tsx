import React, { useState } from 'react';
import { Link } from 'react-router-dom';
import { ArrowLeft, ShieldCheck, CheckCircle2, Send } from 'lucide-react';
import { motion } from 'framer-motion';

const ForgotPassword = () => {
  const [email, setEmail] = useState('');
  const [error, setError] = useState('');
  const [loading, setLoading] = useState(false);
  const [successMsg, setSuccessMsg] = useState('');

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    
    if (!email.trim()) {
      setError("Email bắt buộc phải nhập");
      return;
    }
    
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email.trim())) {
      setError("Email không đúng định dạng");
      return;
    }

    setError('');
    setSuccessMsg('');
    setLoading(true);

    try {
      const response = await fetch('http://localhost/KLTN_CaoBao/BE/?module=api&action=forgot', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({ email: email.trim() })
      });
      
      const data = await response.json();
      
      if (data.status === 'success') {
        setSuccessMsg(data.msg);
      } else {
        setError(data.msg || 'Đã có lỗi xảy ra!');
      }
    } catch (err) {
      setError('Lỗi kết nối đến máy chủ. Vui lòng thử lại!');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="min-h-screen bg-white flex overflow-hidden">
      <div className="hidden lg:flex lg:w-1/2 relative bg-slate-900 items-center justify-center p-12">
        <div className="absolute inset-0 overflow-hidden">
          <img 
            src="https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?q=80&w=1964&auto=format&fit=crop" 
            alt="Abstract Art" 
            className="w-full h-full object-cover opacity-40 brightness-75 scale-110"
          />
          <div className="absolute inset-0 bg-gradient-to-br from-slate-900/80 via-transparent to-blue-600/20"></div>
          
          <div className="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] border border-white/10 rounded-full animate-[spin_20s_linear_infinite]"></div>
          <div className="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[400px] h-[400px] border border-white/5 rounded-full animate-[spin_15s_linear_infinite_reverse]"></div>
        </div>

        <div className="relative z-10 max-w-lg text-center">
            <motion.div 
                initial={{ opacity: 0, y: 20 }}
                animate={{ opacity: 1, y: 0 }}
                className="w-16 h-16 bg-white/10 backdrop-blur-xl rounded-2xl flex items-center justify-center border border-white/20 mx-auto mb-8 shadow-2xl"
            >
                <ShieldCheck size={32} className="text-white" />
            </motion.div>
            <motion.h1 
                initial={{ opacity: 0, y: 20 }}
                animate={{ opacity: 1, y: 0 }}
                transition={{ delay: 0.1 }}
                className="text-4xl font-black text-white mb-6 tracking-tight leading-tight"
            >
                Khôi phục quyền truy cập nhanh chóng
            </motion.h1>
            <motion.p 
                initial={{ opacity: 0, y: 20 }}
                animate={{ opacity: 1, y: 0 }}
                transition={{ delay: 0.2 }}
                className="text-slate-300 text-lg font-medium leading-relaxed"
            >
                Nhập email của bạn để nhận liên kết đặt lại mật khẩu an toàn.
            </motion.p>
            
            <motion.div 
                initial={{ opacity: 0 }}
                animate={{ opacity: 1 }}
                transition={{ delay: 0.4 }}
                className="mt-12 flex flex-col gap-4 text-left"
            >
                {['Bảo mật thông tin tối đa', 'Xác thực nhanh chóng', 'Hỗ trợ kỹ thuật 24/7'].map((text, idx) => (
                    <div key={idx} className="flex items-center gap-3 text-slate-300 text-sm font-bold bg-white/5 backdrop-blur-md p-4 rounded-2xl border border-white/5 hover:bg-white/10 transition-all cursor-default">
                        <CheckCircle2 size={18} className="text-blue-600" />
                        {text}
                    </div>
                ))}
            </motion.div>
        </div>
      </div>

      <div className="w-full lg:w-1/2 flex items-center justify-center p-8 lg:p-20 bg-background relative overflow-y-auto">
        <div className="absolute -top-20 -right-20 w-64 h-64 bg-blue-600/5 rounded-full blur-3xl"></div>
        <div className="absolute -bottom-20 -left-20 w-64 h-64 bg-blue-600/5 rounded-full blur-3xl"></div>

        <motion.div 
          initial={{ opacity: 0, x: 20 }}
          animate={{ opacity: 1, x: 0 }}
          className="w-full max-w-md relative z-10"
        >
          <div className="lg:hidden flex justify-center mb-10">
              <div className="w-12 h-12 bg-blue-600 rounded-2xl flex items-center justify-center text-white font-black shadow-xl shadow-blue-200">K</div>
          </div>

          <Link to="/login" className="inline-flex items-center gap-2 text-xs font-black text-slate-400 hover:text-blue-600 uppercase tracking-widest transition-colors mb-8">
            <ArrowLeft size={16} />
            Quay lại đăng nhập
          </Link>

          <div className="mb-12 text-center">
            <h2 className="text-4xl font-black text-slate-900 tracking-tighter mb-2">Quên mật khẩu?</h2>
            <p className="text-slate-400 font-bold text-sm tracking-tight uppercase px-4 leading-relaxed">
              Nhập địa chỉ email đăng ký để nhận liên kết khôi phục
            </p>
          </div>

          {successMsg ? (
            <motion.div 
              initial={{ opacity: 0, scale: 0.95 }}
              animate={{ opacity: 1, scale: 1 }}
              className="bg-green-50 border border-green-100 rounded-2xl p-6 text-center"
            >
              <div className="w-16 h-16 bg-green-100 text-green-500 rounded-full flex items-center justify-center mx-auto mb-4">
                <CheckCircle2 size={32} />
              </div>
              <h3 className="text-xl font-black text-slate-800 mb-2">Kiểm tra hộp thư!</h3>
              <p className="text-sm font-medium text-slate-600 mb-6 leading-relaxed">
                {successMsg}
              </p>
              <button 
                onClick={() => setSuccessMsg('')}
                className="w-full bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 py-4 rounded-xl font-black text-sm uppercase tracking-widest transition-all active:scale-95 flex items-center justify-center"
              >
                Gửi lại email
              </button>
            </motion.div>
          ) : (
            <form className="space-y-6" onSubmit={handleSubmit}>
              <div className="space-y-2 flex flex-col">
                <label className="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Địa chỉ Email</label>
                <div className="relative">
                  {error && (
                      <motion.div 
                        initial={{ opacity: 0, scale: 0.95 }}
                        animate={{ opacity: 1, scale: 1 }}
                        className="absolute -top-2.5 right-4 bg-red-50 text-red-600 text-[9px] uppercase tracking-widest font-black px-2 py-0.5 rounded border border-red-100 shadow-sm z-10"
                      >
                        {error}
                      </motion.div>
                  )}
                  <input 
                    type="text" 
                    value={email}
                    onChange={(e) => {
                      setEmail(e.target.value);
                      if (error) setError('');
                    }}
                    placeholder="name@gmail.com"
                    disabled={loading}
                    className={`w-full bg-slate-50 border ${error ? 'border-red-300 focus:ring-red-50 focus:border-red-500' : 'border-slate-100 focus:ring-blue-50 focus:border-blue-600'} rounded-[1.25rem] px-6 py-4 focus:bg-white focus:ring-4 transition-all text-sm font-medium outline-none ${loading ? 'opacity-50 cursor-not-allowed' : ''}`}
                  />
                </div>
              </div>

              <button 
                disabled={loading}
                className={`w-full bg-blue-600 hover:bg-blue-700 text-white py-5 rounded-[1.25rem] font-black text-sm uppercase tracking-widest shadow-2xl shadow-blue-200 transition-all ${loading ? 'opacity-70 cursor-not-allowed' : 'active:scale-95'} flex items-center justify-center gap-3`}
              >
                {loading ? 'Đang gửi...' : 'Gửi yêu cầu'}
                {!loading && <Send size={20} />}
              </button>
            </form>
          )}

        </motion.div>
      </div>
    </div>
  );
};

export default ForgotPassword;
