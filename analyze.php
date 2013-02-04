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
  
  print("PHP Version: " . $version . "\n");
  print("CVE URL: " . $cve[0] . "\n");
  print("Related CVE's: " . implode(", ", $cve[1]) . "\n");
  print("Disabled Functions: " . $disabled_functions . "\n");
  print("Disabled Classes: " . $disabled_classes . "\n");
  print("Safe Mode: " . $safe_mode . "\n");
  print("Document Root: " . $DOCUMENT_ROOT . "\n");
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

for ($i = 1; $i < count($argv); $i++) {
  $arg = $argv[$i];
  parse(downloadPage($arg));
}

?>