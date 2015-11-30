<?php
/**
 * @file
 * Contains \Drupal\slack_invite\Form\SlackInviteForm.
 */

namespace Drupal\slack_invite\Form;

use Drupal\Core\Http\Client;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Exception;

/**
 * Builds the search form for the search block.
 */
class SlackInviteForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormID() {
    return 'slack_invite_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('slack_invite.settings');

    $form['#action'] = $this->url('<current>', [], ['query' => $this->getDestinationArray(), 'external' => FALSE]);
    $form['slack_email'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Email'),
      '#description' => $this->t('Enter email address for slack invite'),
      '#required' => TRUE,
    ];

    $form['actions'] = [
      '#type' => 'actions',
    ];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Send')
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $email = $form_state->getValue('slack_email');
    if (!valid_email_address($email)) {
      $form_state->setErrorByName('slack_email', $this->t('Enter email address in valid format (ex. example@example.com)'));
    }
  }
  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = \Drupal::config('slack_invite.settings');
    $team_hostname = $config->get('hostname');
    $api_url = "https://{$team_hostname}.slack.com/api/users.admin.invite?t=" . time();

    $email = $form_state->getValue('slack_email');
    $data = [
      'form_params' => [
        '_attempts' => 1,
        'email' => $email,
        'set_active' => 'true',
        'token' => $config->get('token'),
      ],
    ];

    try {
      $client = \Drupal::httpClient();
      $response = $client->request('POST', $api_url, $data);

      $response_data = json_decode('' . $response->getBody());
      if ($response_data->ok == TRUE) {
        drupal_set_message(t('You will receive an email notification inviting you to join the slack team shortly.'));
      }
      else {
        $message = '';
        switch($response_data->error) {
          case SLACK_INVITE_ALREADY_IN_TEAM:
            $message = $this->t('The user is already a member of the team');
            break;

          case SLACK_INVITE_SENT_RECENTLY:
            $message = $this->t('The user was recently sent an invitation.');
            break;

          case SLACK_INVITE_ALREADY_INVITED:
            $message = $this->t('The user is already invited.');
            break;

          default:
            $message = $data['error'];
            break;
        }
        drupal_set_message($this->t('There was an error sending your invite. Please contact the administrator with the following error details. The error message from slack was: @message', ['@message' => $message]));
      }
    }
    catch (Exception $e) {
      drupal_set_message($this->t('Something went wrong with the request. Please contact site administrator.'), 'error');
    }
  }
}
