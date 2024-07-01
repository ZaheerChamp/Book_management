<?php

/**
 * @file
 * Contains add, edit book Form.
 */

namespace Drupal\book_manager\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;

class BookForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'book_manager_form';
  }

  /**
   * Builds the add/edit form for book nodes.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   * @param \Drupal\node\Entity\Node|null $node
   *   The node being edited, or NULL if creating a new one.
   *
   * @return array
   *   The form structure.
   */
  public function buildForm(array $form, FormStateInterface $form_state, Node $node = NULL) {
    $form['title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Title'),
      '#required' => TRUE,
      '#default_value' => $node ? $node->label() : '',
    ];

    $form['author'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Author'),
      '#required' => TRUE,
      '#default_value' => $node ? $node->get('field_author')->value : '',
    ];

    $form['publication_year'] = [
      '#type' => 'number',
      '#title' => $this->t('Publication Year'),
      '#required' => TRUE,
      '#default_value' => $node ? $node->get('field_publication_year')->value : '',
    ];

    $form['actions'] = [
      '#type' => 'actions',
      'submit' => [
        '#type' => 'submit',
        '#value' => $this->t('Save'),
      ],
    ];

    return $form;
  }

  /**
   * Form submission handler.
   *
   * @param array &$form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $node = Node::create([
      'type' => 'book',
      'title' => $form_state->getValue('title'),
      'field_author' => $form_state->getValue('author'),
      'field_publication_year' => $form_state->getValue('publication_year'),
    ]);
    $node->save();
    $this->messenger()->addStatus($this->t('Book saved successfully.'));
    $form_state->setRedirect('book_manager.book_list');
  }

  /**
   * Form validation handler.
   *
   * @param array &$form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $year = $form_state->getValue('publication_year');
    if (!is_numeric($year) || $year < 0 || $year > date('Y')) {
      $form_state->setErrorByName('publication_year', $this->t('Please enter a valid year.'));
    }
  }
}
