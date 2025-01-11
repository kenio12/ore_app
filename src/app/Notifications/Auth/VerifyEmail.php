<?php

namespace App\Notifications\Auth;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\VerifyEmail as VerifyEmailBase;

class VerifyEmail extends VerifyEmailBase
{
    protected function buildMailMessage($url)
    {
        return (new MailMessage)
            ->subject('メールアドレス認証のお知らせ')
            ->line('メールアドレスを認証するために、以下のボタンをクリックしてください。')
            ->action('メールアドレスを認証', $url)
            ->line('このアカウントを作成していない場合は、このメールを無視してください。');
    }
}
