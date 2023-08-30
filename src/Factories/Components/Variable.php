<?php

namespace TakeBlip\Factories\Components;

class Variable
{
    /**
     * @var string
     */
    private string $variable;

    /**
     * @param string $variable
     */
    public function __construct(string $variable)
    {
        $this->variable = $variable;
    }

    /**
     * @return array
     */
    public function export(): array
    {
        return [
            'type' => 'text',
            'text' => $this->variable
        ];
    }
}
