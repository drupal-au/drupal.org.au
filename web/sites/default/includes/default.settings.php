<?php

// @codingStandardsIgnoreFile

/**
 * @file
 * Copy this to settings.local.php.
 *
 * Many useful things are already overridden in dev.settings.php. But you can
 * copy this file to settings.local.php and override more, or reverse some
 * settings.
 */

$settings['update_free_access'] = FALSE;
$settings['container_yamls'][] = $app_root . '/includes/default.services.yml';
$settings['file_scan_ignore_directories'] = ['node_modules', 'bower_components',];
$settings['hash_salt'] = '83b68bbde034aca12a30cff03be7b77a1699af29';
$config_directories[CONFIG_SYNC_DIRECTORY] = '../config/default';

// Detect hosting environment and load appropriately.
if (getenv('PLATFORM_RELATIONSHIPS')) {
  // We are on the Platform.sh
  include $app_root . '/sites/default/includes/platformsh.settings.php';
}
else {
  // We are developing locally.
  include $app_root . '/sites/default/includes/lando.settings.php';
}
