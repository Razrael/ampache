<?php
namespace lib\ViewHelpers\Format;

/*
 * This file belongs to the package "TYPO3 Fluid".
 * See LICENSE.txt that was shipped with this package.
 */

use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Example:
 *
 * <a:format.strftime format="%H:%M:%S"></a:format.strftime>
 *
 * {a:format.strftime(format: '%M:%S', value: '12345')}
 *
 * {time -> a:format.strftime(format: '%M:%S')}
 *
 * Class StrftimeViewHelper
 * @package lib\ViewHelpers\Format
 */
class StrftimeViewHelper extends AbstractViewHelper
{

    /**
     * @return void
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('format', 'string', 'The format for strftime', true, '');
        $this->registerArgument('value', 'string', 'Timestamp to format', false, null);
    }

    /**
     * Format the arguments with the given strftime format string.
     *
     * @return string The formatted value
     * @api
     */
    public function render()
    {
        return self::renderStatic($this->arguments, $this->buildRenderChildrenClosure(), $this->renderingContext);
    }

    /**
     * Applies strftime() on the specified value.
     *
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param \TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface $renderingContext
     * @return string
     */
    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ) {
        $value = $arguments['value'];
        if ($value === null) {
            $value = $renderChildrenClosure();
        }

        return strftime($arguments['format'], $value);
    }
}
