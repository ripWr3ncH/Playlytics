@extends('admin.layout')

@section('title', 'Leagues Management')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-trophy me-2"></i>
        Leagues Management
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.leagues.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>Add New League
        </a>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">All Leagues</h5>
            </div>
            <div class="card-body">
                @if($leagues->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Country</th>
                                <th>Season</th>
                                <th>Teams Count</th>
                                <th>Logo</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($leagues as $league)
                            <tr>
                                <td>{{ $league->id }}</td>
                                <td>
                                    <strong>{{ $league->name }}</strong>
                                    @if($league->slug)
                                        <small class="text-muted d-block">{{ $league->slug }}</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ $league->country }}</span>
                                </td>
                                <td>{{ $league->season }}</td>
                                <td>
                                    <span class="badge bg-info">{{ $league->teams->count() }} teams</span>
                                </td>
                                <td>
                                    @if($league->logo_url)
                                        <img src="{{ $league->logo_url }}" alt="{{ $league->name }}" class="img-thumbnail" style="width: 40px; height: 40px;">
                                    @else
                                        <i class="fas fa-image text-muted"></i>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('leagues.show', $league->slug) }}" 
                                           class="btn btn-sm btn-outline-info" 
                                           target="_blank" 
                                           title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.leagues.edit', $league) }}" 
                                           class="btn btn-sm btn-outline-warning" 
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.leagues.delete', $league) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to delete this league?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-outline-danger" 
                                                    title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-4">
                    <i class="fas fa-trophy fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No leagues found</h5>
                    <p class="text-muted">Start by creating your first league.</p>
                    <a href="{{ route('admin.leagues.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>Create League
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection