# CVPrenotazioni

Applicazione Symfony per la gestione prenotazioni con:
- form web di inserimento prenotazione
- validazione PIN del porteur
- invio email della richiesta firmata
- API REST in lettura per prenotazioni
- API REST amministrativa per creazione porteur

## Requisiti

- PHP `>= 8.2`
- Composer
- Estensioni PHP abilitate: `ctype`, `iconv`
- Database (default SQLite)
- Server SMTP raggiungibile (per invio email)

## Stack Tecnico

- Symfony `7.4`
- Doctrine ORM + Migrations
- Twig
- Symfony Mailer

## Setup Rapido

1. Installa dipendenze:

```bash
composer install
```

2. Configura `.env` (o meglio `.env.local`) con i valori reali.

3. Crea schema DB:

```bash
php bin/console doctrine:migrations:migrate -n
```

4. Carica dati iniziali (facoltativo ma consigliato in sviluppo):

```bash
php bin/console doctrine:fixtures:load -n
```

5. Avvia il server locale:

```bash
symfony server:start
```

In alternativa:

```bash
php -S 127.0.0.1:8000 -t public
```

## Variabili Ambiente Principali

Nel file `.env` sono gia presenti esempi. Le piu importanti:

- `APP_ENV`
- `DATABASE_URL`
- `MAILER_DSN`
- `MAIL_FROM`
- `MAIL_TO`
- `AGENTE_SECRET`
- `AGENTE_ID`
- `PIN_PEPPER`
- `API_KEY_CONSUMER` (API lettura prenotazioni)
- `API_KEY_ADMIN` (API amministrative, es. creazione porteur)

## Flusso Applicativo

1. L'utente inserisce una prenotazione dal form web (`/`).
2. Il PIN inserito viene verificato contro l'hash del porteur.
3. Se valido, la prenotazione viene salvata.
4. Viene creato un payload JSON firmato HMAC-SHA256.
5. Il payload viene inviato via email come allegato JSON.

## API Disponibili

Base path API: `/api`

Autenticazione API:
- header HTTP `X-API-KEY`
- valore confrontato lato server con chiavi in `.env`

### 1) Lista prenotazioni

- Metodo: `GET`
- Endpoint: `/api/prenotazioni`
- API key richiesta: `API_KEY_CONSUMER`
- Query params opzionali:
- `limit` (default `50`, min `1`, max `200`)
- `page` (default `1`, min `1`)

Esempio `curl`:

```bash
curl -s "http://127.0.0.1:8000/api/prenotazioni?limit=20&page=1" \
  -H "X-API-KEY: una_api_key_lunga_random"
```

Esempio PowerShell:

```powershell
Invoke-RestMethod `
  -Uri "http://127.0.0.1:8000/api/prenotazioni?limit=20&page=1" `
  -Headers @{"X-API-KEY" = "una_api_key_lunga_random"}
```

Risposta `200` (struttura):

```json
{
  "page": 1,
  "limit": 20,
  "count": 1,
  "items": [
    {
      "id": 10,
      "cliente": "Mario Rossi",
      "dal": "2026-02-20",
      "al": "2026-02-23",
      "pax": {
        "adulti": 2,
        "bambini": 1,
        "adolescenti": 0
      },
      "albergo": {"id": 1, "descrizione": "Grand Hotel Billia"},
      "tipologia_sistemazione": {"id": 2, "descrizione": "PARK MATR/DP"},
      "tipologia_ospitalita": {"id": 1, "descrizione": "Cashback"},
      "tariffa": {"id": 1, "descrizione": "FULL BOARD (con SPA/EXTRA)"},
      "porteur": {"id": 1, "descrizione": "Porteur 1"},
      "note": "Richiesta late check-in"
    }
  ]
}
```

Errori:
- `401 Unauthorized` se `X-API-KEY` non valida

### 2) Dettaglio prenotazione

- Metodo: `GET`
- Endpoint: `/api/prenotazioni/{id}`
- API key richiesta: `API_KEY_CONSUMER`

Esempio `curl`:

```bash
curl -s "http://127.0.0.1:8000/api/prenotazioni/10" \
  -H "X-API-KEY: una_api_key_lunga_random"
```

Esempio PowerShell:

```powershell
Invoke-RestMethod `
  -Uri "http://127.0.0.1:8000/api/prenotazioni/10" `
  -Headers @{"X-API-KEY" = "una_api_key_lunga_random"}
```

Risposta `200`: stesso schema di un elemento in `items` della lista.

Errori:
- `401 Unauthorized` se `X-API-KEY` non valida
- `404 Not found` se ID inesistente

### 3) Creazione porteur (admin)

- Metodo: `POST`
- Endpoint: `/api/porteurs`
- API key richiesta: `API_KEY_ADMIN`
- Content-Type: `application/json`

Body JSON:

```json
{
  "descrizione": "Porteur Nuovo",
  "pin": "1234",
  "obsoleto": false
}
```

Note:
- `descrizione` e `pin` sono obbligatori
- il `pin` viene hashato lato server prima del salvataggio
- `obsoleto` e opzionale, default `false`

Esempio `curl`:

```bash
curl -s -X POST "http://127.0.0.1:8000/api/porteurs" \
  -H "Content-Type: application/json" \
  -H "X-API-KEY: una_api_key_admin_lunga_random" \
  -d "{\"descrizione\":\"Porteur Nuovo\",\"pin\":\"1234\",\"obsoleto\":false}"
```

Esempio PowerShell:

```powershell
$body = @{
  descrizione = "Porteur Nuovo"
  pin = "1234"
  obsoleto = $false
} | ConvertTo-Json

Invoke-RestMethod `
  -Method Post `
  -Uri "http://127.0.0.1:8000/api/porteurs" `
  -Headers @{"X-API-KEY" = "una_api_key_admin_lunga_random"} `
  -ContentType "application/json" `
  -Body $body
```

Risposta `201`:

```json
{
  "id": 4,
  "descrizione": "Porteur Nuovo",
  "obsoleto": false
}
```

Errori:
- `400 Invalid JSON body` se il body non e JSON valido
- `400 Fields "descrizione" and "pin" are required` se campi mancanti/vuoti
- `401 Unauthorized` se `X-API-KEY` non valida

## Test Email SMTP

E disponibile un comando di test:

```bash
php bin/console app:test-mail
```

## Dati Fixtures Utili

Dopo `doctrine:fixtures:load` vengono creati, tra gli altri:
- `Porteur 1` con PIN `1111`
- `Porteur 2` con PIN `2222`
- `Porteur 3` con PIN `3333`

I PIN in database sono salvati hashati con `PIN_PEPPER`.

## Sicurezza

- Non committare mai chiavi API o segreti reali in repository.
- Usa `.env.local` per credenziali reali.
- Ruota periodicamente `API_KEY_CONSUMER`, `API_KEY_ADMIN`, `AGENTE_SECRET` e `PIN_PEPPER`.
