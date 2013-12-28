<?php namespace Cryptoparty\Admin\Form;
/**
 * @author Ondřej Profant, 2013
 */

use Cryptoparty,
	Nette\Application\UI\Form;

class NewUploadForm extends Form
{
    /**
     * @var UploadManager
     **/
    protected  $uploadManager;

    /**
     * @param UploadManager $um
     **/
    public function __construct(UploadManager $um )
    {
        parent::__construct();

        $this->uploadManager = $um;

        $this->addText('name', 'Název:')
            ->setRequired('%label is required')
            ->setAttribute('class', 'input-medium');
        $this->addText('description', 'Popis')
            ->setAttribute('class', 'input-xlarge');
        $this->addUpload('path', 'Cesta')
            ->setRequired();
        $this->addSubmit('send', 'Přidat')
            ->setAttribute('class', 'btn btn-success btn-block');
        $this->onSuccess[] = $this->add;
    }

    /**
     * @param Form $form
     **/
    public function add(Form $form)
    {
        $values = $form->getValues();

        try {
            $this->uploadManager->upload($values);
            $this->presenter->flashMessage('Úspěšně nahrán', 'success');
        } catch(\IOException $e) {
            $this->presenter->flashMessage('Něco se pokazilo: '. $e->getMessage(), 'error');
        }

        $this->presenter->redirect('this');
    }
}
