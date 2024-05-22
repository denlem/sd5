<?php
declare(strict_types = 1);

namespace App\Api;

/**
 * ActionConstants
 */
class ActionConstants
{
    public const PAGE_NOT_FOUND                = 'Страница не найдена';
    public const SOME_PARAMS_ARE_EMPTY         = 'Некоторые параметры не заполнены';
    public const USER_DOES_NOT_HAVE_RESUME     = 'У пользователя нет еще ни одного резюме';
    public const NOT_INSTANCE_OF_USER_CLASS    =
        'Информация о пользователе не является экземпляром класса App\Entity\User';
    public const LANG_PARAM_ERROR_MESSAGE      =
        'Ошибка инициализации параметров. Проверьте наличие и правильность get параметра lang';
    public const UNSUPPORTED_LANG              = 'Запрошенный язык не поддерживается';
    public const JWT_TOKEN_PARAM_ERROR_MESSAGE =
        'Ошибка инициализации параметров. Проверьте наличие и правильность JWT-токена';
    public const CONTENT_PARAM_ERROR_MESSAGE   =
        'Ошибка инициализации параметров. Ошибка наличие и правильность содержимого(content) запроса';
    public const PROFESSION_SLUG_NOT_FOUND     = 'Profession slug не найден в базе данных';
}
