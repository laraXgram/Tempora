<?php

declare(strict_types=1);


namespace LaraGram\Tempora\Traits;

use LaraGram\Tempora\Factory;
use LaraGram\Tempora\FactoryImmutable;
use LaraGram\Tempora\WrapperClock;
use Closure;

/**
 * Remember the factory that was the current at the creation of the object.
 */
trait LocalFactory
{
    /**
     * The clock that generated the current instance (or FactoryImmutable::getDefaultInstance() if none)
     */
    private ?WrapperClock $clock = null;

    public function getClock(): ?WrapperClock
    {
        return $this->clock;
    }

    private function initLocalFactory(): void
    {
        $this->clock = FactoryImmutable::getCurrentClock();
    }

    /**
     * Trigger the given action using the local factory of the object, so it will be transmitted
     * to any object also using this trait and calling initLocalFactory() in its constructor.
     *
     * @template T
     *
     * @param Closure(): T $action
     *
     * @return T
     */
    private function transmitFactory(Closure $action): mixed
    {
        $previousClock = FactoryImmutable::getCurrentClock();
        FactoryImmutable::setCurrentClock($this->clock);

        try {
            return $action();
        } finally {
            FactoryImmutable::setCurrentClock($previousClock);
        }
    }

    private function getFactory(): Factory
    {
        return $this->getClock()?->getFactory() ?? FactoryImmutable::getDefaultInstance();
    }
}
