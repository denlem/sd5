# Пример использования RabbitMQ 

### Создание тестовых сервисов консюмера и продюсера и проверка их правильной работы

1. В папке `Messaging/Producer/` создаем пустой класс продюсера `SendTestMessageProducer.php` 
   который должен наследоваться от класса `OldSound\RabbitMqBundle\RabbitMq\Producer` 

   В будущем надо подумать как избежать создание пустых классов, но пока такая реализация, описана тут 
   https://stackoverflow.com/questions/49858171/how-to-inject-rabbitmqbundle-producer-into-service

2. файле `config/packages/old_sound_rabbit_mq.yaml` в секции producer создаем данные по продюсеру

        send_test_message:
        class: App\Messaging\Producer\SendTestMessageProducer
        connection: default
        exchange_options: { name: 'send.test.message', type: direct }
       # use 'old_sound_rabbit_mq.send_test_message_producer' service to send data.

3. Создаем в service.yaml настройку сервиса продюсера
    
        App\Messaging\Producer\SendTestMessageProducer: '@old_sound_rabbit_mq.send_test_message_producer'
 
   id сервиса частично берется из настроек прописанных в файле `old_sound_rabbit_mq.yaml`


4. Чтобы проверить создался ли сервис, можем запустить в консоле следующее:\
    `bin/console debug:container old`
    и в случае если сервис создан, то там будет такая строка\
    `[17] OldSound\RabbitMqBundle\RabbitMq\ProducerInterface $sendTestMessageProducer`\
    и далее выбрав число в квадратных скобках возле сервиса и нажав Enter можно посмотреть информацию по сервису 


5. Создаем консюмер в папке консюмеров `Messaging/Consumer/SendTestMessageConsumer.php` 
   который наследует интерфейс `OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface` в котором
   создаем метод `execute(AMQPMessage $msg)`, где будет обработка соощения из очереди 

       public function execute(AMQPMessage $msg)
       {
          echo 'Идет отправка сообшения в очередь: '.$msg->getBody().PHP_EOL;
          echo 'Сообщение обработано консюмером успешно!';
       }

6. В файле `config/packages/old_sound_rabbit_mq.yaml` в секции consumer создаем данные по консюмеру

       send_test_message:
       connection: default
       exchange_options: { name: 'send.test.message', type: direct }
       queue_options:    { name: 'send.test.message'}
       callback:         App\Messaging\Consumer\SendTestMessageConsumer

7. Чтобы проверить создался ли сервис, можем запустить в консоле следующее:

       bin/console debug:container old 

   и делаем то же что и в пункте (3) только для консюмера

8. Для ручного теста можно запустить в консоле контейнера приложения слушатель слушающий нужную очередь rabbitMQ и 
   направляя поступающие сообщения на обработку консюмерам 

       bin/console rabbitmq:consumer send_test_message -vvv

   В случае успешного запуска слушателя не будет выброшено никакой ошибки, 
   а просто перестанет мигать курсов через несколько секунд

9. Для того чтобы консюмеры попали в сборку и запускались автоматически нужно поместить в файл

    `config/docker/app/loadconsumers.sh`

    такую строку запуска консюмера:

    `/usr/bin/pm2 start "/var/www/symfony/bin/console rabbitmq:consumer telegram_notification -vvv"`

    после этого нужно перебилдить проект без очистки кеша

    `docker-compose -f docker-compose.yml -f docker-compose.override.dev.yml up -d --build`

    И перезапустить проект

     `mic c r`

    Чтобы проверить, что новый консюмер запустился, нужно зайти в контейнер приложения `mic s` 
    и запустить от суперпользователя команду для вывода списка консюмеров, которые работают в памяти в текущий момент
   
    `sudo pm2 list`

    В случае успешного запуска в выведенном списке вы увидите и свой консюмер

    Также для мониторинга консюмеров можно использовать команду 

    `sudo pm2 monit`

    Подробнее о pm2 читайте тут https://pm2.keymetrics.io/docs/usage/process-management/#start-any-process-type


10. Для проверки работы отправки сообщений в очереди и обработки сообщений из очереди 
    можно где либо подключить через DI продюсер как сервис `methodName (SendTestMessageProducer $producer)`
    и запустить вызов продюсера, отправив туда тестовое сообщение, например:

        $this->producer->publish('Сообщение для отправки в очередь...');
  
    Этот продюсер можно подключить например через команду, контроллер или другой сервис


11. Другой способ протестировать отправку сообщения - через создания теста (тест не нужно сохранять в проекте)\
    В файле теста нужно объявить сервис продюсера:

        self::$sendTestMessageProducer = self::$container->get('old_sound_rabbit_mq.send_test_message');

    и вызвать метод publish с сообщением

        self::$sendTestMessageProducer->publish('Test');

12. При ручном тестировании в локальном проекте нужно отключить запрет отправки сообщений в очередь, 
    то есть закомментировать эту проверку в базовом продюсере `Messaging/Producer/BaseProducer.php` :
    
        if ($this->isForbiddenPublishingToQueue()) {
            return;
        }
    

13. После вызова метода publish в очередь попадет сообщение, и сразу же направится 
    на обработку консюмеру. Если сообщение успешно отправлено в очередь, то мы увидим в консоле там,
    где запщен слушатель, вывод текста который выводится методом execute нашего консюмера

    Активность этих сообщений можно увидеть в админке RabbitMQ например в виде изменившися графиков
    http://127.0.0.1:15672/ - админка RabbitMQ
 
    Если слушатель будет выключен, то сообщения будут попадать в очередь, 
    но не будут обрабатываться продюсером, это также можно увидеть на графике в админке

### Источники для изучения RabbitMQ

* Общая информация RabbitMQ\
   https://www.youtube.com/watch?v=GmqVIKwC0uo \
   https://www.youtube.com/watch?v=hfUIWe1tK8E \
   https://www.youtube.com/watch?v=2F_-Lag-_hE \
   https://www.youtube.com/watch?v=4cWg5FVZV6Y
* Symfony + RabbitMQ Быстрый старт для молодых	https://habr.com/ru/articles/338950/
* RabbitMQ Bundle	https://github.com/php-amqplib/RabbitMqBundle
* Настройка по этой инструкции	https://habr.com/ru/companies/southbridge/articles/704208/
* RabbitMQ: терминология и базовые сущности	https://habr.com/ru/companies/southbridge/articles/703060/
* Типовое использование RabbitMQ	https://habr.com/ru/company/southbridge/blog/714358/
* Разбираемся с RabbitMQ: High Availability и High Load	https://habr.com/ru/company/southbridge/blog/723706/
* RabbitMQ: дополнительные возможности	https://habr.com/ru/company/southbridge/blog/724520/
* Настройка Продьюсера	https://stackoverflow.com/questions/49858171/how-to-inject-rabbitmqbundle-producer-into-service
* Хорошая объемная статья	https://dzen.ru/a/XgBWLR6OPwCw5Wsk
* Symfony Messenger plus RabbitMQ	https://www.youtube.com/watch?v=wMNSIL96cTk
* Symfony and RabbitMQ 2019	https://q.agency/blog/symfony-and-rabbitmq/
* Использование RabbitMQ в качестве брокера сообщений	https://symfony.com/doc/current/the-fast-track/ru/32-rabbitmq.html
* Messenger: работа с синхронизированными сообщениями и сообщениями в очереди	https://symfony.ru/doc/current/messenger.html
* Компонент Messenger	https://symfony.ru/doc/current/components/messenger.html