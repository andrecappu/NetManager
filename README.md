# NetManager

Gestionale aziendale modulare per la gestione di infrastrutture di rete e impianti di videosorveglianza.

## Stack Tecnologico
- **Backend:** Laravel 12 (PHP 8.3)
- **Frontend:** React 18 + TypeScript + TailwindCSS
- **Database:** MariaDB 10.6
- **Cache/Code:** Redis
- **Storage:** MinIO (S3 compatible)
- **Websockets:** Laravel Reverb

## Requisiti
- Docker
- Docker Compose

## Setup
1. Clona il repository
2. Copia i file `.env.example` in `.env` sia nella root (se presente) che nelle cartelle `backend` e `frontend`.
3. Esegui `docker-compose up -d --build`
4. Esegui le migrazioni: `docker-compose exec laravel-app php artisan migrate --seed`
5. Accedi all'applicazione:
   - Frontend: http://localhost:3000
   - Backend API: http://localhost:8000
   - MinIO Console: http://localhost:8900
