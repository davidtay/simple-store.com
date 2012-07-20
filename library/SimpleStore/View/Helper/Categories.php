<?php
/**
 *
 * @author David Tay
 * @version 
 */
require_once 'Zend/View/Interface.php';
/**
 * Categories helper
 *
 * @uses viewHelper SimpleStore_View_Helper
 */
class SimpleStore_View_Helper_Categories
{
    /**
     * @var Zend_View_Interface 
     */
    public $view;
    /**
     * 
     */
    public function categories ()
    {
        $categoryTable = Doctrine_Core::getTable('Catalog_Model_Category');
        $categories = $categoryTable->findAll();
        return $categories;
    }
    /**
     * Sets the view field 
     * @param $view Zend_View_Interface
     */
    public function setView (Zend_View_Interface $view)
    {
        $this->view = $view;
    }
}
