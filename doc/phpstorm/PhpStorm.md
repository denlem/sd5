# Настройки PhpStorm

## 1. Code-Style
Файл `CodeStyle_mic_v{x}.xml`, где {x} - это актуальный номер версии

![Импорт](images/phpstorm_import_cs.png)

## 2. Inspections
1. Файл `Inspections_mic_v{x}.xml`, где {x} - это актуальный номер версии

![InsImp](images/phpstorm_import_insp.png)

## 3. Установить плагины
- [Symfony Support](https://plugins.jetbrains.com/plugin/7219-symfony-support/)
- [Php Inspections](https://plugins.jetbrains.com/plugin/7622-php-inspections-ea-extended-)

## 4. Настройка xdebug PHP
### 4.1. Для связки Linux + Docker + PhpStorm
- Заходим в php контейнер: `docker exec -it dev_myitcareer_app bash`
- Определяем IP хоста: `ip route | grep default | awk '{print $3}'`
- Полученный IP указываем в `.env` файле, параметр `DOCKER_HOST_IP`
- в .env `XDEBUG_CONFIG=remote_host=ip` ip нужно изменить на полученный в предыдущем пункте ip адрес и пересобрать контейнеры
- - Пересобираем образы и применяем настройки: `docker-compose -f docker-compose.yml -f docker-compose.override.dev.yml up -d --build`

### 4.2. Настройка PHPStorm
- настраиваем PHP language level `PHP`  
  ![DIFF](images/php-settings.png)
- добавляем cli интерпретатор (Host: `localhost`, username: `www-data`, password: `myitcareerdev`, port: `40085`)  
  ![DIFF](images/cli-settings.png)  
  ![DIFF](images/cli-settings_2.png)
- добавляем Path mappings  
  ![DIFF](images/map-settings.png)
- добавляем порты 9000, 9003, 9009 в xdebug `PHP | Debug`  
  ![DIFF](images/xdebug-settings_2.png)
- добавляем сервер `PHP | Servers`  
  ![DIFF](images/server-settings.png) 

## 5. Настройка phpUnit
![DIFF](images/phpunit_1.png)

![DIFF](images/phpunit_2.png)

## 6. Проверка работоспособности xdebug & unit tests
- Перейти к файлу src/tests/ApiAction/Entity/CityActionTest.php
- Поставить брейкпойнт (см. скрин)
- Нажать правой кнопкой мыши на нем
- Выбрать пункт Debug 'CityActionTest (PHPUnit)', запустим тем самым тест в режиме xdebug
- Должно появиться нижнее окно (см. скрин)

![DIFF](images/check_phpunit_xdebug.png)

## 7. Открытие файлов из веб-профайлера в PhpStorm
https://github.com/aik099/PhpStormProtocol
