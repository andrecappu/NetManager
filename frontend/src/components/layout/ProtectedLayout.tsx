import { Navigate, Outlet } from 'react-router-dom';
import { useAuthStore } from '@/store/authStore';
import { SidebarProvider, Sidebar, SidebarContent, SidebarHeader, SidebarMenu, SidebarMenuItem, SidebarMenuButton, SidebarTrigger } from '@/components/ui/sidebar';
import { LayoutDashboard, Map, Network, Calendar, CheckSquare, Bell, LogOut } from 'lucide-react';
import { Link, useLocation } from 'react-router-dom';
import { Button } from '@/components/ui/button';

export default function ProtectedLayout() {
  const { isAuthenticated, user, logout } = useAuthStore();
  const location = useLocation();

  if (!isAuthenticated) {
    return <Navigate to="/login" replace />;
  }

  const handleLogout = () => {
    logout();
  };

  const navItems = [
    { title: 'Dashboard', icon: LayoutDashboard, path: '/dashboard' },
    { title: 'Topologia', icon: Map, path: '/topology' },
    { title: 'Apparati', icon: Network, path: '/apparati' },
    { title: 'Interventi', icon: CheckSquare, path: '/interventi' },
    { title: 'Calendario', icon: Calendar, path: '/calendario' },
  ];

  return (
    <SidebarProvider>
      <div className="flex min-h-screen w-full bg-slate-50">
        <Sidebar>
          <SidebarHeader className="p-4">
            <h2 className="text-xl font-bold tracking-tight text-primary">NetManager</h2>
          </SidebarHeader>
          <SidebarContent>
            <SidebarMenu>
              {navItems.map((item) => (
                <SidebarMenuItem key={item.path}>
                  <SidebarMenuButton asChild isActive={location.pathname.startsWith(item.path)}>
                    <Link to={item.path} className="flex items-center gap-3">
                      <item.icon className="h-5 w-5" />
                      <span>{item.title}</span>
                    </Link>
                  </SidebarMenuButton>
                </SidebarMenuItem>
              ))}
            </SidebarMenu>
          </SidebarContent>
          <div className="mt-auto p-4">
            <div className="mb-4 flex items-center gap-3 px-2">
              <div className="flex h-8 w-8 items-center justify-center rounded-full bg-primary/10 text-primary font-medium">
                {user?.nome?.[0]}{user?.cognome?.[0]}
              </div>
              <div className="flex flex-col">
                <span className="text-sm font-medium leading-none">{user?.nome} {user?.cognome}</span>
                <span className="text-xs text-muted-foreground">{user?.roles?.[0] || 'Utente'}</span>
              </div>
            </div>
            <Button variant="outline" className="w-full justify-start gap-2" onClick={handleLogout}>
              <LogOut className="h-4 w-4" />
              Logout
            </Button>
          </div>
        </Sidebar>
        
        <div className="flex flex-1 flex-col overflow-hidden">
          <header className="flex h-14 items-center gap-4 border-b bg-white px-6">
            <SidebarTrigger />
            <div className="ml-auto flex items-center gap-4">
              <Button variant="ghost" size="icon" className="relative">
                <Bell className="h-5 w-5" />
                <span className="absolute right-2 top-2 h-2 w-2 rounded-full bg-destructive"></span>
              </Button>
            </div>
          </header>
          <main className="flex-1 overflow-auto p-6">
            <Outlet />
          </main>
        </div>
      </div>
    </SidebarProvider>
  );
}
