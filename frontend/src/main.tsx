import { StrictMode } from 'react';
import { createRoot } from 'react-dom/client';
import { BrowserRouter, Routes, Route, Navigate } from 'react-router-dom';
import { QueryClientProvider } from '@tanstack/react-query';
import { queryClient } from './lib/queryClient';
import './index.css';

import LoginPage from './pages/LoginPage';
import ProtectedLayout from './components/layout/ProtectedLayout';
import DashboardPage from './pages/DashboardPage';
import TopologyPage from './pages/TopologyPage';
import InterventiPage from './pages/InterventiPage';
import CalendarioPage from './pages/CalendarioPage';

createRoot(document.getElementById('root')!).render(
  <StrictMode>
    <QueryClientProvider client={queryClient}>
      <BrowserRouter>
        <Routes>
          <Route path="/login" element={<LoginPage />} />
          
          <Route element={<ProtectedLayout />}>
            <Route path="/dashboard" element={<DashboardPage />} />
            <Route path="/topology" element={<TopologyPage />} />
            <Route path="/interventi" element={<InterventiPage />} />
            <Route path="/calendario" element={<CalendarioPage />} />
            {/* Aggiungeremo le altre rotte qui */}
            <Route path="/" element={<Navigate to="/dashboard" replace />} />
          </Route>
        </Routes>
      </BrowserRouter>
    </QueryClientProvider>
  </StrictMode>
);
