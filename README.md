# Настройка проекта Esports API

### 1. Установить docker & docker compose

### 2. Склонировать проект

### 3. Сборка контейнеров

`make build-nc`

### 4. Запустить проект и проверить структуру контейнеров 

#### 4.1 Запустить 

`make up`

#### 4.2 Проверить контейнеры

`docker compose ps`

Docker контейнеры которые должны развернуться:

```
esports_nginx
esports_php_fpm
esports_php_cli
esports_percona
esports_keydb 
esports_cron
esports_swagger
```

### 5. Создать пустую БД

Варианты:
- через консоль в контейнере esports_percona
- через клиент, например встроенный в phpstorm

```
Доступы: 
host: localhost
port: 3306
user: root
password: example
```

`create database esports_dev`

### 6. Настройка хостов
Нужно в файл `/etc/hosts` добавить:

- 127.0.0.1 api.esports.local
- 127.0.0.1 api.esports.local.test

### 7. Установка пакетов композера

#### 7.1 Войти в контейнер

`make sh`

#### 7.2 Запустить установку пакетов

`composer install`

### 8. Проверяем доступ к проекту из браузера

http://api.esports.local:8081/

### 9. Документация в Swagger

http://localhost:8757/

### 9. Команды оболочки

#### Запустить проект
`make up`

#### Остановить контейнеры
`make down`

#### Войти в контейнер php
`make sh`

#### Войти в контейнер php под пользователем root
`make shr`

#### Войти в контейнер nginx
`make sh-n`

#### Очистить кэш Docker (при необходимости делать перед ребилдом)
`make ccd`

#### Собрать проект (с кэшом)
`make build` или `make b`

#### Собрать проект с удалением кэша
`make build-nc` или `make bnc`

#### Показать логи
`make logs`

#### перегенерировать swagger-файл документации 
`make swagger-doc`












