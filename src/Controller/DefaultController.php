<?php

namespace Drupal\document_mgmt\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Drupal\Component\Serialization\Json;
use Drupal\Core\Ajax;
use Drupal\Component\Utility\Crypt;
use Drupal\Core\Url;
use Drupal\Core\Site\Settings;
use Drupal\Core\Session\AnonymousUserSession;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Class DefaultController.
 *
 * @package Drupal\document_mgmt\Controller
 */
class DefaultController extends ControllerBase {


/**
   * Show menu for file
   *
   * @return string
   *   Return Hello string.
   */
  public function Show_menu_file($id_file, $name_file) {
    
    $html = "<a class='use-ajax quantity button_ajax' data-dialog-type='modal' href='/document_mgmt/form/update_file/1'>add qualification</a>
    <ul class='file_menu'>
              <li><h2><span class='document-action' id='update-document' id-file='".$id_file."' name-file='".$name_file."' url='/document_mgmt/form/update_file/".$id_file."'>- Update</span></h2></li>
              <li><h2><span class='folder action' url='/document_mgmt/form/delete_file/".$id_file."/".$name_file."'>- Delete</span></h2></li>
              <li><h2><span class='folder action' url='/document_mgmt/form/rename_file/".$id_file."/".$name_file."'>- Rename</span></h2></li>
              <li><h2><span class='folder action' url=''>- Versions</span></h2></li>
              <li><h2><span class='folder action' url=''>- Properties</span></h2></li>
            </ul>";

    return $this->send_response($html);
  }


  /**
   * Show menu for folder
   *
   * @return string
   *   Return Hello string.
   */
  public function Show_menu_folder($id_folder, $name_folder) {
    
    $html = "<ul class='file_menu'>
              <li><h2><span class='' url='/document_mgmt/show_menu_add/".$id_folder."/".$name_folder."'>- Add</span></h2></li>
              <li><h2><span class='' href='/document_mgmt/form/rename_folder/".$id_folder."/".$name_folder."'>- Rename</span></h2></li>
              <li><h2><span class='' url='/document_mgmt/form/delete_folder/".$id_folder."/".$name_folder."'>- Delete</span></h2></li>
              <li><h2><span class='' url=''>- Properties</span></h2></li>
            </ul>";

    return $this->send_response($html);
  }


  /**
   * Show menu for add
   *
   * @return string
   *   Return Hello string.
   */
  public function Show_menu_add($id_folder, $name_folder) {
    
    $html = "<ul class='file_menu'>
              <li><h2><a class='use-ajax quantity button_ajax' data-dialog-type='modal' href='/document_mgmt/form/add_file/".$id_folder."/".$name_folder."'>- Document</a></h2></li>
              <li><h2><a class='use-ajax quantity button_ajax' data-dialog-type='modal' href='/document_mgmt/form/add_folder/".$id_folder."/".$name_folder."'>- Folder</a></h2></li>
            </ul>";
    return [
      '#type' => 'markup',
      '#markup' => $html,
    ];
  }


  /**
   * Get_data.
   *
   * @return string
   *   Return Hello string.
   */
  public function Get_data(Request $request) {
    
    $id_folder = $request->query->get('id_folder');

    $table = "
    <table class='table table-striped'>
        <tr>
          <td>Name</td>
          <td>Date created</td>
          <td>Date Modified</td>
          <td>Modified By</td>
          <td>Size</td>
        </tr>
        ";

        $storage = \Drupal::entityTypeManager()->getStorage('document');

    $ids = $storage->getQuery()
    ->condition('id_parent',$id_folder, '=')
    ->execute();
    $result = $storage->loadMultiple($ids);

     foreach($result as $key){
      $file = \Drupal\file\Entity\File::load($key->name->target_id);
    $array_url = explode('//', $file->uri->value);
    
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

    $table = $table."<tr>

    <td id='name_element' class='document'><a href='/sites/default/files/".$array_url[1]."' download='".$file->filename->value."' download>".$file->filename->value."</a><span class='triangle' name='".$file->filename->value."'  url='/document_mgmt/show_menu_file/".$key->id->value."/".$file->filename->value."'><img src='/sites/default/files/007436-blue-jelly-icon-arrows-triangle-solid-down.png'></span></td>
    <td id='date_created_element'>".$key->date_created->value."</td>
    <td id='latest_date_element'>".$key->latest_date->value."</td>
    <td id='modified_by_element'>".$key->modified_by->value."</td>
    <td>".$bytes."</td></tr>";
  }


  $storage = \Drupal::entityTypeManager()->getStorage('folder');

    $ids = $storage->getQuery()
    ->condition('id_parent',$id_folder, '=')
    ->execute();
    $result = $storage->loadMultiple($ids);

     foreach($result as $key){

      $table = $table."<tr>

    <td id='name_element' class='folder'><p name='".$key->id->value."'>".$key->name->value."</p><span class='triangle' url='/document_mgmt/show_menu_folder/".$key->id->value."/".$key->name->value."' name='".$key->name->value."'><img src='/sites/default/files/007436-blue-jelly-icon-arrows-triangle-solid-down.png'></span</td>
    <td id='date_created_element'>".$key->date_created->value."</td>
    <td id='latest_date_element'>".$key->latest_date->value."</td>
    <td id='modified_by_element'>".$key->modified_by->value."</td>
    <td> </td></tr>";


     }


        $table = $table."</table>";

    return $this->send_response($table);
  }


  /**
   * Get data for document
   *
   * @return string
   *   Return Hello string.
   */
  public function GetDateForDocument($action, $id_document, $name_document) {

    
    $form_class = '\Drupal\document_mgmt\Form\UpdateFile';
    $build = \Drupal::formBuilder()->getForm($form_class);
    $html = drupal_render($build);
  

     return [
      '#type' => 'markup',
      '#markup' => $html,
    ];
  }




  /**
   * Versions_file.
   *
   * @return string
   *   Return Hello string.
   */
  public function Versions_file($id_file) {
    return [
      '#type' => 'markup',
      '#markup' => $this->t('Implement method: Versions_file with parameter(s): $id_file'),
    ];
  }

  /**
   * Properties_file.
   *
   * @return string
   *   Return Hello string.
   */
  public function Properties_file($id_file) {
    return [
      '#type' => 'markup',
      '#markup' => $this->t('Implement method: Properties_file with parameter(s): $id_file'),
    ];
  }


  /**
   * Properties_file.
   *
   * @return string
   *   Return Hello string.
   */
  public function Properties_folder($id_file) {
    return [
      '#type' => 'markup',
      '#markup' => $this->t('Implement method: Properties_folder with parameter(s): $id_file'),
    ];
  }

  private function send_response($data){
    $response = new Response();
    $response->setStatusCode(200);
    $response->setContent(json_encode($data));
    $response->headers->add(['Content-Type' =>'application/json']);

    return $response;
  }

  private function send_response_html($data){
    $response = new Response();
    $response->setStatusCode(200);
    $response->setContent($data);
    $response->headers->add(['Content-Type' =>'multipart/form-data']);
    return $response;
  }

}
