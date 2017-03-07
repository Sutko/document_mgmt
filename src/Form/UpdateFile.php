<?php

namespace Drupal\document_mgmt\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax;
use Drupal\Core\Ajax\OpenModalDialogCommand;
use Drupal\Core\Ajax\CssCommand;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Respons;
use Drupal\document_mgmt\Entity\Document;
use Drupal\Component\Serialization\Json;


/**
 * Class Update File.
 *
 * @package Drupal\document_mgmt\Form
 */
class UpdateFile extends FormBase {


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'update_file';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $id_file = NULL) {

    $form['document'] = array(
            '#type' => 'managed_file',
            '#title' => t('Select file for update'),
            '#description' => t('The uploaded image will be displayed on this page using the image style chosen below.'),
            '#required' => TRUE,
            '#upload_location' => 'public://document/',
            '#attributes' => array('class' => array('form_upload_document')),
          );


    $form['submit'] = [
        '#type' => 'submit',
        '#value' => t('Update File'),
        '#ajax' => array(
        'callback' => '::upload_file',
        'wrapper' => 'form-wrap',
        'effect' => 'fade'
      ),
    ];

    return $form;
  }

  /**
    * {@inheritdoc}
    */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Display result.
    /*foreach ($form_state->getValues() as $key => $value) {
        drupal_set_message($key . ': ' . $value);
    }*/

  }

  /**
   * {@inheritdoc}
   */
  public function upload_file(array &$form, FormStateInterface $form_stat, Request $requeste) {

   $values = $form_state;
   $id_file = $form_state->getValue('document');
   $file = \Drupal\file\Entity\File::load($id_file['0']);
   $userCurrent = \Drupal::currentUser();

   $bytes = $file->filesize->value;

        if ($bytes >= 1073741824)
        {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        }
        elseif ($bytes >= 1048576)
        {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        }
        elseif ($bytes >= 1024)
        {
            $bytes = number_format($bytes / 1024, 2) . ' kB';
        }
        elseif ($bytes > 1)
        {
            $bytes = $bytes . ' bytes';
        }
        elseif ($bytes == 1)
        {
            $bytes = $bytes . ' byte';
        }
        else
        {
            $bytes = '0 bytes';
        }

   Document::create([
               'name' => ['target_id' => $id_file['0']],
               'size' => $bytes,
               'date_created' => date("Y-m-d h:i:s"),
               'latest_date' => date("Y-m-d h:i:s"),
               'modified_by' => $userCurrent->getUsername(),
    ])->save();

   $response = new AjaxResponse();

  $options = array(
        'dialogClass' => 'popup-dialog-class',
        'width' => '500',
        'height' => '200',
      );
    $response->addCommand(new OpenModalDialogCommand('Rename File', "Test", $options));
    
    return $response;

    // Display result.
    /*foreach ($form_state->getValues() as $key => $value) {
        drupal_set_message($key . ': ' . $value);
    }*/

  }

}
