<?php

/**
 * Simple PDO storage for all storage types
 *
 * NOTd: This class is meant to get users started 
 * quickly. If your application requires further 
 * customization, extend this class or create your own.
 *
 * @author Simon Roberts <simon@labs.coop>
 */
class OAuth2_Storage_Xoops implements OAuth2_Storage_AuthorizationCodeInterface,
    OAuth2_Storage_AccessTokenInterface, OAuth2_Storage_ClientCredentialsInterface,
    OAuth2_Storage_UserCredentialsInterface, OAuth2_Storage_RefreshTokenInterface, OAuth2_Storage_JWTBearerInterface
{

    /* ClientCredentialsInterface */
    public function checkClientCredentials($client_id, $client_secret = null)
    {
    	$handler = xoops_getmodulehandler('oauth_clients', 'profile');
    	$criteria = new Criteria('client_id', $client_id);
    	$objects = $handler->getObjects($criteria);
    	foreach($objects as $object)
			if ($object->getVar('client_secret') == $client_secret)
    			return true;
    	return false;
    }

    public function getClientDetails($client_id)
    {
    	$handler = xoops_getmodulehandler('oauth_clients', 'profile');
    	$criteria = new Criteria('client_id', $client_id);
    	$objects = $handler->getObjects($criteria);
    	if (isset($objects[0]))
    		return $objects[0]->toArray();
    	return array();
    }

    public function checkRestrictedGrantType($client_id, $grant_type)
    {
        $details = $this->getClientDetails($client_id);
        if (isset($details['grant_types'])) {
            return in_array($grant_type, (array) $details['grant_types']);
        }

        // if grant_types are not defined, then none are restricted
        return true;
    }

    /* AccessTokenInterface */
    public function getAccessToken($access_token)
    {
        $handler = xoops_getmodulehandler('oauth_access_tokens', 'profile');
    	$criteria = new Criteria('access_token', $access_token);
    	$objects = $handler->getObjects($criteria);
    	if (isset($objects[0]))
	     	if ($token = $objects[0]->toArray()) {
	            // convert date string back to timestamp
	            $token['expires'] = strtotime($token['expires']);
	        }

        return $token;
    }

    public function setAccessToken($access_token, $client_id, $user_id, $expires, $scope = null)
    {
        // convert expires to datestring
        $expires = date('Y-m-d H:i:s', $expires);
        $handler = xoops_getmodulehandler('oauth_access_tokens', 'profile');
        $criteria = new Criteria('access_token', $access_token);
        $objects = $handler->getObjects($criteria);
        if (isset($objects[0])) {
            $objects[0]->setVar('client_id', $client_id);
            $objects[0]->setVar('expires', $expires);
            $objects[0]->setVar('user_id', $user_id);
            $objects[0]->setVar('scope', $scope);
        } else {
        	$objects[0] = $handler->create(true);
        	$objects[0]->setVar('access_token', $access_token);
        	$objects[0]->setVar('client_id', $client_id);
        	$objects[0]->setVar('expires', $expires);
        	$objects[0]->setVar('user_id', $user_id);
        	$objects[0]->setVar('scope', $scope);
        }
        return $handler->insert($objects[0])!=0;
    }

    /* AuthorizationCodeInterface */
    public function getAuthorizationCode($code)
    {
    	$handler = xoops_getmodulehandler('oauth_authorization_codes', 'profile');
    	$criteria = new Criteria('authorization_code', $code);
    	$objects = $handler->getObjects($criteria);
    	if (isset($objects[0]))
	        if ($code = $objects[0]->toArray()) {
	            // convert date string back to timestamp
	            $code['expires'] = strtotime($code['expires']);
	        }

        return $code;
    }

    public function setAuthorizationCode($code, $client_id, $user_id, $redirect_uri, $expires, $scope = null)
    {
        // convert expires to datestring
        $expires = date('Y-m-d H:i:s', $expires);

        // if it exists, update it.
        $handler = xoops_getmodulehandler('oauth_authorization_codes', 'profile');
    	$criteria = new Criteria('authorization_code', $code);
    	$objects = $handler->getObjects($criteria);
    	if (isset($objects[0])) {
    		$objects[0]->setVar('client_id', $client_id);
    		$objects[0]->setVar('redirect_uri', $redirect_uri);
    		$objects[0]->setVar('user_id', $user_id);
    		$objects[0]->setVar('scope', $scope);
    		$objects[0]->setVar('expires', $expires);
    		
        } else {
        	$objects[0] = $handler->create(true);
        	$objects[0]->setVar('authorization_code', $code);
        	$objects[0]->setVar('client_id', $client_id);
        	$objects[0]->setVar('redirect_uri', $redirect_uri);
        	$objects[0]->setVar('user_id', $user_id);
        	$objects[0]->setVar('scope', $scope);
        	$objects[0]->setVar('expires', $expires);
        }
        return $handler->insert($objects[0])!=0;
    }

    public function expireAuthorizationCode($code)
    {
    	$handler = xoops_getmodulehandler('oauth_authorization_codes', 'profile');
    	$criteria = new Criteria('authorization_code', $code);
    	return $handler->deleteAll($criteria);
    }

    /* UserCredentialsInterface */
    public function checkUserCredentials($username, $password)
    {
    	if ($user = $this->getUser($username)) {
            return $this->checkPassword($user, $password);
        }
        return false;
    }

    public function getUserDetails($username)
    {
        return $this->getUser($username);
    }

    /* RefreshTokenInterface */
    public function getRefreshToken($refresh_token)
    {
    	$handler = xoops_getmodulehandler('oauth_refresh_tokens', 'profile');
    	$criteria = new Criteria('refresh_token', $refresh_token);
    	$objects = $handler->getObjects($criteria);
    	if (isset($objects[0]))
	        if ($token = $objects[0]->toArray()) {
	            // convert expires to epoch time
	            $token['expires'] = strtotime($token['expires']);
	        }
        return $token;
    }

    public function setRefreshToken($refresh_token, $client_id, $user_id, $expires, $scope = null)
    {
        // convert expires to datestring
        $expires = date('Y-m-d H:i:s', $expires);

        $handler = xoops_getmodulehandler('oauth_refresh_tokens', 'profile');
        $object = $handler->create(true);
        $object->setVar('refresh_token', $refresh_token);
        $object->setVar('client_id', $client_id);
        $object->setVar('redirect_uri', $redirect_uri);
        $object->setVar('scope', $scope);
        $object->setVar('expires', $expires);
        
        return $handler->insert($object)!=0;
    }

    public function unsetRefreshToken($refresh_token)
    {
    	$handler = xoops_getmodulehandler('oauth_refresh_tokens', 'profile');
    	$criteria = new Criteria('refresh_token', $refresh_token);
    	return $handler->deleteAll($criteria);
    }

    // plaintext passwords are bad!  Override this for your application
    protected function checkPassword($user, $password)
    {
    	if (strlen($password)!=32||strtolower($password)!=$password)
    		$password = md5($password);
        return $user['pass'] == $password;
    }

    public function getUser($username, $as_array = true)
    {
    	$handler = xoops_gethandler('user');
    	$criteria = new Criteria('uname', $username);
    	$objects = $handler->getObjects($criteria);
    	if (isset($objects[0]))
    		if ($as_array==true)
	        	return $objects[0]->toArray();
    		else
    			return $objects[0];
    	if ($as_array==true)
    		return array();
    	else
    		return $handler->create();
    }

    public function setUser($username, $password, $firstName = null, $lastName = null)
    {
    	if (strlen($password)!=32||strtolower($password)!=$password)
    		$password = md5($password);
    	 
    	// if it exists, update it.
        if ($user = $this->getUser($username)) {
        	$user->setVar('uname', $username);
        	$user->setVar('pass', $password);
            $user->setVar('name', $firstName . ' ' . $lastName);
        }
        $handler = xoops_gethandler('user');
        return $handler->insert($user)!=0;
    }

    public function getClientKey($client_id, $subject)
    {
    	$handler = xoops_getmodulehandler('oauth_jwt', 'profile');
    	$criteria = new CriteriaCompo(new Criteria('client_id', $client_id));
    	$criteria->addElement(new Criteria('subject', $subject));
    	$objects = $handler->getObjects($criteria);
    	if (isset($objects[0]))
    		return $objects[0]->toArray();
    	return array();
    }
}
