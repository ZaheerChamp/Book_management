<?php

declare(strict_types=1);

namespace Drupal\Tests\system\Functional\Common;

use Drupal\Core\Url;
use Drupal\Tests\BrowserTestBase;

/**
 * Confirm that the link generator works correctly.
 *
 * @group Common
 */
class UrlTest extends BrowserTestBase {

  protected static $modules = ['common_test'];

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * Tests the active class in links.
   */
  public function testActiveLinkAttributes(): void {
    $options_no_query = [];
    $options_query = [
      'query' => [
        'foo' => 'bar',
        'one' => 'two',
      ],
    ];
    $options_query_reverse = [
      'query' => [
        'one' => 'two',
        'foo' => 'bar',
      ],
    ];

    // Test #type link.
    $path = 'common-test/type-link-active-class';

    $this->drupalGet($path, $options_no_query);

    // Test that a link generated by the link generator to the current page is
    // marked active.
    $this->assertSession()->elementExists('xpath', $this->assertSession()->buildXPathQuery(
      '//a[@href = :href and contains(@class, "is-active")]', [
        ':href' => Url::fromRoute('common_test.l_active_class', [], $options_no_query)->toString(),
      ]
    ));
    // Test that a link generated by the link generator to the current page
    // with a query string when the current page has no query string is not
    // marked active.
    $this->assertSession()->elementExists('xpath', $this->assertSession()->buildXPathQuery(
      '//a[@href = :href and not(contains(@class, "is-active"))]', [
        ':href' => Url::fromRoute('common_test.l_active_class', [], $options_query)->toString(),
      ]
    ));

    $this->drupalGet($path, $options_query);

    // Test that a link generated by the link generator to the current page with
    // a query string that matches the current query string is marked active.
    $this->assertSession()->elementExists('xpath', $this->assertSession()->buildXPathQuery(
      '//a[@href = :href and contains(@class, "is-active")]', [
        ':href' => Url::fromRoute('common_test.l_active_class', [], $options_query)->toString(),
      ]
    ));
    // Test that a link generated by the link generator to the current page with
    // a query string that has matching parameters to the current query string
    // but in a different order is marked active.
    $this->assertSession()->elementExists('xpath', $this->assertSession()->buildXPathQuery(
      '//a[@href = :href and contains(@class, "is-active")]', [
        ':href' => Url::fromRoute('common_test.l_active_class', [], $options_query_reverse)->toString(),
      ]
    ));
    // Test that a link generated by the link generator to the current page
    // without a query string when the current page has a query string is not
    // marked active.
    $this->assertSession()->elementExists('xpath', $this->assertSession()->buildXPathQuery(
      '//a[@href = :href and not(contains(@class, "is-active"))]', [
        ':href' => Url::fromRoute('common_test.l_active_class', [], $options_no_query)->toString(),
      ]
    ));
  }

}
