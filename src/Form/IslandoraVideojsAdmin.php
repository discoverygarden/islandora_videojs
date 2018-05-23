<?php

/**
 * @file
 * Contains \Drupal\islandora_videojs\Form\IslandoraVideojsAdmin.
 */

namespace Drupal\islandora_videojs\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element;

class IslandoraVideojsAdmin extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'islandora_videojs_admin';
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('islandora_videojs.settings');

    foreach (Element::children($form) as $variable) {
      $config->set($variable, $form_state->getValue($form[$variable]['#parents']));
    }
    $config->save();

    if (method_exists($this, '_submitForm')) {
      $this->_submitForm($form, $form_state);
    }

    parent::submitForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['islandora_videojs.settings'];
  }

  public function buildForm(array $form, \Drupal\Core\Form\FormStateInterface $form_state) {
    // Get settings.
    $form = [];
    $form['islandora_videojs_hls_library'] = [
      '#type' => 'checkbox',
      '#title' => t('Videojs-contrib-hls library'),
      '#description' => t('Include the videojs-contrib-hls library js, videojs.contrib-hls.js?'),
      '#default_value' => \Drupal::config('islandora_videojs.settings')->get('islandora_videojs_hls_library'),
      '#element_validate' => [
        'islandora_videojs_admin_islandora_videojs_hls_library_validate'
        ],
    ];
    $form['islandora_videojs_center_play_button'] = [
      '#type' => 'checkbox',
      '#title' => t('Center play button'),
      '#description' => t('Put the play button in the center of the player, rather than the top left corner'),
      '#default_value' => \Drupal::config('islandora_videojs.settings')->get('islandora_videojs_center_play_button'),
    ];
    $form['islandora_videojs_responsive'] = [
      '#type' => 'checkbox',
      '#title' => t('Responsive player'),
      '#description' => t('Make the videojs player responsive (requires a responsive theme)'),
      '#default_value' => \Drupal::config('islandora_videojs.settings')->get('islandora_videojs_responsive'),
    ];

    return parent::buildForm($form, $form_state);
  }

}
?>
