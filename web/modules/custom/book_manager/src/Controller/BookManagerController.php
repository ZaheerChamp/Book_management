<?php
/**
 * @file
 * Controller file provides opration for managing books.
 */

namespace Drupal\book_manager\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;

class BookManagerController extends ControllerBase {

  /**
   * Lists all Book nodes.
   *
   * @return array
   *   A render array for a table of book nodes.
   */
  public function listBooks() {
    $header = [
      'title' => $this->t('Title'),
      'author' => $this->t('Author'),
      'year' => $this->t('Publication Year'),
      'operations' => $this->t('Operations'),
    ];

    // Query to load all book nodes.
    $query = \Drupal::entityQuery('node')
      ->condition('type', 'book')
      ->sort('created', 'DESC');
    $nids = $query->execute();

    $rows = [];
    // Load each book node and prepare the rows for the table.
    foreach (Node::loadMultiple($nids) as $node) {
      $rows[] = [
        'title' => $node->label(),
        'author' => $node->get('field_author')->value,
        'year' => $node->get('field_publication_year')->value,
        'operations' => [
          'data' => [
            '#type' => 'operations',
            '#links' => $this->getOperations($node),
          ],
        ],
      ];
    }

    $build['table'] = [
      '#type' => 'table',
      '#header' => $header,
      '#rows' => $rows,
    ];

    return $build;
  }

  /**
   * Helper function to get the operations links for a book node.
   *
   * @param \Drupal\node\Entity\Node $node
   *   The book node.
   *
   * @return array
   *   An array of operation links.
   */
  private function getOperations(Node $node) {
    $operations = [];
    if ($node->access('update')) {
      $operations['edit'] = [
        'title' => $this->t('Edit'),
        'url' => $node->toUrl('edit-form'),
      ];
    }
    if ($node->access('delete')) {
      $operations['delete'] = [
        'title' => $this->t('Delete'),
        'url' => $node->toUrl('delete-form'),
      ];
    }
    return $operations;
  }
}
