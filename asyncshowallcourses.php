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

      $promise = $client->requestAsync('GET','http://localhost/shedulerapi/controller/course.php');

      $promise->then(
        function (ResponseInterface $res) {
            echo "kif" . $res->getStatusCode() . "\n";
            $body = $res->getBody();
            $string = $body->getContents();
            $json = json_decode($string);
        },
        function (RequestException $e) {
            echo $e->getMessage() . "\n";
            echo $e->getRequest()->getMethod();
        }
);


       ?>
       <center><h1>SHOW ALL COURSES</h1></center>
       <pre><?php   //print_r($json); ?></pre>

       <?php footernav(); ?>
