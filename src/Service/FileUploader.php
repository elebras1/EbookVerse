<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader
{
    public function __construct(
        private $imageDirectory,
        private $ebookDirectory,
        private SluggerInterface $slugger,
    ) {
    }

    public function upload(UploadedFile $file, string $fileType): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();
        
        // Sélectionner le répertoire de destination en fonction du type de fichier
        $targetDirectory = $this->getTargetDirectory($fileType);

        try {
            $file->move($targetDirectory, $fileName);
        } catch (FileException $e) {
            // ... gérer l'exception en cas de problème lors du téléchargement du fichier
        }

        return $fileName;
    }

    private function getTargetDirectory(string $fileType): string
    {
        if ($fileType === 'image/jpeg' || $fileType === 'image/png') {
            return $this->imageDirectory;
        } elseif ($fileType === 'application/epub+zip') {
            return $this->ebookDirectory;
        } else {
            // Gérer le cas où le type de fichier n'est pas pris en charge
            throw new \InvalidArgumentException('Invalid file type.');
        }
    }

    
}
