<?php
/** 
 * @author David
 * 
 * 
 */
class SimpleStore_Acl_Factory
{
    private static $_sessionNameSpace = 'SimpleStore_Acl_Namespace';
    private static $_objAuth;
    private static $_objAclSession;
    private static $_objAcl;
    public static function get (Zend_Auth $objAuth, $clearACL = false)
    {
        self::$_objAuth = $objAuth;
        self::$_objAclSession = new Zend_Session_Namespace(self::$_sessionNameSpace);
        
        if ($clearACL) {
            self::_clear();
        }
        if (isset(self::$_objAclSession->acl)) {
            return self::$_objAclSession->acl;
        } else {
            return self::_loadAclFromDB();
        }
    }
    private static function _clear ()
    {
        unset(self::$_objAclSession->acl);
    }
    private static function _saveAclToSession ()
    {
        self::$_objAclSession->acl = self::$_objAcl;
    }
    private static function _loadAclFromDB ()
    {
		
        $arrRoles = Doctrine::getTable("Default_Model_Role")->findAll();
        $arrResources = Doctrine::getTable("Default_Model_Resource")->findAll();
        $arrRoleResources = Doctrine::getTable("Default_Model_RoleResource")->findAll();

        self::$_objAcl = new Zend_Acl();
        
		if (count($arrRoles)>0):
            foreach ($arrRoles as $role) :
            	self::$_objAcl->addRole(new Zend_Acl_Role($role->name));
        	endforeach;
		endif;
			
		if (count($arrResources)>0):
			foreach ($arrResources as $resource) :
            	self::$_objAcl->addResource(new Zend_Acl_Resource($resource->module . '::' . $resource->controller . '::' . $resource->action));
	        endforeach;
		endif;
		        
		if (count($arrRoleResources)>0):
	        foreach ($arrRoleResources as $roleResource) :
	        	$role = $roleResource->Role;
	        	$resource = $roleResource->Resource;
	        	
	            self::$_objAcl->allow($roleResource->Role->name, $roleResource->Resource->module . '::' . $roleResource->Resource->controller . '::' . $roleResource->Resource->action);
	        endforeach;
        endif;
        
        self::_saveAclToSession();
        return self::$_objAcl;
    }
}
?>