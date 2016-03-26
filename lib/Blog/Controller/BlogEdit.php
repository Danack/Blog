<?php

namespace Blog\Controller;

use Blog\Form\BlogEditForm;
use Blog\Form\BlogReplaceForm;
use Blog\Repository\BlogPostRepo;
use Blog\UserPermissions;
use Blog\BlogPermissionException;
use Tier\Bridge\JigExecutable;

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

                    return JigExecutable::create('pages/blogEditSuccess');
            }
            else {
                return JigExecutable::createWithSharedObjects('pages/blogEdit', [$blogEditForm]);
            }
        }
        
        $blogPost = $blogPostMapper->getBlogPost($blogPostID);
        $blogEditForm->initFromBlogPost($blogPost);

        return JigExecutable::createWithSharedObjects('pages/blogEdit', [$blogEditForm]);
    }

    /**
     * @param UserPermissions $userPermissions
     * @param BlogReplaceForm $blogReplaceForm
     * @param BlogPostRepo $blogPostMapper
     * @param $blogPostID
     * @throws BlogPermissionException
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

                return JigExecutable::create('pages/replaceSuccess');
            }
            else {
                return JigExecutable::createWithSharedObjects(
                    'pages/displayReplaceForm',
                    [$blogReplaceForm]
                );
            }
        }

        $blogReplaceForm->initFromData([]);

        return JigExecutable::createWithSharedObjects(
            'pages/displayReplaceForm',
            [$blogReplaceForm]
        );
    }
}
