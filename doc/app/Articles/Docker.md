# Некоторые полезные команды для работы с Docker

### 1. Очистка кэша docker и мусорных файлов

https://habr.com/ru/post/486200/ , https://linux-notes.org/ostanovit-udalit-vse-docker-kontejnery/

`docker system df`    - посмотреть сколько места занимают контейнеры докера

`docker image ls -f dangling=true`     - сколько занимают имеджи

- `docker container prune`   - удаляет все остановленные контейнеры
- `docker image prune`   - удаляет все несвязанный (dangling) образы
- `docker system prune --volumes` - удалить все неиспользуемые тома
- `docker volume prune`   - удаление томов
- `docker builder prune`   - удаление кеша

`docker system prune` - удалит все (контейнеры, образы, тома, кеш)

`docker rmi $(docker images | awk '{ print $3; }')`  - удаляет все образы

#### Команды для радикальной очистки всего кеша:

`docker system prune -a --volumes -f`  - Радикальная чистка (Deleted build cache objects)

`docker volume rm $(docker volume ls -q)`  - удаляет все volume

`docker rm --force $(docker ps -a -q)`  - удаление всех контейнеров

### 2. Миграции в контейнере:

    docker exec dev-scp-app bin/console doctrine:migrations:diff
    docker exec dev-scp-app bin/console doctrine:migrations:migrate

    docker-compose exec app bin/console doctrine:migrations:migrate

   dir\ папок:\
   `docker exec dev-scp-app ls`

### 3. Пинг контейнера
    docker exec <contanter-name> ping [hostname:port](hostname:port)
    docker exec dev-graphql-admin-nodejs ping nodejs:4000
### 4. Логи контейнера
    docker logs <container_name>
    docker logs dev-graphql-admin-nodejs

### 5. Запуск контейнера
    docker-compose up -d --build ( запуск + освобождает консоль + билд )
    docker-compose up -d ( запуск + освобождает консоль )
    docker-compose up ( запуск )
    docker-compose up -d --build --no-cache ( запуск + освобождает консоль + билд + игнорировать кэш ) - НЕ РАБОТАЕТ, ПРОВЕРИТЬ!
### 6. Порт контейнера
   `docker port dev-graphql-admin-nodejs`
### 7. Просмотр свободных контейнеров в текущей папке
   `docker-compose ps`
### 8. Просмотр всех контейнеров глобально
   `docker ps`
### 9. Чтобы войти в любой из контейнеров, делаем следующее:
   `docker exec -it <container_name> bash`

### 10. Запуск проекта (пример https://github.com/lolychank/symfony-docker ) :
    cp .env.test .env
    docker-compose up --build -d
