<?php


namespace Blog\Controller;

use Blog\Mapper\BlogPostMapper;
use BaseReality\Form\BlogUploadForm;
use Intahwebz\UploadedFile;
use Arya\RedirectBody;

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
    public function showUpload(BlogUploadForm $blogUploadForm)
    {
        $storedData = $blogUploadForm->getSessionStoredData(true);
        if (!$storedData) {
            $blogUploadForm->addRowValues('new', []);
        }

        return getRenderTemplateTier('pages/displayUploadForm');
    }

    public function uploadPost(
        BlogUploadForm $blogUploadForm,
        BlogPostMapper $blogPostMapper
    ) {
        $blogUploadForm->useSubmittedValues();
        $valid = $blogUploadForm->validate();

        if (!$valid) {
            return new RedirectBody("asdd", '/upload');
        }

        $newLink = $blogUploadForm->getRowValues('new');
        list($title, $text) = processUploadedFile($newLink['blogFile']);
        $blogPostMapper->createBlogPost($title, $text);
        $blogUploadForm->reset();

        return new RedirectBody("asdd", '/uploadResult');
    }

    /**
     * @return \Tier\Tier
     */
    public function uploadResult()
    {
        return getRenderTemplateTier('pages/uploadSuccess');
    }
}
