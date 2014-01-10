<?php namespace Cryptoparty\Form;
/**
 * @author David Grudl {@link https://github.com/nette/examples/blob/master/Forms/bootstrap3-rendering.php}
 * @author Ondřej Profant, 2013
 * @package Cryptoparty
 */

use	Nette\Application\UI\Form,
	Nette\Forms\Controls;


class BootstrapRenderer
{
	public static function set(Form $form)
	{
		// setup form rendering
		$renderer = $form->getRenderer();
		$renderer->wrappers['controls']['container'] = NULL;
		$renderer->wrappers['pair']['container'] = 'div class=form-group';
		$renderer->wrappers['pair']['.error'] = 'has-error';
		$renderer->wrappers['control']['container'] = 'div class=col-sm-9';
		$renderer->wrappers['label']['container'] = 'div class="col-sm-3 control-label"';
		$renderer->wrappers['control']['description'] = 'span class=help-block';
		$renderer->wrappers['control']['errorcontainer'] = 'span class=help-block';

		// make form and controls compatible with Twitter Bootstrap
		$form->getElementPrototype()->class('form-horizontal');

		foreach ($form->getControls() as $control) {
			if ($control instanceof Controls\Button) {
				$control->setAttribute('class', empty($usedPrimary) ? 'btn btn-primary' : 'btn btn-default');
				$usedPrimary = TRUE;

			} elseif ($control instanceof Controls\TextBase || $control instanceof Controls\SelectBox || $control instanceof Controls\MultiSelectBox) {
				$control->setAttribute('class', 'form-control');

			} elseif ($control instanceof Controls\Checkbox || $control instanceof Controls\CheckboxList || $control instanceof Controls\RadioList) {
				$control->getSeparatorPrototype()->setName('div')->class($control->getControlPrototype()->type);
			}
		}
	}
}