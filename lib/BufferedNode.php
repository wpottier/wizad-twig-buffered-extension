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

use Twig_Node;

class BufferedNode extends Twig_Node
{
    public function __construct($name, Twig_Node $value, $line, $tag = null)
    {
        parent::__construct(array('value' => $value), array('name' => $name), $line, $tag);
    }

    public function compile(\Twig_Compiler $compiler)
    {
        $compiler
            ->addDebugInfo($this)
            ->write("if (!isset(\$context['buffered_node'])) { \$context['buffered_node'] = array(); }\n\n")
            ->write("if (!isset(\$context['buffered_node']['{$this->getAttribute('name')}'])) {\n")
            ->indent();

        $compiler
            ->write("\$context['buffered_node']['{$this->getAttribute('name')}'] = '';\n")
            ->outdent()
            ->write("}\n\n")
        ;

        $compiler
            ->write(sprintf("\$buffered_%s = function (\$context)\n", spl_object_hash($this)), "{\n")
            ->indent()
            ->write("ob_start();\n")
        ;

        $compiler
            ->subcompile($this->getNode('value'))
            ->write("return ob_get_clean();\n")
            ->outdent()
            ->write("};\n\n");

        $compiler
            ->write(
                "\$context['buffered_node']['{$this->getAttribute('name')}'] .=",
                sprintf("\$buffered_%s(\$context);\n", spl_object_hash($this))
            );
    }
}