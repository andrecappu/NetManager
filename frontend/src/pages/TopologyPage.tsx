import { useQuery } from '@tanstack/react-query';
import api from '@/lib/api';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { MapContainer, TileLayer, Marker, Popup, Polyline } from 'react-leaflet';
import 'leaflet/dist/leaflet.css';
import L from 'leaflet';

// Fix per i marker di Leaflet in React
delete (L.Icon.Default.prototype as any)._getIconUrl;
L.Icon.Default.mergeOptions({
  iconRetinaUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-icon-2x.png',
  iconUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-icon.png',
  shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png',
});

// Icone personalizzate per tipo di sito
const icons = {
  rack: new L.Icon({
    iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-red.png',
    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png',
    iconSize: [25, 41],
    iconAnchor: [12, 41],
    popupAnchor: [1, -34],
    shadowSize: [41, 41]
  }),
  armadio: new L.Icon({
    iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-orange.png',
    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png',
    iconSize: [25, 41],
    iconAnchor: [12, 41],
    popupAnchor: [1, -34],
    shadowSize: [41, 41]
  }),
  edificio: new L.Icon({
    iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-blue.png',
    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png',
    iconSize: [25, 41],
    iconAnchor: [12, 41],
    popupAnchor: [1, -34],
    shadowSize: [41, 41]
  }),
  impianto_vsrv: new L.Icon({
    iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-green.png',
    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png',
    iconSize: [25, 41],
    iconAnchor: [12, 41],
    popupAnchor: [1, -34],
    shadowSize: [41, 41]
  })
};

// Colori per i collegamenti
const linkColors = {
  fibra: '#eab308', // giallo
  wireless: '#3b82f6', // blu
  rame: '#6b7280' // grigio
};

export default function TopologyPage() {
  const { data: mapData, isLoading } = useQuery({
    queryKey: ['topology-map'],
    queryFn: async () => {
      const response = await api.get('/topology/map');
      return response.data;
    },
  });

  if (isLoading) {
    return <div>Caricamento mappa...</div>;
  }

  const points = mapData?.features.filter((f: any) => f.geometry.type === 'Point') || [];
  const lines = mapData?.features.filter((f: any) => f.geometry.type === 'LineString') || [];

  // Centro di default (es. Roma)
  const defaultCenter: [number, number] = [41.9028, 12.4964];
  
  // Calcola il centro basato sui punti se presenti
  const center = points.length > 0 
    ? [points[0].geometry.coordinates[1], points[0].geometry.coordinates[0]] as [number, number]
    : defaultCenter;

  return (
    <div className="space-y-6">
      <div>
        <h1 className="text-3xl font-bold tracking-tight">Topologia di Rete</h1>
        <p className="text-muted-foreground">
          Mappa geografica dei siti e dei collegamenti.
        </p>
      </div>

      <Card className="h-[600px] overflow-hidden">
        <MapContainer center={center} zoom={13} scrollWheelZoom={true} style={{ height: '100%', width: '100%' }}>
          <TileLayer
            attribution='&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
          />
          
          {/* Renderizza i collegamenti (Linee) */}
          {lines.map((line: any, index: number) => {
            const positions = line.geometry.coordinates.map((coord: number[]) => [coord[1], coord[0]] as [number, number]);
            const color = linkColors[line.properties.tipo as keyof typeof linkColors] || '#000';
            
            return (
              <Polyline 
                key={`line-${index}`} 
                positions={positions} 
                pathOptions={{ color, weight: 3, opacity: 0.7 }} 
              >
                <Popup>
                  <div className="font-semibold">Collegamento {line.properties.tipo}</div>
                  {line.properties.banda_mbps && <div>Banda: {line.properties.banda_mbps} Mbps</div>}
                </Popup>
              </Polyline>
            );
          })}

          {/* Renderizza i siti (Punti) */}
          {points.map((point: any, index: number) => {
            const position: [number, number] = [point.geometry.coordinates[1], point.geometry.coordinates[0]];
            const icon = icons[point.properties.tipo as keyof typeof icons] || icons.edificio;
            
            return (
              <Marker key={`point-${index}`} position={position} icon={icon}>
                <Popup>
                  <div className="font-semibold text-lg">{point.properties.nome}</div>
                  <div className="text-sm capitalize text-muted-foreground mb-2">{point.properties.tipo.replace('_', ' ')}</div>
                  <div className="text-sm">Apparati: {point.properties.apparati_count}</div>
                </Popup>
              </Marker>
            );
          })}
        </MapContainer>
      </Card>

      <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
        <Card>
          <CardContent className="p-4 flex items-center gap-3">
            <img src={icons.rack.options.iconUrl} alt="Rack" className="h-6" />
            <span className="font-medium">Rack</span>
          </CardContent>
        </Card>
        <Card>
          <CardContent className="p-4 flex items-center gap-3">
            <img src={icons.armadio.options.iconUrl} alt="Armadio" className="h-6" />
            <span className="font-medium">Armadio stradale</span>
          </CardContent>
        </Card>
        <Card>
          <CardContent className="p-4 flex items-center gap-3">
            <img src={icons.edificio.options.iconUrl} alt="Edificio" className="h-6" />
            <span className="font-medium">Edificio</span>
          </CardContent>
        </Card>
        <Card>
          <CardContent className="p-4 flex items-center gap-3">
            <img src={icons.impianto_vsrv.options.iconUrl} alt="Videosorveglianza" className="h-6" />
            <span className="font-medium">Videosorveglianza</span>
          </CardContent>
        </Card>
      </div>
    </div>
  );
}
