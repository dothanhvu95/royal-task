<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Utils\EndPoint;

class AddAuthorCommand extends Command
{
    protected $signature = 'app:add-author 
                          {--first-name= : First name of the author}
                          {--last-name= : Last name of the author}
                          {--birthday= : Birthday of the author (YYYY-MM-DD)}
                          {--gender= : Gender of the author}
                          {--place-of-birth= : Place of birth of the author}';

    protected $description = 'Add a new author via API';

    public function handle()
    {
        // Collect all required information if not provided
        $firstName = $this->option('first-name') ?: $this->ask('What is the author\'s first name?');
        $lastName = $this->option('last-name') ?: $this->ask('What is the author\'s last name?');
        $birthday = $this->option('birthday') ?: $this->ask('What is the author\'s birthday? (YYYY-MM-DD)');
        $gender = $this->option('gender') ?: $this->choice('What is the author\'s gender?', ['male', 'female', 'other']);
        $placeOfBirth = $this->option('place-of-birth') ?: $this->ask('What is the author\'s place of birth?');

        // Validate birthday format
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $birthday)) {
            $this->error('Birthday must be in YYYY-MM-DD format');
            return 1;
        }

        // Show confirmation
        $this->info('Please confirm the author details:');
        $this->table(
            ['Field', 'Value'],
            [
                ['First Name', $firstName],
                ['Last Name', $lastName],
                ['Birthday', $birthday],
                ['Gender', $gender],
                ['Place of Birth', $placeOfBirth],
            ]
        );

        if (!$this->confirm('Do you want to proceed?', true)) {
            $this->info('Operation cancelled');
            return 0;
        }

        // Get token from .env or ask user
        $token = env('API_TOKEN') ?: $this->secret('Please enter your API token:');

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->post(EndPoint::URL . '/api/v2/authors', [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'birthday' => $birthday,
                'gender' => $gender,
                'place_of_birth' => $placeOfBirth
            ]);

            if ($response->successful()) {
                $author = $response->json();
                $this->info('Author created successfully!');
                $this->table(
                    ['ID', 'Name', 'Birthday'],
                    [[
                        $author['id'],
                        $author['first_name'] . ' ' . $author['last_name'],
                        $author['birthday']
                    ]]
                );
                return 0;
            }

            $this->error('Failed to create author: ' . $response->body());
            return 1;

        } catch (\Exception $e) {
            $this->error('An error occurred: ' . $e->getMessage());
            return 1;
        }
    }
}