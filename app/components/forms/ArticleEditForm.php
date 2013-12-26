<?php namespace Cryptoparty\Form;
/**
 * @author Ondřej Profant, 2013
 * @package Cryptoparty
 */

use Nette\Application\UI\Form,
	dflydev\markdown\MarkdownParser;

class ArticleEditForm extends Form
{
	/**
	 * @var \Cryptoparty\ArticleRepository $aRep
	 **/
	private $articleRep;

	/**
	 * @var \Cryptoparty\AuthorRepository
	 */
	private $authorRep;

	/**
	 * @var \Cryptoparty\TagRepository
	 */
	private $tagRep;

	/**
	 * @param \Cryptoparty\ArticleRepository $aRep
	 * @param \Cryptoparty\AuthorRepository $authRep
	 * @param \Cryptoparty\TagRepository $tRep
	 **/
	public function __construct( \Cryptoparty\ArticleRepository $aRep, \Cryptoparty\AuthorRepository $authRep, \Cryptoparty\TagRepository $tRep )
	{
		parent::__construct();
		$this->articleRep = $aRep;
		$this->authorRep = $authRep;
		$this->tagRep = $tRep;

		$this->addHidden('id')
			->setDefaultValue(0);
		$this->addText('title', 'Název')
			->setRequired();
		$this->addCheckbox('visible', 'viditelny');
		$authors = $this->authorRep->findPairs();
		$this->addSelect('author_id', 'Autor', $authors);
		$this->addTextArea('perex_md', 'Perex')
			->addRule(Form::MAX_LENGTH, 'Perex smí být dlouhý max. %d znaků', 220)
			->setRequired()
			->setHtmlId('input-perex');
		$this->addTextArea('text_md', 'Text')
			->setHtmlId('input-text');

		$tags = $this->tagRep->findPairs();
		$this->addMultiSelect('tags', 'Tagy', $tags);

		$this->addSubmit('send', 'Uložit')
			->setAttribute('class', 'btn btn-primary btn-large');

		$this->onSuccess[] = $this->editFormSubmit;
	}

	public function editFormSubmit(Form $form)
	{
		$values = $form->getValues();
		$tags = $values['tags'];
		unset($values['tags']);
		$markdownParser = new MarkdownParser();
		try {
			$values['perex_html'] = $markdownParser->transformMarkdown( $values->perex_md  );
			$values['text_html'] = $markdownParser->transformMarkdown( $values->text_md  );
		} catch (\Exception $e) {
			$this->addError('Chyba v MarkDown parseru.');
		}
		$values['modifier_id'] = $this->presenter->user->id;
		$values['redactor_id'] = $this->presenter->user->id;
		$values['date_modified'] = date("Y-m-d H:i:s");

		$id = $values->id;
		try {
			if( $id == 0) {
				$values['date_published'] = date("Y-m-d H:i:s");
				$row = $this->articleRep->insert($values);
				$this->articleRep->updateTagRel($row->id, $tags);
				$this->presenter->flashMessage('Článek uspěšně přidán.', 'success');
			} else {
				$this->articleRep->update($id, $values);
				$this->articleRep->updateTagRel($id, $tags);
				$this->presenter->flashMessage('Článek uspěšně aktualizován.', 'success');
			}
		} catch(\Exception $e) {
			$this->presenter->flashMessage('Něco se pokazilo při prací s DB.', 'error');
		}

		$this->presenter->redirect('default');
	}
}