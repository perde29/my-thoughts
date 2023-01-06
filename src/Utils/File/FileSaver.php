<?php

namespace App\Utils\File;

use App\Utils\Filesystem\FilesystemWorker;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileSaver {

    private $slugger;
    private $uploadsTemp;
    private $filesystemWorker;

    public function __construct(SluggerInterface $slugger, FilesystemWorker $filesystemWorker , string $uploadsTempDir)
    {
        
        $this->slugger = $slugger;
        $this->uploadsTemp = $uploadsTempDir;
        $this->filesystemWorker = $filesystemWorker;

    }


    public function saveUploadedFileIntoTemp(UploadedFile $uploadedFile) {

        $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME );
        $safeFilename = $this->slugger->slug($originalFilename);

        $filename = sprintf( '%s-%s.%s',$safeFilename , uniqid(), $uploadedFile->guessExtension());
        $this->filesystemWorker->createFolderIfitNotExist($this->uploadsTemp);

        try {
            $uploadedFile->move($this->uploadsTemp, $filename);
        } catch (FileException $exception) {
            return null;
        }

        return $filename;
    }


    


}