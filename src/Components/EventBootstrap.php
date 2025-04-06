<?php

namespace App\Components;

use App\Models\Provider;
use yii\base\BootstrapInterface;
use yii\base\Event;

class EventBootstrap implements BootstrapInterface
{

    /**
     * @inheritDoc
     */
    public function bootstrap($app)
    {
        Event::on(Provider::class, Provider::EVENT_AFTER_PROVIDER_CREATED, [ProviderEventHandler::class, 'onProviderCreated']);
    }
}