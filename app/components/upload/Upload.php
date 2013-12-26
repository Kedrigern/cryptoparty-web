<?php namespace Cryptoparty\Components\Admin;

use Nette\Application\UI\Form;
use Nette\Forms\Controls\SubmitButton;

/**
 * @author Ondřej Profant
 * @package Cryptoparty
 */
class Upload extends \Nette\Application\UI\Control
{
    /**
     * @var \Cryptoparty\UploadManager
     */
    private $uploadManager;

    /**
     * @persistent int
     */
    public $multiUploadCount = null;

    /**
     * @param \Cryptoparty\UploadManager $um
     */
    public function __construct(\Cryptoparty\UploadManager $um)
    {
        $this->uploadManager = $um;
    }

    public function render()
    {
        $this->template->uploads = $this->uploadManager->findAll();

        $this->template->setFile(__DIR__ . '/Upload.latte');
        $this->template->render();
    }

    /**
     * @return \Nette\Application\UI\Multiplier
     */
    protected function createComponentUploadForm()
    {
        $um = $this->uploadManager;
        return new \Nette\Application\UI\Multiplier(function ($id) use ($um) {
            return new \Cryptoparty\Admin\Form\UploadedFileForm(
                $um,
                $id
            );
        });
    }

    /**
     * @return \Cryptoparty\Admin\Form\NewUploadForm
     */
    protected function createComponentUploadForm2()
    {
        return new \Cryptoparty\Admin\Form\NewUploadForm($this->uploadManager);
    }

    /**
     * @return Form
     */
    protected function createComponentStartMulti()
    {
        $form = new \Nette\Application\UI\Form();
        $form->addText('count', 'Přidat')
            ->setType('integer')
            ->addRule(Form::INTEGER, '')
            ->addRule(Form::RANGE, '', array(2,10))
            ->setAttribute('class', 'input-mini')
            ->setDefaultValue(3);
        $form->addSubmit('submit', 'Přidat')
            ->setAttribute('class', 'btn btn-success btn-block');
        $form->onSuccess[] = function(Form $form) {
            $vals = $form->getValues();
            $this->multiUploadCount = $vals->count;
            if( $this->presenter->isAjax() ) {
                $this->invalidateControl('multi');
            } else {
                $this->presenter->redirect('this');
            }
        };
        return $form;
    }

    /**
     * @param int $n
     */
    protected function createComponentMultiUpload()
    {
        $form = new \Nette\Application\UI\Form();

        $renderer = $form->getRenderer();
        $renderer->wrappers['form']['container'] = 'table class="table table-condensed table-striped"';
        $renderer->wrappers['controls']['container'] = NUll;
        $renderer->wrappers['group']['container'] = 'tr';
        $renderer->wrappers['group']['label'] = null;
        $renderer->wrappers['pair']['container'] = NULL;
        $renderer->wrappers['label']['container'] = 'td';
        $renderer->wrappers['control']['container'] = 'td';

        $form->getElementPrototype()->addAttributes(array('class' => 'ajax'));

        for($i = 1; $i <= $this->multiUploadCount; $i++) {
            $form->addGroup($i);
            $form->addText("name$i", 'Name');
            $form->addText("description$i", 'Description');
            $form->addUpload("file$i", 'File');
        }
        $form->addGroup(0);
        $form->addSubmit('send', 'Nahrát všechny soubory')
            ->setAttribute('class', 'btn btn-primary')
            ->onClick[] = function(SubmitButton $form) {
                $this->multiUploadCount = null;
                $this->presenter->flashMessage("Zpracovávám");
                $this->presenter->redirect('this');
            };
        $form->addSubmit('hide', 'Skrýt')
            ->setAttribute('class', 'btn btn-inverse')
            ->onClick[] = function(SubmitButton $form) {
                    $this->multiUploadCount = null;
                    if($this->presenter->isAjax()) {
                        $this->invalidateControl('multi');
                    } else {
                        $this->presenter->redirect('this');
                    }
                };
        return $form;
    }
}