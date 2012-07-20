<?php

/**
 * Default_Model_User
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Default_Model_User extends Default_Model_Base_User
{
    const NOT_FOUND = 1;
    const WRONG_PW  = 2;

    /**
     * Perform authentication of a user
     * @param string $email
     * @param string $password
     */
    public static function authenticate($email, $password)
    {
        $user = Doctrine_Core::getTable('Default_Model_User')->findOneByEmail($email);
        if ($user){
            if ($user->password == sha1($password.$user->salt))
                return $user;

            throw new Exception(self::WRONG_PW);
        }
        throw new Exception(self::NOT_FOUND);
    }
    
    public function getRoles(){
    	
    	return $this->Roles;
    }
    
}