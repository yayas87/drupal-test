<?php

declare(strict_types=1);

namespace Drupal\pdf_generator\Plugin\Block;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Url;
use Drupal\node\Entity\Node;
use Drupal\pdf_generator\Access\PdfAccessCheck;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a download pdf block.
 *
 * @Block(
 *   id = "pdf_generator_download_pdf",
 *   admin_label = @Translation("Download PDF"),
 *   category = @Translation("pdf_generator"),
 * )
 */
final class DownloadPdfBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Constructs the plugin instance.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    private readonly PdfAccessCheck $pdfGeneratorAccessCheckPdf,
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition): self {
    return new self(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('pdf_generator.access_check.pdf'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build(): array {
    $current_path = \Drupal::service('path.current')->getPath();
    $current_url = Url::fromUserInput($current_path);

    // Check if the current URL is a node entity.
    if ($current_url->getRouteName() == 'entity.node.canonical') {
      // Get the node ID from the route parameters.
      $node_id = $current_url->getRouteParameters()['node'];

      // Load the node entity.
      $node = Node::load($node_id);

      // Check if the node is loaded and is of the type 'evaluation'.
      if ($node && $node->getType() == 'evaluation') {
        // Build the 'Download PDF' link.
        $build['content'] = [
          '#markup' => '<a href="/node/' . $node_id . '/pdf" class="btn btn-primary btn-small">Download PDF</a>',
        ];
      }
      else {
        // Node is not loaded or not of the type 'evaluation'.
        $build['content'] = [
          '#markup' => '',
        ];
      }
    }
    else {
      // If the current page is not a node, return an empty array.
      $build = [];
    }
    // Disable caching for this block.
    $build['#cache'] = [
      'contexts' => [],
      'tags' => [],
      'max-age' => 0,
    ];
    return $build;
  }

  /**
   * {@inheritdoc}
   */
  protected function blockAccess(AccountInterface $account): AccessResult {
    // @todo Evaluate the access condition here.
    return AccessResult::allowedIf(TRUE);
  }

}
