<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendSoket implements ShouldQueue
{
    use InteractsWithQueue;
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {

        logger('SendSoket');

        // $to = $eventto = $event->user->email;
        // $subject = 'esto va ser un soket';
        // $message = 'Hola ' . $event->user->name . ' Bienvenido a la aplicacion';



        // dispatch(function() use ($to, $subject, $message) {
        //     mail($to, $subject, $message);

        // });

    }
}
