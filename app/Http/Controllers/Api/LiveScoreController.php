<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FootballMatch;
use Illuminate\Http\Request;

class LiveScoreController extends Controller
{
    /**
     * Get live scores
     */
    public function index()
    {
        $liveMatches = FootballMatch::with(['homeTeam', 'awayTeam', 'league'])
            ->where('status', 'live')
            ->get()
            ->map(function ($match) {
                return [
                    'id' => $match->id,
                    'home_team' => $match->homeTeam->short_name,
                    'away_team' => $match->awayTeam->short_name,
                    'home_team_logo' => $match->homeTeam->logo,
                    'away_team_logo' => $match->awayTeam->logo,
                    'home_score' => $match->home_score,
                    'away_score' => $match->away_score,
                    'minute' => $match->minute,
                    'status' => $match->status,
                    'league' => $match->league->name
                ];
            });

        return response()->json($liveMatches);
    }

    /**
     * Simulate live score updates (for demonstration)
     */
    public function update()
    {
        // Get some live matches and simulate score updates
        $liveMatches = FootballMatch::where('status', 'live')->limit(5)->get();
        $updated = [];

        foreach ($liveMatches as $match) {
            // Simulate random score changes (for demo purposes)
            if (rand(1, 10) > 7) { // 30% chance of score update
                if (rand(1, 2) == 1) {
                    $match->home_score += 1;
                } else {
                    $match->away_score += 1;
                }
                
                // Random minute update
                $match->minute = rand(45, 90);
                $match->save();

                $updated[] = [
                    'id' => $match->id,
                    'home_score' => $match->home_score,
                    'away_score' => $match->away_score,
                    'minute' => $match->minute,
                    'status' => $match->status
                ];
            }
        }

        return response()->json([
            'updated' => count($updated),
            'matches' => $updated
        ]);
    }
}
