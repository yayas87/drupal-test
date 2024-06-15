<?php

namespace Drupal\pdf_generator\Access;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\Routing\Route;

class PdfAccessCheck {

  /**
   * A custom access check for the PDF generation route.
   *
   * @param \Symfony\Component\Routing\Route $route
   *   The route.
   * @param \Drupal\Core\Routing\RouteMatchInterface $route_match
   *   The current route match.
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The currently logged-in user.
   *
   * @return \Drupal\Core\Access\AccessResult
   *   The access result.
   */
  public function access(Route $route, RouteMatchInterface $route_match, AccountInterface $account) {
    // Allow access for user ID 1.
    if ($account->id() == 1) {
      return AccessResult::allowed();
    }

    // Allow access for administrators.
    if ($account->hasPermission('administer site configuration')) {
      return AccessResult::allowed();
    }

    // Define the roles that are allowed to access the PDF download.
    $allowed_roles = ['role1', 'role2']; // Replace with your specific roles.

    foreach ($allowed_roles as $role) {
      if ($account->hasRole($role)) {
        return AccessResult::allowed();
      }
    }
    return AccessResult::forbidden();
  }
}
