<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Notification;
use App\Models\DetailSubTask;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendLaporanKinerjaReminder extends Command
{
    protected $signature = 'laporan:reminder {--days=3 : Jumlah hari sebelum deadline} {--force : Force kirim reminder tanpa cek tanggal}';
    protected $description = 'Kirim reminder untuk submit laporan kinerja';

    public function handle()
    {
        $daysBefore = $this->option('days');
        $isForce = $this->option('force');

        $this->info("Memeriksa reminder {$daysBefore} hari sebelum deadline...");

        $now = Carbon::now();
        
        $startDate = $now->copy()->day(26)->subMonth();
        $endDate = $now->copy()->day(25);

        if ($now->day > 25) {
            $startDate = $now->copy()->day(26);
            $endDate = $now->copy()->day(25)->addMonth();
        }

        $deadline = $endDate;
        $reminderDate = $deadline->copy()->subDays($daysBefore);

        if (!$isForce && !$now->isSomeDay($reminderDate)) {
            $this->info("Hari ini ({$now->format('Y-m-d')}) bukan hari reminder untuk ($daysBefore) hari sebelum deadline ({$reminderDate->format('Y-m-d')})");
            return;
        }

        if ($isForce) {
            $this->warn("⚠️ FORSE MODE: Mengirim reminder tanpa cek tanggal!");
        }

        $this->info("Periode aktif: {$startDate->format('Y-m-d')} sampai {$endDate->format('Y-m-d')}");
        $this->info("Deadline: {$deadline->format('Y-m-d')}, Reminder hari ini untuk {$daysBefore} hari sebelumnya");

        $users = User::with('role')
            ->whereHas('role', function($q){
                $q->where('slug', ['karyawan', 'admin-sdm']);
            })
            ->where('is_active', 1)
            ->get();

        $remindersSent = 0;
        $usersChecked = 0;

        foreach ($users as $user) {
            $usersChecked++;
            
            $existingReports = DetailSubTask::whereHas('subtask', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->where('is_active', 1)
            ->count();

            if ($existingReports > 0) {
                $this->line("User {$user->name} sudah memiliki {$existingReports} laporan, skip reminder");
                continue;
            }
            
            $existingReminder = Notification::where('type', 'laporan_kinerja_reminder')
                ->where('notifiable_type', User::class)
                ->where('notifiable_id', $user->id)
                ->whereDate('created_at', $now->format('Y-m-d'))
                ->where('data->days_remaining', $daysBefore)
                ->exists();

            if ($existingReminder && !$isForce) {
                $this->line("Reminder hari ini sudah dikirim ke {$user->name}, skip");
                continue;
            }

            $userRoleSlug = $user->role->slug ?? '';
            $actionUrl = ($userRoleSlug === 'karyawan') ? route('karyawan.laporan_kinerja') : route('admin_sdm.laporan_kinerja');
            
            Notification::create([
                'type' => 'laporan_kinerja_reminder',
                'notifiable_type' => User::class,
                'notifiable_id' => $user->id,
                'data' => [
                    'message' => "⏰ Reminder: Segera submit laporan kinerja periode {$startDate->translatedFormat('d M Y')} - {$endDate->translatedFormat('d M Y')}",
                    'periode_start' => $startDate->format('Y-m-d'),
                    'periode_end' => $endDate->format('Y-m-d'),
                    'deadline' => $deadline->format('Y-m-d'),
                    'days_remaining' => $daysBefore,
                    'action_url' => $actionUrl
                ]
            ]);

            $remindersSent++;

            $roleName = $user->role->name ?? 'No Role';
            $this->line("✅ Reminder dikirim ke: {$user->name} ({$roleName})");
        }

        $this->info("Ringkasan: {$usersChecked} user diperiksa, {$remindersSent} reminder dikirim");
        
        Log::info("Laporan Reminder: {$remindersSent} reminder dikirim untuk {$daysBefore} hari sebelum deadline", [
            'tanggal' => $now->format('Y-m-d'),
            'reminders_sent' => $remindersSent,
            'users_checked' => $usersChecked,
            'periode_start' => $startDate->format('Y-m-d'),
            'periode_end' => $endDate->format('Y-m-d'),
            'deadline' => $deadline->format('Y-m-d')
        ]);
    }
}