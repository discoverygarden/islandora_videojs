<?php

namespace Drupal\islandora_videojs\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Module settings form.
 */
class Admin extends ConfigFormBase {

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

    $config->set('islandora_videojs_hls_library', $form_state->getValue('islandora_videojs_hls_library'));
    $config->set('islandora_videojs_center_play_button', $form_state->getValue('islandora_videojs_center_play_button'));
    $config->set('islandora_videojs_responsive', $form_state->getValue('islandora_videojs_responsive'));

    $config->save();
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['islandora_videojs.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form_state->loadInclude('islandora_videojs', 'inc', 'includes/admin.form');
    $form = [];
    $form['islandora_videojs_hls_library'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Videojs-contrib-hls library'),
      '#description' => $this->t('Include the videojs-contrib-hls library js, videojs.contrib-hls.js?'),
      '#default_value' => $this->config('islandora_videojs.settings')->get('islandora_videojs_hls_library'),
      '#element_validate' => [
        'islandora_videojs_admin_islandora_videojs_hls_library_validate',
      ],
    ];
    $form['islandora_videojs_center_play_button'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Center play button'),
      '#description' => $this->t('Put the play button in the center of the player, rather than the top left corner'),
      '#default_value' => $this->config('islandora_videojs.settings')->get('islandora_videojs_center_play_button'),
    ];
    $form['islandora_videojs_responsive'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Responsive player'),
      '#description' => $this->t('Make the videojs player responsive (requires a responsive theme)'),
      '#default_value' => $this->config('islandora_videojs.settings')->get('islandora_videojs_responsive'),
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];
    return $form;
  }

}
