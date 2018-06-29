<?php

declare(strict_types=1);

namespace Blog\Repository\BlogPostRepo;

use Blog\Content\BlogPost;
use Blog\Repository\BlogPostRepo;


class FileBlogPostRepo implements BlogPostRepo
{
    public function getBlogPost($blogPostID)
    {
        $rawList = $this->getRawList();

        $blogPostID = intval($blogPostID);

        foreach ($rawList as $raw) {
            if ($raw[0] === $blogPostID) {
                $path = __DIR__ . '/../../../../posts/'. $raw[2];
                $content = @file_get_contents($path);
                if ($content === false) {
                    throw new \Exception("File " . $raw[2] . "not found.");
                }

                return BlogPost::create(
                    $blogPostID, //$blogPostID,
                    $raw[1], // $title,
                    $content, //$text,
                    $raw[3], // $datestamp,
                    true //$isActive
                );
            }
        }

        throw new \Exception("Blog post ID $blogPostID not found.");
    }

    public function getBlogPostsForYear($year, $includeInactive)
    {
        $blogPost1 = $this->getBlogPost(1);
        $blogPost2 = $this->getBlogPost(2);
        return [$blogPost2, $blogPost1];
    }

    public function updateBlogPost($title, $isActive, $blogPostID)
    {
        throw new \Exception("// TODO: Implement updateBlogPost() method.");
    }

    public function createBlogPost($title, $text, $isActive)
    {
        throw new \Exception("// TODO: Implement createBlogPost() method.");
    }

    public function updateBlogPostText($blogPostID, $text)
    {
        throw new \Exception("TODO: Implement updateBlogPostText() method.");
    }

    private function getRawList()
    {
        return [
            [8, 'title_8', 'anti_alias_image_gd.tpl', date('Y-M-d H:m')],
            [7, 'title_7', 'arguing_on_the_internet.tpl', date('Y-M-d H:m')],
            [6, 'title_6', 'including_functions.tpl', date('Y-M-d H:m')],
            [5, 'title_5', 'naming_conventions.tpl', date('Y-M-d H:m')],
            [4, 'title_4', 'nginx_complete_config.tpl', date('Y-M-d H:m')],
            [3, 'title_3', 'php_you_are_drunk.tpl', date('Y-M-d H:m')],
            [2, 'title_2', 'too_many_frameworks.tpl', date('Y-M-d H:m')],
            [1, 'title_1', 'frist_post.tpl', date('Y-M-d H:m')],
        ];
    }
}
