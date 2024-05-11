# Настройка smtp сервера для отправки рассылки уведомлений, на примере mail.ru


## План
1. Включение доступа для внешних сервисов к smtp.mail.ru серверу
2. Создание пароля приложения для использования в настройках в проекте
3. Прописать настройки в файле .env проекта
4. Протестировать отправку имейлов

## Включение доступа для внешних сервисов к smtp.mail.ru серверу

Из акаунта mail.ru перейти по ссылке https://account.mail.ru/oauth/applications 
и включить галочку разрешения доступа внешним сервисам

## Создание пароля приложения для использования в настройках в проекте

Из аккаунта mail.ru перейти по ссылке https://help.mail.ru/mail/mailer/trouble
и создать пароль для приложения, и далее сохранить его для прописывания в настройках

## Прописать настройки в файле .env проекта

В файле .env прописать строку настройки соединения с smtp.mail.ru сервером

    MAILER_DSN=smtp://myitcareer%40mail.ru:********@smtp.mail.ru:465

    myitcareer%40mail.ru - имейл адрес акаунта, через который будем отправлять письма (urlencoded) 
    ******** - пароль полученный на втором шаге


## Протестировать отправку имейлов

Запустить следующий код и проверить получение письма на требуемый имейл (пример: recieved_test@mail.ru) :

    use Symfony\Component\Mailer\MailerInterface;
    use Symfony\Component\Mime\Email;
    
    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }
    
    public function sendEmail()
    {
        $email = (new Email())
        ->from('myitcareer@mail.ru')
        ->to('recieved_test@mail.ru')
        //->cc('cc@example.com')
        //->bcc('bcc@example.com')
        //->replyTo('fabien@example.com')
        //->priority(Email::PRIORITY_HIGH)
        ->subject('Time for Symfony Mailer!')
        ->text('Sending emails is fun again!')
        ->html('<p>See Twig integration for better HTML integration!</p>');
    
        $mailer->send($email);
    }



