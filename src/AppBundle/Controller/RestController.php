<?php

namespace AppBundle\Controller;

use Pimcore\Bundle\AdminBundle\Controller\Rest\AbstractRestController;
use Pimcore\Bundle\AdminBundle\HttpFoundation\JsonResponse;
use Pimcore\Bundle\AdminBundle\Security\BruteforceProtectionHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Pimcore\Model\DataObject\Product;


/**
 * Class RestController
 * @package AppBundle\Controller
 */

 class RestController extends AbstractRestController
 {
     CONST BASE_API_SERVICE = 'base_api_service';

     /**
      * @Route("/webservice/accessProductList")
      * @Method({"GET"})
      * @param Request $request
      * @return \Symfony\Component\HttpFoundation\JsonResponse
      * @throws \Pimcore\Http\Exception\ResponseException
      * @throws \Exception
      */
    public function getProductList(Request $request, BruteforceProtectionHandler $bruteforceProtectionHandler)
    {
        $data = [];
        $products = new \Pimcore\Model\DataObject\Product\Listing();                
        $products->getObjects();
        foreach ($products as $product)
        {
            $data[] = $this->getProductJson($product);
        }
        if (!empty($data)) {
            return $this->createSuccessResponse($data, true);
        }
        return $this->createErrorResponse("No product found!", Response::HTTP_NOT_FOUND);
    }

    /**
      * @Route("/webservice/filterProductList")
      * @Method({"GET"})
      * @param Request $request
      * @return \Symfony\Component\HttpFoundation\JsonResponse
      * @throws \Pimcore\Http\Exception\ResponseException
      * @throws \Exception
      */
      public function getFilteredProductList(Request $request, BruteforceProtectionHandler $bruteforceProtectionHandler)
      {
          $brand = $request->query->get('brand');
          $category = $request->query->get('category');
          $data = [];
          $products = new \Pimcore\Model\DataObject\Product\Listing();                  
          $products->getObjects();
          foreach ($products as $product)
          {
              if ($brand) {
                  if ($category) {
                      if (strcasecmp($category, $product->getCategory()->getName()) == 0 && strcasecmp($brand, $product->getBrand()->getName()) == 0) {
                        $data[] = $this->getProductJson($product);
                      }
                  }
                  else {
                      if (strcasecmp($brand, $product->getBrand()->getName()) == 0) {
                        $data[] = $this->getProductJson($product);
                      }

                  }
              }
              elseif ($category) {
                  if (strcasecmp($category, $product->getCategory()->getName()) == 0) {
                    $data[] = $this->getProductJson($product);
                  }

              }
              else {
                $data[] = $this->getProductJson($product);
              }
            
          }
          if (!empty($data)) {
            return $this->createSuccessResponse($data, true);
        }
        return $this->createErrorResponse("No product found with given filter(s)!", Response::HTTP_NOT_FOUND);
          
      }

      function getProductJson(Product $product) {
          return [
            'id' => $product->getId(),
            'sku' => $product->getSku(),
            'productName' => $product->getName(),
            'description' => $product->getDescription(),
            'brandName' => $product->getBrand()->getName(),
            'size' => $product->getSize(),
            'color' => $product->getColor()->getHex(),
            'price' => $product->getPrice(),
            'discount' => $product->getDiscount(),
            'texture' => $product->getTexture(),
            'category' => $product->getCategory()->getName(),
            'skintype' => $product->getSkintype(),
            'finish' => $product->getFinish(),
            'applicationArea' => $product->getApplicationarea()->getName(),
            'image' => $product->getImage()->getRelativeFileSystemPath(),
            'rating' => $product->getRating(),
            'availableFrom' => $product->getAvailablefrom()->toDateString(),
            // $obj->getWaterproof();
            // $product->getClassification()->getCosmetics($obj);
            //'classification' => $product->getClassification(),
            /* add here all Objectbricks you need in the condition */
            //'objectbricks' => ['cosmetics','appliance','body','face','hair'],
            /* in the condition access Objectbrick attributes with OBJECTBRICKNAME.ATTRIBUTENAME */
            //'condition' => "cosmetics.waterproof > 2",
            'country' => $product->getCountry(),
            'quantity' => $product->getQuantity()->__toString(),
            'ingredients' => $product->getIngredients(),
            'maximumlife' => $product->getMaximumlife()->__toString(),
          ];
      }
 }
 ?>