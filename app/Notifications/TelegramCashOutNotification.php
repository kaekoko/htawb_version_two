<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class TelegramCashOutNotification extends Notification
{
    use Queueable;
    protected $cash_out;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($cash_out)
    {
        $this->cash_out = $cash_out;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['telegram'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toTelegram($notifiable)
    {

        $name = $this->cash_out['account_name'];
        $amount = $this->cash_out['amount'];
        $phone = $this->cash_out['user_phone'];

        $url = "https://admin.myvipmm.com/super_admin/cash-out";
        $exchange_rate = $this->cash_out["payment_method"]["exchange_rate"];
        if (!empty($exchange_rate)) {
            $amount = round($amount / $exchange_rate, 2) . "Baht ( " . $amount . "Ks )";
        }
        return TelegramMessage::create()
            ->to('-1001702812664')
            ->content("*New CashOut MYVIPMM*\nPhone=$phone\nName=$name\nAmount=$amount\n")
            ->button('View Cash Out', $url);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
