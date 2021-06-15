<?php

namespace App\Actions\Fortify;

use Stripe\Stripe;
use App\Models\User;
use Laravel\Jetstream\Jetstream;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    public function __construct() {
        Stripe::setApiKey(env('STRIPE_SECRET'));
    }
    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function create(array $input)
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['required', 'accepted'] : '',
            'countrySelected' => ['required'],
            'phone'   => ['required']
        ])->validate();

        // dd(request()->all());

        if ($input['payment_method_id']) {
            $user = User::create([
                'name' => $input['name'],
                'email' => $input['email'],
                'amount' => $input['amount'],
                'phone' => $input['phone'],
                'country' => $input['countrySelected'],
                'payment_method_id' => $input['payment_method_id'],
                'password' => Hash::make($input['password']),
            ]);
            $user->createOrGetStripeCustomer();
            $payment = $user->charge(
                $input['amount'] ,
                $input['payment_method_id']
            );
            $payment = $payment->asStripePaymentIntent();
            return $user;
        }else{
            echo "Please check your card information!";
        }



    }
}
