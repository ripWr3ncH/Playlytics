<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PlayerStat;
use App\Models\Player;
use App\Models\FootballMatch;

class PlayerStatsSeeder extends Seeder
{
    public function run()
    {
        // Get all finished matches
        $finishedMatches = FootballMatch::where('status', 'finished')->get();
        
        foreach ($finishedMatches as $match) {
            // Get players from both teams
            $homePlayers = Player::where('team_id', $match->home_team_id)->take(11)->get();
            $awayPlayers = Player::where('team_id', $match->away_team_id)->take(11)->get();
            
            // Generate stats for home team players
            $this->generatePlayerStats($homePlayers, $match, true);
            
            // Generate stats for away team players
            $this->generatePlayerStats($awayPlayers, $match, false);
        }
    }
    
    private function generatePlayerStats($players, $match, $isHome)
    {
        $totalGoals = $isHome ? $match->home_score : $match->away_score;
        $goalsDistributed = 0;
        
        foreach ($players as $player) {
            $minutesPlayed = $this->getRandomMinutes($player->position);
            $goals = 0;
            $assists = 0;
            $yellowCards = 0;
            $redCards = 0;
            
            // Distribute goals based on position
            if ($goalsDistributed < $totalGoals && $this->shouldScoreGoal($player->position)) {
                $goals = 1;
                $goalsDistributed++;
            }
            
            // Generate assists (40% chance for midfielders and forwards)
            if (in_array($player->position, ['MID', 'FWD']) && rand(1, 100) <= 40) {
                $assists = 1;
            }
            
            // Generate cards (10% chance yellow, 2% chance red)
            if (rand(1, 100) <= 10) {
                $yellowCards = 1;
            }
            if (rand(1, 100) <= 2) {
                $redCards = 1;
                $minutesPlayed = rand(10, 80); // Red card = early exit
            }
            
            // Generate rating (5.0 to 10.0)
            $rating = rand(50, 100) / 10;
            
            // Adjust rating based on performance
            if ($goals > 0) $rating += 0.5;
            if ($assists > 0) $rating += 0.3;
            if ($redCards > 0) $rating -= 1.0;
            if ($yellowCards > 0) $rating -= 0.2;
            
            $rating = max(5.0, min(10.0, $rating));
            
            PlayerStat::create([
                'player_id' => $player->id,
                'match_id' => $match->id,
                'goals' => $goals,
                'assists' => $assists,
                'yellow_cards' => $yellowCards,
                'red_cards' => $redCards,
                'minutes_played' => $minutesPlayed,
                'rating' => round($rating, 1)
            ]);
        }
    }
    
    private function getRandomMinutes($position)
    {
        // Goalkeepers and main players usually play full match
        if ($position === 'GK') {
            return 90;
        }
        
        // 80% chance of playing full match, 20% chance of substitution
        if (rand(1, 100) <= 80) {
            return 90;
        }
        
        // Substituted players
        return rand(10, 85);
    }
    
    private function shouldScoreGoal($position)
    {
        $chances = [
            'FWD' => 60, // 60% chance
            'MID' => 25, // 25% chance
            'DEF' => 8,  // 8% chance
            'GK' => 1    // 1% chance
        ];
        
        $chance = $chances[$position] ?? 0;
        return rand(1, 100) <= $chance;
    }
}