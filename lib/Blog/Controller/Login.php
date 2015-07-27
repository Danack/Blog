<?php

namespace Blog\Controller;

use Intahwebz\Response\RedirectResponse;
use Intahwebz\Session;
use Blog\Mapper\LoginMapper;
use BaseReality\Form\LoginForm;
use BaseReality\Security\Role;
//use BaseReality\Security\Role;
//use Intahwebz\Response\TemplateResponseFactory;

use Arya\Response;
use Arya\RedirectBody;

class Login
{
    public function loginGet(
        Response $response,
        LoginForm $loginForm,
        Session $session,
        LoginMapper $loginMapper
    ) {
        $dataStoredInSession = $loginForm->getSessionStoredData(true);

        if ($loginForm->isSubmitted()) {
            $valid = $loginForm->validate();
            $values = $loginForm->getAllValues();

            $loginForm->setFormError("Username was not accepted.");
        }

        return getRenderTemplateTier('pages/login');
    }

    public function loginPost(
        Response $response,
        LoginForm $loginForm,
        Session $session,
        LoginMapper $loginMapper
    ) {
        $loginForm->useSubmittedValues();
        
        $valid = $loginForm->validate();

        if ($valid) {
            $values = $loginForm->getAllValues();

            if ($loginMapper->isLoginValid($values['username'], $values['password']) == true) {
                $session->setSessionVariable(
                    \BaseReality\Content\BaseRealityConstant::$userRole,
                    Role::ADMIN
                );

                $response->setStatus(302);
                //Logged in

                return new RedirectBody("asdd", '/');
            }
        }
        
        $loginForm->storeValuesInSession();
        $response->setStatus(302);

        return new RedirectBody("This should redirect", '/login');
    }

    /**
     * @param Session $session
     * @return RedirectBody
     */
    public function logout(Session $session)
    {
        $redirectURL = $session->getSessionVariable('redirectURL', false);

        // About to delete all session info
        // This destroys all session information. Any info we want to
        // retain needs to be set again.
        $session->logoutUser();
        //All session info deleted -
        if ($redirectURL != false) {
            $session->setSessionVariable('redirectURL', $redirectURL);
        }
        
        //$session->logoutUser();

        $redirectResponse = new RedirectResponse('/');
        $redirectResponse->unsetCookie(SESSION_NAME);
        $redirectResponse->unsetCookie('sessionID');
        $redirectResponse->unsetCookie('cookieID');
        $redirectResponse->unsetCookie('secureLoginCheck');

        return new RedirectBody("asdd", '/');
    }
}
