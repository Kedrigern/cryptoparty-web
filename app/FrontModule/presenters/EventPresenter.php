<?php namespace App\FrontModule;

/**
 * @author Ondřej Profant
 * @package Cryptoparty
 *
 * Event presenter.
 */
class EventPresenter extends \App\BasePresenter
{
	/**
	 * @return \Cryptoparty\SrazyControl
	 **/
	protected function createComponentSrazy()
	{
		$srazy = new \Cryptoparty\SrazyControl();
		return $srazy
			->addEvent('cryptoparty', 'Praha' )
			->addEvent('cryptoparty-brno', 'Brno');
	}
}