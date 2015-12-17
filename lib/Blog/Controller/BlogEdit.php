<?php

namespace Blog\Controller;

use Blog\Form\BlogEditForm;
use Blog\Form\BlogReplaceForm;
use Blog\Repository\BlogPostRepo;
use Blog\TemplatePlugin\BlogPostPlugin;
use Blog\UserPermissions;
use Blog\BlogPermissionException;

class BlogEdit
{
    public function showEdit(
        UserPermissions $userPermissions,
        BlogPostRepo $blogPostMapper,
        BlogEditForm $blogEditForm,
        $blogPostID
    ) {        
        if (!$userPermissions->isLoggedIn()) {
            throw new BlogPermissionException("Not allowed");
        }
        
        $storedData = $blogEditForm->initFromStorage();
        if ($storedData) {
            $valid = $blogEditForm->validate();
            if ($valid) {
                $title = $blogEditForm->getValue('end', 'title');
                $isActive = $blogEditForm->getValue('end', 'isActive');
                $blogPostMapper->updateBlogPost($title, $isActive, $blogPostID);

                return \Tier\getRenderTemplateTier('pages/blogEditSuccess');
            }
            else {
                return \Tier\getRenderTemplateTier('pages/blogEdit', [$blogEditForm]);
            }
        }
        
        $blogPost = $blogPostMapper->getBlogPost($blogPostID);
        $blogEditForm->initFromBlogPost($blogPost);

        return \Tier\getRenderTemplateTier('pages/blogEdit', [$blogEditForm]);
    }

    /**
     * @param BlogReplaceForm $blogReplaceForm
     * @param BlogPostRepo $blogPostMapper
     * @param BlogPostPlugin $blogPostPlugin
     * @param $blogPostID
     * @return \Tier\Executable
     */
    public function showReplace(
        UserPermissions $userPermissions,
        BlogReplaceForm $blogReplaceForm,
        BlogPostRepo $blogPostMapper,
        $blogPostID
    ) {
        if (!$userPermissions->isLoggedIn()) {
            throw new BlogPermissionException("Not allowed");
        }

        $storedData = $blogReplaceForm->initFromStorage();
        if ($storedData) {
            $valid = $blogReplaceForm->validate();
            if ($valid) {
                $uploadedFile = $blogReplaceForm->getValue('end', 'blogFile');
                /** @var $uploadedFile \FCForms\UploadedFile */
                $fileContents = file_get_contents($uploadedFile->getFilename());
                $blogPostMapper->updateBlogPostText($blogPostID, trim($fileContents));

                return \Tier\getRenderTemplateTier('pages/replaceSuccess');
            }
            else {
                return \Tier\getRenderTemplateTier('pages/displayReplaceForm', [$blogReplaceForm]);
            }
        }

        $blogReplaceForm->initFromData([]);

        return \Tier\getRenderTemplateTier('pages/displayReplaceForm', [$blogReplaceForm]);
    }
}
