<?php

namespace AppBundle\EventListener;
use Pimcore\Event\Model\DataObjectEvent;
use Pimcore\Model\DataObject\Product;
use Pimcore\Model\DataObject\ImportData;

class TestListener
{
    /**
     * @Param DataObjectEvent $e
     */

     public function dateValidation(DataObjectEvent $e)
     {
         if($e->getObject() instanceof Product)
         {
             $t=date("Y-m-d");
             $prod=$e->getObject();
             if($prod->getManufacturedon()>$t)
             {
                 throw new \Pimcore\Model\Element\ValidationException("Manufacturing date cannot be in future.");
             }
         }
     }
     public function extension(DataObjectEvent $e)
     {
         if($e->getObject() instanceof ImportData)
         {
            $files= new \Pimcore\Model\DataObject\ImportData\Listing();
            foreach($files as $path)
            {
                $file=$path->getFile();
                // console.log(PIMCORE_PROJECT_ROOT);
                $file=(PIMCORE_PROJECT_ROOT . '/web/var/assets' .$file->getPath().$file->getFilename());
            }
            $array=explode("/",$file);
            // console.log($array);
            $originalname=$array[10];
            $ext = substr(strrchr($originalname, '.'), 1);
            // console.log($ext);
            if($ext!="csv")
             {
                 throw new \Pimcore\Model\Element\ValidationException("PLease choose csv file");
             }
         }
     }
}


