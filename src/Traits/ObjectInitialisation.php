<?php

declare(strict_types=1);


namespace LaraGram\Tempora\Traits;

trait ObjectInitialisation
{
    /**
     * True when parent::__construct has been called.
     *
     * @var string
     */
    protected $constructedObjectId;
}
