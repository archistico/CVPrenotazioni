Per il consumo dell'API

```bash
 Invoke-RestMethod `
>>   -Uri "https://8000--main--workout--archistico--pippo.coder.app/api/prenotazioni" `
>>   -Headers @{ "X-API-KEY" = "una_api_key_lunga_random" }
```