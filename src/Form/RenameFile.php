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

/**
 * Class Rename File.
 *
 * @package Drupal\document_mgmt\Form
 */
class RenameFile extends FormBase {


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'rename_file';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $id_file = NULL, $name_file = NULL) {

    $form['old_file_name'] = [
        '#type' => 'textfield',
        '#title' => t('Enter new name file'),
        '#default_value' => $name_file,
    ];

     $form['id_file'] = array(
      '#type' => 'hidden',
      '#value' => $id_file,
      );

      $form['name_file'] = array(
      '#type' => 'hidden',
      '#value' => $name_file,
      );


     $form['submit'] = [
        '#type' => 'submit',
        '#value' => t('Rename file'),
        '#ajax' => array(
        'callback' => '::rename_file',
        'wrapper' => 'form-wrap',
        'effect' => 'fade'
      ),
    ];

    return $form;
  }


  public function rename_file(&$form, FormStateInterface $form_state, Request $request) {

    $storage = \Drupal::entityTypeManager()->getStorage('document');
              $id = $storage->getQuery()
              ->condition('id',$form_state->getValue('id_file'), '=')
              ->execute();

              $ids = $storage->loadMultiple($id);
              $EntityDocument = array_values($ids)[0];
              
              $file = \Drupal\file\Entity\File::load($EntityDocument->name->target_id);
              $file->filename->value = $form_state->getValue('old_file_name');
              $file->save();
              


  
      $content = "<div>
      <h4>Your file ".$form_state->getValue('name_file')." rename to ".$form_state->getValue('old_file_name').".</h4>
        <a tex-aligin='center' href='/'>Go Home</a>
      </div>";


    $response = new AjaxResponse();

  $options = array(
        'dialogClass' => 'popup-dialog-class',
        'width' => '500',
        'height' => '200',
      );
    $response->addCommand(new OpenModalDialogCommand('Rename File', $content, $options));
    
    return $response;
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
   

  }

}
