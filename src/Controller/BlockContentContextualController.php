<?php

namespace Drupal\block_content_contextual\Controller;

use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class BlockContentContextualController.
 *
 * @package Drupal\block_content_contextual\Controller
 */
class BlockContentContextualController extends ControllerBase {

  /**
   * @var \Drupal\Core\Entity\EntityTypeManager
   */
  protected $entityTypeManager;

  /**
   * BlockContentContextualController constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   *
   * @return static
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager')
    );
  }

  /**
   * Page callback for example of theming block content with contextual links.
   */
  public function example() {

    // Prepare render array.
    $build = [
      '#type' => 'container',
      '#attributes' => [
        '#class' => ['content-wrapper'],
      ],
    ];

    // Load all block_content entities.
    $blocks = $this->entityTypeManager->getStorage('block_content')->loadMultiple();

    // If no entities has been found, leave some message.
    if (empty($blocks)) {
      $link = Link::fromTextAndUrl($this->t('custom blocks library'), Url::fromRoute('entity.block_content.collection'));

      $build['content'] = [
        '#type' => 'html_tag',
        '#tag' => 'p',
        '#value' => $this->t('No custom blocks found, create some blocks in @link.', ['@link' => $link->toString()]),
      ];
      return $build;
    }

    // Wrapper for blocks without contextual links.
    $build['blocks_no_contextual'] = [
      '#type' => 'container',
      'title' => [
        '#type' => 'html_tag',
        '#tag' => 'h2',
        '#value' => $this->t('Rendered blocks without contextual links:'),
      ],
    ];

    // Here we'll show blocks with contextual links.
    $build['blocks_contextual'] = [
      '#type' => 'container',
      'title' => [
        '#type' => 'html_tag',
        '#tag' => 'h2',
        '#value' => $this->t('Rendered blocks with contextual links:'),
      ],
    ];

    // Get entity view builder.
    $block_builder = $this->entityTypeManager->getViewBuilder('block_content');
    kint($block_builder);
    // Render blocks in two variants to show the difference.
    // What's interesting - markup for those variant will be the same
    // for users without access to contextual links.
    foreach ($blocks as $block) {
      $block_view = $block_builder->view($block);

      // In general, if we will return just a block view,
      // it will render all fields inside of that block, without taking care
      // of attributes, title suffix etc.
      $build['blocks_no_contextual'][$block->id()] = $block_view;

      // If we will theme block content entity using our custom hook,
      // our template will take care of it and it will add markup
      // needed to display contextual links.
      // Turn on theme debug to catch the differences in HTML markup.
      $build['blocks_contextual'][$block->id()] = [
        '#theme' => 'block_content_contextual_links_wrap',
        '#content' => $block_view,
      ];
    }

    return $build;
  }

}
