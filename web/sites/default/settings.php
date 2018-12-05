<?php

// @codingStandardsIgnoreFile

/**
 * @file
 * Settings entry point.
 *
 * - Global defaults -> default.settings.php
 *
 * - platform.sh specific -> platformsh.settings.php
 *
 * - Lando local settings -> lando.settings.php
 *
 * - Local settings (just for you) -> local.settings.php
 */

$databases = [];
$config_directories = [];

// Default settings. All in git.
include './sites/default/includes/default.settings.php';

// Totally *optional* local overrides that are not in git.
if (file_exists('./sites/default/includes/local.settings.php')) {
  include './sites/default/includes/local.settings.php';
}

// Put this last to prevent core trying to add it in some configurations.
$settings['install_profile'] = 'minimal';
