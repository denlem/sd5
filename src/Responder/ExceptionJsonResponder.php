<?php
declare(strict_types = 1);

namespace App\Responder;

use App\Exception\ClientErrorException;
use App\Service\Logger\HealthLogger;
use App\Service\ResponseDebugModeChecker;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Throwable;
use function sprintf;

/**
 * Респондер, возвращающий HTML-ответ с информацией о падении ЭП
 *
 * При {@see ResponseDebugModeChecker допустимости} вывода отладочной
 * информации будет выведена подробная информация об исключении.
 */
class ExceptionJsonResponder
{
    private const int    DEFAULT_STATUS_CODE                            = Response::HTTP_BAD_REQUEST;
    private const string DEFAULT_ERROR_MESSAGE_TO_CLIENT_ON_BAD_REQUEST = 'Ошибка в запросе';
    private const string DEFAULT_UNKNOWN_ERROR                          = 'Неизвестная ошибка';

    private readonly LoggerInterface $logger;

    public function __construct(private readonly ResponseDebugModeChecker $debugModeChecker, HealthLogger $healthLogger)
    {
        $this->logger = $healthLogger->getLogger();
    }

    public function __invoke(
        Throwable $exception,
        $customErrorMessage = null,
        ?int $statusCode = self::DEFAULT_STATUS_CODE
    ): JsonResponse {
        $errorMessage = $customErrorMessage ?? self::DEFAULT_UNKNOWN_ERROR;

        if ($exception instanceof ClientErrorException) {
            $statusCode   = self::DEFAULT_STATUS_CODE;
            $errorMessage = $customErrorMessage ?? self::DEFAULT_ERROR_MESSAGE_TO_CLIENT_ON_BAD_REQUEST;
        }

        $md5Str    = sprintf(
            '%s_%s',
            $exception->getMessage(),
            microtime(true)
        );
        $errorHash = md5($md5Str);

        $this->logger->error($errorMessage, [
            'location'                => __METHOD__,
            'log_level'               => 'error',
            'exception'               => $exception,
            'errorMessage'            => $exception->getMessage(),
            'exception_file'          => $exception->getFile(),
            'exception_line'          => $exception->getLine(),
            'exception_traceAsString' => $exception->getTraceAsString(),
            'errorHash'               => $errorHash,
            'error'                   => $errorMessage,
            'code'                    => $exception->getCode(),
            'customErrorMessage'      => $customErrorMessage,
        ]);

        if ($this->debugModeChecker->isDebugModeAllowed()) {
            return new JsonResponse(
                [
                    'error'             => $errorMessage,
                    'errorMessage'      => $exception->getMessage(),
                    'errorHash'         => $errorHash,
                    'exceptionLocation' => sprintf(
                        '%1$s:%2$d',
                        $exception->getFile(),
                        $exception->getLine(),
                    ),
                    'code'              => $exception->getCode(),
                    'traceAsString'     => $exception->getTraceAsString(),
                    'trace'             => $exception->getTrace()
                ],
                $statusCode
            );
        }

        return new JsonResponse(
            [
                'error'     => $errorMessage,
                'errorCode' => $exception->getCode(),
                'errorHash' => $errorHash,
            ],
            $statusCode
        );
    }
}
