<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Services\Importers\GuardianDataImporter;
use App\Services\Importers\NewsApiOrgImporter;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory()->create([
            'name' => 'mubasharkk',
            'email' => 'demo@demo.com',
            'password' => bcrypt('demo123')
        ]);

        $this->call([
            WorldSeeder::class
        ]);

        // Import sources / categories from API
        $this->command->call('import:news-sources', ['origin' => GuardianDataImporter::ORIGIN]);
        $this->command->call('import:news-sources', ['origin' => NewsApiOrgImporter::ORIGIN]);
        $this->command->call('import:news-sources', [
            'origin' => NewsApiOrgImporter::ORIGIN,
            '--lang' => 'de',
            '--country' => 'de'
        ]);

        // Create queued jobs to import items per source
        $this->command->call('import:news-items-from-sources', ['origin' => GuardianDataImporter::ORIGIN]);
        $this->command->call('import:news-items-from-sources', ['origin' => NewsApiOrgImporter::ORIGIN]);
    }
}
