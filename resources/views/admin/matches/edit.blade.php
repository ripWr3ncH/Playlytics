@extends('admin.layout')

@section('title', 'Edit Match')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-edit me-2"></i>
        Edit Match: {{ $match->homeTeam->name }} vs {{ $match->awayTeam->name }}
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.matches') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i>Back to Matches
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Match Information</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.matches.update', $match) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="league_id" class="form-label">League <span class="text-danger">*</span></label>
                                <select class="form-select @error('league_id') is-invalid @enderror" 
                                        id="league_id" 
                                        name="league_id" 
                                        required>
                                    <option value="">Select a league</option>
                                    @foreach($leagues as $league)
                                        <option value="{{ $league->id }}" 
                                                {{ old('league_id', $match->league_id) == $league->id ? 'selected' : '' }}>
                                            {{ $league->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('league_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="home_team_id" class="form-label">Home Team <span class="text-danger">*</span></label>
                                <select class="form-select @error('home_team_id') is-invalid @enderror" 
                                        id="home_team_id" 
                                        name="home_team_id" 
                                        required>
                                    <option value="">Select home team</option>
                                    @foreach($teams as $team)
                                        <option value="{{ $team->id }}" 
                                                data-league="{{ $team->league_id }}"
                                                {{ old('home_team_id', $match->home_team_id) == $team->id ? 'selected' : '' }}>
                                            {{ $team->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('home_team_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="away_team_id" class="form-label">Away Team <span class="text-danger">*</span></label>
                                <select class="form-select @error('away_team_id') is-invalid @enderror" 
                                        id="away_team_id" 
                                        name="away_team_id" 
                                        required>
                                    <option value="">Select away team</option>
                                    @foreach($teams as $team)
                                        <option value="{{ $team->id }}" 
                                                data-league="{{ $team->league_id }}"
                                                {{ old('away_team_id', $match->away_team_id) == $team->id ? 'selected' : '' }}>
                                            {{ $team->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('away_team_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="match_date" class="form-label">Match Date</label>
                                <input type="datetime-local" 
                                       class="form-control @error('match_date') is-invalid @enderror" 
                                       id="match_date" 
                                       name="match_date" 
                                       value="{{ old('match_date', $match->match_date ? $match->match_date->format('Y-m-d\TH:i') : '') }}">
                                @error('match_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select @error('status') is-invalid @enderror" 
                                        id="status" 
                                        name="status">
                                    <option value="scheduled" {{ old('status', $match->status) == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                    <option value="live" {{ old('status', $match->status) == 'live' ? 'selected' : '' }}>Live</option>
                                    <option value="finished" {{ old('status', $match->status) == 'finished' ? 'selected' : '' }}>Finished</option>
                                    <option value="postponed" {{ old('status', $match->status) == 'postponed' ? 'selected' : '' }}>Postponed</option>
                                    <option value="cancelled" {{ old('status', $match->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row" id="score-section">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="home_score" class="form-label">Home Team Score</label>
                                <input type="number" 
                                       class="form-control @error('home_score') is-invalid @enderror" 
                                       id="home_score" 
                                       name="home_score" 
                                       value="{{ old('home_score', $match->home_score ?? 0) }}" 
                                       min="0">
                                @error('home_score')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="away_score" class="form-label">Away Team Score</label>
                                <input type="number" 
                                       class="form-control @error('away_score') is-invalid @enderror" 
                                       id="away_score" 
                                       name="away_score" 
                                       value="{{ old('away_score', $match->away_score ?? 0) }}" 
                                       min="0">
                                @error('away_score')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('admin.matches') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Update Match
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-info-circle me-1"></i>
                    Match Details
                </h6>
            </div>
            <div class="card-body">
                <h6>Current Information</h6>
                <ul class="list-unstyled small">
                    <li><strong>ID:</strong> {{ $match->id }}</li>
                    <li><strong>Created:</strong> {{ $match->created_at->format('M d, Y') }}</li>
                    <li><strong>Updated:</strong> {{ $match->updated_at->format('M d, Y') }}</li>
                    @if($match->api_match_id)
                        <li><strong>API ID:</strong> {{ $match->api_match_id }}</li>
                    @endif
                </ul>
                
                @if($match->playerStats && $match->playerStats->count() > 0)
                <div class="alert alert-warning small mt-3">
                    <i class="fas fa-exclamation-triangle me-1"></i>
                    This match has {{ $match->playerStats->count() }} player statistics. 
                    Changing teams may affect stat relationships.
                </div>
                @endif
                
                <div class="mt-3">
                    <a href="{{ route('matches.show', $match->id) }}" 
                       class="btn btn-sm btn-outline-primary" 
                       target="_blank">
                        <i class="fas fa-external-link-alt me-1"></i>
                        View Match Page
                    </a>
                </div>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-trash me-1"></i>
                    Danger Zone
                </h6>
            </div>
            <div class="card-body">
                <p class="small text-muted">
                    Deleting this match will also remove all associated player statistics and cannot be undone.
                </p>
                <form action="{{ route('admin.matches.delete', $match) }}" 
                      method="POST" 
                      onsubmit="return confirm('Are you sure you want to delete this match? This action cannot be undone and will remove all match statistics.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger">
                        <i class="fas fa-trash me-1"></i>Delete Match
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const leagueSelect = document.getElementById('league_id');
    const homeTeamSelect = document.getElementById('home_team_id');
    const awayTeamSelect = document.getElementById('away_team_id');
    const statusSelect = document.getElementById('status');
    const scoreSection = document.getElementById('score-section');
    
    // Store original team options
    const allTeams = @json($teams);
    
    // Filter teams based on selected league
    function filterTeams() {
        const selectedLeague = leagueSelect.value;
        const currentHomeTeam = homeTeamSelect.value;
        const currentAwayTeam = awayTeamSelect.value;
        
        // Clear options
        homeTeamSelect.innerHTML = '<option value="">Select home team</option>';
        awayTeamSelect.innerHTML = '<option value="">Select away team</option>';
        
        if (selectedLeague) {
            // Add teams from selected league
            allTeams.forEach(team => {
                if (team.league_id == selectedLeague) {
                    const homeOption = new Option(team.name, team.id);
                    const awayOption = new Option(team.name, team.id);
                    
                    if (team.id == currentHomeTeam) homeOption.selected = true;
                    if (team.id == currentAwayTeam) awayOption.selected = true;
                    
                    homeTeamSelect.add(homeOption);
                    awayTeamSelect.add(awayOption);
                }
            });
        }
    }
    
    // Show/hide score section based on status
    function toggleScoreSection() {
        const status = statusSelect.value;
        if (status === 'live' || status === 'finished') {
            scoreSection.style.display = 'block';
        } else {
            scoreSection.style.display = 'none';
        }
    }
    
    // Prevent same team selection
    function preventSameTeam() {
        const homeTeam = homeTeamSelect.value;
        const awayTeam = awayTeamSelect.value;
        
        if (homeTeam && awayTeam && homeTeam === awayTeam) {
            alert('Home team and away team cannot be the same!');
            if (this === homeTeamSelect) {
                awayTeamSelect.value = '';
            } else {
                homeTeamSelect.value = '';
            }
        }
    }
    
    // Event listeners
    leagueSelect.addEventListener('change', filterTeams);
    statusSelect.addEventListener('change', toggleScoreSection);
    homeTeamSelect.addEventListener('change', preventSameTeam);
    awayTeamSelect.addEventListener('change', preventSameTeam);
    
    // Initial load
    toggleScoreSection();
});
</script>
@endsection