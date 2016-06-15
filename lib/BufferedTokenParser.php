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

use Twig_Token;
use Twig_TokenParser;

class BufferedTokenParser extends \Twig_TokenParser
{
    public function parse(\Twig_Token $token)
    {
        $lineno = $token->getLine();
        $parser = $this->parser;
        $stream = $parser->getStream();

        $name = $stream->expect(Twig_Token::NAME_TYPE)->getValue();
        $stream->expect(Twig_Token::BLOCK_END_TYPE);

        $body = $this->parser->subparse(array($this, 'decideBlockEnd'), true);

        $stream->expect(\Twig_Token::BLOCK_END_TYPE);

        return new BufferedNode($name, $body, $lineno, $this->getTag());
    }

    public function getTag()
    {
        return 'buffered';
    }

    public function decideBlockEnd(Twig_Token $token)
    {
        return $token->test('endbuffered');
    }
}