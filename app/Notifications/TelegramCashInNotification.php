<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class TelegramCashInNotification extends Notification
{
    use Queueable;
    protected $cash_in;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($cash_in)
    {
        $this->cash_in = $cash_in;
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
        $transactionid = $this->cash_in['transaction_id'];
        $name = $this->cash_in['account_name'];
        $amount = $this->cash_in['amount'];
        $phone = $this->cash_in['userphone'];
        $holderphone = $this->cash_in['holderphone'];
        $oldamount = $this->cash_in['old_amount'];
        $url = "https://admin.htawb2d3d.com/super_admin/cash-in";
        $exchange_rate = $this->cash_in["payment_method"]["exchange_rate"];
        if (!empty($exchange_rate)) {
            $amount = $amount * $exchange_rate . "Ks ( " . $amount . "Baht )";
        }
        return TelegramMessage::create()
            ->to('-1001702812664')
            ->content("*New Cashin htawb2d3d*\nTansactionID=$transactionid\nName=$name\nAmount=$amount\n")
            ->button('View Cash In', $url);
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
