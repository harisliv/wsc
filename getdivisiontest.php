    <?php include "header.php"; ?>
    <?php include "footer.php"; ?>
    <?php session_start();
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    //error_reporting(0);

    require "vendor/autoload.php";
    use GuzzleHttp\Client;
    use GuzzleHttp\Exception\RequestException;
    use GuzzleHttp\Exception\ClientException;
    use GuzzleHttp\Pool;
    use GuzzleHttp\Psr7\Request;
    use GuzzleHttp\Psr7\Response;
    use Psr\Http\Message\ResponseInterface;

    use GuzzleHttp\Promise;

    //echo $_SESSION["authtoken"];

    headernav();

         try
         {

           $id = 1;
           $type ="LAB";
           $acad = 1;
           $client = new GuzzleHttp\Client();
           //http://localhost/shedulerapi/controller/scheduler.php?id_course=123ΑΒ&id_acadsem=1&type_division=LAB
          $res = $client->request('GET', 'http://localhost/shedulerapi/controller/scheduler.php',
        [
          'headers' => ['Authorization' => $_SESSION["authtoken"]],
          'query' => ['id' => $id ,
                      'id_acadsem' => $acad,
                      'type_division' => $type,
                    ]
        ]
        );

        $body = $res->getBody();
        $string = $body->getContents();
        $json = json_decode($string);
        $messages = $json->messages;
        ?>
        <pre> <?php
        print_r($json);?>
        <pre> <?php
        $promises = [
            'scheduler'  => $client->getAsync('http://localhost/shedulerapi/controller/scheduler.php', ['headers' => ['Authorization' => $_SESSION["authtoken"]],
            'query' => ['id' => $id, 'id_acadsem' => 1, 'type_division' => $type]]),

        ];

        // Wait on all of the requests to complete. Throws a ConnectException
        // if any of the requests fail
        $results = Promise\unwrap($promises);

        // Wait for the requests to complete, even if some of them fail
        $results = Promise\settle($promises)->wait();

        $body = $results['scheduler']['value']->getBody();
        $string = $body->getContents();
        $json = json_decode($string);
        print_r($json);



        footernav();

        }catch (GuzzleHttp\Exception\BadResponseException $e) {
            $response = $e->getResponse();
            $responseBodyAsString = (string) $response->getBody();
            $json = json_decode($responseBodyAsString);
            $responsestatuscode = $response->getStatusCode();
            $messages = $json->messages;
        }
      ?>
