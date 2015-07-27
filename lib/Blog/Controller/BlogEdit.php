<?php

namespace Blog\Controller;

use BaseReality\Form\BlogEditForm;
use BaseReality\Form\BlogReplaceForm;
use Blog\Mapper\BlogPostMapper;
use Arya\RedirectBody;

class BlogEdit
{
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
        $storedData = $blogEditForm->getSessionStoredData(true);
        if (!$storedData) {
             $blogPost = $blogPostMapper->getBlogPost($blogPostID);

            $data = array(
                'blogPostID' => $blogPostID,
                'title' => $blogPost->title,
                'isActive' => $blogPost->isActive,
            );
            $blogEditForm->addRowValues($blogPostID, $data);
        }

        return getRenderTemplateTier('pages/blogEdit');
    }

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
