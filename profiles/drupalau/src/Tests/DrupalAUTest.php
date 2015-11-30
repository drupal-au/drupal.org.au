<?php

/**
 * @file
 * Contains \Drupal\drupalau\Tests\DrupalAUTest.
 */

namespace Drupal\drupalau\Tests;

use Drupal\simpletest\WebTestBase;

/**
 * Tests DrupalAU installation profile expectations.
 *
 * @group drupalau
 */
class DrupalAUTest extends WebTestBase {

  protected $profile = 'drupalau';

  /**
   * Tests Minimal installation profile.
   */
  function testMinimal() {
    $this->drupalGet('');
  }
}
