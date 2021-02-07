<?php

namespace Files\Infrastructure\Controller;

use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ErrorHandler\ErrorRenderer\ErrorRendererInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * Class ErrorController
 * @package Files\Infrastructure\Controller
 */
class ErrorController extends AbstractController
{
    /**
     * @var HttpKernelInterface
     */
    private HttpKernelInterface $kernel;
    /**
     * @var ErrorRendererInterface
     */
    private ErrorRendererInterface $errorRenderer;

    /**
     * ErrorController constructor.
     * @param HttpKernelInterface $kernel
     * @param ErrorRendererInterface $errorRenderer
     */
    public function __construct(HttpKernelInterface $kernel, ErrorRendererInterface $errorRenderer)
    {
        $this->kernel = $kernel;
        $this->errorRenderer = $errorRenderer;
    }

    /**
     * @param Throwable $exception
     * @param Request $request
     * @return JsonResponse|Response
     */
    public function show(Throwable $exception, Request $request)
    {
        if ($exception instanceof FlattenException) {
            $exception = $this->errorRenderer->render($exception);

            return new Response($exception->getAsString(), $exception->getStatusCode(), $exception->getHeaders());
        } else {
            $status = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        if ($exception instanceof FileNotFoundException) {
            $status = Response::HTTP_NOT_FOUND;
        }

        if ($exception instanceof HttpExceptionInterface) {
            $status = Response::HTTP_BAD_REQUEST;
        }

        return JsonResponse::fromJsonString($exception->getMessage(), $status);
    }
}