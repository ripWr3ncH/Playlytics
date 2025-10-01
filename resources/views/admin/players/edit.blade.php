@extends('admin.layout')

@section('title', 'Edit Player')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-user-edit me-2"></i>
        Edit Player: {{ $player->name }}
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.players') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i>Back to Players
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Player Information</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.players.update', $player) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Player Name <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name', $player->name) }}" 
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="slug" class="form-label">Slug</label>
                                <input type="text" 
                                       class="form-control @error('slug') is-invalid @enderror" 
                                       id="slug" 
                                       name="slug" 
                                       value="{{ old('slug', $player->slug) }}">
                                @error('slug')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">URL-friendly version of the name</div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="team_id" class="form-label">Team <span class="text-danger">*</span></label>
                                <select class="form-select @error('team_id') is-invalid @enderror" 
                                        id="team_id" 
                                        name="team_id" 
                                        required>
                                    <option value="">Select a team</option>
                                    @foreach($teams as $team)
                                        <option value="{{ $team->id }}" 
                                                {{ old('team_id', $player->team_id) == $team->id ? 'selected' : '' }}>
                                            {{ $team->name }} ({{ $team->league->name }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('team_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="position" class="form-label">Position</label>
                                <select class="form-select @error('position') is-invalid @enderror" 
                                        id="position" 
                                        name="position">
                                    <option value="">Select position</option>
                                    <option value="Goalkeeper" {{ old('position', $player->position) == 'Goalkeeper' ? 'selected' : '' }}>Goalkeeper</option>
                                    <option value="Defender" {{ old('position', $player->position) == 'Defender' ? 'selected' : '' }}>Defender</option>
                                    <option value="Midfielder" {{ old('position', $player->position) == 'Midfielder' ? 'selected' : '' }}>Midfielder</option>
                                    <option value="Forward" {{ old('position', $player->position) == 'Forward' ? 'selected' : '' }}>Forward</option>
                                </select>
                                @error('position')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="age" class="form-label">Age</label>
                                <input type="number" 
                                       class="form-control @error('age') is-invalid @enderror" 
                                       id="age" 
                                       name="age" 
                                       value="{{ old('age', $player->age) }}" 
                                       min="16" 
                                       max="50">
                                @error('age')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nationality" class="form-label">Nationality</label>
                                <input type="text" 
                                       class="form-control @error('nationality') is-invalid @enderror" 
                                       id="nationality" 
                                       name="nationality" 
                                       value="{{ old('nationality', $player->nationality) }}"
                                       placeholder="e.g., Brazilian, Spanish, English">
                                @error('nationality')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('admin.players') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Update Player
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
                    Player Details
                </h6>
            </div>
            <div class="card-body">
                <h6>Current Information</h6>
                <ul class="list-unstyled small">
                    <li><strong>ID:</strong> {{ $player->id }}</li>
                    <li><strong>Created:</strong> {{ $player->created_at->format('M d, Y') }}</li>
                    <li><strong>Updated:</strong> {{ $player->updated_at->format('M d, Y') }}</li>
                </ul>
                
                @if($player->playerStats && $player->playerStats->count() > 0)
                <div class="alert alert-warning small mt-3">
                    <i class="fas fa-exclamation-triangle me-1"></i>
                    This player has {{ $player->playerStats->count() }} match statistics. 
                    Changing the team will not affect existing stats.
                </div>
                @endif
                
                <div class="mt-3">
                    <a href="{{ route('players.show', $player->slug) }}" 
                       class="btn btn-sm btn-outline-primary" 
                       target="_blank">
                        <i class="fas fa-external-link-alt me-1"></i>
                        View Player Page
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
                    Deleting this player will also remove all associated statistics and cannot be undone.
                </p>
                <form action="{{ route('admin.players.delete', $player) }}" 
                      method="POST" 
                      onsubmit="return confirm('Are you sure you want to delete this player? This action cannot be undone and will remove all player statistics.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger">
                        <i class="fas fa-trash me-1"></i>Delete Player
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
// Auto-generate slug from name
document.getElementById('name').addEventListener('input', function() {
    const name = this.value;
    const slugField = document.getElementById('slug');
    
    // Only auto-generate if slug field is empty or matches the old pattern
    const originalSlug = '{{ $player->slug }}';
    if (!slugField.value || slugField.value === originalSlug) {
        const slug = name
            .toLowerCase()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/^-+|-+$/g, '');
        slugField.value = slug;
    }
});
</script>
@endsection