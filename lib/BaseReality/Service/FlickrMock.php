<?php


namespace BaseReality\Service;


class FlickrMock implements FlickrAPI {


    /** {@inheritdoc} */
    function flickr_people_getPublicPhotos($user_id, $per_page, $page) {
        
        $result = <<< END
{"photos":{"page":1, "pages":153, "perpage":8, "total":"1220", "photo":[{"id":"13983484766", "owner":"46085186@N02", "secret":"0df7981ebb", "server":"5490", "farm":6, "title":"MK3L7186", "ispublic":1, "isfriend":0, "isfamily":0}, {"id":"14003393482", "owner":"46085186@N02", "secret":"71cf28f6b4", "server":"7328", "farm":8, "title":"Dat snozzle", "ispublic":1, "isfriend":0, "isfamily":0}, {"id":"14003392262", "owner":"46085186@N02", "secret":"a408743da9", "server":"2916", "farm":3, "title":"Hungry hungry chiff chaff", "ispublic":1, "isfriend":0, "isfamily":0}, {"id":"14007043774", "owner":"46085186@N02", "secret":"698974e277", "server":"2900", "farm":3, "title":"Hungry hungry chiff chaff", "ispublic":1, "isfriend":0, "isfamily":0}, {"id":"10655484184", "owner":"46085186@N02", "secret":"9a257fe077", "server":"7345", "farm":8, "title":"Time to fatten up", "ispublic":1, "isfriend":0, "isfamily":0}, {"id":"9766482556", "owner":"46085186@N02", "secret":"c1e48692b7", "server":"5326", "farm":6, "title":"Lost Pigeon ?", "ispublic":1, "isfriend":0, "isfamily":0}, {"id":"9673398681", "owner":"46085186@N02", "secret":"6c4bbe7feb", "server":"3681", "farm":4, "title":"Gulls at Bristol floating harbour", "ispublic":1, "isfriend":0, "isfamily":0}, {"id":"9673398181", "owner":"46085186@N02", "secret":"4ab06a2d8b", "server":"7426", "farm":8, "title":"Gulls at Bristol floating harbour", "ispublic":1, "isfriend":0, "isfamily":0}]}, "stat":"ok"}
END;

        $jsonData = json_decode($result, true);
        $photoList = \Intahwebz\FlickrGuzzle\DTO\PhotoList::createFromJson($jsonData['photos']);

        return $photoList;
    }
}

 