# Как пересобрать проект

### 1. Перейти на ветку Develop и стянуть новую версию с удаленного репозитория
### 2. Подготовка проекта к обновлению
   Остановить проект с удалением запущенных контейнеров

     docker-compose down
   Синхронизировать .env файл с обновленным файлом .env.dist
### 3. Чистка кеша
   Выполнить в консоли по очереди команды, везде нажимать "Yes" :

    `docker container prune`         - удаляет все остановленные контейнеры
    `docker image prune`             - удаляет все несвязанные (dangling) образы
    `docker system prune --volumes`  - удалить все неиспользуемые тома
    `docker volume prune`            - удаление томов
    `docker builder prune`           - удаление кеша

   На практике после применения этого набора команд иногда остаются неудалённые тома (volumes).
   Чтобы проверить, остались ли тома, нужно выполнить `docker volume ls`. Эта команда вернёт таблицу.
   Если в ней есть тома, то можно удалить их по отдельности. Для этого нужно для каждого тома выполнить
   `docker volume rm имя_тома`, где `имя_тома` берётся из столбца `VOLUME NAME`.

   Полная очистка:
  
    docker system prune -a --volumes -f  - Радикальная чистка (Deleted build cache objects)
    docker volume rm $(docker volume ls -q)  - удаляет все volume
    docker rm --force $(docker ps -a -q)  - удаление всех контейнеров


### 4. Перебилдить проект с удалением кеша (долго билдит)
`docker-compose -f docker-compose.yml -f docker-compose.override.dev.yml -f docker-compose.dev.phpstorm.yml build --no-cache`
### 5. Создание сети
   `docker network create ib-common-network`
### 6. Перезапустить проект
   `mic c r`
### 7. Создать БД (Коннект к БД в PHPStorm должен работать)

    create database myitcareer_dev;
    create database myitcareer_tg_dev;
    create database myitcareer_test;
    create database myitcareer_tg_test;
    create database myitcareer_tg_dev_test;

### 8. Установить пакеты composer
    bash bin/control.sh s (или `mic s`)
    composer install
### 9. Обновить фикстуры
   `mic cc test`\
    Удалить картинки из папки public/upload/media\
    Залить фикстуры\
   `mic db fixture default`
### 10. Проверить работает ли проект
    https://api.myitcareer.local.test:444/api/v1/page/homepage?lang=ru
    пароль:`apiuser1`
    логин: `9f3m2JBLDQHgfaItkFb4`
### 11. Настройка IP для правильной работы PHPStorm с Docker и xDebug
   Заходим в php контейнер под рутом:\
   `docker exec -it dev_myitcareer_app bash`\
   Определяем IP хоста:\
   `ip route | grep default | awk '{print $3}'`\
   Полученный IP указываем\
   в .env файле, параметр `DOCKER_HOST_IP`\
   в .env `XDEBUG_CONFIG=remote_host=ip`\
   ip нужно изменить на полученный в предыдущем пункте ip адрес и пересобрать (см. далее) контейнеры
### 12. Выходим из контейнера exit и останавливаем проект, удаляя контейнеры
   `docker-compose down`
### 13. Пересобираем контейнеры без --no-cache и без запуска проекта
   `docker-compose -f docker-compose.yml -f docker-compose.override.dev.yml build`
### 14. Перезапускаем проект
перезапуск делается потому что эта команда удаляет возможные висящие контейнеры, даже при остановленном проекте

    mic c r
### 15. Проверить, не сломались ли ip (временное решение, до выяснения причины изменения IP)
Выполняем еще раз п.11
### 16. Проверить, работает ли xDebug
   В PHPStorm отметить галочкой Menu -> Run -> Break at first line PHP scripts\
   Активировать иконку дебага в шторме\
   Вызвать любой запрос проекта в браузере

    https://api.myitcareer.local.test:444/api/v1/page/homepage?lang=ru
### 17. Проверить работу тестов
   При первом запуске тестов в шторме возможно запросит подтвердить коннект к хосту - нажать Yes

## Возможные проблемы и способы их решения

### Не работают точки останова или отладка в целом
Повторите [шаг 11 (Настройка IP для правильной работы PHPStorm с Docker и xDebug)](#11-настройка-ip-для-правильной-работы-phpstorm-с-docker-и-xdebug).
Иногда этот IP меняется после настройки.

### Тесты ЭП из Personal Area надолго зависают и падают с ошибкой
Текст ошибки:
```shell
GuzzleHttp\Exception\ConnectException: cURL error 7:
Failed to connect to api.myitcareer.local.test port 444:
Connection timed out
```

Повторите [шаг 11 (Настройка IP для правильной работы PHPStorm с Docker и xDebug)](#11-настройка-ip-для-правильной-работы-phpstorm-с-docker-и-xdebug).
Иногда этот IP меняется после настройки.