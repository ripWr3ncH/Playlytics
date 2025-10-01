<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\League;
use App\Models\Team;
use App\Models\Player;
use App\Models\FootballMatch;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    // League Management
    public function leagues()
    {
        $leagues = League::withCount(['teams', 'matches'])->orderBy('name')->get();
        return view('admin.leagues.index', compact('leagues'));
    }

    public function createLeague()
    {
        return view('admin.leagues.create');
    }

    public function storeLeague(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:leagues,slug',
            'country' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $data = $request->all();
        if (!$data['slug']) {
            $data['slug'] = \Str::slug($data['name']);
        }

        League::create($data);
        
        return redirect()->route('admin.leagues')->with('success', 'League created successfully!');
    }

    public function editLeague(League $league)
    {
        return view('admin.leagues.edit', compact('league'));
    }

    public function updateLeague(Request $request, League $league)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:leagues,slug,' . $league->id,
            'country' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $data = $request->all();
        if (!$data['slug']) {
            $data['slug'] = \Str::slug($data['name']);
        }

        $league->update($data);
        
        return redirect()->route('admin.leagues')->with('success', 'League updated successfully!');
    }

    public function deleteLeague(League $league)
    {
        $league->delete();
        return redirect()->route('admin.leagues')->with('success', 'League deleted successfully!');
    }

    // Team Management
    public function teams()
    {
        $teams = Team::with(['league'])->withCount('players')->orderBy('name')->get();
        return view('admin.teams.index', compact('teams'));
    }

    public function createTeam()
    {
        $leagues = League::orderBy('name')->get();
        return view('admin.teams.create', compact('leagues'));
    }

    public function storeTeam(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:teams,slug',
            'league_id' => 'required|exists:leagues,id',
            'city' => 'nullable|string|max:255',
            'founded' => 'nullable|integer|min:1800|max:' . date('Y'),
            'description' => 'nullable|string',
        ]);

        $data = $request->all();
        if (!$data['slug']) {
            $data['slug'] = \Str::slug($data['name']);
        }

        Team::create($data);
        
        return redirect()->route('admin.teams')->with('success', 'Team created successfully!');
    }

    public function editTeam(Team $team)
    {
        $leagues = League::orderBy('name')->get();
        return view('admin.teams.edit', compact('team', 'leagues'));
    }

    public function updateTeam(Request $request, Team $team)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:teams,slug,' . $team->id,
            'league_id' => 'required|exists:leagues,id',
            'city' => 'nullable|string|max:255',
            'founded' => 'nullable|integer|min:1800|max:' . date('Y'),
            'description' => 'nullable|string',
        ]);

        $data = $request->all();
        if (!$data['slug']) {
            $data['slug'] = \Str::slug($data['name']);
        }

        $team->update($data);
        
        return redirect()->route('admin.teams')->with('success', 'Team updated successfully!');
    }

    public function deleteTeam(Team $team)
    {
        $team->delete();
        return redirect()->route('admin.teams')->with('success', 'Team deleted successfully!');
    }

    // Player Management
    public function players()
    {
        $players = Player::with(['team', 'team.league'])->orderBy('name')->get();
        return view('admin.players.index', compact('players'));
    }

    public function createPlayer()
    {
        $teams = Team::with('league')->orderBy('name')->get();
        return view('admin.players.create', compact('teams'));
    }

    public function storePlayer(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:players,slug',
            'team_id' => 'required|exists:teams,id',
            'position' => 'nullable|string|max:255',
            'age' => 'nullable|integer|min:16|max:50',
            'nationality' => 'nullable|string|max:255',
        ]);

        $data = $request->all();
        if (!$data['slug']) {
            $data['slug'] = \Str::slug($data['name']);
        }

        Player::create($data);
        
        return redirect()->route('admin.players')->with('success', 'Player created successfully!');
    }

    public function editPlayer(Player $player)
    {
        $teams = Team::with('league')->orderBy('name')->get();
        return view('admin.players.edit', compact('player', 'teams'));
    }

    public function updatePlayer(Request $request, Player $player)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:players,slug,' . $player->id,
            'team_id' => 'required|exists:teams,id',
            'position' => 'nullable|string|max:255',
            'age' => 'nullable|integer|min:16|max:50',
            'nationality' => 'nullable|string|max:255',
        ]);

        $data = $request->all();
        if (!$data['slug']) {
            $data['slug'] = \Str::slug($data['name']);
        }

        $player->update($data);
        
        return redirect()->route('admin.players')->with('success', 'Player updated successfully!');
    }

    public function deletePlayer(Player $player)
    {
        $player->delete();
        return redirect()->route('admin.players')->with('success', 'Player deleted successfully!');
    }

    // Match Management
    public function matches()
    {
        $matches = FootballMatch::with(['homeTeam', 'awayTeam', 'league'])
            ->orderByDesc('match_date')
            ->get();
        return view('admin.matches.index', compact('matches'));
    }

    public function createMatch()
    {
        $teams = Team::with('league')->orderBy('name')->get();
        $leagues = League::orderBy('name')->get();
        return view('admin.matches.create', compact('teams', 'leagues'));
    }

    public function storeMatch(Request $request)
    {
        $request->validate([
            'home_team_id' => 'required|exists:teams,id',
            'away_team_id' => 'required|exists:teams,id|different:home_team_id',
            'league_id' => 'required|exists:leagues,id',
            'match_date' => 'nullable|date',
            'home_score' => 'nullable|integer|min:0',
            'away_score' => 'nullable|integer|min:0',
            'status' => 'required|in:scheduled,live,finished,postponed,cancelled',
        ]);

        FootballMatch::create($request->all());
        
        return redirect()->route('admin.matches')->with('success', 'Match created successfully!');
    }

    public function editMatch(FootballMatch $match)
    {
        $teams = Team::with('league')->orderBy('name')->get();
        $leagues = League::orderBy('name')->get();
        return view('admin.matches.edit', compact('match', 'teams', 'leagues'));
    }

    public function updateMatch(Request $request, FootballMatch $match)
    {
        $request->validate([
            'home_team_id' => 'required|exists:teams,id',
            'away_team_id' => 'required|exists:teams,id|different:home_team_id',
            'league_id' => 'required|exists:leagues,id',
            'match_date' => 'nullable|date',
            'home_score' => 'nullable|integer|min:0',
            'away_score' => 'nullable|integer|min:0',
            'status' => 'required|in:scheduled,live,finished,postponed,cancelled',
        ]);

        $match->update($request->all());
        
        return redirect()->route('admin.matches')->with('success', 'Match updated successfully!');
    }

    public function deleteMatch(FootballMatch $match)
    {
        $match->delete();
        return redirect()->route('admin.matches')->with('success', 'Match deleted successfully!');
    }
    
    // SQL Query Logs (for academic demonstration)
    public function sqlLogs()
    {
        $sqlQueries = [
            [
                'type' => 'SELECT with JOIN',
                'description' => 'Get all matches with team names',
                'query' => 'SELECT m.id, m.match_date, ht.name as home_team, at.name as away_team, l.name as league 
                           FROM football_matches m 
                           JOIN teams ht ON m.home_team_id = ht.id 
                           JOIN teams at ON m.away_team_id = at.id 
                           JOIN leagues l ON m.league_id = l.id 
                           ORDER BY m.match_date DESC',
                'purpose' => 'Demonstrates INNER JOIN operations between multiple tables'
            ],
            [
                'type' => 'SELECT with COUNT and GROUP BY',
                'description' => 'Count players by team',
                'query' => 'SELECT t.name as team_name, COUNT(p.id) as player_count 
                           FROM teams t 
                           LEFT JOIN players p ON t.id = p.team_id 
                           GROUP BY t.id, t.name 
                           ORDER BY player_count DESC',
                'purpose' => 'Demonstrates LEFT JOIN, COUNT aggregate function, and GROUP BY clause'
            ],
            [
                'type' => 'SELECT with Subquery',
                'description' => 'Teams with most players',
                'query' => 'SELECT t.name, 
                           (SELECT COUNT(*) FROM players p WHERE p.team_id = t.id) as player_count 
                           FROM teams t 
                           WHERE (SELECT COUNT(*) FROM players p WHERE p.team_id = t.id) > 0 
                           ORDER BY player_count DESC',
                'purpose' => 'Demonstrates subqueries in SELECT and WHERE clauses'
            ],
            [
                'type' => 'Complex Analytics Query',
                'description' => 'League statistics with multiple aggregations',
                'query' => 'SELECT 
                           l.name as league_name,
                           COUNT(DISTINCT t.id) as total_teams,
                           COUNT(DISTINCT p.id) as total_players,
                           COUNT(DISTINCT m.id) as total_matches,
                           ROUND(AVG(CASE WHEN m.status = "finished" THEN (m.home_score + m.away_score) END), 2) as avg_goals_per_match
                           FROM leagues l
                           LEFT JOIN teams t ON l.id = t.league_id
                           LEFT JOIN players p ON t.id = p.team_id
                           LEFT JOIN football_matches m ON l.id = m.league_id
                           GROUP BY l.id, l.name
                           ORDER BY total_teams DESC',
                'purpose' => 'Demonstrates complex aggregations, CASE statements, and multiple JOINs'
            ]
        ];
        
        return view('admin.sql-logs', compact('sqlQueries'));
    }
}
