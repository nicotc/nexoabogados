<?php

namespace App\Listeners;

use App\Models\User;
use App\Models\Attempt;
use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use App\Events\SubscriptionCreated;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendMail implements ShouldQueue
{

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $id_Subscription = $event->subscription;
        $subscription = Subscription::find($id_Subscription);
        $attempts =  $subscription->attempts;
        $user = User::find($subscription->user_id);
        $count  = count($attempts) + 1  ;

        if($event->random == 1){
            $to = $user->email;
            $subject = 'email pago de subscripcion exitoso';
            $message = 'Hola ' . $user->name . ' Subscripcion exitosa';
            Subscription::where('id', $id_Subscription)->update(['status' => 1]);
            Attempt::create(['subscription_id' => $id_Subscription, 'attempts'=>  $count ,  'success' => 1]);
        }

        if($event->random == 0){
            Attempt::create(['subscription_id' => $id_Subscription, 'attempts'=>  $count ,  'success' => 0]);
            $to = $user->email;
               if(count($attempts) < 3){
                    $subject = 'email pago de subscripcion fallido';
                    $message = 'Hola ' . $user->name . ' Subscripcion fallida intento ' .  $count ;
                }else{
                    $subscription->delete();
                    $subject = 'su subscripcion ha sido cancelada';
                    $message = 'Hola ' . $user->name . ' Subscripcion fallida';
               }
        }
        logger($event->directo);

        if($event->directo != "no"){

            Mail::to($to)->send(new \App\Mail\notification($subject, $message ));
            logger('email enviado directo');
        }else{
            dispatch(function() use ($to, $subject, $message, $id_Subscription, $event, $count) {
                if($count == 1){
                    sleep(30*60); // 30 minutos
                    //sleep(1); // 30 minutos
                }elseif($count == 2){
                     sleep(1*60*24); // 1 dia
                    //sleep(5); // 1 dia
                }
                Mail::to($to)->send(new \App\Mail\notification($subject, $message ));


                if($event->random == 0){
                    $random = rand(0,1);
                        event(new SubscriptionCreated($id_Subscription, $random));
                }

        });
        }




    }



}
