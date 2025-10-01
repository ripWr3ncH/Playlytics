@extends('admin.layout')

@section('title', 'Edit League')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-edit me-2"></i>
        Edit League: {{ $league->name }}
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.leagues') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>Back to Leagues
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">League Information</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.leagues.update', $league) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">League Name <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('name') is-invalid @enderror" 
                               id="name" 
                               name="name" 
                               value="{{ old('name', $league->name) }}" 
                               required
                               placeholder="e.g., Premier League">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="country" class="form-label">Country <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('country') is-invalid @enderror" 
                                       id="country" 
                                       name="country" 
                                       value="{{ old('country', $league->country) }}" 
                                       required
                                       placeholder="e.g., England">
                                @error('country')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="season" class="form-label">Season <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('season') is-invalid @enderror" 
                                       id="season" 
                                       name="season" 
                                       value="{{ old('season', $league->season) }}" 
                                       required
                                       placeholder="e.g., 2024-25">
                                @error('season')
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
                               value="{{ old('logo_url', $league->logo_url) }}"
                               placeholder="https://example.com/logo.png">
                        @error('logo_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Optional: URL to the league logo image</div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.leagues') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-1"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Update League
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
                    League Details
                </h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <strong>Created:</strong> {{ $league->created_at->format('M d, Y') }}
                    </li>
                    <li class="mb-2">
                        <strong>Updated:</strong> {{ $league->updated_at->format('M d, Y') }}
                    </li>
                    <li class="mb-2">
                        <strong>Slug:</strong> {{ $league->slug }}
                    </li>
                    <li class="mb-2">
                        <strong>Teams:</strong> {{ $league->teams->count() }}
                    </li>
                </ul>
                
                @if($league->logo_url)
                <div class="mt-3">
                    <strong>Current Logo:</strong>
                    <div class="mt-2">
                        <img src="{{ $league->logo_url }}" alt="{{ $league->name }}" class="img-thumbnail" style="max-width: 100px;">
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection