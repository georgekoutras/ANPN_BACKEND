<?php


namespace App\Mail;

use App\Models\Accounts;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotificationMail extends Mailable
{
    use SerializesModels;

    public $account;
    public $emailText;

    public function __construct(Accounts $account, $emailText)
    {
        $this->account = $account;
        $this->emailText = $emailText;
    }

    public function build()
    {
        $view = 'mails.notification';
        $subject = 'Αναπνέω - ΕΠΕΙΓΟΥΣΑ ΕΙΔΟΠΟΙΗΣΗ';
        $html = $this->emailText;

        return $this->view($view)->subject($subject)->with(['html' => $html]);
    }
}
