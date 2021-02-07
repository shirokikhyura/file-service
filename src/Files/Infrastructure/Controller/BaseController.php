<?php

namespace Files\Infrastructure\Controller;

use Files\Application\Command\SaveCommandFactory;
use Files\Application\Service\FileServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{BinaryFileResponse,
    File\Exception\FileNotFoundException,
    File\File,
    File\UploadedFile,
    JsonResponse,
    Request,
    Response};
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class BaseController
 * @package Files\Infrastructure\Controller
 */
class BaseController extends AbstractController
{
    /**
     * @Route("/", name="main", methods={"GET"})
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function main(Request $request) : JsonResponse
    {
        return $this->returnResponse(
            $request,
            ['ok'],
            [],
            Response::HTTP_OK,
            []
        );
    }

    /**
     * @Route("/get/{path}/{fileName}", name="get", methods={"GET"})
     *
     * @param string $path
     * @param string $fileName
     * @return BinaryFileResponse
     */
    public function getFile(
        string $path,
        string $fileName
    ) : BinaryFileResponse {
        /** @var \Kernel $kernel */
        $kernel = $this->get('kernel');
        try {
            $originalFile = new File(implode(DIRECTORY_SEPARATOR, [$kernel->getProjectDir(), 'files', $path, $fileName]));
            $metaFile = new File(implode(DIRECTORY_SEPARATOR, [
                $kernel->getProjectDir(),
                'files',
                $path,
                sprintf('%s.txt', pathinfo($fileName, PATHINFO_FILENAME))
            ]));
            $metaResource = $metaFile->openFile();
        } catch (FileNotFoundException $ex) {
            throw new FileNotFoundException($fileName);
        }

        return $this->file($originalFile, $metaResource->getCurrentLine());
    }

    /**
     * @Route("/upload", name="upload", methods={"POST"})
     *
     * @param Request $request
     * @param SaveCommandFactory $commandFactory
     * @param FileServiceInterface $service
     * @return JsonResponse
     */
    public function loadFile(
        Request $request,
        SaveCommandFactory $commandFactory,
        FileServiceInterface $service
    ) : JsonResponse {
        $file = $request->files->get('file');

        if (!($file instanceof UploadedFile)) {
            throw new BadRequestHttpException("Request should contains file.");
        }

        if (!$file->isValid()) {
            throw new BadRequestHttpException(sprintf("File has problem [%s]", $file->getErrorMessage()));
        }

        $command = $commandFactory->create($file, $request->get('folder'));
        $savedFileDto = $service->save($command);

        $context = $this->get('router')->getContext();
        $context->setHost($savedFileDto->getHost());

        return $this->returnResponse(
            $request,
            [
                'baseUrl' => $this->generateUrl('get', [
                    'path' => $savedFileDto->getPath(),
                    'fileName' => $savedFileDto->getFilename()
                ], UrlGeneratorInterface::ABSOLUTE_URL)
            ],
            [],
            Response::HTTP_OK,
            []
        );
    }

    /**
     * @param $content
     * @return mixed
     */
    protected function decodeRequest($content)
    {
        return json_decode($content, true, JSON_THROW_ON_ERROR);
    }

    /**
     * Returns response.
     *
     * @param Request $request
     * @param mixed $data
     * @param array $headers
     * @param int $status
     * @param array $headersFromRequest
     * @return JsonResponse
     */
    protected function returnResponse(
        Request $request,
        $data,
        array $headers = [],
        $status = Response::HTTP_OK,
        array $headersFromRequest = []
    ) : JsonResponse {
        foreach ($headersFromRequest as $eachHeaderName) {
            $concreteHeader = $request->headers->get($eachHeaderName);

            if ($concreteHeader) {
                $headers[$eachHeaderName] = $concreteHeader;
            }
        }

        return $this->json(
            $data,
            $status,
            $headers
        );
    }
}