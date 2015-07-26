<?php

namespace Intahwebz;

interface ContentWithThumbNail extends ContentName {
    function render($asContentObject, $proxyURL, $thumbURL);
}
 