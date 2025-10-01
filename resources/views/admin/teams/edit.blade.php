@extends('admin.layout')

@section('title', 'Edit Team')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-edit me-2"></i>
        Edit Team: {{ $team->name }}
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.teams') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>Back to Teams
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Team Information</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.teams.update', $team) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Team Name <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('name') is-invalid @enderror" 
                               id="name" 
                               name="name" 
                               value="{{ old('name', $team->name) }}" 
                               required
                               placeholder="e.g., Manchester United">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="league_id" class="form-label">League <span class="text-danger">*</span></label>
                        <select class="form-select @error('league_id') is-invalid @enderror" 
                                id="league_id" 
                                name="league_id" 
                                required>
                            <option value="">Select a league</option>
                            @foreach($leagues as $league)
                                <option value="{{ $league->id }}" 
                                        {{ old('league_id', $team->league_id) == $league->id ? 'selected' : '' }}>
                                    {{ $league->name }} ({{ $league->country }})
                                </option>
                            @endforeach
                        </select>
                        @error('league_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="founded" class="form-label">Founded Year</label>
                                <input type="number" 
                                       class="form-control @error('founded') is-invalid @enderror" 
                                       id="founded" 
                                       name="founded" 
                                       value="{{ old('founded', $team->founded) }}" 
                                       min="1800" 
                                       max="{{ date('Y') }}"
                                       placeholder="e.g., 1878">
                                @error('founded')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="stadium" class="form-label">Stadium</label>
                                <input type="text" 
                                       class="form-control @error('stadium') is-invalid @enderror" 
                                       id="stadium" 
                                       name="stadium" 
                                       value="{{ old('stadium', $team->stadium) }}"
                                       placeholder="e.g., Old Trafford">
                                @error('stadium')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="logo_url" class="form-label">Logo URL</label>
                        <input type="url" 
                               class="form-control @error('logo_url') is-invalid @enderror" 
                               id="logo_url" 
                               name="logo_url" 
                               value="{{ old('logo_url', $team->logo_url) }}"
                               placeholder="https://example.com/logo.png">
                        @error('logo_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Optional: URL to the team logo image</div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.teams') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-1"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Update Team
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Team Details
                </h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <strong>Created:</strong> {{ $team->created_at->format('M d, Y') }}
                    </li>
                    <li class="mb-2">
                        <strong>Updated:</strong> {{ $team->updated_at->format('M d, Y') }}
                    </li>
                    <li class="mb-2">
                        <strong>Slug:</strong> {{ $team->slug }}
                    </li>
                    <li class="mb-2">
                        <strong>Current League:</strong> {{ $team->league->name }}
                    </li>
                </ul>
                
                @if($team->logo_url)
                <div class="mt-3">
                    <strong>Current Logo:</strong>
                    <div class="mt-2">
                        <img src="{{ $team->logo_url }}" alt="{{ $team->name }}" class="img-thumbnail" style="max-width: 100px;">
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection