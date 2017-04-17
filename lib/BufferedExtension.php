<?php

/*
 * This file is part of wizad/twig-buffered-extension.
 *
 * (c) 2016 William
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Wizad\TwigBufferedExtension;

class BufferedExtension extends \Twig_Extension implements \Twig_Extension_GlobalsInterface
{
    public function getName()
    {
        return 'buffered';
    }

    public function getGlobals()
    {
        return [
            'buffered_node' => new BufferedStorage(),
        ];
    }


    public function getTokenParsers()
    {
        return [
            new BufferedTokenParser(),
        ];
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('buffered', [$this, 'renderBuffered'], [
                'needs_context' => true,
                'needs_environment' => true,
                'is_safe' => ['*' => true],
            ])
        ];
    }

    public function renderBuffered(\Twig_Environment $twig, $context, $name, $exceptionOnFail = false)
    {
        if (!array_key_exists('buffered_node', $context)) {
            if ($twig->isDebug() && $exceptionOnFail) {
                throw new \Twig_Error(sprintf('Missing buffer named %s', $name));
            }

            return '';
        }

        return $context['buffered_node']->getBufferContent($name, $twig->isDebug() && $exceptionOnFail);
    }
}
