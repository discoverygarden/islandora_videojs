<?php

namespace Drupal\islandora_videojs\Commands;

use Drush\Commands\DrushCommands;
use Drush\SiteAlias\SiteAliasManagerAwareInterface;
use Drush\SiteAlias\SiteAliasManagerAwareTrait;
use Drupal\Core\File\FileSystemInterface;
use Drupal\Core\Archiver\Tar;
use Symfony\Component\Filesystem\Filesystem;
use Drupal\Component\Plugin\PluginManagerInterface;

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
   * Archive plugin manager.
   *
   * @var Drupal\Core\Archiver\ArchiverManager
   */
  protected $archiveManager;

  /**
   * Constructor.
   */
  public function __construct(FileSystemInterface $file_system, PluginManagerInterface $archive_manager) {
    $this->fileSystem = $file_system;
    $this->archiveManager = $archive_manager;
  }

  /**
   * The Video.js plugin URI.
   */
  const DOWNLOAD_URI = 'https://github.com/videojs/video.js/releases/download/v5.10.2/video-js-5.10.2.zip';

  const INSTALL_DIR = 'video-js';
  /**
   * The initial Video.js directory.
   */
  const ORIGINAL_DIR = self::INSTALL_DIR;
  const DESCRIPTOR = 'Video.js plugin';

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
  public function plugin($path = NULL) {
    if (!$path) {
      $this->logger()->debug('Acquiring default installation path.');
      $path = implode('/', [
        $this->siteAliasManager()->getSelf()->root(),
        'sites',
        'all',
        'libraries',
      ]);
      $this->logger()->info('Installing to {0}.', [$path]);
    }

    $filesystem = new Filesystem();

    // Create the path if it does not exist.
    if (!is_dir($path)) {
      $this->fileSystem->mkdir($path);
      $this->logger()->notice('Directory {0} was created', [$path]);
    }

    $original_dir = implode('/', [$path, static::ORIGINAL_DIR]);
    $install_dir = implode('/', [$path, static::INSTALL_DIR]);

    // Download the archive.
    if ($filepath = system_retrieve_file(static::DOWNLOAD_URI, $path, FALSE, FILE_EXISTS_REPLACE)) {
      $filename = $this->fileSystem->basename($filepath);

      // Remove any existing plugin directory.
      if (is_dir($original_dir) || is_dir($install_dir)) {
        $filesystem->remove([$original_dir, $install_dir]);
        $this->logger()->notice('A existing {1} was deleted from {0}', [$path, static::DESCRIPTOR]);
      }

      // Decompress the archive.
      $this->archiveManager
        ->getInstance(['filepath' => $filepath])
        ->extract($install_dir);

      // Change the directory name if needed.
      if (static::ORIGINAL_DIR != static::INSTALL_DIR) {
        $filesystem->rename(
          $original_dir,
          $install_dir
        );
      }
    }

    if (is_dir($install_dir)) {
      $this->logger()->success('{1} has been installed in {0}', [$install_dir, static::DESCRIPTOR]);
    }
    else {
      $this->logger()->error('Drush was unable to install the {1} to {0}', [$install_dir, static::DESCRIPTOR]);
    }

  }

}
