@extends('admin.layout')

@section('title', 'Teams Management')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-users me-2"></i>
        Teams Management
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.teams.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>Add New Team
        </a>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">All Teams</h5>
            </div>
            <div class="card-body">
                @if($teams->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Logo</th>
                                <th>Name</th>
                                <th>League</th>
                                <th>Founded</th>
                                <th>Stadium</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($teams as $team)
                            <tr>
                                <td>{{ $team->id }}</td>
                                <td>
                                    @if($team->logo_url)
                                        <img src="{{ $team->logo_url }}" alt="{{ $team->name }}" class="img-thumbnail" style="width: 40px; height: 40px;">
                                    @else
                                        <i class="fas fa-image text-muted"></i>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $team->name }}</strong>
                                    @if($team->slug)
                                        <small class="text-muted d-block">{{ $team->slug }}</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-primary">{{ $team->league->name }}</span>
                                    <small class="text-muted d-block">{{ $team->league->country }}</small>
                                </td>
                                <td>
                                    @if($team->founded)
                                        {{ $team->founded }}
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    @if($team->stadium)
                                        {{ $team->stadium }}
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('teams.show', $team->slug) }}" 
                                           class="btn btn-sm btn-outline-info" 
                                           target="_blank" 
                                           title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.teams.edit', $team) }}" 
                                           class="btn btn-sm btn-outline-warning" 
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.teams.delete', $team) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to delete this team?')">
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
                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No teams found</h5>
                    <p class="text-muted">Start by creating your first team.</p>
                    <a href="{{ route('admin.teams.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>Create Team
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection