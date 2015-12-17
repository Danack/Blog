<?php

namespace Blog\Site;

use Blog\Site\EditBlogPostBox\LoggedInEditBox;
use Blog\Site\EditBlogPostBox\NullEditBlogPostBox;
use Blog\Model\ActiveBlogPost;


abstract class EditBlogPostBox
{
    abstract public function render();

    public static function createEditBox(
        LoginStatus $loginStatus,
        ActiveBlogPost $activeBlogPost
    ) {
        if ($loginStatus->isLoggedIn()) {
            return new LoggedInEditBox($activeBlogPost);
        }

        return new NullEditBlogPostBox();
    }
}


