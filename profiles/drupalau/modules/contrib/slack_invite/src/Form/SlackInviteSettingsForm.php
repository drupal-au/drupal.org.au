<?php
/**
 * @file
 * Contains \Drupal\slack_invite\Form\SlackInviteSettingsForm.
 */

namespace Drupal\slack_invite\Form;

use Drupal\Core\Http\Client;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Exception;

/**
 * Configure locale settings for this site.
 */
class SlackInviteSettingsForm extends ConfigFormBase {
  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['slack_invite.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormID() {
    return 'slack_invite_settings';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('slack_invite.settings');

    $form['slack_invite_token'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Slack Web API Token'),
      '#description' => $this->t('Enter the Web API token you get from your team domain.'),
      '#default_value' => $config->get('token'),
      '#required' => TRUE,
    ];

    $form['slack_invite_hostname'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Slack Domain Hostname'),
      '#description' => $this->t('Enter your slack team domain (ex. if your domain is https://drupal.slack.com, you would enter "drupal" minus the quotations).'),
      '#default_value' => $config->get('hostname'),
      '#required' => TRUE,
    ];

    $form['slack_bypass_check'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Bypass credential check'),
      '#description' => $this->t('Bypass checking that token and hostname combination are valid'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    if($form_state->getValue('slack_bypass_check')) {
      return;
    }
    $team_hostname = $form_state->getValue('slack_invite_hostname');
    $token = $form_state->getValue('slack_invite_token');
    $api_url = "https://{$team_hostname}.slack.com/api/api.test";

    $data = [
      'form_params' => [
        '_attempts' => 1,
        'token' => $token,
      ],
    ];

    try {
      $client = \Drupal::httpClient();
      $response = $client->request('POST', $api_url, $data);
      // Expected result.
      $response_data = json_decode('' . $response->getBody());
      if ($response_data->ok !== TRUE) {
        throw new Exception('Failed request');
      }
    }
    catch (Exception $e) {
      $form_state->setErrorByName('slack_invite_token', $this->t('Please check the token and hostname; unable to test request'));
    }
  }
  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();

    $config = $this->config('slack_invite.settings');
    $config->set('token', $values['slack_invite_token'])
      ->set('hostname', $values['slack_invite_hostname'])
      ->save();
    parent::submitForm($form, $form_state);
  }

}
