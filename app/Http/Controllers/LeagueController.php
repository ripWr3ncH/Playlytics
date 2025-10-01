<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\League;
use App\Models\Team;
use App\Models\FootballMatch;
use App\Models\Player;

class LeagueController extends Controller
{
    public function index()
    {
        $leagues = League::with('teams')->where('is_active', true)->get();
        return view('leagues.index', compact('leagues'));
    }

    public function show($slug)
    {
        $league = League::where('slug', $slug)->firstOrFail();
        
        // Calculate standings from database
        $teams = Team::where('league_id', $league->id)->get();
        
        $standings = $teams->map(function ($team) {
            $homeMatches = FootballMatch::where('home_team_id', $team->id)
                ->where('status', 'finished')
                ->get();
            
            $awayMatches = FootballMatch::where('away_team_id', $team->id)
                ->where('status', 'finished')
                ->get();
            
            $played = $homeMatches->count() + $awayMatches->count();
            $wins = $homeMatches->where('home_score', '>', 'away_score')->count() +
                   $awayMatches->where('away_score', '>', 'home_score')->count();
            $draws = $homeMatches->where('home_score', 'away_score')->count() +
                    $awayMatches->where('away_score', 'home_score')->count();
            $losses = $played - $wins - $draws;
            
            $goalsFor = $homeMatches->sum('home_score') + $awayMatches->sum('away_score');
            $goalsAgainst = $homeMatches->sum('away_score') + $awayMatches->sum('home_score');
            $goalDifference = $goalsFor - $goalsAgainst;
            $points = ($wins * 3) + $draws;
            
            return [
                'position' => 0, // Will be set after sorting
                'team' => [
                    'name' => $team->name,
                    'shortName' => $team->short_name,
                    'crest' => $team->logo
                ],
                'playedGames' => $played,
                'won' => $wins,
                'draw' => $draws,
                'lost' => $losses,
                'goalsFor' => $goalsFor,
                'goalsAgainst' => $goalsAgainst,
                'goalDifference' => $goalDifference,
                'points' => $points
            ];
        })->sortByDesc('points')->values()->map(function ($item, $index) {
            $item['position'] = $index + 1;
            return $item;
        })->toArray();

        // Get top scorers from database
        $topScorers = Player::whereHas('team', function($q) use ($league) {
                $q->where('league_id', $league->id);
            })
            ->whereHas('stats')
            ->with(['team', 'stats'])
            ->get()
            ->map(function ($player) {
                $totalGoals = $player->stats->sum('goals');
                return [
                    'player' => [
                        'name' => $player->name,
                        'position' => $player->position,
                        'nationality' => $player->nationality
                    ],
                    'team' => [
                        'name' => $player->team->name,
                        'shortName' => $player->team->short_name,
                        'crest' => $player->team->logo
                    ],
                    'goals' => $totalGoals,
                    'assists' => $player->stats->sum('assists'),
                    'playedGames' => $player->stats->count()
                ];
            })
            ->sortByDesc('goals')
            ->take(10)
            ->values()
            ->toArray();

        // Get recent matches
        $recentMatches = FootballMatch::with(['homeTeam', 'awayTeam'])
            ->where('league_id', $league->id)
            ->where('status', 'finished')
            ->orderBy('match_date', 'desc')
            ->limit(10)
            ->get();

        // Get upcoming matches
        $upcomingMatches = FootballMatch::with(['homeTeam', 'awayTeam'])
            ->where('league_id', $league->id)
            ->where('status', 'scheduled')
            ->orderBy('match_date')
            ->limit(10)
            ->get();

        return view('leagues.show', compact('league', 'standings', 'recentMatches', 'upcomingMatches', 'topScorers'));
    }

    /**
     * Show detailed standings page
     */
    public function standings($slug)
    {
        $league = League::where('slug', $slug)->firstOrFail();
        
        // Calculate standings from database
        $teams = Team::where('league_id', $league->id)->get();
        
        $standings = $teams->map(function ($team) {
            $homeMatches = FootballMatch::where('home_team_id', $team->id)
                ->where('status', 'finished')
                ->get();
            
            $awayMatches = FootballMatch::where('away_team_id', $team->id)
                ->where('status', 'finished')
                ->get();
            
            $played = $homeMatches->count() + $awayMatches->count();
            $wins = $homeMatches->where('home_score', '>', 'away_score')->count() +
                   $awayMatches->where('away_score', '>', 'home_score')->count();
            $draws = $homeMatches->where('home_score', 'away_score')->count() +
                    $awayMatches->where('away_score', 'home_score')->count();
            $losses = $played - $wins - $draws;
            
            $goalsFor = $homeMatches->sum('home_score') + $awayMatches->sum('away_score');
            $goalsAgainst = $homeMatches->sum('away_score') + $awayMatches->sum('home_score');
            $goalDifference = $goalsFor - $goalsAgainst;
            $points = ($wins * 3) + $draws;
            
            return [
                'position' => 0, // Will be set after sorting
                'team' => [
                    'name' => $team->name,
                    'shortName' => $team->short_name,
                    'crest' => $team->logo
                ],
                'playedGames' => $played,
                'won' => $wins,
                'draw' => $draws,
                'lost' => $losses,
                'goalsFor' => $goalsFor,
                'goalsAgainst' => $goalsAgainst,
                'goalDifference' => $goalDifference,
                'points' => $points
            ];
        })->sortByDesc('points')->values()->map(function ($item, $index) {
            $item['position'] = $index + 1;
            return $item;
        })->toArray();

        // Get top scorers from database
        $topScorers = Player::whereHas('team', function($q) use ($league) {
                $q->where('league_id', $league->id);
            })
            ->whereHas('stats')
            ->with(['team', 'stats'])
            ->get()
            ->map(function ($player) {
                $totalGoals = $player->stats->sum('goals');
                return [
                    'player' => [
                        'name' => $player->name,
                        'position' => $player->position,
                        'nationality' => $player->nationality
                    ],
                    'team' => [
                        'name' => $player->team->name,
                        'shortName' => $player->team->short_name,
                        'crest' => $player->team->logo
                    ],
                    'goals' => $totalGoals,
                    'assists' => $player->stats->sum('assists'),
                    'playedGames' => $player->stats->count()
                ];
            })
            ->sortByDesc('goals')
            ->take(20)
            ->values()
            ->toArray();

        return view('leagues.standings', compact('league', 'standings', 'topScorers'));
    }
}
