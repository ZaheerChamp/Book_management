<?php

/**
 * @file
 * Contains delete book Form.
 */

namespace Drupal\book_manager\Form;

use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\RedirectResponse;

class BookDeleteForm extends ConfirmFormBase {

  /**
   * The node to be deleted.
   *
   * @var \Drupal\node\Entity\Node
   */
  protected $node;

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'book_manager_delete_form';
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return $this->t('Are you sure you want to delete the book %title?', ['%title' => $this->node->label()]);
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    return new RedirectResponse('/book');
  }

  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {
    return $this->t('Delete');
  }

  /**
   * Builds the delete confirmation form.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   * @param \Drupal\node\Entity\Node|null $node
   *   The node being deleted.
   *
   * @return array
   *   The form structure.
   */
  public function buildForm(array $form, FormStateInterface $form_state, Node $node = NULL) {
    $this->node = $node;
    return parent::buildForm($form, $form_state);
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
    $this->node->delete();
    $this->messenger()->addStatus($this->t('Book deleted successfully.'));
    $form_state->setRedirect('book_manager.book_list');
  }
}
