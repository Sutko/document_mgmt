<?php

/**
 * @file
 * Contains document.page.inc.
 *
 * Page callback for Document entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Document templates.
 *
 * Default template: document.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_document(array &$variables) {
  // Fetch Document Entity Object.
  $document = $variables['elements']['#document'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
