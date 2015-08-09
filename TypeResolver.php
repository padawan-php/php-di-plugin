<?php

namespace Mkusher\PadawanDi;

use Parser\UseParser;
use Complete\Resolver\TypeResolveEvent;
use Entity\FQCN;
use PhpParser\Node\Arg;
use PhpParser\Node\Scalar\String_;

class TypeResolver
{
    public function __construct(
        UseParser $useParser
    ) {
        $this->useParser = $useParser;
    }

    public function handleParentTypeEvent(TypeResolveEvent $e)
    {
        $this->parentType = $e->getType();
    }

    public function handleTypeResolveEvent(TypeResolveEvent $e)
    {
        $parentType = $this->parentType;
        if ($parentType instanceof FQCN
            && $parentType->toString() === 'DI\\Container'
        ) {
            /** @var \Entity\Chain\MethodCall */
            $chain = $e->getChain();
            if ($chain->getType() === 'method' && count($chain->getArgs()) > 0) {
                $firstArg = array_pop($chain->getArgs())->value;
                if ($firstArg instanceof String_) {
                    $className = $firstArg->value;
                    $fqcn = $this->useParser->parseFQCN($className);
                    $e->setType($fqcn);
                }
            }
        }
    }

    /** @var UseParser */
    private $useParser;
    private $parentType;
}
