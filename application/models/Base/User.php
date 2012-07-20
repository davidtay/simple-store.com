<?php

/**
 * Default_Model_Base_User
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property string $firstname
 * @property string $lastname
 * @property string $email
 * @property string $password
 * @property string $salt
 * @property Doctrine_Collection $Roles
 * @property Doctrine_Collection $Addresses
 * @property Doctrine_Collection $Default_Model_UserRole
 * @property Doctrine_Collection $Checkout_Model_Order
 * @property Doctrine_Collection $Default_Model_Reset
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class Default_Model_Base_User extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('default__model__user');
        $this->hasColumn('firstname', 'string', 255, array(
             'type' => 'string',
             'length' => '255',
             ));
        $this->hasColumn('lastname', 'string', 255, array(
             'type' => 'string',
             'length' => '255',
             ));
        $this->hasColumn('email', 'string', 255, array(
             'type' => 'string',
             'length' => '255',
             ));
        $this->hasColumn('password', 'string', 255, array(
             'type' => 'string',
             'length' => '255',
             ));
        $this->hasColumn('salt', 'string', 255, array(
             'type' => 'string',
             'length' => '255',
             ));


        $this->index('email_unique', array(
             'fields' => 
             array(
              0 => 'email',
             ),
             'type' => 'unique',
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('Default_Model_Role as Roles', array(
             'refClass' => 'Default_Model_UserRole',
             'local' => 'user_id',
             'foreign' => 'role_id'));

        $this->hasMany('Default_Model_Address as Addresses', array(
             'local' => 'id',
             'foreign' => 'user_id'));

        $this->hasMany('Default_Model_UserRole', array(
             'local' => 'id',
             'foreign' => 'user_id'));

        $this->hasMany('Checkout_Model_Order', array(
             'local' => 'id',
             'foreign' => 'user_id'));

        $this->hasMany('Default_Model_Reset', array(
             'local' => 'id',
             'foreign' => 'user_id'));

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