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
 * Class Add Folder.
 *
 * @package Drupal\document_mgmt\Form
 */
class AddFolder extends FormBase {


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'add_folder';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $id_folder = NULL, $name_folder = NULL) {

    $form['name_folder'] = [
        '#type' => 'textfield',
        '#title' => t('Enter name folder'),
    ];

     $form['parent_id_folder'] = array(
      '#type' => 'hidden',
      '#value' => $id_folder,
      );

      $form['parent_name_folder'] = array(
      '#type' => 'hidden',
      '#value' => $name_folder,
      );
    

    $form['submit'] = [
        '#type' => 'submit',
        '#value' => t('Submit'),
        '#ajax' => array(
        'callback' => '::add_folder',
        'wrapper' => 'form-wrap',
        'effect' => 'fade'
      ),
    ];

    return $form;
  }


   public function add_folder(&$form, FormStateInterface $form_state, Request $request) {

    $parent_id_folder = $form_state->getValue('parent_id_folder');
    $parent_name_folder = $form_state->getValue('parent_name_folder');
    $name_folder = $form_state->getValue('name_folder');
    $userCurrent = \Drupal::currentUser();

   Folder::create([
               'name' => $name_folder,
               'id_parent' => $parent_id_folder,
               'date_created' => date("Y-m-d h:i:s"),
               'latest_date' => date("Y-m-d h:i:s"),
               'modified_by' => $userCurrent->getUsername(),
    ])->save();


    $content = "<div>
      <h4>Your folder ".$name_folder." added in ".$name_folder." folder .</h4>
        <a tex-aligin='center' href='/'>Go Home</a>
      </div>";


    $response = new AjaxResponse();

  $options = array(
        'dialogClass' => 'popup-dialog-class',
        'width' => '500',
        'height' => '200',
      );
    $response->addCommand(new OpenModalDialogCommand('Add Folder', $content, $options));
    
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
