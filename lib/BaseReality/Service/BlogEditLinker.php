<?php

namespace BaseReality\Service;

use Intahwebz\Router;
use Intahwebz\Session\Session;
use BaseReality\Security\AccessControl;

class BlogEditLinker
{

    /**
     * @var Router
     */
    private $router;
    
    public function __construct(Router $router, AccessControl $accessControl, Session $session)
    {
        $this->router = $router;
        $this->session = $session;
        $this->accessControl = $accessControl;
    }

    /**
     * @param $resourceType
     * @param null $privilegeName
     * @return bool
     */
    private function isAccessAllowed($resourceType, $privilegeName = null)
    {
        $userRole = $this->session->getSessionVariable(\BaseReality\Content\BaseRealityConstant::$userRole);
        return $this->accessControl->isAllowed($userRole, $resourceType, $privilegeName);
    }

    /**
     * @param $draftFilename
     * @param $blogPost
     */
    public function render($draftFilename, $blogPost)
    {
        if (!$this->isAccessAllowed('admin', 'edit')) {
            return;
        }
         
        if ($draftFilename) {
            return;
        }

        $editUrl = $this->router->generateURLForRoute('blogPost_Edit', array('blogPostID' => $blogPost->blogPostID));

        $data = array('blogPostID' => $blogPost->blogPostID);
        
        $replaceUrl = $this->router->generateURLForRoute('blogPost_Replace', $data);

        echo <<< END
    <a href = "$editUrl" > Edit title / active </a ><br /> 
    <a href = "$replaceUrl" > Replace text </a ><br />
END;

    }
}
