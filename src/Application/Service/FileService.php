<?php

namespace FileService\Application\Service;

use FileService\Application\Command\UploadCommandInterface;
use FileService\Application\Dto\DownloadFileDtoFactory;
use FileService\Application\Dto\DownloadFileDtoInterface;
use FileService\Application\Dto\SaveFileDtoFactory;
use FileService\Application\Dto\SaveFileDtoInterface;
use FileService\Application\Model\FileMeta;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class FileService
 * @package Application\Service
 */
class FileService implements FileServiceInterface
{
    /**
     * @var Filesystem
     */
    private Filesystem $fileSystem;

    /**
     * @var SaveFileDtoFactory
     */
    private SaveFileDtoFactory $dtoFactory;

    /**
     * Serializer.
     *
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     * @var DownloadFileDtoFactory
     */
    private DownloadFileDtoFactory $downloadDtoFactory;

    /**
     * PathResolver.
     *
     * @var PathResolverInterface
     */
    private PathResolverInterface $pathResolver;

    /**
     * FileService constructor.
     * @param Filesystem $fileSystem
     * @param SaveFileDtoFactory $dtoFactory
     * @param SerializerInterface $serializer
     * @param DownloadFileDtoFactory $downloadDtoFactory
     * @param PathResolverInterface $pathResolver
     */
    public function __construct(
        Filesystem $fileSystem,
        SaveFileDtoFactory $dtoFactory,
        SerializerInterface $serializer,
        DownloadFileDtoFactory $downloadDtoFactory,
        PathResolverInterface $pathResolver
    ) {
        $this->fileSystem = $fileSystem;
        $this->dtoFactory = $dtoFactory;
        $this->serializer = $serializer;
        $this->downloadDtoFactory = $downloadDtoFactory;
        $this->pathResolver = $pathResolver;
    }

    /**
     * @inheritDoc
     * @throws \Exception
     */
    public function upload(UploadCommandInterface $command) : SaveFileDtoInterface
    {
        $file = $command->getFile();
        $path = bin2hex(random_bytes(2));
        $newFileName = uniqid();
        $file->move(
            implode(DIRECTORY_SEPARATOR, [
                $this->pathResolver->getPath(),
                $path
            ]),
            $newFileName
        );
        $metaModel = new FileMeta(
            $newFileName,
            $path,
            $file->getClientOriginalName(),
            $file->getClientOriginalExtension()
        );

        $this->fileSystem->dumpFile(implode(DIRECTORY_SEPARATOR, [
            $this->pathResolver->getPath(),
            'meta',
            sprintf('%s.%s', $newFileName, JsonEncoder::FORMAT)
        ]), $this->serializer->serialize(
            $metaModel,
            JsonEncoder::FORMAT,
            [
                JsonEncode::OPTIONS => JSON_UNESCAPED_UNICODE
            ]
        ));

        return $this->dtoFactory->create([
            'id' => $newFileName,
            'name' => sprintf('%s.%s', $newFileName, $file->getClientOriginalExtension()),
            'clientName' => $file->getClientOriginalName()
        ]);
    }

    public function download(string $fileName) : DownloadFileDtoInterface
    {
        $metaFilePath = implode(DIRECTORY_SEPARATOR, [
            $this->pathResolver->getPath(),
            'meta',sprintf('%s.json', $fileName)
        ]);

        if (!file_exists($metaFilePath)) {
            throw new NotFoundHttpException("File not found");
        }

        /** @var FileMeta $fileMeta */
        $fileMeta = $this->serializer->deserialize(
            file_get_contents($metaFilePath),
            FileMeta::class,
            JsonEncoder::FORMAT
        );

        return $this->downloadDtoFactory->create([
            'path' => implode(DIRECTORY_SEPARATOR, [
                $this->pathResolver->getPath(),
                $fileMeta->getPath(),
                $fileMeta->getName()
            ]),
            'meta' => $fileMeta,
        ]);
    }

    public function get(string $fullFileName): DownloadFileDtoInterface
    {
        $fileName = pathinfo($fullFileName, PATHINFO_FILENAME);
        $fileExtension = pathinfo($fullFileName, PATHINFO_EXTENSION);

        if (empty($fileName) || empty($fileExtension)) {
            throw new NotFoundHttpException("File not found");
        }

        $fileDto = $this->download($fileName);
        $fileMeta = $fileDto->getFileMeta();

        if ($fileMeta->getClientExtension() != $fileExtension) {
            throw new NotFoundHttpException("File not found");
        }

        return $fileDto;
    }
}