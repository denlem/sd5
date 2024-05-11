# Использование нотификатора

## Методы нотификатора для использования на клиенте

### NB!
 Нужно учесть то что сейчас по умолчанию отправка заблокирована везде при исполненнии тестов, 
 а также в окружениях test и dev (локальный проект). Чтобы протестировать нотификации на 
 локальном проекте нужно закомментировать это условие в базовом продюсере `Messaging/Producer/BaseProducer.php`

    if ($this->isForbiddenPublishingToQueue()) {
        return;
    }

 Кроме этого надо убедиться что крон джобы включены в dev и test окружении.

   `config/docker/cron/config.test.ini`

   `config/docker/cron/config.dev.ini`

 Для локального проекта, который работает с тестовой базой, крон джобы настраиваются в файле `config/docker/cron/config.test.ini`

### Типы нотификаций 
 Типы нотификаций (уведомлений) находятся в сущности Notification, там же находятся константы, 
 которые соответствуют id записям в таблицах. 
 Уведомления бывают одиночные и массовые. Массовые уведомления имеют флаг isMultiNotification в сущности Notification
 Одиночные и массовые уведомления в виде константы передаются в методы отправки уведомления. 
 По ним определяется какой нужен шаблон уведомления и какой алгоритм для получения требуемых данных, 
 например списка пользователей `pushUserNotification` и `pushMultiNotifications`. 
 Также в метод отправки сообшения передаются необходимые параметры: Id пользователя для `pushUserNotification` и 
 `$params` для `pushMultiNotifications`.

### pushUserNotification()

    // Пример отправки уведомления пользователю в тот момент, когда ответили на его комментарий
    $this->notificator->pushUserNotification(
        Notification::ID_REPLY_TO_YOUR_COMMENT,    // NB! Тут указывается константа одиночного уведомления
        $parentComment->getUser()->getId()
    );

### pushMultiNotifications()

    // Отправляет мультинотификации пользователям по заданным условиям
    $this->notificator->pushMultiNotifications(
        Notification::ID_NOTIFICATION_FOR_ALL_USERS,  // NB! Тут указывается константа массового уведомления
        $params
    )
