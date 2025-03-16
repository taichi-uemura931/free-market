<?php

namespace App\Services;

use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;

class StripeService
{
    public function createCheckoutSession(array $params)
    {
        Stripe::setApiKey(config('services.stripe.secret'));
        return StripeSession::create($params);
    }
}
