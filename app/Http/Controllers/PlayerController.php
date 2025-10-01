<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Player;
use App\Models\League;
use Illuminate\Support\Facades\Log;

class PlayerController extends Controller
{
    public function index(Request $request)
    {
        $query = Player::with(['team.league']);
        
        // Filter by league if specified
        if ($request->has('league')) {
            $query->whereHas('team.league', function($q) use ($request) {
                $q->where('slug', $request->league);
            });
        }
        
        // Filter by position if specified
        if ($request->has('position')) {
            $query->where('position', $request->position);
        }
        
        // Search by name
        if ($request->has('search')) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }
        
        $players = $query->orderBy('name')->paginate(20);
        $leagues = League::where('is_active', true)->get();
        $positions = ['GK', 'DEF', 'MID', 'FWD'];
        
        return view('players.index', compact('players', 'leagues', 'positions'));
    }

    public function show($slug)
    {
        $player = Player::with([
            'team.league',
            'stats.match.homeTeam',
            'stats.match.awayTeam'
        ])->where('slug', $slug)->firstOrFail();
        
        // Calculate season stats
        $seasonStats = [
            'goals' => $player->stats()->sum('goals'),
            'assists' => $player->stats()->sum('assists'),
            'yellow_cards' => $player->stats()->sum('yellow_cards'),
            'red_cards' => $player->stats()->sum('red_cards'),
            'minutes_played' => $player->stats()->sum('minutes_played'),
            'matches_played' => $player->stats()->distinct('match_id')->count(),
            'average_rating' => $player->stats()->whereNotNull('rating')->avg('rating')
        ];
        
        // Get recent matches with this player
        $recentMatches = $player->stats()
            ->with(['match.homeTeam', 'match.awayTeam'])
            ->latest()
            ->limit(5)
            ->get();
        
        return view('players.show', compact('player', 'seasonStats', 'recentMatches'));
    }
}
