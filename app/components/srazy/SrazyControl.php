<?php namespace Cryptoparty;

use \Nette\Utils\Strings;

/**
 * @author Ondřej Profant
 * @package Cryptoparty
 **/
class SrazyControl extends \Nette\Application\UI\Control
{
	/**
	 * @var array|string[]
	 */
	private $events;

	/**
	 * @param string $url last unique part of url at srazy.info
	 * @param string $name to show
	 * @return SrazyControl
	 **/
	public function addEvent($url, $name)
	{
		$this->events[$url] = $name;

		return $this;
	}

	public function render()
	{
		$this->template->events = $this->events;
		$this->template->setFile(__DIR__ . '/default.latte');
		$this->template->render();
	}
}