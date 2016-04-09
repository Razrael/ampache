<?php
namespace lib\ViewHelpers\Link;

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
class ActionViewHelper extends AbstractViewHelper
{

    /**
     * @var boolean
     */
    protected $escapeOutput = false;

    /**
     * @return void
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('page', 'string', 'relative page url', true, null);
        $this->registerArgument('action', 'string', 'action', false, null);
        $this->registerArgument('title', 'string', 'Title', false, null);
        $this->registerArgument('arguments', 'array', 'URL Arguments', false, array());
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

        $urlArguments = array_merge(['action' => $arguments['action']], $arguments['arguments']);

        $args = [];
        foreach ($urlArguments as $name => $urlArgument) {
            $args[] = sprintf('%s=%s', $name, urlencode($urlArgument));
        }

        return sprintf('<a href="%s?%s" title="%s">%s</a>', $arguments['page'], implode('&', $args),
            $arguments['title'], $value);
    }
}
