<?php

namespace Drupal\practise\Controller;

use Drupal\practise\HelloWorldSalutation;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Controller\ControllerBase;

/**
 * Provides route responses for the Example module.
 */
class ExampleController extends ControllerBase {

	/**
	 * @var \Drupal\practise\HelloWorldSalutation
	 */
	 protected $salutation;
	 /**
	 * HelloWorldController constructor.
	 *
	 * @param \Drupal\practise\HelloWorldSalutation $salutation
	 */
	 public function __construct(HelloWorldSalutation $salutation) {
	 	$this->salutation = $salutation;
	 }

	/**
	 * {@inheritdoc}
	 */
	 public static function create(ContainerInterface $container) {
	 	return new static(
	 		$container->get('practise.salutation')
	 	);
	 }

	/**
	 * Returns a simple page.
	 *
	 * @return array
	 *   A simple renderable array.
	 */
	public function myPage() {
		$element = array(
		  '#markup' => $this->salutation->getSalutation(),
		);
		return $element;
	}

}