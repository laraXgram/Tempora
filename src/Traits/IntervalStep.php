<?php

declare(strict_types=1);


namespace LaraGram\Tempora\Traits;

use LaraGram\Tempora\Callback;
use LaraGram\Tempora\Tempora;
use LaraGram\Tempora\TemporaImmutable;
use LaraGram\Tempora\TemporaInterface;
use Closure;
use DateTimeImmutable;
use DateTimeInterface;

trait IntervalStep
{
    /**
     * Step to apply instead of a fixed interval to get the new date.
     *
     * @var Closure|null
     */
    protected $step;

    /**
     * Get the dynamic step in use.
     *
     * @return Closure
     */
    public function getStep(): ?Closure
    {
        return $this->step;
    }

    /**
     * Set a step to apply instead of a fixed interval to get the new date.
     *
     * Or pass null to switch to fixed interval.
     *
     * @param Closure|null $step
     */
    public function setStep(?Closure $step): void
    {
        $this->step = $step;
    }

    /**
     * Take a date and apply either the step if set, or the current interval else.
     *
     * The interval/step is applied negatively (typically subtraction instead of addition) if $negated is true.
     *
     * @param DateTimeInterface $dateTime
     * @param bool              $negated
     *
     * @return TemporaInterface
     */
    public function convertDate(DateTimeInterface $dateTime, bool $negated = false): TemporaInterface
    {
        /** @var TemporaInterface $temporaDate */
        $temporaDate = $dateTime instanceof TemporaInterface ? $dateTime : $this->resolveTempora($dateTime);

        if ($this->step) {
            $temporaDate = Callback::parameter($this->step, $temporaDate->avoidMutation());

            return $temporaDate->modify(($this->step)($temporaDate, $negated)->format('Y-m-d H:i:s.u e O'));
        }

        if ($negated) {
            return $temporaDate->rawSub($this);
        }

        return $temporaDate->rawAdd($this);
    }

    /**
     * Convert DateTimeImmutable instance to TemporaImmutable instance and DateTime instance to Tempora instance.
     */
    private function resolveTempora(DateTimeInterface $dateTime): Tempora|TemporaImmutable
    {
        if ($dateTime instanceof DateTimeImmutable) {
            return TemporaImmutable::instance($dateTime);
        }

        return Tempora::instance($dateTime);
    }
}
