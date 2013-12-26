<?php namespace Cryptoparty\Admin\Form;
/**
 * @author Ondřej Profant, 2013
 */

use Nette\Application\UI\Form;

class NewUploadForm extends Form
{
    /**
     * @var \Cryptoparty\UploadManager
     **/
    protected  $uploadManager;

    /**
     * @param \Cryptoparty\UploadManager $um
     **/
    public function __construct( \Cryptoparty\UploadManager $um )
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
     * @param \Nette\Application\UI\Form $form
     **/
    public function add(\Nette\Application\UI\Form $form)
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
