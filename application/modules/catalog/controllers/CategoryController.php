<?php

class Catalog_CategoryController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
    }

    public function productsAction()
    {
    	$this->_helper->layout()->getView()->headTitle('Category Products');
    	$this->_helper->layout()->getView()->headScript()->appendFile('/js/catalog/product.js');
    	$this->_helper->layout()->setLayout('index');
    	
    	$id = (int)$this->getRequest()->getParam("id");
        
        if ($id):
			$categoryTable = Doctrine_Core::getTable('Catalog_Model_Category');
        	$category = $categoryTable->find($id);     
        	$this->view->category = $category;
        	$products = $category->Products;
        	$this->view->products = $products;
        else :
        	$this->_helper->redirector('index');
        endif;
    }


}





