<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader {

    static $maxSize = 1024 * 1024 * 20;
    static $extentions = ['csv'];
    private $targetDirectory;
    private $slugger;

    public function __construct($targetDirectory, SluggerInterface $slugger) {
        $this->targetDirectory = $targetDirectory;
        $this->slugger = $slugger;
    }

    public function upload(UploadedFile $file) {

        if (is_uploaded_file($file->getPathname())) {

            if ($file->getSize() <= static::$maxSize && $file->getError() == 0) {

                if (in_array($file->guessExtension(), static::$extentions)) {

                    $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = $this->slugger->slug($originalFilename);
                    $fileName = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

                    try {
                        $file->move($this->getTargetDirectory(), $fileName);
                    } catch (FileException $e) {
                        // ... handle exception if something happens during file upload
                        dd($e);
                    }

                    return $fileName;
                }
            }
        }
        return;
    }

    public function getTargetDirectory() {
        return $this->targetDirectory;
    }

}
