<?php

namespace Intahwebz;

interface DisplayableContent {


    function displayPreview();
    function displayThumbnail();
    function getContentID();
    function getDOMID();

    function getDisplayableVersion();
}