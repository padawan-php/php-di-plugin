<?php

namespace Mkusher\PadawanDi;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Complete\Resolver\NodeTypeResolver;
use Complete\Completer\CompleterFactory;
use Parser\UseParser;

class Plugin
{
    public function __construct(
        EventDispatcher $dispatcher,
        TypeResolver $resolver,
        Completer $completer
    ) {
        $this->dispatcher = $dispatcher;
        $this->resolver = $resolver;
        $this->completer = $completer;
    }

    public function init()
    {
        $this->dispatcher->addListener(
            NodeTypeResolver::BLOCK_START,
            [$this->resolver, 'handleParentTypeEvent']
        );
        $this->dispatcher->addListener(
            NodeTypeResolver::BLOCK_END,
            [$this->resolver, 'handleTypeResolveEvent']
        );
        $this->dispatcher->addListener(
            CompleterFactory::CUSTOM_COMPLETER,
            [$this, 'handleCompleteEvent']
        );
    }

    public function handleCompleteEvent($e)
    {
        $context = $e->context;
        if ($context->isMethodCall()) {
            $e->completer = $this->completer;
        }
    }

    /** @var Completer */
    private $completer;
    /** @var TypeResolver */
    private $resolver;
    /** @var EventDispatcher */
    private $dispatcher;
}
