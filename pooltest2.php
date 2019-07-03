<?php include "header.php"; ?>
<?php include "footer.php"; ?>
      <?php
      session_start();
      ini_set('display_errors', 1);
      ini_set('display_startup_errors', 1);
      error_reporting(E_ALL);
      error_reporting(0);
      require "vendor/autoload.php";

      use Psr\Http\Message\ResponseInterface;
      use GuzzleHttp\Exception\RequestException;
      use GuzzleHttp\Pool;
      use GuzzleHttp\Client;
      use GuzzleHttp\Psr7\Request;

    /*
    *  Using a key => value pair with the yield keyword is
    *  the cleanest method I could find to add identifiers or tags
    *  to asynchronous concurrent requests in Guzzle,
    *  so you can identify which response is from which request!
    */
    $client = new GuzzleHttp\Client(['base_uri' => 'http://httpbin.org']);
  $requestGenerator = function($searchTerms) use ($client) {
  	foreach($searchTerms as $searchTerm) {
  		// The magic happens here, with yield key => value
  		yield $searchTerm => function() use ($client, $searchTerm) {
  			// Our identifier does not have to be included in the request URI or headers
  			return $client->getAsync('/get?q='.$searchTerm, ['headers' => ['X-Search-Term' => $searchTerm]]);
  		};
  	}
  };
  $searchTerms = ['apple','banana','pear','orange','melon','grape','raisin'];
  $pool = new GuzzleHttp\Pool($client, $requestGenerator($searchTerms), [
  	'concurrency' => 3,
  	'fulfilled' => function(GuzzleHttp\Psr7\Response $response, $index) {
  		// This callback is delivered each successful response
  		// $index will be our special identifier we set when generating the request
  		$json = json_decode((string)$response->getBody());
  		// If these values don't match, something is very wrong
  		echo "Requested search term: ", $index, "\n";
  		echo "Parsed from response: ", $json->headers->{'X-Search-Term'}, "\n\n";
  	},
  	'rejected' => function(Exception $reason, $index) {
  		// This callback is delivered each failed request
  		echo "Requested search term: ", $index, "\n";
  		echo $reason->getMessage(), "\n\n";
  	},
  ]);
  // Initiate the transfers and create a promise
  $promise = $pool->promise();
  // Force the pool of requests to complete
  $promise->wait();
       ?>


       <?php footernav(); ?>
