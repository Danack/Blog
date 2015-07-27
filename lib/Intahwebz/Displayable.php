<?php

namespace Intahwebz;

interface Displayable
{
    public function display();

    public function displayPreview();

    public function displayThumbnail($url);
}
