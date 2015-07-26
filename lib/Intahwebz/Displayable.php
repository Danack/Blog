<?php

namespace Intahwebz;

interface Displayable {

    function display();

    function displayPreview();

    function displayThumbnail($url);
}