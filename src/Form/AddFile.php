<?php

namespace Drupal\document_mgmt\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\user\Entity\User;
use Drupal\document_mgmt\Entity\Document;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax;
use Drupal\Core\Ajax\OpenModalDialogCommand;
use Drupal\Core\Ajax\CssCommand;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Respons;

/**
 * Class AddFile.
 *
 * @package Drupal\document_mgmt\Form
 */
class AddFile extends FormBase {


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'add_file';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $id_folder = NULL, $name_folder = NULL) {

    $form['document'] = array(
            '#type' => 'managed_file',
            '#title' => t('Select file for update'),
            '#description' => t('The uploaded image will be displayed on this page using the image style chosen below.'),
            '#required' => TRUE,
            '#upload_location' => 'public://document/',
            '#attributes' => array('class' => array('form_upload_document')),
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
        '#value' => t('Add file'),
        '#ajax' => array(
        'callback' => '::add_file',
        'wrapper' => 'form-wrap',
        'effect' => 'fade'
      ),
    ];

    return $form;
  }


  public function add_file(&$form, FormStateInterface $form_state, Request $request) {

    $id_folder = $form_state->getValue('id_folder');
    $name_folder = $form_state->getValue('name_folder');
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
               'id_parent' => $id_folder,
               'size' => $bytes,
               'date_created' => date("Y-m-d h:i:s"),
               'latest_date' => date("Y-m-d h:i:s"),
               'modified_by' => $userCurrent->getUsername(),
    ])->save();


    $content = "<div>
      <h4>Your file ".$file->filename->value." added in ".$name_folder." folder .</h4>
        <a tex-aligin='center' href='/'>Go Home</a>
      </div>";


    $response = new AjaxResponse();

  $options = array(
        'dialogClass' => 'popup-dialog-class',
        'width' => '500',
        'height' => '200',
      );
    $response->addCommand(new OpenModalDialogCommand('Add File', $content, $options));
    
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
