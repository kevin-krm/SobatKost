<?php
interface ReminderNotificationAdapter
{
    public function sendReminder(Tagihan $tagihan);
}

class SessionReminderNotificationAdapter implements ReminderNotificationAdapter
{
    public function sendReminder(Tagihan $tagihan)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $id_pengguna = $tagihan->getIdPengguna();
        if (!$id_pengguna) {
            return false;
        }

        $payload = $this->adaptTagihanToNotification($tagihan);
        $_SESSION['reminder_notifications'][$id_pengguna][$tagihan->getIdTagihan()] = $payload;

        return true;
    }

    private function adaptTagihanToNotification(Tagihan $tagihan)
    {
        return [
            'id_tagihan' => $tagihan->getIdTagihan(),
            'judul' => 'Reminder Jatuh Tempo Tagihan',
            'pesan' => sprintf(
                'Tagihan %s kamar %s jatuh tempo pada %s. Total: Rp %s.',
                $tagihan->getIdTagihan(),
                $tagihan->getNomorKamar() ?? '-',
                date('d/m/Y', strtotime($tagihan->getTanggalJatuhTempo())),
                number_format($tagihan->getTotalTagihan(), 0, ',', '.')
            ),
            'tanggal_jatuh_tempo' => $tagihan->getTanggalJatuhTempo(),
            'total_tagihan' => $tagihan->getTotalTagihan(),
            'created_at' => date('Y-m-d H:i:s')
        ];
    }
}

class LogReminderNotificationAdapter implements ReminderNotificationAdapter
{
    public function sendReminder(Tagihan $tagihan)
    {
        $message = sprintf(
            'REMINDER TAGIHAN: pengguna=%s tagihan=%s jatuh_tempo=%s total=%s',
            $tagihan->getIdPengguna() ?? '-',
            $tagihan->getIdTagihan(),
            $tagihan->getTanggalJatuhTempo(),
            $tagihan->getTotalTagihan()
        );

        error_log($message);
        return true;
    }
}
?>
