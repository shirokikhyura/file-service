<?php

namespace FileService\Infrastructure\Controller\V1;

use FileService\Application\Command\UploadCommandFactory;
use FileService\Application\Service\FileServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

class UploadAction extends AbstractController
{
    /**
     * @Route("/upload", name="upload-file", methods={"POST"})
     *
     * @param Request $request
     * @param UploadCommandFactory $commandFactory
     * @param FileServiceInterface $service
     * @return JsonResponse
     */
    public function __invoke(
        Request $request,
        UploadCommandFactory $commandFactory,
        FileServiceInterface $service
    ) : JsonResponse {
        $file = $request->files->get('file');

        if (!($file instanceof UploadedFile)) {
            throw new BadRequestHttpException("Request should contains file.");
        }

        if (!$file->isValid()) {
            throw new BadRequestHttpException(sprintf("File has problem [%s]", $file->getErrorMessage()));
        }

        $command = $commandFactory->create($file);
        $savedFileDto = $service->upload($command);

        return $this->json($savedFileDto);
    }
}