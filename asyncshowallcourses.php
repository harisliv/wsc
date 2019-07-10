      <?php include "header.php"; ?>
      <?php include "footer.php"; ?>
      <?php
      session_start();
      ini_set('display_errors', 1);
      ini_set('display_startup_errors', 1);
      error_reporting(E_ALL);
      //error_reporting(0);
      headernav();


      require "vendor/autoload.php";
      use GuzzleHttp\Client;
      use GuzzleHttp\Exception\RequestException;
      use GuzzleHttp\Pool;
      use GuzzleHttp\Psr7\Request;
      use GuzzleHttp\Psr7\Response;
      use Psr\Http\Message\ResponseInterface;

      use GuzzleHttp\Promise;

      $client = new GuzzleHttp\Client;

      //$promise = $client->getAsync('http://localhost/shedulerapi/controller/course.php',['headers' => ['Authorization' => $_SESSION["authtoken"]] , 'query' => ['id' => 1]]);
      $promise = $client->postAsync('http://httpbin.org/post');

      $promise->then(
        function (ResponseInterface $res) {
            echo "kif" . $res->getStatusCode() . "\n";
            $json = json_decode((string)$res->getBody());
            print_r($json);

        },
        function (RequestException $e) {
            echo $e->getMessage() . "\n";
            echo $e->getRequest()->getMethod();
        }
);

// Force the pool of requests to complete
$promise->wait();

       ?>
       <center><h1>SHOW ALL COURSES</h1></center>
       <pre><?php   print_r($json); ?></pre>

       <?php footernav(); ?>
