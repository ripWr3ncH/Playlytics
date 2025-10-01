<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Player;
use App\Models\Team;
use App\Models\League;

class PlayerSeeder extends Seeder
{
    public function run()
    {
        $premierLeague = League::where('slug', 'premier-league')->first();
        $laLiga = League::where('slug', 'la-liga')->first();
        $serieA = League::where('slug', 'serie-a')->first();

        if ($premierLeague) {
            $this->createPlayersForLeague($premierLeague);
        }

        if ($laLiga) {
            $this->createPlayersForLeague($laLiga);
        }

        if ($serieA) {
            $this->createPlayersForLeague($serieA);
        }
    }

    private function createPlayersForLeague($league)
    {
        $teams = Team::where('league_id', $league->id)->get();
        
        foreach ($teams as $team) {
            // Create 11 players per team (basic lineup)
            $this->createTeamPlayers($team);
        }
    }

    private function createTeamPlayers($team)
    {
        $positions = [
            ['position' => 'GK', 'count' => 1],
            ['position' => 'DEF', 'count' => 4],
            ['position' => 'MID', 'count' => 4],
            ['position' => 'FWD', 'count' => 2],
        ];

        $nationalities = ['England', 'Spain', 'France', 'Germany', 'Italy', 'Brazil', 'Argentina', 'Portugal', 'Netherlands', 'Belgium'];
        $playerNumber = 1;

        foreach ($positions as $positionData) {
            for ($i = 0; $i < $positionData['count']; $i++) {
                $playerName = $this->generatePlayerName($team->name, $positionData['position'], $i);
                
                Player::create([
                    'name' => $playerName,
                    'slug' => str($playerName . '-' . $team->short_name . '-' . $playerNumber)->slug(),
                    'team_id' => $team->id,
                    'position' => $positionData['position'],
                    'nationality' => $nationalities[array_rand($nationalities)],
                    'date_of_birth' => $this->generateDateOfBirth(),
                    'jersey_number' => $playerNumber,
                    'height' => rand(165, 200) / 100, // 1.65m to 2.00m
                    'weight' => rand(60, 95), // 60kg to 95kg
                    'is_active' => true
                ]);
                
                $playerNumber++;
            }
        }
    }

    private function generatePlayerName($teamName, $position, $index)
    {
        $firstNames = ['James', 'John', 'Robert', 'Michael', 'William', 'David', 'Richard', 'Joseph', 'Thomas', 'Charles',
                       'Marco', 'Alessandro', 'Luca', 'Francesco', 'Lorenzo', 'Andrea', 'Matteo', 'Gabriele', 'Davide',
                       'Carlos', 'Luis', 'Diego', 'Fernando', 'Antonio', 'Manuel', 'Pablo', 'Miguel', 'Angel', 'Jorge',
                       'Pierre', 'Antoine', 'Nicolas', 'Alexandre', 'Olivier', 'Julien', 'Sebastien', 'Maxime', 'Florian'];
        
        $lastNames = ['Smith', 'Johnson', 'Williams', 'Brown', 'Jones', 'Garcia', 'Miller', 'Davis', 'Rodriguez', 'Martinez',
                      'Rossi', 'Ferrari', 'Esposito', 'Bianchi', 'Romano', 'Colombo', 'Ricci', 'Marino', 'Greco',
                      'Garcia', 'Martinez', 'Lopez', 'Gonzalez', 'Rodriguez', 'Sanchez', 'Perez', 'Martin', 'Gomez',
                      'Dupont', 'Martin', 'Bernard', 'Dubois', 'Thomas', 'Robert', 'Petit', 'Durand', 'Leroy'];

        $firstName = $firstNames[array_rand($firstNames)];
        $lastName = $lastNames[array_rand($lastNames)];
        
        return $firstName . ' ' . $lastName;
    }

    private function generateDateOfBirth()
    {
        // Generate age between 18 and 35
        $age = rand(18, 35);
        $year = date('Y') - $age;
        $month = rand(1, 12);
        $day = rand(1, 28); // Safe day for all months
        
        return sprintf('%04d-%02d-%02d', $year, $month, $day);
    }
}