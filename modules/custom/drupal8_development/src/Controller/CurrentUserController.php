<?php

namespace Drupal\drupal8_development\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Session\AccountProxy;

/**
 * Provides route responses for the Example module.
 */
class CurrentUserController extends ControllerBase {

	/**
	 * Returns a simple page.
	 *
	 * @return array
	 *   A simple renderable array.
	 */
	public function userDetail(AccountProxy $user) {

		$userService = \Drupal::service('drupal8_development.user');
		$userName = $userService->getUserName();

		$element = array(
		  '#markup' => $userName,
		);
		return $element;
	}

}
