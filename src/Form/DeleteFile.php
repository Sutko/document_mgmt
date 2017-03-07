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
 * Class DeleteFile.
 *
 * @package Drupal\document_mgmt\Form
 */
class DeleteFile extends FormBase {


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'delete_file';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $id_file = NULL, $name_file = NULL) {

    //dump($id_file);

    $options = array('1' => 'Yes sure', '2' => 'No sure');
    $text = "Are you sure you want to delete this ".$name_file." file?";
    
  $form['sure'] = array(
  '#type' => 'radios',
  '#title' => $text,
  '#options' => $options,
  '#description' => t('The file will be permanently deleted.'),
  );

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
        '#value' => t('Delete file'),
        '#ajax' => array(
        'callback' => '::delete_file',
        'wrapper' => 'form-wrap',
        'effect' => 'fade'
      ),
    ];

    return $form;
  }


  public function delete_file(&$form, FormStateInterface $form_state, Request $request) {

  
    if($form_state->getValue('sure') == 1){


      Document::load($form_state->getValue('id_file'))->delete();

      $content = "<div>
      <h4>File ".$form_state->getValue('name_file')." was deleted.</h4>
        <a tex-aligin='center' href='/'>Go Home</a>
      </div>";

    }elseif ($form_state->getValue('sure') == 2) {
      $content = "<div>
      <h4>File ".$form_state->getValue('name_file')." not was deleted.</h4>
        <a text-aligin='center' href='/'>Go Home</a>
      </div>";
    }


    $response = new AjaxResponse();

  $options = array(
        'dialogClass' => 'popup-dialog-class',
        'width' => '500',
        'height' => '200',
      );
    $response->addCommand(new OpenModalDialogCommand('Delete File', $content, $options));
    
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
