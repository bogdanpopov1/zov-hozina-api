# Railway Deployment Instructions

## Проблема
После деплоя на Railway миграции не запускаются автоматически, поэтому в базе данных остаются только начальные таблицы.

## Решение

### Автоматическое решение (уже настроено)
Файл `railway.json` обновлен для автоматического запуска миграций при деплое:
```json
{
  "deploy": {
    "startCommand": "chmod +x deploy.sh && ./deploy.sh"
  }
}
```

### Ручное решение

#### Способ 1: Через Railway CLI
```bash
# Подключиться к проекту
railway link

# Выбрать проект "valiant-reflection"

# Выполнить миграции
railway run php artisan migrate --force

# Запустить сидеры
railway run php artisan db:seed --force
```

#### Способ 2: Через Railway Console
1. Зайдите в проект на Railway
2. Перейдите в раздел "Logs" для web сервиса
3. Нажмите "Console" или "Terminal"
4. Выполните команды:
   ```bash
   php artisan migrate --force
   php artisan db:seed --force
   ```

#### Способ 3: Через переменные окружения
1. Зайдите в проект на Railway
2. Перейдите в раздел "Variables" для web сервиса
3. Добавьте переменную: `RAILWAY_RUN_MIGRATIONS=true`
4. Перезапустите сервис

## Проверка
После выполнения миграций в базе данных должны появиться таблицы:
- categories
- breeds
- photos
- messages
- favorites
- reviews

А также обновленные таблицы:
- users (с дополнительными полями)
- announcements (с дополнительными полями)
