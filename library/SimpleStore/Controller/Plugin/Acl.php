<?php
/** 
 * @author David Tay
 * 
 * 
 */
class SimpleStore_Controller_Plugin_Acl extends Zend_Controller_Plugin_Abstract
{
    public function preDispatch (Zend_Controller_Request_Abstract $request)
    {
    	if ($request->getModuleName()=="default")
    		return;
    	
        $objAuth = Zend_Auth::getInstance();
        $clearACL = false;
        // initially treat the user as a guest so we can determine if the current
        // resource is accessible by guest users
        $role = 'guest';
        // if its not accessible then we need to check for a user login
        // if the user is logged in then we check if the role of the logged
        // in user has the credentials to access the current resource
        
        $resource = $request->getModuleName() . '::' . $request->getControllerName() . '::' . $request->getActionName();
        try {
            if ($objAuth->hasIdentity()) {
                $user = $objAuth->getIdentity();
                $sess = new Zend_Session_Namespace('SimpleStore_Acl');
                if ($sess->clearACL) {
                    $clearACL = true;
                    unset($sess->clearACL);
                }
                
                $isAllowed = false;
                $objAcl = SimpleStore_Acl_Factory::get($objAuth, true);
                $roles = $user->getRoles();
                $resource = $request->getModuleName().'::'.$request->getControllerName().'::'.$request->getActionName();
                
                foreach($roles as $role):
                	if ($objAcl->isAllowed($role->name, $resource))
                		$isAllowed = true;
                endforeach;
                //var_dump($isAllowed);
                
                if (!$isAllowed) {
                    $request->setModuleName('default');
                    $request->setControllerName('error');
                    $request->setActionName('index');
                }
            } else {
            	
                $objAcl = SimpleStore_Acl_Factory::get($objAuth, $clearACL);
                $isAllowed = $objAcl->isAllowed($role, $resource);
                
                if (!$isAllowed && $resource !="admin::login::index") {
                	$urlOptions = array('module'=>'admin','controller'=>'login', 'action'=>'index');
					return Zend_Controller_Action_HelperBroker::getStaticHelper('redirector')->gotoRoute($urlOptions);
                }
            }
        } catch (Zend_Exception $e) {
        	var_dump($e->getMessage());
            $request->setModuleName('default');
            $request->setControllerName('error');
            $request->setActionName('error');
        }
    }
	
}
?>