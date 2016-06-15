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

class BufferedExtension extends \Twig_Extension
{
    public function getName()
    {
        return 'buffered';
    }

    public function getTokenParsers()
    {
        return [
            new BufferedTokenParser(),
        ];
    }

    public function getGlobals()
    {
        return [
            'buffered_node' => [],
        ];
    }

    public function getFunctions()
    {
        return [
            new \Twig_Function('buffered', [$this, 'renderBuffered'], [
                'needs_context' => true,
                'needs_environment' => true,
                'is_safe' => ['*' => true],
            ])
        ];
    }

    public function renderBuffered(\Twig_Environment $twig, $context, $name, $exceptionOnFail = false)
    {
        if (!array_key_exists($name, $context['buffered_node'])) {
            if ($twig->isDebug() && $exceptionOnFail) {
                throw new \Twig_Error(sprintf('Missing buffer named %s', $name));
            }

            return '';
        }

        return $context['buffered_node'][$name];
    }
}