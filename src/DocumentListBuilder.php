<?php

namespace Drupal\document_mgmt;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Routing\LinkGeneratorTrait;
use Drupal\Core\Url;

/**
 * Defines a class to build a listing of Document entities.
 *
 * @ingroup document_mgmt
 */
class DocumentListBuilder extends EntityListBuilder {

  use LinkGeneratorTrait;

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Document ID');
    $header['id_parent'] = $this->t('Id Parent');
    $header['name'] = $this->t('Name');
    $header['date_created'] = $this->t('Date Created');
    $header['latest_date'] = $this->t('Date Modified');
    $header['modified_by'] = $this->t('Modified By');
    $header['size'] = $this->t('Size Document');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\document_mgmt\Entity\Document */
    $row['id'] = $entity->id();
    $row['id_parent'] = $entity->id_parent->value;
    $row['name'] = $entity->name->value;
    $row['date_created'] = $entity->date_created->value;
    $row['latest_date'] = $entity->latest_date->value;
    $row['modified_by'] = $entity->modified_by->value;
    $row['size'] = $entity->size->value;
    return $row + parent::buildRow($entity);
  }

}
