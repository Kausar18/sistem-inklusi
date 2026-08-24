<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

/**
 * Buat akun admin untuk masuk ke panel pengelolaan data.
 *
 * Contoh pemakaian:
 *   php artisan admin:buat
 */
class BuatAdminCommand extends Command
{
    protected $signature = 'admin:buat';

    protected $description = 'Buat satu akun admin untuk masuk ke panel pengelolaan data';

    public function handle(): int
    {
        $nama = $this->ask('Nama');

        $email = $this->ask('Email');
        $validasiEmail = Validator::make(['email' => $email], [
            'email' => ['required', 'email', 'unique:users,email'],
        ]);

        if ($validasiEmail->fails()) {
            $this->error($validasiEmail->errors()->first());

            return self::FAILURE;
        }

        $password = $this->secret('Kata sandi (minimal 8 karakter)');
        $konfirmasi = $this->secret('Ulangi kata sandi');

        if ($password !== $konfirmasi) {
            $this->error('Kata sandi tidak sama.');

            return self::FAILURE;
        }

        $validasiPassword = Validator::make(['password' => $password], [
            'password' => ['required', 'string', 'min:8'],
        ]);

        if ($validasiPassword->fails()) {
            $this->error($validasiPassword->errors()->first());

            return self::FAILURE;
        }

        User::create([
            'name'     => $nama,
            'email'    => $email,
            'password' => $password,
        ]);

        $this->info("Akun admin \"{$email}\" berhasil dibuat.");

        return self::SUCCESS;
    }
}
