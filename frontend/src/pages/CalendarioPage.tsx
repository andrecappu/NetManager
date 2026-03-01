import { useQuery } from '@tanstack/react-query';
import api from '@/lib/api';
import FullCalendar from '@fullcalendar/react';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import interactionPlugin from '@fullcalendar/interaction';
import itLocale from '@fullcalendar/core/locales/it';
import { Card, CardContent } from '@/components/ui/card';
import { useAuthStore } from '@/store/authStore';

export default function CalendarioPage() {
  const { user } = useAuthStore();

  const { data: eventi, isLoading } = useQuery({
    queryKey: ['calendario'],
    queryFn: async () => {
      // Se non è admin, vedi solo i propri eventi
      const url = user?.roles?.includes('admin') ? '/calendario' : `/calendario?user_id=${user?.id}`;
      const response = await api.get(url);
      return response.data.data;
    },
  });

  if (isLoading) {
    return <div>Caricamento calendario...</div>;
  }

  const calendarEvents = eventi?.map((e: any) => ({
    id: e.id.toString(),
    title: e.titolo,
    start: e.data_inizio,
    end: e.data_fine,
    backgroundColor: e.colore || '#3b82f6',
    borderColor: e.colore || '#3b82f6',
    extendedProps: {
      intervento_id: e.intervento_id,
      user: e.user,
      note: e.note
    }
  })) || [];

  return (
    <div className="space-y-6 h-full flex flex-col">
      <div>
        <h1 className="text-3xl font-bold tracking-tight">Calendario</h1>
        <p className="text-muted-foreground">
          Pianificazione interventi e disponibilità operatori.
        </p>
      </div>

      <Card className="flex-1">
        <CardContent className="p-6 h-full">
          <div className="h-[700px]">
            <FullCalendar
              plugins={[dayGridPlugin, timeGridPlugin, interactionPlugin]}
              initialView="timeGridWeek"
              headerToolbar={{
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
              }}
              locale={itLocale}
              events={calendarEvents}
              slotMinTime="07:00:00"
              slotMaxTime="20:00:00"
              allDaySlot={false}
              height="100%"
              eventClick={(info) => {
                // Qui potremmo aprire un modale con i dettagli
                console.log('Evento cliccato:', info.event);
              }}
            />
          </div>
        </CardContent>
      </Card>
    </div>
  );
}
