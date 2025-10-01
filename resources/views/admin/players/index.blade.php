@extends('admin.layout')

@section('title', 'Players Management')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-user-alt me-2"></i>
        Players Management
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.players.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>Add New Player
        </a>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">All Players</h5>
            </div>
            <div class="card-body">
                @if($players->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Team</th>
                                <th>Position</th>
                                <th>Age</th>
                                <th>Nationality</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($players as $player)
                            <tr>
                                <td>{{ $player->id }}</td>
                                <td>
                                    <strong>{{ $player->name }}</strong>
                                    @if($player->slug)
                                        <small class="text-muted d-block">{{ $player->slug }}</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-primary">{{ $player->team->name }}</span>
                                    <small class="text-muted d-block">{{ $player->team->league->name }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $player->position }}</span>
                                </td>
                                <td>
                                    @if($player->age)
                                        {{ $player->age }} years
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    @if($player->nationality)
                                        {{ $player->nationality }}
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('players.show', $player->slug) }}" 
                                           class="btn btn-sm btn-outline-info" 
                                           target="_blank" 
                                           title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.players.edit', $player) }}" 
                                           class="btn btn-sm btn-outline-warning" 
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.players.delete', $player) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to delete this player?')">
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
                    <i class="fas fa-user-alt fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No players found</h5>
                    <p class="text-muted">Start by creating your first player.</p>
                    <a href="{{ route('admin.players.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>Create Player
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection