<?php

namespace Drupal\islandora_videojs\Commands;

use Drush\Commands\DrushCommands;
use Drush\SiteAlias\SiteAliasManagerAwareInterface;
use Drush\SiteAlias\SiteAliasManagerAwareTrait;
use Drupal\Core\File\FileSystemInterface;
use Drupal\Core\Archiver\Tar;
use Symfony\Component\Filesystem\Filesystem;

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
class IslandoraVideojsCommands extends DrushCommands implements SiteAliasManagerAwareInterface {

  use SiteAliasManagerAwareTrait;

  /**
   * Drupal's filesystem.
   *
   * @var Drupal\Core\File\FileSystemInterface
   */
  protected $fileSystem;

  /**
   * Constructor.
   */
  public function __construct(FileSystemInterface $file_system) {
    $this->fileSystem = $file_system;
  }

  /**
   * The Video.js plugin URI.
   */
  const DOWNLOAD_URI = 'https://github.com/videojs/video.js/releases/download/v5.10.2/video-js-5.10.2.zip';

  /**
   * The initial Video.js directory.
   */
  const ORIGINAL_DIR = 'video-js';

  /**
   * Download and install the Video.js plugin.
   *
   * @param string $path
   *   Optional. A path where to install the Video.js plugin. If omitted Drush
   *   will use the default location.
   *
   * @command videojs:plugin
   * @aliases videojsplugin,videojs-plugin
   */
  public function plugin($path = NULL) {
    if ($path === NULL) {
      $this->logger()->debug('Acquiring default installation path.');
      $path = implode('/', [
        $this->siteAliasManager()->getSelf()->root(),
        'sites',
        'all',
        'libraries',
      ]);
    }

    $filesystem = new Filesystem();

    // Create the path if it does not exist.
    if (!is_dir($path)) {
      $this->fileSystem->mkdir($path);
      $this->logger()->notice('Directory @path was created', ['@path' => $path]);
    }

    // Download the zip archive.
    if ($filepath = system_retrieve_file(static::DOWNLOAD_URI)) {
      $filename = $this->fileSystem->basename($filepath);
      $dirname = static::ORIGINAL_DIR;

      // Remove any existing Video.js plugin directory.
      if (is_dir($dirname) || is_dir('video-js')) {
        $filesystem->remove([$dirname, 'video-js']);
        $this->logger()->notice('A existing Video.js plugin was deleted from @path', ['@path' => $path]);
      }

      // Decompress the zip archive.
      (new Tar($filename))->extract('video-js');

      // Change the directory name to "video-js" if needed.
      if ($dirname != 'video-js') {
        $filesystem->rename($dirname, 'video-js');
        $dirname = 'video-js';
      }
    }

    if (is_dir($dirname)) {
      $this->logger()->success('Video.js plugin has been installed in @path', ['@path' => $path]);
    }
    else {
      $this->logger()->error('Drush was unable to install the Video.js plugin to @path', ['@path' => $path]);
    }

  }

}
