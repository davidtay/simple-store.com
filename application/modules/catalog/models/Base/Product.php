<?php

/**
 * Catalog_Model_Base_Product
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property string $name
 * @property string $sku
 * @property string $image
 * @property string $author
 * @property decimal $price
 * @property text $description
 * @property Doctrine_Collection $Categories
 * @property Doctrine_Collection $Catalog_Model_ProductCategory
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class Catalog_Model_Base_Product extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('catalog__model__product');
        $this->hasColumn('name', 'string', 50, array(
             'type' => 'string',
             'length' => '50',
             ));
        $this->hasColumn('sku', 'string', 50, array(
             'type' => 'string',
             'length' => '50',
             ));
        $this->hasColumn('image', 'string', 50, array(
             'type' => 'string',
             'length' => '50',
             ));
        $this->hasColumn('author', 'string', 50, array(
             'type' => 'string',
             'length' => '50',
             ));
        $this->hasColumn('price', 'decimal', null, array(
             'type' => 'decimal',
             ));
        $this->hasColumn('description', 'text', null, array(
             'type' => 'text',
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('Catalog_Model_Category as Categories', array(
             'refClass' => 'Catalog_Model_ProductCategory',
             'local' => 'product_id',
             'foreign' => 'category_id'));

        $this->hasMany('Catalog_Model_ProductCategory', array(
             'local' => 'id',
             'foreign' => 'product_id'));
    }
}