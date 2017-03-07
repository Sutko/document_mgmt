<?php

namespace Drupal\document_mgmt\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Document entities.
 */
class DocumentViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    // Additional information for Views integration, such as table joins, can be
    // put here.

    return $data;
  }

}
