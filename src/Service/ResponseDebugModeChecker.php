<?php
declare(strict_types=1);

namespace App\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Средство проверки допустимости отладочного поведения в при формировании
 * ответа ЭП
 *
 * Отладочное поведение допустимо, если выполнены следующие условия:
 * - отладочный режим включён при помощи переменной окружения app.response_debug_mode;
 * - в строке запроса присутствует параметр _debug=1.
 */
class ResponseDebugModeChecker
{
    private const API_DEBUG_MODE_ENABLED_VALUE = 'enable';

    private readonly bool    $isDebugModeEnable;
    private readonly Request $request;

    public function __construct(RequestStack $requestStack, $responseDebugMode)
    {
        $this->isDebugModeEnable = $responseDebugMode === self::API_DEBUG_MODE_ENABLED_VALUE;
        $this->request = $requestStack->getCurrentRequest();
    }

    /**
     * @return bool
     */
    public function isDebugModeAllowed(): bool
    {
        $debugParam = $this->request->query->getInt('_debug');

        return ($debugParam === 1 && $this->isDebugModeEnable);
    }
}
