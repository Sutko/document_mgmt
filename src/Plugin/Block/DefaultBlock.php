<?php

namespace Drupal\document_mgmt\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Database\Connection;
//use Drupal\document_mgmt\Entity\Document;

/**
 * Provides a 'DefaultBlock' block.
 *
 * @Block(
 *  id = "default_block",
 *  admin_label = @Translation("Document MGMT"),
 * )
 */
class DefaultBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {

$storage = \Drupal::entityTypeManager()->getStorage('folder');

    $ids = $storage->getQuery()
    ->condition('id',1, '=')
        ->execute();

    $result = $storage->loadMultiple($ids);

    $table = "<a class='use-ajax quantity button_ajax' data-dialog-type='modal' href='/document_mgmt/form/update_file/1'>add qualification</a>
<div id='table_document' class='table-responsive'>
    <table class='table table-striped'>
        <tr>
          <td>Name</td>
          <td>Date created</td>
          <td>Date Modified</td>
          <td>Modified By</td>
          <td>Size</td>
        </tr>
        ";
        
  foreach($result as $key){
  /*
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
*/

    $table = $table."<tr>

    <td id='name_element' class='folder'><p name='".$key->id->value."'>".$key->name->value."</p><span class='triangle'  url='/document_mgmt/show_menu_folder/".$key->id->value."/".$key->name->value."' name='".$key->name->value."'><img src='/sites/default/files/007436-blue-jelly-icon-arrows-triangle-solid-down.png'></span></td>
    <td id='date_created_element'>".$key->date_created->value."</td>
    <td id='latest_date_element'>".$key->latest_date->value."</td>
    <td id='modified_by_element'>".$key->modified_by->value."</td>
    <td> </td></tr>";
}

$table = $table."</table></div>";





    $build = [];
    $build['default_block']['#markup'] = $table;
    $build['#cache']['max-age'] = 0;

    return $build;
  }

}
