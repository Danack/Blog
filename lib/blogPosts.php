<?php

declare(strict_types=1);



function getAllBlogPosts()
{

    $blogPosts[] = new \Blog\DTO\BlogPostDTO(

        1, // $blogPostID
        "Test blog post", // $title
        "testing.jig"    //$filename
    );



    return $blogPosts;
}

