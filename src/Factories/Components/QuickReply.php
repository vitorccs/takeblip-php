<?php

namespace TakeBlip\Factories\Components;

class QuickReply
{
    /**
     * @var string
     */
    private string $label;

    /**
     * @var int
     */
    private int $index;

    /**
     * @param string $label
     * @param int $index
     */
    public function __construct(string $label, int $index)
    {
        $this->label = $label;
        $this->index = $index;
    }

    /**
     * @return array
     */
    public function export(): array
    {
        return [
            'type' => 'button',
            'sub_type' => 'quick_reply',
            'index' => $this->index,
            'parameters' => [
                [
                    'type' => 'payload',
                    'payload' => $this->label
                ]
            ]
        ];
    }
}
