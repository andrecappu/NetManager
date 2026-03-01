import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import api from '@/lib/api';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { DndContext, closestCorners, KeyboardSensor, PointerSensor, useSensor, useSensors, DragEndEvent } from '@dnd-kit/core';
import { SortableContext, arrayMove, sortableKeyboardCoordinates, verticalListSortingStrategy } from '@dnd-kit/sortable';
import { useSortable } from '@dnd-kit/sortable';
import { CSS } from '@dnd-kit/utilities';
import { format } from 'date-fns';
import { it } from 'date-fns/locale';

// Componente per la singola Card dell'intervento
function InterventoCard({ intervento }: { intervento: any }) {
  const { attributes, listeners, setNodeRef, transform, transition } = useSortable({ id: intervento.id });

  const style = {
    transform: CSS.Transform.toString(transform),
    transition,
  };

  const getPrioritaColor = (priorita: string) => {
    switch (priorita) {
      case 'urgente': return 'bg-red-500 text-white';
      case 'alta': return 'bg-orange-500 text-white';
      case 'media': return 'bg-yellow-500 text-white';
      case 'bassa': return 'bg-green-500 text-white';
      default: return 'bg-gray-500 text-white';
    }
  };

  return (
    <div ref={setNodeRef} style={style} {...attributes} {...listeners} className="mb-3 cursor-grab active:cursor-grabbing">
      <Card className="hover:shadow-md transition-shadow">
        <CardContent className="p-4">
          <div className="flex justify-between items-start mb-2">
            <Badge className={getPrioritaColor(intervento.priorita)} variant="outline">
              {intervento.priorita}
            </Badge>
            <span className="text-xs text-muted-foreground">INT-{intervento.id}</span>
          </div>
          <h4 className="font-semibold text-sm mb-1 line-clamp-2">{intervento.titolo}</h4>
          <div className="text-xs text-muted-foreground mb-2">
            {intervento.ente?.nome} {intervento.sito ? `- ${intervento.sito.nome}` : ''}
          </div>
          <div className="flex justify-between items-center mt-3 pt-3 border-t">
            <div className="flex items-center gap-1">
              <div className="h-6 w-6 rounded-full bg-primary/10 flex items-center justify-center text-[10px] font-medium text-primary">
                {intervento.assegnato_a_user ? `${intervento.assegnato_a_user.nome[0]}${intervento.assegnato_a_user.cognome[0]}` : '?'}
              </div>
            </div>
            {intervento.data_scadenza && (
              <span className="text-[10px] text-muted-foreground">
                {format(new Date(intervento.data_scadenza), 'dd MMM', { locale: it })}
              </span>
            )}
          </div>
        </CardContent>
      </Card>
    </div>
  );
}

// Componente per la colonna della Kanban
function KanbanColumn({ title, id, items }: { title: string, id: string, items: any[] }) {
  return (
    <div className="flex flex-col bg-slate-100 rounded-xl p-4 min-h-[500px]">
      <div className="flex items-center justify-between mb-4">
        <h3 className="font-semibold text-sm uppercase tracking-wider text-slate-700">{title}</h3>
        <Badge variant="secondary" className="bg-slate-200 text-slate-700">{items.length}</Badge>
      </div>
      
      <SortableContext id={id} items={items.map(i => i.id)} strategy={verticalListSortingStrategy}>
        <div className="flex-1">
          {items.map((item) => (
            <InterventoCard key={item.id} intervento={item} />
          ))}
          {items.length === 0 && (
            <div className="h-full flex items-center justify-center border-2 border-dashed border-slate-300 rounded-lg text-sm text-slate-400 p-4 text-center">
              Nessun intervento
            </div>
          )}
        </div>
      </SortableContext>
    </div>
  );
}

export default function InterventiPage() {
  const queryClient = useQueryClient();

  const { data: boardData, isLoading } = useQuery({
    queryKey: ['interventi-board'],
    queryFn: async () => {
      const response = await api.get('/interventi?board=true');
      return response.data;
    },
  });

  const updateStatoMutation = useMutation({
    mutationFn: async ({ id, stato }: { id: number, stato: string }) => {
      const response = await api.patch(`/interventi/${id}/stato`, { stato });
      return response.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['interventi-board'] });
    },
  });

  const sensors = useSensors(
    useSensor(PointerSensor, {
      activationConstraint: {
        distance: 5,
      },
    }),
    useSensor(KeyboardSensor, {
      coordinateGetter: sortableKeyboardCoordinates,
    })
  );

  if (isLoading) {
    return <div>Caricamento board...</div>;
  }

  const handleDragEnd = (event: DragEndEvent) => {
    const { active, over } = event;
    
    if (!over) return;

    const activeId = active.id as number;
    const overId = over.id;
    
    // Trova l'elemento trascinato
    let draggedItem = null;
    let sourceCol = '';
    
    Object.entries(boardData).forEach(([colId, items]: [string, any]) => {
      const item = items.find((i: any) => i.id === activeId);
      if (item) {
        draggedItem = item;
        sourceCol = colId;
      }
    });

    if (!draggedItem) return;

    // Determina la colonna di destinazione
    let targetCol = '';
    if (['todo', 'in_corso', 'completato', 'annullato'].includes(overId as string)) {
      targetCol = overId as string;
    } else {
      // Se è stato droppato su un altro elemento, trova la colonna di quell'elemento
      Object.entries(boardData).forEach(([colId, items]: [string, any]) => {
        if (items.some((i: any) => i.id === overId)) {
          targetCol = colId;
        }
      });
    }

    if (sourceCol !== targetCol && targetCol) {
      // Aggiorna lo stato sul server
      updateStatoMutation.mutate({ id: activeId, stato: targetCol });
      
      // Aggiornamento ottimistico locale
      queryClient.setQueryData(['interventi-board'], (oldData: any) => {
        const newData = { ...oldData };
        newData[sourceCol] = newData[sourceCol].filter((i: any) => i.id !== activeId);
        newData[targetCol] = [...newData[targetCol], { ...draggedItem, stato: targetCol }];
        return newData;
      });
    }
  };

  return (
    <div className="space-y-6 h-full flex flex-col">
      <div>
        <h1 className="text-3xl font-bold tracking-tight">Interventi</h1>
        <p className="text-muted-foreground">
          Gestione interventi tecnici (Kanban Board).
        </p>
      </div>

      <div className="flex-1 overflow-x-auto pb-4">
        <DndContext sensors={sensors} collisionDetection={closestCorners} onDragEnd={handleDragEnd}>
          <div className="grid grid-cols-1 md:grid-cols-4 gap-6 min-w-[1000px] h-full">
            <KanbanColumn id="todo" title="Da Fare" items={boardData?.todo || []} />
            <KanbanColumn id="in_corso" title="In Corso" items={boardData?.in_corso || []} />
            <KanbanColumn id="completato" title="Completato" items={boardData?.completato || []} />
            <KanbanColumn id="annullato" title="Annullato" items={boardData?.annullato || []} />
          </div>
        </DndContext>
      </div>
    </div>
  );
}
