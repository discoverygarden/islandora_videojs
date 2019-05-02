<?php

namespace Drupal\islandora_videojs\Commands;

use Drupal\islandora\Commands\AbstractPluginAcquisition;

/**
 * Drush commandfile for Islandora Video.js.
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
