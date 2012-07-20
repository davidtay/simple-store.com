<?php

/**
 * Default_Model_Base_Resource
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property string $module
 * @property string $controller
 * @property string $action
 * @property string $name
 * @property string $routeName
 * @property Doctrine_Collection $Roles
 * @property Doctrine_Collection $Default_Model_RoleResource
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class Default_Model_Base_Resource extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('default__model__resource');
        $this->hasColumn('module', 'string', 255, array(
             'type' => 'string',
             'length' => '255',
             ));
        $this->hasColumn('controller', 'string', 255, array(
             'type' => 'string',
             'length' => '255',
             ));
        $this->hasColumn('action', 'string', 255, array(
             'type' => 'string',
             'length' => '255',
             ));
        $this->hasColumn('name', 'string', 255, array(
             'type' => 'string',
             'length' => '255',
             ));
        $this->hasColumn('routeName', 'string', 255, array(
             'type' => 'string',
             'length' => '255',
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('Default_Model_Role as Roles', array(
             'refClass' => 'Default_Model_RoleResource',
             'local' => 'resource_id',
             'foreign' => 'role_id'));

        $this->hasMany('Default_Model_RoleResource', array(
             'local' => 'id',
             'foreign' => 'resource_id'));

        $timestampable0 = new Doctrine_Template_Timestampable(array(
             'created' => 
             array(
              'name' => 'date_created',
              'type' => 'timestamp',
              'format' => 'Y-m-d H:i:s',
             ),
             'updated' => 
             array(
              'name' => 'date_updated',
              'type' => 'timestamp',
              'format' => 'Y-m-d H:i:s',
             ),
             ));
        $this->actAs($timestampable0);
    }
}