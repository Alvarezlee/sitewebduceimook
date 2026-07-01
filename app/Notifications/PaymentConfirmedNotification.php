<?php

namespace App\Notifications;

use App\Models\Library\LibrarySubscription;
use App\Models\Payment\Payment;
use App\Models\Quiz\QuizCandidate;
use App\Models\Shop\Order;
use App\Notifications\Channels\SmsChannel;
use App\Notifications\Channels\WhatsAppChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentConfirmedNotification extends Notification
{
    use Queueable;

    public function __construct(private readonly Payment $payment) {}

    /**
     * @return array<int, string>
     */
    public function via(mixed $notifiable): array
    {
        return ['mail', 'database', WhatsAppChannel::class, SmsChannel::class];
    }

    public function toMail(mixed $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('CEIMO — Paiement confirmé')
            ->greeting('Bonjour '.$notifiable->first_name.',')
            ->line('Votre paiement a bien été confirmé : '.$this->label().'.')
            ->line('Montant : '.number_format((float) $this->payment->amount, 0, ',', ' ').' '.$this->payment->currency)
            ->line('Merci de votre confiance.');
    }

    public function toWhatsApp(mixed $notifiable): string
    {
        return "CEIMO : votre paiement de {$this->payment->amount} {$this->payment->currency} pour {$this->label()} a été confirmé.";
    }

    public function toSms(mixed $notifiable): string
    {
        return "CEIMO: paiement confirme ({$this->label()}). Merci.";
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(mixed $notifiable): array
    {
        return [
            'payment_id' => $this->payment->id,
            'label' => $this->label(),
            'amount' => $this->payment->amount,
        ];
    }

    private function label(): string
    {
        return match (true) {
            $this->payment->payable instanceof LibrarySubscription => 'votre abonnement à la bibliothèque numérique',
            $this->payment->payable instanceof QuizCandidate => 'votre inscription au MOUNGO TIC QUIZZ',
            $this->payment->payable instanceof Order => 'votre commande '.$this->payment->payable->order_number,
            default => 'votre paiement',
        };
    }
}
