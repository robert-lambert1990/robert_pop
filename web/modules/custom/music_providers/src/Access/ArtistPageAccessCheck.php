<?php

namespace Drupal\music_providers\Access;

use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Url;

class ArtistPageAccessCheck {

  /**
   * Custom access check for the artist page.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The current user account.
   *
   * @return \Drupal\Core\Access\AccessResult
   *   The access result.
   */
  public function access(AccountInterface $account) {
    if ($account->isAuthenticated()) {
      return AccessResult::allowed();
    }

    $response = new RedirectResponse(Url::fromRoute('<front>')->toString());
    $response->send();
    return AccessResult::forbidden();
  }
}
