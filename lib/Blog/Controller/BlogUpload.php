<?php


namespace Blog\Controller;

use Blog\Repository\BlogPostRepo;
use BaseReality\Form\BlogUploadForm;
use Intahwebz\UploadedFile;
use ASM\Session;
use Blog\Debug;


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
        Session $session,
        BlogUploadForm $blogUploadForm,
        BlogPostRepo $blogPostRepo,
        Debug $debug
    ) {
        $dataStoredInSession = $blogUploadForm->initFromStorage();
        if (!$dataStoredInSession) {
            return \Tier\getRenderTemplateTier('pages/displayUploadForm', [$blogUploadForm]);
        }
        //$session->save();
        $valid = $blogUploadForm->validate();

        if (!$valid) {
            return \Tier\getRenderTemplateTier('pages/displayUploadForm', [$blogUploadForm]);
        }

        list($title, $text, $isActive) = $blogUploadForm->getBlogUpload();
        $blogPostID = $blogPostRepo->createBlogPost($title, $text, $isActive);

        $debug->add("blog post ID is $blogPostID"); 

        return \Tier\getRenderTemplateTier('pages/uploadSuccess');
    }

    /**
     * @return \Tier\Executable
     */
    public function uploadResult()
    {
        return \Tier\getRenderTemplateTier('pages/uploadSuccess');
    }
}
