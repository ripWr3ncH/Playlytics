@extends('admin.layout')

@section('title', 'Create Player')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-user-plus me-2"></i>
        Create New Player
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
                <form action="{{ route('admin.players.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Player Name <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name') }}" 
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
                                       value="{{ old('slug') }}"
                                       placeholder="Auto-generated if left empty">
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
                                                {{ old('team_id') == $team->id ? 'selected' : '' }}>
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
                                    <option value="Goalkeeper" {{ old('position') == 'Goalkeeper' ? 'selected' : '' }}>Goalkeeper</option>
                                    <option value="Defender" {{ old('position') == 'Defender' ? 'selected' : '' }}>Defender</option>
                                    <option value="Midfielder" {{ old('position') == 'Midfielder' ? 'selected' : '' }}>Midfielder</option>
                                    <option value="Forward" {{ old('position') == 'Forward' ? 'selected' : '' }}>Forward</option>
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
                                       value="{{ old('age') }}" 
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
                                       value="{{ old('nationality') }}"
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
                            <i class="fas fa-save me-1"></i>Create Player
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
                    <li><strong>Player Name:</strong> Full name of the player</li>
                    <li><strong>Team:</strong> The team this player belongs to</li>
                </ul>
                
                <h6 class="mt-3">Optional Fields</h6>
                <ul class="small">
                    <li><strong>Slug:</strong> Auto-generated from name if empty</li>
                    <li><strong>Position:</strong> Player's primary position</li>
                    <li><strong>Age:</strong> Current age of the player</li>
                    <li><strong>Nationality:</strong> Player's nationality</li>
                </ul>
                
                <div class="alert alert-info small mt-3">
                    <i class="fas fa-lightbulb me-1"></i>
                    The slug will be automatically generated from the player's name if you leave it empty.
                </div>
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
    
    // Only auto-generate if slug field is empty
    if (!slugField.value) {
        const slug = name
            .toLowerCase()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/^-+|-+$/g, '');
        slugField.value = slug;
    }
});
</script>
@endsection