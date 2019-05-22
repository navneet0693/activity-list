<?php

namespace Drupal\hands_on_activity\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
* Configuration form definition for the salutation message.
*/

class XmlImportConfigurationForm extends ConfigFormBase {

  /**
  * {@inheritdoc}
  */
  protected function getEditableConfigNames() {
    return ['hands_on_activity.custom_xml_import'];
  }

  /**
  * {@inheritdoc}
  */
  public function getFormId() {
    return 'xml_import_configuration_form';
  }

  /**
  * {@inheritdoc}
  */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('hands_on_activity.custom_xml_import');
    $form['xml_import_url'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('XML Import URL'),
      '#description' => $this->t('Please provide the XML URL you want to
      use.'),
      '#default_value' => $config->get('xml_import_url'),
      );
    return parent::buildForm($form, $form_state);
  }

  /**
  * {@inheritdoc}
  */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $xml_import_url = $form_state->getValue('xml_import_url');
    if (filter_var($xml_import_url, FILTER_VALIDATE_URL) === FALSE) {
    $form_state->setErrorByName('xml_import_url', $this->t('Please enter a valid URL'));
    }
  }

  /**
  * {@inheritdoc}
  */
  public function submitForm(array &$form, FormStateInterface $form_state){
    $this->config('hands_on_activity.custom_xml_import')
    ->set('xml_import_url', $form_state->getValue('xml_import_url'))
    ->save();
    parent::submitForm($form, $form_state);
  }
}