<?php

namespace Intahwebz;

interface DisplayableContent
{
    public function displayPreview();
    public function displayThumbnail();
    public function getContentID();
    public function getDOMID();

    public function getDisplayableVersion();
}
