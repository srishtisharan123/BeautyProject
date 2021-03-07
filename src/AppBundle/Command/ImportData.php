<?php
    namespace AppBundle\Command;

    use Pimcore\Console\AbstractCommand;
    use Pimcore\Console\Dumper;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;
    use Pimcore\Model\DataObject\Product;
    use Pimcore\Model\DataObject\prodegory;
    use Symfony\Component\Console\Input\InputOption;
    use Pimcore\Model\Asset;
    use Pimcore\Model\DataObject;
    //use Pimcore\MOdel\DataObject\ObjectBrick\Data\Bag;

    class ImportData extends AbstractCommand {
        protected function configure() {
            $this->setName('product:save')->setDescription('add new product');
            //->addOption('file', 'f', InputOption::VALUE_REQUIRED, 'xyz');
        }

        protected function execute(InputInterface $input, OutputInterface $output) {
            $files= new \Pimcore\Model\DataObject\ImportData\Listing();
            //$files->setCondition('class_name= ?','Product');
            foreach($files as $path)
            {
                $file=$path->getFile();
                $class=$path->getClass_name();
                $stat=$path->getStatus();
                $file=(PIMCORE_PROJECT_ROOT . '/web/var/assets' .$file->getPath().$file->getFilename());
            }
            // $this->dump($file);
            // die();
            if(($h=fopen($file,"r"))!==FALSE)
            {
                while(($data=fgetcsv($h,1000,","))!==FALSE)
                {
                    $num=count($data);
                    $a[]=$data;
                    
                }
                if($class=="Product" && $stat == false)
                {
                $count=1;
                // $msg = "";
                foreach($a as $entry)
                {
                    $c=0;
                    $prod=new \Pimcore\Model\DataObject\Product();
                    $prod->setKey($entry[$c]);
                    $prod->setPublished(true);
                    $prod->setParentId(3);
                    $prod->setName($entry[$c++]);
                    $prod->setSku($entry[$c++]);
                    $prod->setDescription($entry[$c++]);
                    $prod->setBrand($entry[$c++]);
                    $prod->setSize($entry[$c++]);
                    $t= new \Pimcore\Model\DataObject\Data\RgbaColor();
                    $t->setHex($entry[$c++]);
                    $prod->setColor($t);
                    $prod->setPrice($entry[$c++]);
                    $prod->setTexture($entry[$c++]);
                    $category= new \Pimcore\Model\DataObject\Category\Listing();
                    $category->setCondition('name = ?',$entry[$c++]);
                    foreach($category as $cat)
                    {
                        $prod->setCategory($cat);
                    }
                    $prod->setSkintype($entry[$c++]);
                    $prod->setFinish($entry[$c++]);
                    $applicationArea= new \Pimcore\Model\DataObject\ApplicationArea\Listing();
                    $applicationArea->setCondition('name = ?',$entry[$c++]);
                    foreach($applicationArea as $app)
                    {
                        $prod->setApplicationarea($app);
                    }
                    $img= \Pimcore\Model\Asset\Image::getByPath($entry[$c++]);
                    $prod->setImage($img);
                    $prod->setRating($entry[$c++]);
                    $mat=explode("|",$entry[$c++]);
                    $prod->setIngredients($mat);
                    $unit=DataObject\QuantityValue\Unit::getByAbbreviation("gm");
                    $prod->setQuantity(new DataObject\Data\QuantityValue($entry[$c++],$unit->getId()));
                    $t=strtotime(date("d-m-Y"));
                    $temp=strtotime($entry[$c]);    
                    $this->dump($t);
                    $this->dump($temp);  
                    // die();       
                    if($t<$temp)
                    {
                        $msg.="Date of row ".$count." should be greater than present date\n";
                        p_r("failed");
                        // $entries=new \Pimcore\Model\DataObject\ImportData\Listing();
                        // foreach($entries as $entry)
                        // {
                        //     $entry->setStatus(false);
                        //     $entry->save();
                        // }
                        
                    }
                    else
                    {
                    $startDate= \Carbon\Carbon::parse($entry[$c++]);
                    $prod->setManufacturedon($startDate);
                    $temp=$entry[$c++];
                    if($temp=="body")
                    {
                        $objBrick=new DataObject\Objectbrick\Data\Body($prod);
                        $objBrick->setGender($entry[$c++]);
                        $objBrick->setFragrance($entry[$c++]);
                        $objBrick->setStrength($entry[$c++]);
                        $prod->getClassification()->setBody($objBrick);
                    } 
                    if ($temp=='appliance') 
                    {
                        $objBrick=new DataObject\Objectbrick\Data\Appliance($prod);
                        $objBrick->setWireless($entry[$c++]);
                        //$objBrick->setVolts($entry[$c++]);
                        //$objBrick->setWatt($entry[$c++]);
                        //$objBrick->setHeating($entry[$c++]);
                        //$objBrick->setWarranty($entry[$c++]);
                        $prod->getClassification()->setAppliance($objBrick);
                    } 
                    if ($temp=='cosmetics') 
                    {
                        $objBrick=new DataObject\Objectbrick\Data\Cosmetics($prod);
                        $objBrick->setSmudgeproof($entry[$c++]);
                        //$objBrick->setDuration($entry[$c++]);
                        $objBrick->setWaterproof($entry[$c++]);
                        $objBrick->setTiptype($entry[$c++]);
                        $prod->getClassification()->setCosmetics($objBrick);
                    } 
                    if ($temp=='face') 
                    {
                        $objBrick=new DataObject\Objectbrick\Data\Face($prod);
                        $objBrick->setSkintone($entry[$c++]);
                        $objBrick->setSpf($entry[$c++]);
                        $objBrick->setWaterproof($entry[$c++]);
                        $objBrick->setFor($entry[$c++]);
                        $prod->getClassification()->setFace($objBrick);
                    } 
                    if ($temp=='cosmetics') 
                    {
                        $objBrick=new DataObject\Objectbrick\Data\Hair($prod);
                        $objBrick->setIdeal($entry[$c++]);
                        $objBrick->setSulphate($entry[$c++]);
                        $prod->getClassification()->setHair($objBrick);
                    }
                    $prod->setCountry($entry[$c++]);
                    $startDate= \Carbon\Carbon::parse($entry[$c++]);
                    $prod->setExpirydate($startDate);
                    $prod->save();
                    $msg.="Row ".$count." of product table is imported\n";
                    $this->dump('saved');
                }
                    $count++;
                    $entries=new \Pimcore\Model\DataObject\ImportData\Listing();
                    foreach($entries as $entry)
                    {
                        $entry->setStatus(true);
                        $entry->setMessage($msg);
                        $entry->save();
                    }
                    


                }
            }
            else if($class=="Category" && $stat == false)
           {
                $count=1; 
                foreach($a as $entry)
                {
                    $c=0;
                    $prod=new \Pimcore\Model\DataObject\Category();
                    $prod->setKey($entry[$c]);
                    $prod->setPublished(true);
                    $prod->setParentId(2);
                    $prod->setName($entry[$c++]);
                    $prod->setDescription($entry[$c++]);
                    $prod->save();
                    $msg.="Row ".$count." of Category table is imported\n";
                    $count++;
                    $entries=new \Pimcore\Model\DataObject\ImportData\Listing();
                    foreach($entries as $entry)
                    {
                        $entry->setStatus(true);
                        $entry->setMessage($msg);
                        $entry->save();
                    }
                    $this->dump("saved");
            
            
                }
           }
           else {
               $this->dump("already imported");
           }
        }
                fclose($h);
            }
            
        }
    
?>

	
