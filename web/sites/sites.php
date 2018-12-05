<?php

/**
 * @file
 * Configuration file for multi-site support and directory aliasing feature.
 *
 * @see https://api.drupal.org/api/drupal/sites%21example.sites.php/8.2.x
 */

$sites = [
  '127.0.0.1'                   => 'default',
  'default'                     => 'default',
  'drupal.org.au'               => 'default',
  'drupal-org-au.lndo.site'     => 'default',
];

// Dynamically add Platform.sh sites.
if (!empty($_ENV['PLATFORM_ROUTES'])) {
  $routes = json_decode(base64_decode($_ENV['PLATFORM_ROUTES']), TRUE);

  // The following block adds a $sites[] entry for each subdomain that is defined
  // in routes.yaml.
  // If you are not using subdomain-based multisite routes then you will need to
  // adapt the code below accordingly.
  foreach ($routes as $url => $route) {
    $host = parse_url($url, PHP_URL_HOST);
    if ($host !== FALSE && $route['type'] == 'upstream' && $route['upstream'] == $_ENV['PLATFORM_APPLICATION_NAME']) {
      $subdomain = substr($host, 0, strpos($host,'.'));
      $sites[$host] = $subdomain;
    }
  }
}
