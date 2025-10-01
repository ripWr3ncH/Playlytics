<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FootballMatch;
use App\Models\League;
use App\Models\Team;
use Carbon\Carbon;

class MatchSeeder extends Seeder
{
    public function run()
    {
        // Get leagues and teams
        $premierLeague = League::where('slug', 'premier-league')->first();
        $laLiga = League::where('slug', 'la-liga')->first();
        $serieA = League::where('slug', 'serie-a')->first();
        
        if (!$premierLeague || !$laLiga || !$serieA) {
            $this->command->error('Please run league and team seeders first');
            return;
        }
        
        // Get all teams for each league
        $plTeams = Team::where('league_id', $premierLeague->id)->get();
        $laLigaTeams = Team::where('league_id', $laLiga->id)->get();
        $serieATeams = Team::where('league_id', $serieA->id)->get();
        
        // Create sample matches for each league
        $this->createSampleMatches($premierLeague, $plTeams);
        $this->createSampleMatches($laLiga, $laLigaTeams);
        $this->createSampleMatches($serieA, $serieATeams);
    }
    
    private function createSampleMatches($league, $teams)
    {
        if ($teams->count() < 4) {
            return;
        }
        
        $now = Carbon::now();
        
        // Past matches (finished) - 15 matches over the last 2 weeks
        for ($i = 1; $i <= 15; $i++) {
            $homeTeam = $teams->random();
            $awayTeam = $teams->where('id', '!=', $homeTeam->id)->random();
            
            $homeScore = rand(0, 4);
            $awayScore = rand(0, 4);
            
            FootballMatch::create([
                'league_id' => $league->id,
                'home_team_id' => $homeTeam->id,
                'away_team_id' => $awayTeam->id,
                'match_date' => $now->copy()->subDays(rand(1, 14))->setHour(rand(13, 20))->setMinute(rand(0, 45)),
                'home_score' => $homeScore,
                'away_score' => $awayScore,
                'status' => 'finished',
                'venue' => $homeTeam->stadium ?? $homeTeam->name . ' Stadium',
                'matchweek' => rand(1, 10),
                'attendance' => rand(30000, 80000)
            ]);
        }
        
        // Today's matches (some live, some finished)
        for ($i = 1; $i <= 3; $i++) {
            $homeTeam = $teams->random();
            $awayTeam = $teams->where('id', '!=', $homeTeam->id)->random();
            
            $isLive = $i <= 1; // First match is live
            $homeScore = $isLive ? rand(0, 3) : rand(0, 4);
            $awayScore = $isLive ? rand(0, 3) : rand(0, 4);
            
            FootballMatch::create([
                'league_id' => $league->id,
                'home_team_id' => $homeTeam->id,
                'away_team_id' => $awayTeam->id,
                'match_date' => $now->copy()->setHour(rand(13, 20))->setMinute(rand(0, 45)),
                'home_score' => $homeScore,
                'away_score' => $awayScore,
                'status' => $isLive ? 'live' : 'finished',
                'minute' => $isLive ? rand(1, 90) : null,
                'venue' => $homeTeam->stadium ?? $homeTeam->name . ' Stadium',
                'matchweek' => 11,
                'attendance' => rand(30000, 80000)
            ]);
        }
        
        // Future matches (upcoming) - 20 matches over the next 3 weeks
        for ($i = 1; $i <= 20; $i++) {
            $homeTeam = $teams->random();
            $awayTeam = $teams->where('id', '!=', $homeTeam->id)->random();
            
            FootballMatch::create([
                'league_id' => $league->id,
                'home_team_id' => $homeTeam->id,
                'away_team_id' => $awayTeam->id,
                'match_date' => $now->copy()->addDays(rand(1, 21))->setHour(rand(13, 20))->setMinute(rand(0, 45)),
                'status' => 'scheduled',
                'venue' => $homeTeam->stadium ?? $homeTeam->name . ' Stadium',
                'matchweek' => rand(12, 20),
                'attendance' => null
            ]);
        }
    }
}