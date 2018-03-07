<?php
  $q = $_REQUEST["q"];
  $outputFile = 'output.json';
  $name = str_replace(" ", '%20', $q);
  $baseURL = "https://www.giantbomb.com/api/search/?api_key=";
  $apiKey = "1eb68d69b5b8c3a4d37f93116ba4968ccf789a33";
  $query = "&query=";
  $fileFormat = "&format=json";
  $resourceType = "&resources=game";
  $fullURL = $baseURL.$apiKey.$fileFormat.$query.'"'.$name.'"'.$resourceType."";
  $options = array('http' => array('user_agent' => 'custom user agent string'));
  $context = stream_context_create($options);
  $apiResult = file_get_contents($fullURL, false, $context);
  $json = json_decode($apiResult);
  $url = $json->results[0]->site_detail_url;
  $test = trim(substr($url,strpos ( $json->results[0]->site_detail_url , "3030-") + 5), "/");
  echo "<a href=\"game.php?gameid=".$test."\" ><img src=\"".$json->results[0]->image->screen_url."\"></a>";
  echo "<br><br><p>";
  echo "Game Name: ".$json->results[0]->name;
  echo "<br><br>";
  if ($json->results[0]->expected_release_year == null)
  {

    $time = strtotime($json->results[0]->original_release_date);

    $newformat = date('m-d-Y',$time);

    echo "Original Release Date: ".$newformat;


  } else
  {

  	echo "Expected Release Date: ".$json->results[0]->expected_release_month."-".$json->results[0]->expected_release_day."-".$json->results[0]->expected_release_year;

  }
  echo "<br><br>";
  echo $json->results[0]->deck;
  echo "</p>";
?>
