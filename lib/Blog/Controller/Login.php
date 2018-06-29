<?php

namespace Blog\Controller;

use ASM\Session;
use BaseReality\Security\Role;
use Blog\Form\LoginForm;
use Blog\Site\LoginStatus;
use Blog\UserPermissions;
use Blog\Repository\LoginRepo;
use Room11\HTTP\Response;
use Room11\HTTP\Body\RedirectBody;
use Room11\HTTP\VariableMap;
use Tier\Bridge\JigExecutable;
use Google\Authenticator\GoogleAuthenticator;
use Blog\Config;

class Login
{
    /**
     * @param LoginForm $loginForm
     * @param UserPermissions $userPermissions
     * @return RedirectBody|\Tier\Executable
     */
    public function loginGet(
        LoginForm $loginForm,
        UserPermissions $userPermissions
    ) {
        if ($userPermissions->isLoggedIn()) {
            return new RedirectBody("Already logged in", "/", 303);
        }

        $dataStoredInSession = $loginForm->initFromStorage();
        if ($dataStoredInSession) {
            $loginForm->validate();
        }

        return JigExecutable::createWithSharedObjects(
            'pages/login',
            ['BaseReality\Form\LoginForm' => $loginForm]
        );
    }

    /**
     * @param VariableMap $variableMap
     * @param LoginForm $loginForm
     * @param Session $session
     * @param LoginRepo $loginMapper
     * @return RedirectBody
     */
    public function loginPost(
        VariableMap $variableMap,
        LoginForm $loginForm,
        Session $session,
        LoginRepo $loginMapper,
        Config $config
    ) {
        $wasValid = false;
        $validCallback = function(LoginForm $loginForm) use ($session, $loginMapper, $config, &$wasValid) {
            $username =  $loginForm->getValue('end', 'username');
            $password =  $loginForm->getValue('end', 'password');
            $auth_code =  $loginForm->getValue('end', 'auth_code');

            if ($loginMapper->isLoginValid($username, $password) == true) {
                $secret = $config->getKey(Config::GOOGLE_AUTHENTICATOR_SECRET);

                $g = new GoogleAuthenticator();
                if ($g->checkCode($secret, $auth_code)) {
                    $session->setSessionVariable(
                        \Blog\Site\Constant::$userRole,
                        Role::ADMIN
                    );
                    $wasValid = true;
                    return;
                }
            } 

            $loginForm->setFormError("Username or password or auth incorrect");
        };

        $loginForm->initFromSubmittedData($variableMap);
        $loginForm->validate($validCallback);

        if ($loginForm->hasError()) {
            $loginForm->saveValuesToStorage();
        }
        else {
            $validCallback($loginForm);
            if ($wasValid == false) {
                $loginForm->saveValuesToStorage();
            }
        }

        return new RedirectBody("Form submitted", "/login", 303);
    }


    /**
     * @param LoginStatus $loginStatus
     * @internal param Session $session
     * @return RedirectBody
     */
    public function logout(LoginStatus $loginStatus)
    {
        // About to delete all session info
        // This destroys all session information. Any info we want to
        // retain needs to be set again.
        $loginStatus->logoutUser();

        return new RedirectBody("Form submitted", "/login", 303);
    }
}
