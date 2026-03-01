import { useQuery } from '@tanstack/react-query';
import api from '@/lib/api';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Network, Map, CheckSquare, Users } from 'lucide-react';

export default function DashboardPage() {
  const { data: stats, isLoading } = useQuery({
    queryKey: ['apparati-stats'],
    queryFn: async () => {
      const response = await api.get('/apparati/stats');
      return response.data;
    },
  });

  if (isLoading) {
    return <div>Caricamento...</div>;
  }

  return (
    <div className="space-y-6">
      <div>
        <h1 className="text-3xl font-bold tracking-tight">Dashboard</h1>
        <p className="text-muted-foreground">
          Panoramica dello stato della rete e degli interventi.
        </p>
      </div>

      <div className="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
        <Card>
          <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
            <CardTitle className="text-sm font-medium">
              Totale Apparati
            </CardTitle>
            <Network className="h-4 w-4 text-muted-foreground" />
          </CardHeader>
          <CardContent>
            <div className="text-2xl font-bold">{stats?.totale || 0}</div>
          </CardContent>
        </Card>
        
        <Card>
          <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
            <CardTitle className="text-sm font-medium">
              Apparati Guasti
            </CardTitle>
            <Network className="h-4 w-4 text-destructive" />
          </CardHeader>
          <CardContent>
            <div className="text-2xl font-bold text-destructive">
              {stats?.per_stato?.find((s: any) => s.stato === 'guasto')?.count || 0}
            </div>
          </CardContent>
        </Card>

        <Card>
          <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
            <CardTitle className="text-sm font-medium">
              In Manutenzione
            </CardTitle>
            <Network className="h-4 w-4 text-warning" />
          </CardHeader>
          <CardContent>
            <div className="text-2xl font-bold text-warning">
              {stats?.per_stato?.find((s: any) => s.stato === 'manutenzione')?.count || 0}
            </div>
          </CardContent>
        </Card>

        <Card>
          <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
            <CardTitle className="text-sm font-medium">
              Apparati Attivi
            </CardTitle>
            <Network className="h-4 w-4 text-success" />
          </CardHeader>
          <CardContent>
            <div className="text-2xl font-bold text-success">
              {stats?.per_stato?.find((s: any) => s.stato === 'attivo')?.count || 0}
            </div>
          </CardContent>
        </Card>
      </div>

      <div className="grid gap-4 md:grid-cols-2 lg:grid-cols-7">
        <Card className="col-span-4">
          <CardHeader>
            <CardTitle>Ripartizione per Tipo</CardTitle>
          </CardHeader>
          <CardContent>
            <div className="space-y-4">
              {stats?.per_tipo?.map((tipo: any) => (
                <div key={tipo.tipo} className="flex items-center">
                  <div className="w-32 font-medium capitalize">{tipo.tipo}</div>
                  <div className="flex-1">
                    <div className="h-2 w-full rounded-full bg-secondary">
                      <div 
                        className="h-2 rounded-full bg-primary" 
                        style={{ width: `${(tipo.count / stats.totale) * 100}%` }}
                      />
                    </div>
                  </div>
                  <div className="ml-4 w-12 text-right text-sm text-muted-foreground">
                    {tipo.count}
                  </div>
                </div>
              ))}
            </div>
          </CardContent>
        </Card>
      </div>
    </div>
  );
}
