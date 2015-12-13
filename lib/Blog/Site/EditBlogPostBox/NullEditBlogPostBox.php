<?php

namespace Blog\Site\EditBlogPostBox;

use Blog\Site\EditBlogPostBox;

class NullEditBlogPostBox extends EditBlogPostBox
{
    public function render()
    {
        return "";
    }
}
