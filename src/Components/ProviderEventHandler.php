<?php

namespace App\Components;

use App\Models\Provider;
use App\Models\User;
use Yii;
use yii\base\Event;

class ProviderEventHandler
{
    public static function onProviderCreated(Event $event)
    {
        /** @var Provider $provider */
        $provider = $event->sender;

        $users = User::find()
            ->where(['accept_email' => 1, 'isDeleted' => 0])
            ->all();

        foreach ($users as $user) {
            Yii::$app->mailer->compose()
                ->setTo($user->email)
                ->setFrom(['nao-responda@simplifysoftwares.com.br' => 'Prestadores Manacás'])
                ->setSubject('Novo prestador cadastrado: ' . $provider->name)
                ->setHtmlBody("
                    <p>Olá, {$user->name}!</p>
                    <p>Um novo prestador foi cadastrado no sistema:</p>
                    <p><strong>{$provider->name}</strong></p>
                    <p><strong>Tipo de serviço: </strong>{$provider->serviceType->name}</p>
                    <p><strong>Descrição:</strong> {$provider->service_description}</p>
                    <p>Veja mais em: <a href='" . Yii::$app->urlManager->createAbsoluteUrl(['provider/view', 'id' => $provider->id]) . "'>Clique aqui</a></p>
                ")
                ->send();
        }
    }
}