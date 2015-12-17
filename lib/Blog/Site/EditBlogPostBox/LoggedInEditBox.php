<?php

namespace Blog\Site\EditBlogPostBox;

use Blog\Site\EditBlogPostBox;
use Blog\Model\ActiveBlogPost;
use Blog\Route;

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
        BlogPost is: %s <br/>

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

        $activeString = 'not active';
        if ($this->activeBlogPost->blogPost->isActive) {
            $activeString = 'ACTIVE';
        }

        $output = sprintf(
            $html,
            $activeString,
            Route::blogEdit($this->activeBlogPost->blogPost->blogPostID),
            Route::blogReplace($this->activeBlogPost->blogPost->blogPostID)
        );

        return $output;
    }
}
