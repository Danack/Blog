<?php

namespace Blog\Controller;

use BaseReality\Form\BlogEditForm;
use BaseReality\Form\BlogReplaceForm;
use Blog\Mapper\BlogPostMapper;


class BlogEdit
{

    /**
     * @param BlogPostMapper $blogPostMapper
     * @param BlogEditForm $blogEditForm
     * @param $blogPostID
     * @return \Tier\Tier
     */
    public function showEdit(
        BlogPostMapper $blogPostMapper,
        BlogEditForm $blogEditForm,
        $blogPostID
    ) {
        
        $storedData = $blogEditForm->initFromStorage();
        if ($storedData) {
            $valid = $blogEditForm->validate();
            if ($valid) {
                $title = $blogEditForm->getValue('end', 'title');
                $isActive = $blogEditForm->getValue('end', 'isActive');
                $blogPostMapper->updateBlogPost($title, $isActive, $blogPostID);
                
                return \Tier\getRenderTemplateTier('pages/blogEditSuccess');
            }
        }
        
        $blogPost = $blogPostMapper->getBlogPost($blogPostID);
        $data = array(
            'blogPostID' => $blogPostID,
            'title' => $blogPost->title,
            'isActive' => $blogPost->isActive,
        );
    
        $blogEditForm->initFromData($data);

        return \Tier\getRenderTemplateTier('pages/blogEdit');
    }

    /**
     * @param BlogEditForm $blogEditForm
     * @param BlogPostMapper $blogPostMapper
     * @param $blogPostID
     * @return RedirectBody|\Tier\Tier
     */
    public function processEdit(
        BlogEditForm $blogEditForm,
        BlogPostMapper $blogPostMapper,
        $blogPostID
    ) {
        $blogEditForm->useSubmittedValues();
        $valid = $blogEditForm->validate();

        if (!$valid) {
            $blogEditForm->storeValuesInSession();
            return new RedirectBody("unused var", routeBlogEdit($blogPostID));
        }

        $title = $blogEditForm->getValue($blogPostID, 'title');
        $isActive = $blogEditForm->getValue($blogPostID, 'isActive');
        $blogPostMapper->updateBlogPost($title, $isActive, $blogPostID);

        return getRenderTemplateTier('pages/blogEditSuccess');
    }

    
    
    
    /**
     * @param BlogReplaceForm $blogReplaceForm
     * @param $blogPostID
     * @return \Tier\Executable
     */
    public function showReplace(
        BlogReplaceForm $blogReplaceForm,
        $blogPostID
    ) {
        $storedData = $blogReplaceForm->getSessionStoredData(true);

        if (!$storedData) {
            $blogReplaceForm->addRowValues('new', []);
        }

        return getRenderTemplateTier('pages/displayReplaceForm');
    }


    /**
     * @param BlogReplaceForm $blogReplaceForm
     * @param BlogPostMapper $blogPostMapper
     * @param $blogPostID

     */
    public function processReplace(
        BlogReplaceForm $blogReplaceForm,
        BlogPostMapper $blogPostMapper,
        $blogPostID
    ) {

        $blogReplaceForm->useSubmittedValues();
        $valid = $blogReplaceForm->validate();

        if (!$valid) {
            $blogReplaceForm->storeValuesInSession();
            return new RedirectBody("asdd", routeBlogReplace($blogPostID));
        }

        $newLink = $blogReplaceForm->getRowValues('new');
        $uploadedFile = $newLink['blogFile'];
        $fileContents = file_get_contents($uploadedFile->tmpName);
        $blogPostMapper->updateBlogPostText($blogPostID, trim($fileContents));

        return new RedirectBody("asdd", routeBlogPost($blogPostID));
    }
}
