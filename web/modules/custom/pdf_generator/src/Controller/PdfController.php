<?php

namespace Drupal\pdf_generator\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Drupal\node\NodeInterface;
use Dompdf\Dompdf;
use Dompdf\Options;

class PdfController extends ControllerBase {

  /**
   * The renderer service.
   *
   * @var \Drupal\Core\Render\Renderer
   */
  protected $renderer;

  /**
   * Constructs a PdfController object.
   *
   * @param \Drupal\Core\Render\Renderer $renderer
   *   The renderer service.
   */
  public function __construct($renderer) {
    $this->renderer = $renderer;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('renderer')
    );
  }

  /**
   * Generates a PDF from a node.
   *
   * @param \Drupal\node\NodeInterface $node
   *   The node entity.
   *
   * @return \Symfony\Component\HttpFoundation\Response
   *   The PDF response.
   */
  public function generatePdf(NodeInterface $node) {
    // Render the node using the 'full' view mode.
    $build = $this->entityTypeManager()->getViewBuilder('node')->view($node, 'full');
    $rendered_html = $this->renderer->renderRoot($build);

    // Prepare the HTML content.
    $html = '<html><head><style>
               body { font-family: DejaVu Sans, sans-serif; }
             </style></head><body>';
    $html .= $rendered_html;
    $html .= '</body></html>';

    // Configure DOMPDF options.
    $options = new Options();
    $options->set('defaultFont', 'DejaVu Sans');
    $dompdf = new Dompdf($options);
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    // Generate PDF output.
    $pdf_content = $dompdf->output();

    // Create and return the response.
    $response = new Response($pdf_content);
    $response->headers->set('Content-Type', 'application/pdf');
    $response->headers->set('Content-Disposition', 'attachment;filename="node-' . $node->id() . '.pdf"');

    return $response;
  }
}
