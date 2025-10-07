# Интеграция с клиентским приложением

## 📡 API Endpoints

### Получение срочных объявлений (4 объявления для главной страницы)
```
GET /api/announcements/urgent
```

**Ответ:**
```json
[
  {
    "announcement_id": 1,
    "pet_name": "Рекс",
    "pet_type": "собака",
    "pet_breed": "джек-рассел-терьер",
    "description": "Коричневая мордочка, очень игривый...",
    "location_address": "улица Летняя, 1, Казань",
    "status": "active",
    "age": 3,
    "gender": "male",
    "size": "small",
    "color": "белый с коричневыми пятнами",
    "is_vaccinated": true,
    "is_sterilized": false,
    "has_pedigree": true,
    "price_type": "free",
    "additional_info": "Очень дружелюбный, откликается на кличку Рекс",
    "is_featured": true,
    "created_at": "2025-10-08T00:00:00.000000Z",
    "updated_at": "2025-10-08T00:00:00.000000Z",
    "user": {
      "user_id": 1,
      "name": "Тестовый Пользователь",
      "phone": "+7 (999) 123-45-67",
      "location": "Казань"
    },
    "category": {
      "category_id": 1,
      "name": "Собаки",
      "slug": "dogs"
    },
    "breed": {
      "breed_id": 3,
      "name": "Йоркширский терьер",
      "slug": "yorkshire-terrier"
    },
    "photos": [
      {
        "photo_id": 1,
        "filename": "jack-russell-terrier.svg",
        "path": "announcements/jack-russell-terrier.svg",
        "mime_type": "image/svg+xml",
        "width": 800,
        "height": 600,
        "is_primary": true,
        "url": "http://localhost:8000/storage/announcements/jack-russell-terrier.svg"
      }
    ]
  }
]
```

### Получение всех объявлений с пагинацией
```
GET /api/announcements?page=1
```

## 🖼️ Работа с фотографиями

### URL фотографий
Фотографии доступны по адресу:
```
https://your-railway-domain.up.railway.app/storage/announcements/{filename}
```

### Примеры URL:
- `https://your-railway-domain.up.railway.app/storage/announcements/jack-russell-terrier.svg`
- `https://your-railway-domain.up.railway.app/storage/announcements/corgi.svg`
- `https://your-railway-domain.up.railway.app/storage/announcements/scottish-fold.svg`
- `https://your-railway-domain.up.railway.app/storage/announcements/british-shorthair.svg`

### Основная фотография
Используйте поле `is_primary: true` для определения основной фотографии объявления.

## 🔧 Настройка клиентского приложения

### 1. Обновите API URL
В вашем клиентском приложении обновите базовый URL API:
```javascript
const API_BASE_URL = 'https://your-railway-domain.up.railway.app/api';
```

### 2. Создайте компонент для отображения объявлений
```jsx
const AnnouncementCard = ({ announcement }) => {
  const primaryPhoto = announcement.photos.find(photo => photo.is_primary);
  
  return (
    <div className="announcement-card">
      {primaryPhoto && (
        <img 
          src={primaryPhoto.url} 
          alt={announcement.pet_name}
          className="announcement-photo"
        />
      )}
      <div className="announcement-info">
        <h3>{announcement.pet_name}</h3>
        <p>{announcement.pet_breed}</p>
        <p>{announcement.location_address}</p>
        <p>{announcement.description}</p>
      </div>
    </div>
  );
};
```

### 3. Загрузите данные с сервера
```javascript
const fetchUrgentAnnouncements = async () => {
  try {
    const response = await fetch(`${API_BASE_URL}/announcements/urgent`);
    const announcements = await response.json();
    return announcements;
  } catch (error) {
    console.error('Error fetching announcements:', error);
    return [];
  }
};
```

## 📱 Структура данных для макета

Созданные тестовые объявления соответствуют макету:

1. **Джек-рассел-терьер** - "Пропал джек-рассел-терьер, улица Летняя, 1"
2. **Корги** - "Пропал корги, улица Мира, 27"  
3. **Шотландская вислоухая** - "Пропал шотландец, улица Пушкина, 42"
4. **Британская короткошерстная** - "Пропал британец, улица Восточная, 193"

## 🚀 Следующие шаги

1. Обновите клиентское приложение для работы с новым API
2. Замените SVG заглушки на реальные фотографии животных
3. Настройте загрузку фотографий через API
4. Добавьте фильтрацию по категориям и породам
