<?php namespace App\AdminModule;
/**
 * @author Ondřej Profant, 2013
 * @package Cryptoparty
 */

final class AuthorPresenter extends \App\BasePresenter
{
	/**
	 * @var \Cryptoparty\AuthorRepository @inject
	 */
	public $authorRep;

	/**
	 * @return AuthorNiftyGrid
	 */
	protected function createComponentAuthorGrid()
	{
		return new \AdminModule\AuthorNiftyGrid($this->authorRep);
	}

	protected function createComponentAddForm()
	{
		$form = new \Nette\Application\UI\Form();
		$form->addText('name', 'Jméno');
		$form->addSubmit('save', 'Přidat');
		$form->onSuccess[] = function() {

		};

		return $form;
	}

	public function formSuccess(\Nette\Application\UI\Form $form)
	{
		$values = $form->getValues();
		try {
			$this->authorRep->insert($values);
			$this->flashMessage('Autor přidán', 'success');
		} catch(\Exception $e) {
			$this->flashMessage('Něco se pokazilo: ' . $e->getMessage(), 'error');
		}
		$this->redirect('this');
	}
}