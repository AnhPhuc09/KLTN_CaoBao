import React from 'react';
import { BrowserRouter as Router, Routes, Route, Navigate } from 'react-router-dom';
import PublicLayout from './components/PublicLayout';
import AdminLayout from './components/AdminLayout';
import Home from './pages/Home';
import ArticleDetail from './pages/ArticleDetail';
import Login from './pages/Login';
import Register from './pages/Register';
import ForgotPassword from './pages/ForgotPassword';
import Profile from './pages/Profile';
import AdminDashboard from './pages/AdminDashboard';
import AdminNewsList from './pages/AdminNewsList';

// Placeholders for remaining pages
const Favorites = () => <div className="p-10 text-center bg-white rounded-3xl border border-dashed border-slate-200 text-slate-400 font-bold">Trang Tin yêu thích đang được phát triển...</div>;

interface AppProps {}

function App() {
  return (
    <Router>
      <Routes>
        {/* Auth routes without main Layout */}
        <Route path="/login" element={<Login />} />
        <Route path="/register" element={<Register />} />
        <Route path="/forgot-password" element={<ForgotPassword />} />

        {/* Public Application routes with PublicLayout (Top Navbar only) */}
        <Route path="/article/:id" element={
          <PublicLayout>
            <ArticleDetail />
          </PublicLayout>
        } />
        
        <Route path="/" element={
          <PublicLayout>
            <Home />
          </PublicLayout>
        } />

        <Route path="/profile" element={
          <PublicLayout>
            <Profile />
          </PublicLayout>
        } />

        {/* Protected/Admin routes with AdminLayout (Sidebar) */}
        <Route path="/*" element={
          <AdminLayout>
            <Routes>
              <Route path="/favorites" element={<Favorites />} />
              
              {/* Admin routes */}
              <Route path="/admin" element={<AdminDashboard />} />
              <Route path="/admin/news" element={<AdminNewsList />} />
              <Route path="/admin/users" element={<div className="p-10 text-center bg-white rounded-3xl border border-dashed border-slate-200 text-slate-400 font-bold">Quản lý người dùng đang được xây dựng...</div>} />
              
              <Route path="*" element={<Navigate to="/" replace />} />
            </Routes>
          </AdminLayout>
        } />
      </Routes>
    </Router>
  );
}

export default App;