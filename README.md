**Installation**

Add ana/search-engine to composer.json.

Run composer update 

**Or run**

composer require aleszatloukal/google-search-api


Then add the alias.

'aliases' => [
    'SearchEngine' => ana\SearchEngine\Facades\SearchEngine::class,
]

then need to run 
 #php artisan vendor:publish --provider="ana\SearchEngine\SearchEngineApiProvider" and modify the config file with your own information.

/config/searchengine.php

and go to  https://cse.google.com/cse/  to create engineID 
and go to https://cse.google.com/cse/setup/basic?cx=search_engine_id  to get Pi Key 

**Usage**

$searchEngine= new SearchEngine();

$result=$searchEngine->getResults('ana rasta');

**Output**
List From $url , $title ,$keyword,$description,$promoted



