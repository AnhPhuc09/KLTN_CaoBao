import React, { useState, useRef, useEffect } from 'react';
import axios from 'axios';
import { 
  MessageSquare, 
  X, 
  Send, 
  Trash2, 
  Bot, 
  User, 
  Loader2, 
  Sparkles,
  Maximize2,
  Minimize2
} from 'lucide-react';
import { motion, AnimatePresence } from 'framer-motion';

interface Message {
  role: 'user' | 'bot';
  content: string;
  timestamp: Date;
}

const Chatbot = () => {
  const [isOpen, setIsOpen] = useState(false);
  const [isExpanded, setIsExpanded] = useState(false);
  const [input, setInput] = useState('');
  const [messages, setMessages] = useState<Message[]>([]);
  const [loading, setLoading] = useState(false);
  const chatEndRef = useRef<HTMLDivElement>(null);

  const scrollToBottom = () => {
    chatEndRef.current?.scrollIntoView({ behavior: 'smooth' });
  };

  useEffect(() => {
    scrollToBottom();
  }, [messages, loading]);

  const handleSend = async () => {
    if (!input.trim() || loading) return;

    const userMessage: Message = {
      role: 'user',
      content: input,
      timestamp: new Date()
    };

    setMessages(prev => [...prev, userMessage]);
    setInput('');
    setLoading(true);

    try {
      const host = window.location.hostname === 'localhost' ? 'http://localhost/KLTN_CaoBao' : '';
      const response = await axios.post(`${host}/BE/modules/api/news_chat_ai.php`, {
        prompt: input
      }, {
        headers: {
          'Content-Type': 'application/json'
        }
      });

      const botMessage: Message = {
        role: 'bot',
        content: response.data.content || 'Xin lỗi, tôi gặp sự cố khi xử lý yêu cầu của bạn.',
        timestamp: new Date()
      };

      setMessages(prev => [...prev, botMessage]);
    } catch (error) {
      console.error('Error in AI Chat:', error);
      setMessages(prev => [...prev, {
        role: 'bot',
        content: 'Lỗi kết nối API. Vui lòng thử lại sau.',
        timestamp: new Date()
      }]);
    } finally {
      setLoading(false);
    }
  };

  const clearChat = () => {
    setMessages([]);
  };

  return (
    <div className="fixed bottom-8 right-8 z-[100] flex flex-col items-end">
      <AnimatePresence>
        {isOpen && (
          <motion.div
            initial={{ opacity: 0, y: 20, scale: 0.9 }}
            animate={{ opacity: 1, y: 0, scale: 1 }}
            exit={{ opacity: 0, y: 20, scale: 0.9 }}
            className={`bg-white rounded-3xl shadow-2xl border border-slate-100 flex flex-col overflow-hidden mb-4 ${
              isExpanded ? 'w-[450px] h-[650px]' : 'w-[380px] h-[550px]'
            }`}
          >
            <div className="bg-gradient-to-r from-blue-600 to-indigo-600 p-5 text-white flex items-center justify-between shadow-lg">
              <div className="flex items-center gap-3">
                <div className="w-10 h-10 bg-white/20 backdrop-blur-md rounded-xl flex items-center justify-center border border-white/10 ring-4 ring-white/5 animate-pulse">
                  <Bot size={22} className="text-white" />
                </div>
                <div>
                  <h4 className="font-bold text-sm leading-none">Hỏi đáp AI</h4>
                  <div className="flex items-center gap-1.5 mt-1.5">
                    <div className="w-2 h-2 bg-green-400 rounded-full animate-pulse shadow-[0_0_8px_rgba(74,222,128,0.5)]"></div>
                    <span className="text-[10px] font-bold text-white/80 uppercase tracking-widest">Đang trực tuyến</span>
                  </div>
                </div>
              </div>
              <div className="flex items-center gap-1">
                <button 
                  onClick={() => setIsExpanded(!isExpanded)}
                  className="p-2 hover:bg-white/10 rounded-lg transition-colors"
                >
                  {isExpanded ? <Minimize2 size={18} /> : <Maximize2 size={18} />}
                </button>
                <button 
                  onClick={clearChat}
                  className="p-2 hover:bg-white/10 rounded-lg transition-colors"
                  title="Xóa lịch sử"
                >
                  <Trash2 size={18} />
                </button>
                <button 
                  onClick={() => setIsOpen(false)}
                  className="p-2 hover:bg-white/10 rounded-lg transition-colors ml-1"
                >
                  <X size={18} />
                </button>
              </div>
            </div>

            <div className="flex-1 overflow-y-auto p-5 bg-slate-50/50 space-y-6 scrollbar-hide no-scrollbar">
              {messages.length === 0 && (
                <div className="h-full flex flex-col items-center justify-center text-center px-6">
                  <div className="w-16 h-16 bg-blue-600/10 rounded-3xl flex items-center justify-center text-blue-600 mb-4 animate-bounce">
                    <Sparkles size={32} />
                  </div>
                  <h5 className="font-bold text-slate-800 mb-2 italic">Xin chào! Tôi có thể giúp gì cho bạn?</h5>
                  <p className="text-slate-400 text-xs leading-relaxed max-w-[200px]">
                    Đặt câu hỏi về các tin tức mới nhất hoặc yêu cầu tóm tắt thông tin cho bạn!
                  </p>
                </div>
              )}

              {messages.map((msg, idx) => (
                <motion.div
                  initial={{ opacity: 0, x: msg.role === 'user' ? 10 : -10 }}
                  animate={{ opacity: 1, x: 0 }}
                  key={idx}
                  className={`flex ${msg.role === 'user' ? 'justify-end' : 'justify-start'}`}
                >
                  <div className={`max-w-[85%] flex items-start gap-2.5 ${msg.role === 'user' ? 'flex-row-reverse' : 'flex-row'}`}>
                    <div className={`w-8 h-8 rounded-xl flex shrink-0 items-center justify-center ${
                      msg.role === 'user' 
                        ? 'bg-blue-600 text-white' 
                        : 'bg-white border border-slate-100 text-indigo-600 shadow-sm'
                    }`}>
                      {msg.role === 'user' ? <User size={14} /> : <Bot size={14} />}
                    </div>
                    <div className={`p-4 rounded-2xl text-sm leading-relaxed shadow-sm ${
                      msg.role === 'user'
                        ? 'bg-blue-600 text-white rounded-tr-none shadow-blue-100/30'
                        : 'bg-white text-slate-700 border border-slate-100 rounded-tl-none'
                    }`}>
                      {msg.content}
                      <div className={`text-[10px] mt-2 opacity-50 ${msg.role === 'user' ? 'text-right' : 'text-left'}`}>
                        {msg.timestamp.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}
                      </div>
                    </div>
                  </div>
                </motion.div>
              ))}

              {loading && (
                <div className="flex justify-start">
                  <div className="flex items-start gap-2.5">
                    <div className="w-8 h-8 rounded-xl bg-white border border-slate-100 text-indigo-600 shadow-sm flex items-center justify-center">
                      <Bot size={14} />
                    </div>
                    <div className="p-4 bg-white border border-slate-100 text-slate-400 text-sm rounded-2xl rounded-tl-none flex items-center gap-2">
                       <Loader2 className="w-4 h-4 animate-spin text-blue-600" />
                       <span>AI đang suy nghĩ...</span>
                    </div>
                  </div>
                </div>
              )}
              <div ref={chatEndRef} />
            </div>

            <div className="p-4 bg-white border-t border-slate-100">
              <div className="relative flex items-center gap-2 bg-slate-50 border border-slate-100 rounded-2xl px-4 py-2 focus-within:ring-2 focus-within:ring-blue-100 focus-within:border-blue-600 transition-all">
                <input
                  type="text"
                  value={input}
                  onChange={(e) => setInput(e.target.value)}
                  onKeyPress={(e) => e.key === 'Enter' && handleSend()}
                  placeholder="Nhập tin nhắn..."
                  className="flex-1 bg-transparent border-none py-2 text-sm focus:outline-none"
                />
                <button 
                  onClick={handleSend}
                  disabled={!input.trim() || loading}
                  className={`p-2 rounded-xl transition-all ${
                    input.trim() && !loading
                      ? 'bg-blue-600 text-white shadow-md shadow-blue-200'
                      : 'bg-slate-200 text-slate-400'
                  }`}
                >
                  <Send size={18} />
                </button>
              </div>
              <p className="text-[9px] text-center text-slate-400 mt-2 font-medium tracking-tight">KLTN Chatbot powered by Gemini AI Model</p>
            </div>
          </motion.div>
        )}
      </AnimatePresence>

      <motion.button
        whileHover={{ scale: 1.05 }}
        whileTap={{ scale: 0.95 }}
        onClick={() => setIsOpen(!isOpen)}
        className={`w-14 h-14 rounded-2xl shadow-2xl flex items-center justify-center text-white transition-all transform ${
          isOpen ? 'bg-slate-800 rotate-90 scale-90' : 'bg-blue-600 hover:bg-blue-700'
        }`}
      >
        {isOpen ? <X size={28} /> : <MessageSquare size={28} />}
        {!isOpen && (
            <span className="absolute -top-1 -right-1 w-4 h-4 bg-red-500 rounded-full border-2 border-white animate-pulse"></span>
        )}
      </motion.button>
    </div>
  );
};

export default Chatbot;
