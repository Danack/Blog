<?php


namespace Blog\Repository\Stub;

use Blog\Content\BlogPost;
use Blog\Repository\BlogPostRepo;

class BlogPostStubRepo implements BlogPostRepo
{
    public static function getNextBlogPostID()
    {
        static $id = 100;
        $id++;

        return $id;
    }
    
    /**
     * @param $blogPostID
     * @return \Blog\Content\BlogPost
     * @throws \Exception
     */
    public function getBlogPost($blogPostID)
    {
        $blogPostText = <<< 'TEXT'

{markdown}

Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed sed lobortis erat. Aliquam aliquet sapien et placerat varius. Donec suscipit quam ut facilisis tristique. Mauris cursus tincidunt nisi, a vestibulum nisi accumsan eu. Nulla facilisi. Mauris et nibh sit amet lorem condimentum pellentesque id in neque. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.

Mauris lobortis, risus quis aliquet convallis, ante lorem rhoncus quam, sed lacinia lorem nulla eu enim. Praesent eu congue orci. Vivamus dapibus massa et eleifend aliquam. Ut consectetur rhoncus purus, at suscipit mauris. Integer eu elit dui. Vivamus fringilla dolor ut ante facilisis tincidunt. Vivamus eleifend aliquam leo id malesuada. Mauris iaculis efficitur nunc finibus accumsan. Phasellus sagittis feugiat arcu eu dictum.

Vestibulum quis orci tortor. Nunc vestibulum condimentum nibh, et varius nibh posuere et. Quisque bibendum lorem justo, vel viverra arcu consectetur ut. Phasellus mattis efficitur dolor, in semper est vestibulum a. Suspendisse et ullamcorper purus. Pellentesque tristique aliquet blandit. Lorem ipsum dolor sit amet, consec

{syntaxHighlighter}
class DatabaseUsername {
    private $value;
    public function __construct($value) {
        $this->value = $value;
    }
    public function getValue() {
        return $this->value;
    }
}
{/syntaxHighlighter}

This is some non-PHP code.

{syntaxHighlighter}
    set $originalURI  $uri;
    try_files $uri /routing.php /50x_static.html;
    fastcgi_param  QUERY_STRING  q=$originalURI&$query_string;
{/syntaxHighlighter}


Vestibulum quis orci tortor. Nunc vestibulum condimentum nibh, et varius nibh posuere et. Quisque bibendum lorem justo, vel viverra arcu consectetur ut. Phasellus mattis efficitur dolor, in semper est vestibulum a. Suspendisse et ullamcorper purus. Pellentesque tristique aliquet blandit. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed tristique lorem ultricies tempus consectetur. Fusce convallis lacus vitae dui tempus malesuada.

{/markdown}

TEXT;

        $blogPost = BlogPost::create(
            $blogPostID,
            $title = "This is a stub blog post",
            $text = $blogPostText,
            $datestamp = '2014-05-28 02:06:40',
            $isActive = true,
            $blogPostTextID = $blogPostID
        );

        return $blogPost;
    }

    /**
     * @param $year
     * @param $includeInactive
     * @return \Blog\Content\BlogPost[]
     * @throws \Exception
     * @throws \Intahwebz\DB\DBException
     */
    public function getBlogPostsForYear($year, $includeInactive)
    {
        $blogPosts = [];
        $blogPosts[] = $this->getBlogPost(1);
        $blogPosts[] = $this->getBlogPost(2);
        $blogPosts[] = $this->getBlogPost(3);
        $blogPosts[] = $this->getBlogPost(4);

        return $blogPosts;
    }

    /**
     * @param $title
     * @param $isActive
     * @param $blogPostID
     * @throws \Exception
     */
    public function updateBlogPost($title, $isActive, $blogPostID)
    {
        //not implemented, so just work silently
    }

    /**
     * @param $title
     * @param $text
     * @param $isActive
     * @throws \Exception
     * @return int
     */
    public function createBlogPost($title, $text, $isActive)
    {
        //not implemented, so just work silently
        return 1;
    }

    /**
     * @param $blogPostID
     * @param $text
     * @throws \Exception
     */
    public function updateBlogPostText($blogPostID, $text)
    {
        //throw new \Exception("Not implemented");
    }
}
