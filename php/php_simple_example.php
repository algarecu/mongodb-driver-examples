<?php

/*
 * Written with extension mongo 1.4.3
 * A PHP script connecting to a MongoDB database given a MongoDB Connection URI.
*/

// Standard URI format: mongodb://[dbuser:dbpassword@]host:port/dbname

$uri = "mongodb://sandbox:test@ds039768.mongolab.com:39768/test2345";
$uriParts = explode("/", $uri); 
$dbName = $uriParts[3];

$client = new MongoClient($uri);
$db = $client->$dbName;
$songs = $db->songs;

/*
 * First we'll add a few songs. Nothing is required to create the songs
 * collection; it is created automatically when we insert.
*/

$seventies = array(
    'decade' => '1970s', 
    'artist' => 'Debby Boone',
    'song' => 'You Light Up My Life', 
    'weeksAtOne' => 10
);

$eighties = array(
    'decade' => '1980s', 
    'artist' => 'Olivia Newton-John',
    'song' => 'Physical', 
    'weeksAtOne' => 10
);

$nineties = array(
    'decade' => '1990s', 
    'artist' => 'Mariah Carey',
    'song' => 'One Sweet Day', 
    'weeksAtOne' => 16
);

$songList = array($seventies, $eighties, $nineties);
$songs->batchInsert($songList);

/*
 * Then we need to give Boyz II Men credit for their contribution to
 * the hit "One Sweet Day".
*/

$songs->update(
    array('artist' => 'Mariah Carey'), 
    array('$set' => array('artist' => 'Mariah Carey ft. Boyz II Men'))
);
    
/*
 * Finally we run a query which returns all the hits that spent 10 
 * or more weeks at number 1. 
*/

$query = array('weeksAtOne' => array('$gte' => 10));
$cursor = $songs->find($query)->sort(array('decade' => 1));

foreach($cursor as $doc) {
    echo 'In the ' .$doc['decade'];
    echo ', ' .$doc['song']; 
    echo ' by ' .$doc['artist'];
    echo ' topped the charts for ' .$doc['weeksAtOne']; 
    echo ' straight weeks.', "\n";
}

// Since this is an example, we'll clean up after ourselves.

$songs->drop();

?>
