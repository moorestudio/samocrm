<?php

namespace App\Listeners;

use App\Events\UserReferred;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class RewardUser
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Handle the event.
     *
     * @param  UserReferred  $event
     * @return void
     */
    public function handle(UserReferred $event)
    {
        $referral_id =  $event->referralId;
        $referral = \App\ReferralLink::find($referral_id);
        if (!is_null($referral)) {
            \App\ReferralRelationship::create(['referral_link_id' => $referral->id, 'user_id' => $event->user->id]);
            $seller = $referral->user;
            $new_user = $event->user;
            $new_user->seller_id = $seller->id;
            ///
            $new_user->franchise_id = $seller->id;
//            $new_user->seller = $seller;
            $new_user->setRelation('seller', $seller);/////Это скорее лишнее
//            dd($new_user);
            $new_user->save();
            // if ($referral->program->name === 'event') {
            //     // User who was sharing link
            //     $provider = $referral->user;
            //     $provider->addCredits(15);
            //     // User who used the link
            //     $user = $event->user;
            //     $user->addCredits(20);
            // }

        }
    }
}
