# Тестовое задание по REST api с MongoDB и MariaDB

## Установка
### Исходные файлы
1) `git clone <текущий репозиторий> <окончательный путь>`

### MariaDB
#### Локальная установка
1) [Установить сервер](https://mariadb.org/download/?t=mariadb&p=mariadb&r=10.6.5&os=windows&cpu=x86_64&pkg=msi&m=docker_ru)
2) Импортировать `.sql` файл из корня проекта

#### Docker контейнер
1) Запустить командой `docker run --detach --name <название контейнера> --env MARIADB_USER=<имя пользователя> --env MARIADB_PASSWORD=<пароль пользователя> --env MARIADB_ROOT_PASSWORD=<пароль root пользователя>  mariadb:latest`

### MongoDB
#### Локальная установка
1) [Установить сервер](https://www.mongodb.com/try/download/community)
2) В случае если установка была Standalone
2.1) Открыть консоль и ввести следующую команду
2.2) <путь до mongod.exe> --dbpath "<путь до /data/db/ в папке проекта>"
3) Подсоединиться к обозревателю через Mongo Compass или MongoShell

### Переменные окружения
4) Настроить файл .env под MariaDB и MongoDB

## Примеры использования

### Пользователи
#### GET запрос
1) `GET <siteurl>/users` - вывод списка всех пользователей
2) `GET <siteurl>/users/5` - вывод информации о пользователе с ID = 5

#### POST запрос
`POST <siteurl>/users` - добавление нового пользователя
```Body: 
    {
        "name": "Максим",
        "role_id": "61e44d5553865"
    }
```

#### PUT запрос
`PUT <siteurl>/users/5` - обновление информации о пользователе с ID = 5
```Body: 
    {
        "name": "Максим",
        "role_id": "61e44d5553865"
    }
```

#### DELETE запрос
`DELETE <siteurl>/users/5` - удаление информации о пользователе с ID = 5
```Body: 
    {
        "name": "Максим",
        "role_id": "61e44d5553865"
    }
```

### Роли
1) `GET <siteurl>/roles` - вывод списка всех доступных ролей
2) `GET <siteurl>/roles/61e44d5553865` - вывод информации о роли с идентификатором 61e44d5553865


#### POST запрос
`POST <siteurl>/roles` - добавление новой роли
```Body: 
    {
        "name": "Дизайнеры"
    }
```

#### PUT запрос
`PUT <siteurl>/roles/61e44ef904e10` - обновление роли
```Body: 
    {
        "name": "Дизайнеры",
    }
```

#### DELETE запрос
`DELETE <siteurl>/roles/61e44ef904e10` - удаление роли