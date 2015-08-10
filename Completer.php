<?php

namespace Mkusher\PadawanDi;

use Complete\Completer\CompleterInterface;
use Complete\Completer\ClassNameCompleter;
use Entity\Completion\Entry;
use Entity\Project;
use Entity\Completion\Context;
use Entity\FQCN;

class Completer implements CompleterInterface
{
    public function __construct(ClassNameCompleter $completer)
    {
        $this->classNameCompleter = $completer;
    }
    public function getEntries(Project $project, Context $context)
    {
        return array_map(
            [$this, 'wrapEntry'],
            $this->classNameCompleter->getEntries($project, $context)
        );
    }

    public function wrapEntry($entry)
    {
        return new Entry(
            sprintf('"%s"', $entry->getName()),
            $entry->getSignature(),
            $entry->getDesc(),
            $entry->getMenu()
        );
    }

    private $classNameCompleter;
}
