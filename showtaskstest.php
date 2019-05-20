      <?php
      session_start();
      //error_reporting(0);


      require "vendor/autoload.php";
      use GuzzleHttp\Client;

      //echo $_SESSION["authtoken"];

      $client = new GuzzleHttp\Client();
      $res = $client->request('GET', 'http://localhost/shedulerapi/controller/course.php',
      [
      'headers' => ['Authorization' => $_SESSION["authtoken"]],
      'query' => ['courseid' => $_POST['id']]
      ]
      );


      //$data = json_decode($res->getBody());
      //echo $res->getHeaders();
      //var_dump($res);
      $body = $res->getBody();
      //$data = explode(" ", $res->getBody());
      //echo $data['statusCode'];
      $string = $body->getContents();
      $json = json_decode($string);
      print_r($json->data->courses[0]->id);
      //echo $res->getReasonPhrase();           // 200
      //echo $res->getHeader('content-type'); // 'application/json; charset=utf8'
      //echo $res->getBody()->getContents();           // 200



      //var_export($res->json());
      //$response = (string) $res->getBody();
      //$json = json_decode($res->getBody()->getContents(), true);
      //$messages = $json->messages;
      //$data = $json->data->courses;
      //$data = $json->data->tasks;
      //print_r($course->data);

      //echo $data[0]->id;
      //echo $data[0]->id;

      //print_r($json['statusCode']);
      //echo $json->statusCode;


      //$movies = json_decode($response->getBody()->getContents());

       ?>
