<?php

namespace Drupal\drupal8_development;

use Drupal\Core\Session\AccountProxy;

/**
 * Simple example of the current user
 */
class CurrentUserService
{
   /**
   * Current User.
   *
   * @var Drupal\Core\Session\AccountProxy
   */
	private $CurrentUser;

	public function __construct(AccountProxy $CurrentUSer)
	{
		$this->CurrentUser = $CurrentUSer;
	}

	public function getUserName(){
		return $this->CurrentUser->getDisplayName();
	} 
}