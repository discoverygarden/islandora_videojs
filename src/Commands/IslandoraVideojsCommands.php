<?php

namespace Drupal\islandora_videojs\Commands;

use Drush\Commands\DrushCommands;

/**
 * A Drush commandfile.
 *
 * In addition to this file, you need a drush.services.yml
 * in root of your module, and a composer.json file that provides the name
 * of the services file to use.
 *
 * See these files for an example of injecting Drupal services:
 *   - http://cgit.drupalcode.org/devel/tree/src/Commands/DevelCommands.php
 *   - http://cgit.drupalcode.org/devel/tree/drush.services.yml
 */
class IslandoraVideojsCommands extends DrushCommands {

  /**
   * Download and install the Video.js plugin.
   *
   * @param $path
   *   Optional. A path where to install the Video.js plugin. If omitted Drush will use the default location.
   *
   * @command videojs:plugin
   * @aliases videojsplugin,videojs-plugin
   */
  public function plugin($path) {
    // See bottom of https://weitzman.github.io/blog/port-to-drush9 for details on what to change when porting a
    // legacy command.
  }

}
