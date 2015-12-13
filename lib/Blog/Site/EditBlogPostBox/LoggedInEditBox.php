<?php

namespace Blog\Site\EditBlogPostBox;

use Blog\Site\EditBlogPostBox;
use Blog\Model\ActiveBlogPost;

class LoggedInEditBox extends EditBlogPostBox
{
    /** @var ActiveBlogPost  */
    private $activeBlogPost;

    public function __construct(ActiveBlogPost $activeBlogPost)
    {
        $this->activeBlogPost = $activeBlogPost;
    }

    public function render()
    {        
        $html = <<< HTML
<div class="row">
    <div class="col-md-12">
        You are logged in:<br/>
        <a href="%s">
             Edit blog post
        </a><br/>
        <a href="%s">
             Replace text
        </a><br/>
        <hr/>
    </div>
</div>
HTML;

        $output = sprintf(
            $html,
            routeBlogEdit($this->activeBlogPost->blogPost->blogPostID),
            routeBlogReplace($this->activeBlogPost->blogPost->blogPostID)
        );

        return $output;
    }
}
