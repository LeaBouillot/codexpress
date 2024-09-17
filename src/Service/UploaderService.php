<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Service de televercement de fichier dans l'application Codexpress
 * - images (.jpg, .jpeg, .png, .gif)
 * Document (plus tards)
 * 
 * Méthodes : Televerser, Supprimer
 */
class UploaderService
{
    private $param;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->param = $parameterBag;
    }

   public function uploadImage($file): string
   {
       try {
           // $orignalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
           $fileName = uniqid('image-') . '.' . $file->guessExtension();
           $file->move($this->param->get('uploads_images_directory'), $fileName);
           $res=[
            'message' =>'Image uploaded successfully',
            'file_name' => $fileName,
           ];

           return $this->param->get('uploads_images_directory') . '/' . $fileName;
       } catch (\Exception $e) {
           throw new \Exception('An error occured while uploading the image: ' . $e->getMessage());
       }
   }

//...
}
