<?php

namespace Bliep\Mail;

use Swift_Mailer;

class MailServiceProvider extends \Illuminate\Mail\MailServiceProvider {

    public function registerSwiftMailer()
    {
        if($this->app['config']->get('mail.driver') == 'bliep')
            $this->registerBliepMailer();
        else
            parent::registerSwiftMailer();
    }

    public function registerBliepMailer()
    {
        $bliep = $this->app['config']->get('services.bliep', []);

        $this->app['swift.mailer'] = $this->app->share(function ($app) use ($bliep) {
            return new Swift_Mailer(
                new BliepTransport($bliep['apiKey'], true)
            );
        });
    }

}