<?php

namespace Drupal\document_mgmt\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\user\Entity\User;
use Drupal\document_mgmt\Entity\Folder;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax;
use Drupal\Core\Ajax\OpenModalDialogCommand;
use Drupal\Core\Ajax\CssCommand;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Respons;

/**
 * Class Rename Folder.
 *
 * @package Drupal\document_mgmt\Form
 */
class RenameFolder extends FormBase {


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'rename_folder';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $id_folder = NULL, $name_folder = NULL) {

    $form['new_folder_name'] = [
        '#type' => 'textfield',
        '#title' => t('Enter new name file'),
        '#default_value' => $name_folder,
    ];

    $form['id_folder'] = array(
      '#type' => 'hidden',
      '#value' => $id_folder,
      );

      $form['name_folder'] = array(
      '#type' => 'hidden',
      '#value' => $name_folder,
      );
    

    $form['submit'] = [
        '#type' => 'submit',
        '#value' => t('Rename folder'),
        '#ajax' => array(
        'callback' => '::rename_folder',
        'wrapper' => 'form-wrap',
        'effect' => 'fade'
      ),
    ];

    return $form;
  }


  public function rename_folder(&$form, FormStateInterface $form_state, Request $request) {

    $storage = \Drupal::entityTypeManager()->getStorage('folder');
              $id = $storage->getQuery()
              ->condition('id',$form_state->getValue('id_folder'), '=')
              ->execute();

              $ids = $storage->loadMultiple($id);
              $EntityFolder = array_values($ids)[0];
              
              $EntityFolder->name->value = $form_state->getValue('new_folder_name');
              $EntityFolder->save();
              


  
      $content = "<div>
      <h4>Your folder ".$form_state->getValue('name_folder')." rename to ".$form_state->getValue('new_folder_name').".</h4>
        <a tex-aligin='center' href='/'>Go Home</a>
      </div>";


    $response = new AjaxResponse();

  $options = array(
        'dialogClass' => 'popup-dialog-class',
        'width' => '500',
        'height' => '200',
      );
    $response->addCommand(new OpenModalDialogCommand('Rename Folder', $content, $options));
    
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
    // Display result.
    /*foreach ($form_state->getValues() as $key => $value) {
        drupal_set_message($key . ': ' . $value);
    }*/

  }

}
