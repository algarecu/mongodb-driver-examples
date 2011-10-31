<?php

/*
 php_simple_example.php

 A sample PHP script covering connection to a MongoDB database given a
 fully-qualified URI. There are a few additional means, but we prefer the URI
 connection model because developers can use the same code to handle various
 database configuration possibilities (single, master/slave, replica sets).
 
 Be sure to add extension=mongo.so to your php.ini file.
 
 Author::  Mongolab
*/

/*
 If your database is running in auth mode, you will need to provide a URI
 with user info and a database path, ex:
 'mongodb://username:password@localhost:27017/mongoquest'
*/
$m = new Mongo('mongodb://localhost:27017');
$db = $m->mongoquest;

/*
 What follows is code that can vary widely depending on your style
 First we get our desired collection.
 */
$collection = $db->Spells;

/*
 We insert by first creating an array, and passing that array to
 the collection's insert function. We use arrays to construct
 JSON-like objects.
 */
$obj = array('name' => 'Poke', 'level' => 1);
$collection->insert($obj);
$obj2 = array('name' => 'Zap', 'level' => 1);
$collection->insert($obj2);
$obj3 = array('name' => 'Blast', 'level' => 2);
$collection->insert($obj3);

/*
 At level 1, we only know level 1 spells.
 */
echo 'Level 1 spell list:<br/>';
$query = array('level' => 1);
$cursor = $collection->find($query);
foreach($cursor as $obj) {
    echo 'Spell name: ' .$obj['name'] .'<br/>';
}

/*
 Since these spells aren't very exciting, let's add a little flavor
 to each of them. We can use array syntax in-line to create JSON-like
 queries.
 */
$collection->update(array('name' => 'Poke'), array('$set' => array('flavor' => 'Snick snick!')));
$collection->update(array('name' => 'Zap'), array('$set' => array('flavor' => 'Bzazt!')));
$collection->update(array('name' => 'Blast'), array('$set' => array('flavor' => 'FWOOM!')));
    
/*
 This time we query again, with flavor!
 */
echo '<br/>Level 1 spell list, with flavor:<br/>';
$query2 = array('level' => 1);
$cursor2 = $collection->find($query2);
foreach($cursor2 as $obj2) {
    echo 'Spell name: ' .$obj2['name'];
    echo ' Flavortext: ' .$obj2['flavor'] .'<br/>';
}
    
/*
 Since this is an example, we'll clean up after ourselves.
 */
$collection->drop();
    
?>