<?php

declare(strict_types=1);

namespace LaraGram\Tempora\LaraGram;

use LaraGram\Tempora\Tempora;
use LaraGram\Tempora\TemporaImmutable;
use LaraGram\Tempora\TemporaInterval;
use LaraGram\Tempora\TemporaPeriod;
use LaraGram\Contracts\Events\Dispatcher as DispatcherContract;
use LaraGram\Events\Dispatcher;
use LaraGram\Events\EventDispatcher;
use LaraGram\Support\Tempora as LaraGramTempora;
use LaraGram\Support\Facades\Date;
use Throwable;

class ServiceProvider extends \LaraGram\Support\ServiceProvider
{
    /** @var callable|null */
    protected $appGetter = null;

    /** @var callable|null */
    protected $localeGetter = null;

    /** @var callable|null */
    protected $fallbackLocaleGetter = null;

    public function setAppGetter(?callable $appGetter): void
    {
        $this->appGetter = $appGetter;
    }

    public function setLocaleGetter(?callable $localeGetter): void
    {
        $this->localeGetter = $localeGetter;
    }

    public function setFallbackLocaleGetter(?callable $fallbackLocaleGetter): void
    {
        $this->fallbackLocaleGetter = $fallbackLocaleGetter;
    }

    public function boot()
    {
        $this->updateLocale();
        $this->updateFallbackLocale();

        if (!$this->app->bound('events')) {
            return;
        }

        $service = $this;
        $events = $this->app['events'];

        if ($this->isEventDispatcher($events)) {
            $events->listen(class_exists('LaraGram\Foundation\Events\LocaleUpdated') ? 'LaraGram\Foundation\Events\LocaleUpdated' : 'locale.changed', function () use ($service) {
                $service->updateLocale();
            });
        }
    }

    public function updateLocale()
    {
        $locale = $this->getLocale();

        if ($locale === null) {
            return;
        }

        Tempora::setLocale($locale);
        TemporaImmutable::setLocale($locale);
        TemporaPeriod::setLocale($locale);
        TemporaInterval::setLocale($locale);

        if (class_exists(LaraGramTempora::class)) {
            LaraGramTempora::setLocale($locale);
        }

        if (class_exists(Date::class)) {
            try {
                $root = Date::getFacadeRoot();
                $root->setLocale($locale);
            } catch (Throwable) {
                // Non Tempora class in use in Date facade
            }
        }
    }

    public function updateFallbackLocale()
    {
        $locale = $this->getFallbackLocale();

        if ($locale === null) {
            return;
        }

        Tempora::setFallbackLocale($locale);
        TemporaImmutable::setFallbackLocale($locale);
        TemporaPeriod::setFallbackLocale($locale);
        TemporaInterval::setFallbackLocale($locale);

        if (class_exists(LaraGramTempora::class) && method_exists(LaraGramTempora::class, 'setFallbackLocale')) {
            LaraGramTempora::setFallbackLocale($locale);
        }

        if (class_exists(Date::class)) {
            try {
                $root = Date::getFacadeRoot();
                $root->setFallbackLocale($locale);
            } catch (Throwable) { // @codeCoverageIgnore
                // Non Tempora class in use in Date facade
            }
        }
    }

    public function register()
    {
        // Needed for LaraGram
    }

    protected function getLocale()
    {
        if ($this->localeGetter) {
            return ($this->localeGetter)();
        }

        $app = $this->getApp();
        $app = $app && method_exists($app, 'getLocale')
            ? $app
            : $this->getGlobalApp('translator');

        return $app ? $app->getLocale() : null;
    }

    protected function getFallbackLocale()
    {
        if ($this->fallbackLocaleGetter) {
            return ($this->fallbackLocaleGetter)();
        }

        $app = $this->getApp();

        return $app && method_exists($app, 'getFallbackLocale')
            ? $app->getFallbackLocale()
            : $this->getGlobalApp('translator')?->getFallback();
    }

    protected function getApp()
    {
        if ($this->appGetter) {
            return ($this->appGetter)();
        }

        return $this->app ?? $this->getGlobalApp();
    }

    protected function getGlobalApp(...$args)
    {
        return \function_exists('app') ? \app(...$args) : null;
    }

    protected function isEventDispatcher($instance)
    {
        return $instance instanceof EventDispatcher
            || $instance instanceof Dispatcher
            || $instance instanceof DispatcherContract;
    }
}
