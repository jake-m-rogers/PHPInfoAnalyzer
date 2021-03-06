<?php

function downloadPage($url) {
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($ch, CURLOPT_HEADER, 1);
  $html = curl_exec($ch);
  curl_close($ch);
  return $html;
}

function parse($html) {
  preg_match_all('/<td class="e">(.*?)<\/td><td class="v">(.*?)<\/td>/', $html, $data);
  $data = array_combine($data[1], $data[2]);

  $version = $data["PHP Version "];
  if (! $version) {
    preg_match('/<h1 class="p">PHP Version (.+)<\/h1>/', $html, $version);
    $version = $version[1];
  }

  preg_match('/([0-9\.]+)/', $version, $version);
  $version = $version[1];

  $disabled_functions = $data["disable_functions"];
  $disabled_classes = $data["disable_classes"];
  $cve = getCVE($version);
  $safe_mode = $data["safe_mode"];
  $open_base = $data["open_basedir"];
  
  print("\n");
  print("PHP Version: " . $version . "\n");
  print("\n");
  print("\n");
  print("CVE URL: " . $cve[0] . "\n");
  print("\n");
  print("\n");
  print("Related CVE's: " . implode(", ", $cve[1]) . "\n");
  print("\n");
  print("\n");
  print("Disabled Functions: " . $disabled_functions . "\n");
  print("Disabled Classes: " . $disabled_classes . "\n");
  print("\n");
  print("\n");
  print("Safe Mode: " . $safe_mode . "\n");
  print("\n");
  print("\n");
  print("open_basedir: " . $open_base . "\n");
  print("\n");
  print("\n");
  
  
}

function getCVE($version, $page = false) {
  if ($page) $url = $page;
  else $url = "http://www.cvedetails.com/version-search.php?vendor=PHP&product=PHP&version=" . trim($version);
  $cve = downloadPage($url);

  preg_match('/Location: (.+)/', $cve, $location);
  $location = "http://www.cvedetails.com" . $location[1];

  preg_match_all('/Details of (.+) security/', $cve, $cves);
  $cves = $cves[1];

  if ($page) {
    return $cves;
  } else {
    preg_match_all('/href="(.+)".+title="Go to page (.+)"/', $cve, $pages);
    $pages = array_combine($pages[2], $pages[1]);
    array_shift($pages);

    foreach ($pages as $page) {
      $more_cves = getCVE($version, "http://www.cvedetails.com" . $page);
      array_merge($cves, $more_cves);
    }

    return array($location, $cves);
  }
}

/*

function searchBing($url) {
	  
	  $ip = gethostbyname($arg);
	  echo $ip;
	  }
	  
function searchCrush($url) {

      $ip = gethostbyname($arg);
	  echo $ip;
	  }
	  
*/

 function wafTest($url) {

  $agent = "<script></script> /bin/sh ARGS NULL UNION SELECT order by ../../ etc/passwd";
  
  $ch = curl_init($url);
  curl_setopt ($ch, CURLOPT_USERAGENT, $agent);
  curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt ($ch, CURLOPT_VERBOSE,false);
  curl_setopt ($ch, CURLOPT_TIMEOUT, 5);
  $page=curl_exec($ch);  
  $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  curl_close($ch);
  
  $return_code = '0';
  
  if($httpcode>=200 && $httpcode<300)
  {
    $return_code = 1;
  }    
  
  if($return_code==1)
  {
    if(preg_match('/Fatal/',$page))
    {
       $return_code = 2;
    }
    
    if(preg_match('/Parse error/',$page))
    {
       $return_code = 2;
    }
  }
  
  return $return_code;
}


for ($i = 1; $i < count($argv); $i++) {
  $arg = $argv[$i];
  parse(downloadPage($arg));
  if(wafTest($arg) === 1) {
  print("No WAF Detected");
  }
  else {
  print("Page did not respond, WAF likely");
  }
  print("\n");
  print("\n");
}

?>