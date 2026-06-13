<?php
require_once __DIR__ . '/Tagihan.php';
require_once __DIR__ . '/ReminderNotificationAdapter.php';

class TagihanReminderService
{
    private $adapters = [];

    public function __construct(array $adapters = [])
    {
        $this->adapters = empty($adapters)
            ? [new SessionReminderNotificationAdapter(), new LogReminderNotificationAdapter()]
            : $adapters;
    }

    public function kirimReminder(array $tagihanList)
    {
        $total_terkirim = 0;

        foreach ($tagihanList as $tagihan) {
            foreach ($this->adapters as $adapter) {
                if ($adapter->sendReminder($tagihan)) {
                    $total_terkirim++;
                    break;
                }
            }
        }

        return $total_terkirim;
    }
}
?>
