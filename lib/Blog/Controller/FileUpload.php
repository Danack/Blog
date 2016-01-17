<?php


namespace Blog\Controller;

use Blog\Repository\SourceFileRepo;
use Blog\Form\SourceFileUploadForm;
use Intahwebz\UploadedFile;
use ASM\Session;
use Blog\Debug;
use Blog\UserPermissions;
use Blog\BlogPermissionException;
use Room11\HTTP\Body\TextBody;
use Tier\Tier;

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

class FileUpload
{
    public function showUpload(
        UserPermissions $userPermissions,
        SourceFileUploadForm $sourceFileUploadForm,
        SourceFileRepo $sourceFileRepo,
        Debug $debug
    ) {
        if (!$userPermissions->isLoggedIn()) {
            throw new BlogPermissionException("Not allowed");
        }
        
        $dataStoredInSession = $sourceFileUploadForm->initFromStorage();
        if (!$dataStoredInSession) {
            return Tier::getRenderTemplateTier('pages/sourceFile/displayUploadForm', [$sourceFileUploadForm]);
        }
        $valid = $sourceFileUploadForm->validate();

        if (!$valid) {
            return Tier::getRenderTemplateTier('pages/sourceFile/displayUploadForm', [$sourceFileUploadForm]);
        }

        list($filename, $text) = $sourceFileUploadForm->getBlogUpload();
        $sourceFileID = $sourceFileRepo->addSourceFile($filename, $text);

        $debug->add("sourcefile uploaded Id is $sourceFileID"); 

        return Tier::getRenderTemplateTier('pages/uploadSuccess');
    }

    /**
     * @return \Tier\Executable
     */
    public function uploadResult()
    {
        return Tier::getRenderTemplateTier('pages/uploadSuccess');
    }
    
    public function listFiles()
    {
        return Tier::getRenderTemplateTier('pages/sourceFile/listFiles');
    }
    
    public function showFile(SourceFileRepo $sourceFileRepo, $filename)
    {
        $sourceFile = $sourceFileRepo->getSourceFile($filename);

        return new TextBody($sourceFile->text);
    }
}
