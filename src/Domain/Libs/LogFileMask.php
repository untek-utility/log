<?php

namespace Untek\Utility\Log\Domain\Libs;

use DateTime;
use Untek\Core\Text\Helpers\TemplateHelper;
use Untek\Core\Text\Libs\TemplateRender;

class LogFileMask extends TemplateRender
{

    protected string $beginBlock = '{{';
    protected string $endBlock = '}}';
    
    public function __construct(private string $mask)
    {
    }

    public function render(): string
    {
        return TemplateHelper::render($this->mask, $this->replacement, $this->beginBlock, $this->endBlock);
    }
    
    public function addReplacementFromTime(DateTime $time = null): void
    {
        $time = $time ?: new DateTime();
        $replacement = $this->generateReplacement($time);
        foreach ($replacement as $placeholder => $value) {
            $this->addReplacement($placeholder, $value);
        }
    }

    private function generateReplacement(DateTime $time): array
    {
        return [
            'year' => $time->format('Y'),
            'month' => $time->format('m'),
            'day' => $time->format('d'),
            'hour' => $time->format('H'),
            'minute' => $time->format('i'),
            'second' => $time->format('s'),
        ];
    }
}
