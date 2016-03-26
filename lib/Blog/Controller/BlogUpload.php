<?php


namespace Blog\Controller;

use Blog\Repository\BlogPostRepo;
use Blog\Form\BlogUploadForm;
use Blog\Debug;
use Blog\UserPermissions;
use Blog\BlogPermissionException;
use Intahwebz\UploadedFile;
use Tier\Bridge\JigExecutable;

function processUploadedFile(UploadedFile $uploadedFile)
{
    $title = str_replace("_", " ", $uploadedFile->name);
    $dotPosition = mb_strpos($title, '.');

    if ($dotPosition !== false) {
        $title = mb_substr($title, 0, $dotPosition);
    }

    $fileContents = file_get_contents($uploadedFile->tmpName);
    if ($fileContents == false) {
        throw new \Exception("Failed to read file contents from file: ".$uploadedFile->tmpName);
    }

    return [$title, $fileContents];
}

class BlogUpload
{
    public function showUpload(
        UserPermissions $userPermissions,
        BlogUploadForm $blogUploadForm,
        BlogPostRepo $blogPostRepo,
        Debug $debug
    ) {
        if (!$userPermissions->isLoggedIn()) {
            throw new BlogPermissionException("Not allowed");
        }
        
        $dataStoredInSession = $blogUploadForm->initFromStorage();
        if (!$dataStoredInSession) {
            return JigExecutable::createWithSharedObjects(
                'pages/displayUploadForm',
                [$blogUploadForm]
            );
        }
        //$session->save();
        $valid = $blogUploadForm->validate();

        if (!$valid) {
            return JigExecutable::createWithSharedObjects(
                'pages/displayUploadForm',
                [$blogUploadForm]
            );
        }

        list($title, $text, $isActive) = $blogUploadForm->getBlogUpload();
        $blogPostID = $blogPostRepo->createBlogPost($title, $text, $isActive);

        $debug->add("blog post ID is $blogPostID"); 

        return JigExecutable::create('pages/uploadSuccess');
    }

    /**
     * @return \Tier\Executable
     */
    public function uploadResult()
    {
        return JigExecutable::create('pages/uploadSuccess');
    }
}
