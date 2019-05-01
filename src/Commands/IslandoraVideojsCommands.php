<?php

namespace Drupal\islandora_videojs\Commands;

use Drupal\islandora\Commands\AbstractPluginAcquisition;

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
class IslandoraVideojsCommands extends AbstractPluginAcquisition {

  /**
   * {@inheritdoc}
   */
  protected function getDownloadUri() {
    return 'https://github.com/videojs/video.js/releases/download/v5.10.2/video-js-5.10.2.zip';
  }

  /**
   * {@inheritdoc}
   */
  protected function getInstallDir($path) {
    return implode('/', [
      $path,
      'video-js',
    ]);
  }

  /**
   * {@inheritdoc}
   */
  protected function getDescriptor() {
    return 'Video.js plugin';
  }

  /**
   * Download and install the plugin.
   *
   * @param string $path
   *   Optional. A path where to install the plugin. If omitted Drush
   *   will use the default location.
   *
   * @command islandora_videojs:plugin
   * @aliases videojsplugin,videojs-plugin
   */
  public function installPlugin($path = NULL) {
    return $this->plugin($path);
  }

}
