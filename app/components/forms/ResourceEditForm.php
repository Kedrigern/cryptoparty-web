<?php namespace Cryptoparty\Form;
/**
 * @author Ondřej Profant, 2013
 * @package Cryptoparty
 */

use Cryptoparty,
	Nette\Application\UI\Form;

class ResourceEditForm extends Form
{
	/**
	 * @var ResourceRepository $aRep
	 **/
	private $resourceRep;

	/**
	 * @var AuthorRepository
	 */
	private $authorRep;

	/**
	 * @var TagRepository
	 */
	private $tagRep;

	/**
	 * @param Cryptoparty\ResourceRepository $rRep
	 * @param Cryptoparty\AuthorRepository $authRep
	 * @param Cryptoparty\TagRepository $tRep
	 **/
	public function __construct( Cryptoparty\ResourceRepository $rRep, Cryptoparty\AuthorRepository $authRep, Cryptoparty\TagRepository $tRep )
	{
		parent::__construct();
		$this->resourceRep = $rRep;
		$this->authorRep = $authRep;
		$this->tagRep = $tRep;

		$this->addHidden('id')
			->setDefaultValue(0);
		$this->addText('name', 'Jméno')
			->addRule( \Nette\Application\UI\Form::MAX_LENGTH, "Max. délka %label je %d", 25 )
			->setRequired("%label je vyžadován.");
		$this->addText('link_bin', 'Link (bin)')
			->addRule( \Nette\Application\UI\Form::MAX_LENGTH, "Max. délka %label je %d", 250 )
			->setRequired("%label je vyžadován.")
			->setAttribute('class', 'input input-xxlarge');
		$this->addText('filetype_bin', 'Filetype (bin)')
			->addRule( \Nette\Application\UI\Form::MAX_LENGTH, "Max. délka %label je %d", 10 )
			->setOption('description', 'Ovlivňuje především ikonku. Zadávejte např.: epub, odp, pdf, pptx, tex, txt, unknown, youtube');
		$this->addText('link_src', 'Link (src)')
			->addRule( \Nette\Application\UI\Form::MAX_LENGTH, "Max. délka %label je %d", 250 )
			->setAttribute('class', 'input input-xxlarge')
			->setOption('description', 'Zdrojový kód či editovatelná podoba.');
		$this->addText('filetype_src', 'Filetype (src)')
			->addRule( \Nette\Application\UI\Form::MAX_LENGTH, "Max. délka %label je %d", 10 )
			->setOption('description', 'Ovlivňuje především ikonku. Zadávejte např.: epub, odp, pdf, pptx, tex, txt, unknown, youtube');
		$this->addText('language', 'Jazyk(y)')
			->addRule( \Nette\Application\UI\Form::MAX_LENGTH, "Max. délka %label je %d", 10 )
			->setOption('description', 'Kódy jazyka (cs, en, ..) oddělené čárkou.');
		$this->addTextArea('description','Popis');

		$authors = $this->authorRep->findPairs();
		$this->addSelect('author_id', 'Autor', $authors);
		$tags = $this->tagRep->findPairs();
		$this->addMultiSelect('tags', 'Tagy', $tags);

		$this->addSubmit('send', 'Uložit')
			->setAttribute('class', 'btn btn-primary');
		$this->onSuccess[] = $this->editSuccess;

		BootstrapRenderer::set($this);
	}

	/**
	 * @param Form $form
	 */
	public function editSuccess(Form $form)
	{
		$values = $form->getValues();
		$tags = $values['tags'];
		unset($values['tags']);

		$values['modifier_id'] = $this->presenter->user->id;
		$values['date_modified'] = date("Y-m-d H:i:s");

		$id = $values->id;
		try {
			if( $id == 0) {
				$values['date_published'] = date("Y-m-d H:i:s");
				$row = $this->resourceRep->insert($values);
				$this->resourceRep->updateTagRel($row->id, $tags);
				$this->presenter->flashMessage('Zdroj uspěšně přidán.', 'success');
			} else {
				$this->resourceRep->update($id, $values);
				$this->resourceRep->updateTagRel($id, $tags);
				$this->presenter->flashMessage('Zdroj uspěšně aktualizován.', 'success');
			}
		} catch(\Exception $e) {
			$this->presenter->flashMessage('Něco se pokazilo při prací s DB: ' . $e->getMessage(), 'error');
		}

		$this->presenter->redirect('default');
	}
}