<?php namespace App\AdminModule;
/**
 * @author Ondřej Profant, 2013
 * @package Cryptoparty
 */

final class TagPresenter extends \App\BasePresenter
{
	/**
	 * @var \Cryptoparty\TagRepository @inject
	 */
	public $tagRep;

	/**
	 * @return AuthorNiftyGrid
	 */
	protected function createComponentTagGrid()
	{
		return new \AdminModule\TagNiftyGrid($this->tagRep);
	}

	protected function createComponentAddForm()
	{
		$form = new \Nette\Application\UI\Form();
		$form->addText('name', 'Jméno');
		$form->addSubmit('save', 'Přidat');
		$form->onSuccess[] = $this->formSuccess;

		return $form;
	}

	/**
	 * @param \Nette\Application\UI\Form $form
	 */
	public function formSuccess(\Nette\Application\UI\Form $form)
	{
		$values = $form->getValues();
		try {
			$values['created'] = time();
			$this->tagRep->insert($values);
			$this->flashMessage('Tag přidán', 'success');
		} catch (\Exception $e) {
			$this->flashMessage('Nepodařilo se vložit do DB: ' . $e->getMessage(), 'error');
		}
		$this->redirect('this');
	}
}