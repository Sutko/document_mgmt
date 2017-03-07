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
use Drupal\document_mgmt\Entity\Folder;

/**
 * Class DeleteFolder.
 *
 * @package Drupal\document_mgmt\Form
 */
class DeleteFolder extends FormBase {


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'delete_folder';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $id_folder = NULL, $name_folder = NULL) {

    $options = array('1' => 'Yes sure', '2' => 'No sure');
    $text = "Are you sure you want to delete this ".$name_folder." folder?";
    
  $form['sure'] = array(
  '#type' => 'radios',
  '#title' => $text,
  '#options' => $options,
  '#description' => t('The file will be permanently deleted.'),
  );

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
        '#value' => t('Delete Folder'),
        '#ajax' => array(
        'callback' => '::delete_folder',
        'wrapper' => 'form-wrap',
        'effect' => 'fade'
      ),
    ];

    return $form;
  }


  public function delete_folder(&$form, FormStateInterface $form_state, Request $request) {

  
    if($form_state->getValue('sure') == 1){

      $this->DeleteAll($form_state->getValue('id_folder'));

      $content = "<div>
      <h4>Folder ".$form_state->getValue('name_folder')." was deleted.</h4>
        <a tex-aligin='center' href='/'>Go Home</a>
      </div>";

    }elseif ($form_state->getValue('sure') == 2) {
      $content = "<div>
      <h4>Folder ".$form_state->getValue('name_folder')." not was deleted.</h4>
        <a text-aligin='center' href='/'>Go Home</a>
      </div>";
    }


    $response = new AjaxResponse();

  $options = array(
        'dialogClass' => 'popup-dialog-class',
        'width' => '500',
        'height' => '200',
      );
    $response->addCommand(new OpenModalDialogCommand('Delete Folder', $content, $options));
    
    return $response;
   }

  public function DeleteAll($parent_id){

      $storage = \Drupal::entityTypeManager()->getStorage('folder');
              $id = $storage->getQuery()
              ->condition('id_parent',$parent_id, '=')
              ->execute();
            
              $ids = $storage->loadMultiple($id);
              for($i=0;$i<count($ids); $i++){
                $EntityFolder = array_values($ids)[$i];
                $EntityFolder->delete();
              }

            
      


      Folder::load($parent_id)->delete();
      

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
