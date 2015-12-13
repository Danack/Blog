<?php

namespace Blog\Controller;

use BaseReality\Form\BlogEditForm;
use BaseReality\Form\BlogReplaceForm;
use Blog\Mapper\BlogPostMapper;
use FCForms\UploadedFile;
use Blog\TemplatePlugin\BlogPostPlugin;




class BlogEdit
{
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
            else {
                return \Tier\getRenderTemplateTier('pages/blogEdit', [$blogEditForm]);
            }
        }
        
        $blogPost = $blogPostMapper->getBlogPost($blogPostID);
        $blogEditForm->initFromBlogPost($blogPost);

        return \Tier\getRenderTemplateTier('pages/blogEdit');
    }

    /**
     * @param BlogReplaceForm $blogReplaceForm
     * @param $blogPostID
     * @return \Tier\Executable
     */
    public function showReplace(
        BlogReplaceForm $blogReplaceForm,
        BlogPostMapper $blogPostMapper,
        BlogPostPlugin $blogPostPlugin,
        $blogPostID
    ) {
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
