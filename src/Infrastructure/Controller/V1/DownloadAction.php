<?php

namespace FileService\Infrastructure\Controller\V1;

use FileService\Application\Service\FileServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\Routing\Annotation\Route;

class DownloadAction extends AbstractController
{
    /**
     * Service.
     *
     * @var FileServiceInterface
     */
    private FileServiceInterface $service;

    /**
     * GetAction constructor.
     * @param FileServiceInterface $service
     */
    public function __construct(FileServiceInterface $service)
    {
        $this->service = $service;
    }

    /**
     * @Route("/download/{fileName}", name="download-file", methods={"GET"})
     *
     * @param string $fileName
     * @return BinaryFileResponse
     */
    public function __invoke(string $fileName) : BinaryFileResponse
    {
        $fileDto = $this->service->download($fileName);
        $fileMeta = $fileDto->getFileMeta();
        $response = $this->file(
            $fileDto->getFile(),
            $fileMeta->getClientName()
        );
        $response->headers->add([
            'X-FS-client-name' => $fileMeta->getClientName(),
            'X-FS-name' => $fileMeta->getName(),
        ]);

        return $response;
    }
}