## ТЗ
Разработать сайт для доставки пиццы. Клиентская часть должна включать в себя: главную страницу со списком доступных для заказа пицц, корзину, в которой можно удалять позиции, страницу оформления заказа. Админка не нужна. Авторизации и регистрации пользователей быть не должно.

## Установка backend пакетов.
```
composer install
```

## Подготовка БД
```
php artisan migrate
php artisan db:seed ProductSeeder
php artisan db:seed ProductPhotoSeeder
php artisan db:seed FixProductPhotoExtFromJPGtoPNGSeeder
```

## Утсановка frontend пакетов
```
npm install
```
или
```
yarn install
```

## Сборка
```
npm run dev
```

## Автор решения
https://github.com/darksectordds
