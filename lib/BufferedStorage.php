<?php

namespace Wizad\TwigBufferedExtension;


class BufferedStorage
{
    private $buffers;

    public function __construct()
    {
        $this->buffers = [];
    }

    public function addToBuffer($buffer, $data)
    {
        if (!array_key_exists($buffer, $this->buffers)) {
            $this->buffers[$buffer] = [];
        }

        $this->buffers[$buffer][] = $data;
    }

    public function getBufferContent($buffer, $exceptionOnFail = false)
    {
        if (!array_key_exists($buffer, $this->buffers)) {
            if ($exceptionOnFail) {
                throw new \Twig_Error(sprintf('Missing buffer named %s', $buffer));
            }

            return '';
        }

        return implode(PHP_EOL, $this->buffers[$buffer]);
    }
}