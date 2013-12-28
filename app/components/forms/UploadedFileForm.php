<?php namespace Cryptoparty\Admin\Form;
/**
 * @author Ondřej Profant, 2013
 * @package Cryptoparty
 */

use Cryptoparty,
	Nette\Application\UI\Form,
	Nette\Utils\Html,
	Nette\Forms\Controls\SubmitButton;

class UploadedFileForm extends Form
{
	/**
	 * @var UploadManager
	 */
	protected $uploadManager;

	/**
	 * @param UploadManager $um
	 * @param int $id
	 */
	public function __construct(UploadManager $um, $id)
    {
        parent::__construct();

        $this->uploadManager = $um;
        $record = $this->uploadManager->get($id);

        $this->addHidden('id', 'id')
            ->setDefaultValue($record->id);
        $this->addText('name', 'Název:')
            ->setRequired('Name is required')
            ->setAttribute('class', 'input-medium')
            ->addRule(Form::MAX_LENGTH, 'Max length of %label is %d', 15)
            ->setDefaultValue($record->name);
        $this->addText('description', 'Popis')
            ->addRule(Form::MAX_LENGTH, 'Max length of %label is %d', 160)
            ->setAttribute('class', 'input-xlarge')
            ->setDefaultValue( $record->description );
        $this->addUpload('path', 'Path')
            ->setAttribute('class', 'input-mini');

        $this->addSubmit('save', NULL)
            ->setAttribute('class', 'btn btn-primary')
            ->onClick[] = $this->update;
        $pretyp = $this['save']->getControlPrototype();
        $pretyp->setName("button"); // změna prvku na button
        $pretyp->type = 'submit'; // nastavení typu buttonu
        // následuje vytvoření obsahu mezi <button>...</button>
        $pretyp->create('span')
            ->add(Html::el()
                ->create('span', 'Uložit ')
        );


        $this->addSubmit('delete', '...') //<----- lze standardně nastavit value
            ->setAttribute('class', 'btn btn-danger btn-small')
            ->onClick[] = $this->delete;
        $pretyp = $this['delete']->getControlPrototype();
        $pretyp->setName("button"); // změna prvku na button
        $pretyp->type = 'submit'; // nastavení typu buttonu
        // následuje vytvoření obsahu mezi <button>...</button>
        $pretyp->create('span')
            ->add(Html::el()
                ->create('span', 'Smazat ')
        )
            ->add(Html::el()
                ->create('i class="icon-trash icon-white"')
        );
    }

    /**
     * @param SubmitButton $button
     */
    public function update(SubmitButton $button) {
        $values = $button->form->getValues();

        if( intval($values->path->size) === 0 ) {
            unset($values['path']);
        }

        $this->uploadManager->update($values);

        $this->presenter->flashMessage("Aktualizace $values->name proběhla úspěšně", 'success');

        if( $this->presenter->isAjax() ) {
            $this->presenter->invalidateControl('uploads');
        } else {
            $this->presenter->redirect('this');
        }
    }

    /**
     * @param SubmitButton $button
     */
    public function delete(SubmitButton $button) {
        $values = $button->form->getValues();

        $this->uploadManager->delete( $values->id );

        $this->presenter->flashMessage('Mažu! ' . $values->name, 'error');

        $this->presenter->redirect('this');
    }
}
