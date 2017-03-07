<?php

/**
 * @file
 * Contains folder.page.inc.
 *
 * Page callback for Folder entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Folder templates.
 *
 * Default template: folder.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_folder(array &$variables) {
  // Fetch Folder Entity Object.
  $folder = $variables['elements']['#folder'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
