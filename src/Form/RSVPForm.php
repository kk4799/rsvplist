<?php
/**
 * @file
 * Contains \Drupal\rsvplist\Form\RSVPForm
 */
namespace Drupal\rsvplist\Form;

use Drupal\Core\Database\Database;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a RSVP Email form.
 */

class RSVPForm extends FormBase {
    /**
     * {@inheritdoc}
     */
    public function getFormId (){
        return 'rsvplist_email_form';
    }
    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state) {
        $node = \Drupal::routeMatch () ->getParameter('node');
         $nid = $node->nid->value;
        $form['email'] = array(
            '#title' => $this->t('Email address'),
            '#type' => 'textfield',
            '#size' => 25,
            '#description' => $this-> t("We'll send updates to the email address you provide."),
            '#required' => TRUE,
        );
        $form['submit'] = array (
            '#type' => 'submit',
            '#value' => t('RSVP'),
        );
        $form['nid'] = array (
            '#type' => 'hidden',
            '#value' => $nid,
        );
        return $form;
    }
public function submitForm(array &$form, FormStateInterface $form_state) {
        //To Do: Implement submitForm() method.
    $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
    $conn = Database::getConnection();
    $conn->insert('rsvplist')->fields(
        array(
            'mail' => $form_state->getValue('email'),
            'nid' => $form_state->getValue('nid'),
            'uid' => $user->id(),
            'created' => time(),
        )
    )->execute();
        drupal_set_message (t('The form is working.'));
}
public function validateForm(array &$form, FormStateInterface $form_state) {
        $value = $form_state->getValue('email');
        if (value == !\Drupal::service('email.validator')->
        isValid($value)) {
            $form_state->setErrorByName('email', t('The email address %mail is not valid.', array('%mail'=>$value)));
        }
}
}

