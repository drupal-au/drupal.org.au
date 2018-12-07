# drupal.org.au

This project is under a full re-development. The current codebase is hugely outdated and insecure. It will be replaced in it's entirety.

## Project Outline

See the full project outline here: <https://github.com/drupal-au/drupal-au-docs>

## How to get involved

* See [discussion thread posted](https://groups.drupal.org/node/491403) after DrupalCamp Melb 
* Join the slack channel <http://drupal-au.slack.com>
* Follow this github project <https://github.com/drupal-au/drupal.org.au>

## Start the Drupalz

  * `composer install` - installs the prereqs
  * `lando start` - starts the containers
  * `ahoy sync` - syncs prod database to local
  * `lando drush -l https://drupal-org-au.lndo.site uli` - etc etc.

## Testing

`ahoy lint`

`ahoy unit`

`ahoy func`

## CI

CI/CD has not been set up. We welcome volunteers to set this up.


