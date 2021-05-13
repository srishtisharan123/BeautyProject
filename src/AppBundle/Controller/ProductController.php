<?php

namespace AppBundle\Controller;

use Pimcore\Controller\FrontendController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class ProductController extends FrontendController
{
    public function indexAction(Request $request)
    {
        $perPageLimit = 3;
            
        if (isset($_GET["page"])) {
          $page  = $_GET["page"];    
        }    
        else {
          $page = 1;    
        } 
        $offset = ($page-1) * $perPageLimit; 

        $prod = new \Pimcore\Model\DataObject\Product\Listing();
        $totalProducts = count($prod);
        $prod->setLimit($perPageLimit);
        if($offset > 0){
          $prod->setOffset($offset);
        }
        else{
          $prod->setOffset(0);
        }
        $prod->setOrderKey('name');
        $prod->setOrder('asc');

        $this->view->perPageLimit = $perPageLimit;
        $this->view->page = $page;
        $this->view->totalProducts = $totalProducts;
        $this->view->prod = $prod;
        // return $this->render(":Product:index.html.php", ["foo" => "bar"]);

    }  

}
