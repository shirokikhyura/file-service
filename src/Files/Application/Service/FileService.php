<?php

namespace Files\Application\Service;

use Files\Application\Command\SaveCommandInterface;
use Files\Application\Dto\SaveFileDtoFactory;
use Files\Application\Dto\SaveFileDtoInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class FileService
 * @package Files\Application\Service
 */
class FileService implements FileServiceInterface
{
    /**
     * @var string
     */
    private string $baseDir;

    /**
     * @var Filesystem
     */
    private Filesystem $fileSystem;

    /**
     * @var SaveFileDtoFactory
     */
    private SaveFileDtoFactory $dtoFactory;

    /**
     * FileService constructor.
     * @param string $baseDir
     * @param Filesystem $fileSystem
     * @param SaveFileDtoFactory $dtoFactory
     */
    public function __construct(string $baseDir, Filesystem $fileSystem, SaveFileDtoFactory $dtoFactory)
    {
        $this->baseDir = $baseDir;
        $this->fileSystem = $fileSystem;
        $this->dtoFactory = $dtoFactory;
    }

    /**
     * @inheritDoc
     */
    public function save(SaveCommandInterface $command) : SaveFileDtoInterface
    {
        $file = $command->getFile();
        $path = $command->getPath();
        $newFileName = uniqid();
        $file->move(
            implode(DIRECTORY_SEPARATOR, [
                $this->baseDir,
                $path
            ]),
            $realFileName = sprintf('%s.%s', $newFileName, $file->getClientOriginalExtension())
        );
        $this->fileSystem->dumpFile(implode(DIRECTORY_SEPARATOR, [
            $this->baseDir,
            $path,
            sprintf('%s.txt', $newFileName)
        ]), $file->getClientOriginalName());

        return $this->dtoFactory->create([
            'path' => $path,
            'fileName' => $realFileName
        ]);
    }
}