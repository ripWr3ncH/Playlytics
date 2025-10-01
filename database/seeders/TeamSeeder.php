<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Team;
use App\Models\League;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $premierLeague = League::where('slug', 'premier-league')->first();
        $laLiga = League::where('slug', 'la-liga')->first();
        $serieA = League::where('slug', 'serie-a')->first();

        if ($premierLeague) {
            $plTeams = [
                ['name' => 'Manchester City', 'short_name' => 'MCI', 'city' => 'Manchester', 'stadium' => 'Etihad Stadium'],
                ['name' => 'Arsenal', 'short_name' => 'ARS', 'city' => 'London', 'stadium' => 'Emirates Stadium'],
                ['name' => 'Liverpool', 'short_name' => 'LIV', 'city' => 'Liverpool', 'stadium' => 'Anfield'],
                ['name' => 'Manchester United', 'short_name' => 'MUN', 'city' => 'Manchester', 'stadium' => 'Old Trafford'],
                ['name' => 'Chelsea', 'short_name' => 'CHE', 'city' => 'London', 'stadium' => 'Stamford Bridge'],
                ['name' => 'Tottenham', 'short_name' => 'TOT', 'city' => 'London', 'stadium' => 'Tottenham Hotspur Stadium'],
                ['name' => 'Newcastle United', 'short_name' => 'NEW', 'city' => 'Newcastle', 'stadium' => 'St. James\' Park'],
                ['name' => 'Brighton', 'short_name' => 'BHA', 'city' => 'Brighton', 'stadium' => 'Amex Stadium'],
                ['name' => 'West Ham', 'short_name' => 'WHU', 'city' => 'London', 'stadium' => 'London Stadium'],
                ['name' => 'Aston Villa', 'short_name' => 'AVL', 'city' => 'Birmingham', 'stadium' => 'Villa Park'],
            ];

            foreach ($plTeams as $team) {
                Team::updateOrCreate(
                    ['slug' => str($team['name'])->slug()],
                    array_merge($team, ['league_id' => $premierLeague->id])
                );
            }
        }

        if ($laLiga) {
            $laLigaTeams = [
                ['name' => 'Real Madrid', 'short_name' => 'RMA', 'city' => 'Madrid', 'stadium' => 'Santiago Bernabéu'],
                ['name' => 'Barcelona', 'short_name' => 'BAR', 'city' => 'Barcelona', 'stadium' => 'Camp Nou'],
                ['name' => 'Atletico Madrid', 'short_name' => 'ATM', 'city' => 'Madrid', 'stadium' => 'Wanda Metropolitano'],
                ['name' => 'Sevilla', 'short_name' => 'SEV', 'city' => 'Sevilla', 'stadium' => 'Ramón Sánchez-Pizjuán'],
                ['name' => 'Real Betis', 'short_name' => 'BET', 'city' => 'Sevilla', 'stadium' => 'Benito Villamarín'],
                ['name' => 'Valencia', 'short_name' => 'VAL', 'city' => 'Valencia', 'stadium' => 'Mestalla'],
                ['name' => 'Real Sociedad', 'short_name' => 'RSO', 'city' => 'San Sebastián', 'stadium' => 'Reale Arena'],
                ['name' => 'Athletic Bilbao', 'short_name' => 'ATH', 'city' => 'Bilbao', 'stadium' => 'San Mamés'],
                ['name' => 'Villarreal', 'short_name' => 'VIL', 'city' => 'Villarreal', 'stadium' => 'Estadio de la Cerámica'],
                ['name' => 'Getafe', 'short_name' => 'GET', 'city' => 'Getafe', 'stadium' => 'Coliseum Alfonso Pérez'],
            ];

            foreach ($laLigaTeams as $team) {
                Team::updateOrCreate(
                    ['slug' => str($team['name'])->slug()],
                    array_merge($team, ['league_id' => $laLiga->id])
                );
            }
        }

        if ($serieA) {
            $serieATeams = [
                ['name' => 'Juventus', 'short_name' => 'JUV', 'city' => 'Turin', 'stadium' => 'Allianz Stadium'],
                ['name' => 'Inter Milan', 'short_name' => 'INT', 'city' => 'Milan', 'stadium' => 'San Siro'],
                ['name' => 'AC Milan', 'short_name' => 'MIL', 'city' => 'Milan', 'stadium' => 'San Siro'],
                ['name' => 'AS Roma', 'short_name' => 'ROM', 'city' => 'Rome', 'stadium' => 'Stadio Olimpico'],
                ['name' => 'Lazio', 'short_name' => 'LAZ', 'city' => 'Rome', 'stadium' => 'Stadio Olimpico'],
                ['name' => 'Napoli', 'short_name' => 'NAP', 'city' => 'Naples', 'stadium' => 'Stadio Diego Armando Maradona'],
                ['name' => 'Atalanta', 'short_name' => 'ATA', 'city' => 'Bergamo', 'stadium' => 'Gewiss Stadium'],
                ['name' => 'Fiorentina', 'short_name' => 'FIO', 'city' => 'Florence', 'stadium' => 'Stadio Artemio Franchi'],
                ['name' => 'Torino', 'short_name' => 'TOR', 'city' => 'Turin', 'stadium' => 'Stadio Olimpico Grande Torino'],
                ['name' => 'Bologna', 'short_name' => 'BOL', 'city' => 'Bologna', 'stadium' => 'Stadio Renato Dall\'Ara'],
            ];

            foreach ($serieATeams as $team) {
                Team::updateOrCreate(
                    ['slug' => str($team['name'])->slug()],
                    array_merge($team, ['league_id' => $serieA->id])
                );
            }
        }
    }
}
