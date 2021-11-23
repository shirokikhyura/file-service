<?php

namespace FileService\Infrastructure\Controller\Common;

use FileService\Application\Service\FileServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;

class GetAction extends AbstractController
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
     * @Route("/{fullFileName}",
     *     name="get-public-file",
     *     methods={"GET"},
     *     requirements={"fullFileName"="^[a-z0-9]+\.[a-zA-Z]{1,256}$"}
     *     )
     */
    public function __invoke(string $fullFileName) : BinaryFileResponse
    {
        $fileDto = $this->service->get($fullFileName);
        $fileMeta = $fileDto->getFileMeta();

        return $this->file(
            $fileDto->getFile(),
            $fileMeta->getName(),
            ResponseHeaderBag::DISPOSITION_INLINE
        );
    }
}