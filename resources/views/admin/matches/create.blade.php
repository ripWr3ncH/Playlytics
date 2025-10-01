@extends('admin.layout')

@section('title', 'Create Match')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-plus-circle me-2"></i>
        Create New Match
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
                <form action="{{ route('admin.matches.store') }}" method="POST">
                    @csrf
                    
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
                                                {{ old('league_id') == $league->id ? 'selected' : '' }}>
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
                                                {{ old('home_team_id') == $team->id ? 'selected' : '' }}>
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
                                                {{ old('away_team_id') == $team->id ? 'selected' : '' }}>
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
                                       value="{{ old('match_date') }}">
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
                                    <option value="scheduled" {{ old('status', 'scheduled') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                    <option value="live" {{ old('status') == 'live' ? 'selected' : '' }}>Live</option>
                                    <option value="finished" {{ old('status') == 'finished' ? 'selected' : '' }}>Finished</option>
                                    <option value="postponed" {{ old('status') == 'postponed' ? 'selected' : '' }}>Postponed</option>
                                    <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row" id="score-section" style="display: none;">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="home_score" class="form-label">Home Team Score</label>
                                <input type="number" 
                                       class="form-control @error('home_score') is-invalid @enderror" 
                                       id="home_score" 
                                       name="home_score" 
                                       value="{{ old('home_score', 0) }}" 
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
                                       value="{{ old('away_score', 0) }}" 
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
                            <i class="fas fa-save me-1"></i>Create Match
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
                    Help
                </h6>
            </div>
            <div class="card-body">
                <h6>Required Fields</h6>
                <ul class="small">
                    <li><strong>League:</strong> Select the league for this match</li>
                    <li><strong>Home Team:</strong> The team playing at home</li>
                    <li><strong>Away Team:</strong> The visiting team</li>
                </ul>
                
                <h6 class="mt-3">Match Status</h6>
                <ul class="small">
                    <li><strong>Scheduled:</strong> Match is planned for the future</li>
                    <li><strong>Live:</strong> Match is currently being played</li>
                    <li><strong>Finished:</strong> Match has been completed</li>
                    <li><strong>Postponed:</strong> Match has been delayed</li>
                    <li><strong>Cancelled:</strong> Match has been cancelled</li>
                </ul>
                
                <div class="alert alert-info small mt-3">
                    <i class="fas fa-lightbulb me-1"></i>
                    Teams will be filtered based on the selected league. Score fields will appear for live and finished matches.
                </div>
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
    
    // Filter teams based on selected league
    function filterTeams() {
        const selectedLeague = leagueSelect.value;
        
        // Reset team selections
        homeTeamSelect.innerHTML = '<option value="">Select home team</option>';
        awayTeamSelect.innerHTML = '<option value="">Select away team</option>';
        
        if (selectedLeague) {
            // Show only teams from selected league
            @foreach($teams as $team)
                if ('{{ $team->league_id }}' === selectedLeague) {
                    homeTeamSelect.innerHTML += '<option value="{{ $team->id }}">{{ $team->name }}</option>';
                    awayTeamSelect.innerHTML += '<option value="{{ $team->id }}">{{ $team->name }}</option>';
                }
            @endforeach
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
    if (leagueSelect.value) {
        filterTeams();
    }
});
</script>
@endsection