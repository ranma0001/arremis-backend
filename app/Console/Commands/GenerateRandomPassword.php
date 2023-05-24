<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GenerateRandomPassword extends Command
{
    protected $signature = 'password:generate';

    protected $description = 'Generate a random unique password.';

    public function handle()
    {
        $password = $this->generatePassword(10);
        $this->info('Generated Password: ' . $password);
    }

    private function generatePassword($length = 10)
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+-=[]{}|;:,.<>?';
        $password = '';

        do {
            $password = Str::random($length);
        } while (!$this->isPasswordUnique($password));

        return $password;
    }

    private function isPasswordUnique($password)
    {
        // Modify the logic here to check if the generated password is unique in your specific context.
        // For example, you could check if the password already exists in your user database table.

        // Sample logic (replace with your own implementation):
        // $hashedPassword = Hash::make($password);
        // $existingUser = User::where('password', $hashedPassword)->first();

        // if ($existingUser) {
        //     return false;
        // }

        // return true;

        // For the sake of this example, we assume the password is always unique.
        return true;
    }
}
