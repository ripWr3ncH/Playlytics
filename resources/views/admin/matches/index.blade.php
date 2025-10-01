@extends('admin.layout')

@section('title', 'Matches Management')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-futbol me-2"></i>
        Matches Management
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.matches.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>Add New Match
        </a>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">All Matches</h5>
            </div>
            <div class="card-body">
                @if($matches->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Match</th>
                                <th>League</th>
                                <th>Date</th>
                                <th>Score</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($matches as $match)
                            <tr>
                                <td>{{ $match->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="text-center me-2">
                                            <div class="fw-bold">{{ $match->homeTeam->name }}</div>
                                            <small class="text-muted">vs</small>
                                            <div class="fw-bold">{{ $match->awayTeam->name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-primary">{{ $match->league->name }}</span>
                                </td>
                                <td>
                                    @if($match->match_date)
                                        <div>{{ $match->match_date->format('M d, Y') }}</div>
                                        <small class="text-muted">{{ $match->match_date->format('H:i') }}</small>
                                    @else
                                        <span class="text-muted">TBD</span>
                                    @endif
                                </td>
                                <td>
                                    @if($match->status === 'finished')
                                        <div class="fw-bold">
                                            <span class="badge bg-success me-1">{{ $match->home_score ?? 0 }}</span>
                                            <span class="text-muted">-</span>
                                            <span class="badge bg-success ms-1">{{ $match->away_score ?? 0 }}</span>
                                        </div>
                                    @elseif($match->status === 'live')
                                        <div class="fw-bold text-danger">
                                            <span class="badge bg-danger me-1">{{ $match->home_score ?? 0 }}</span>
                                            <span class="text-muted">-</span>
                                            <span class="badge bg-danger ms-1">{{ $match->away_score ?? 0 }}</span>
                                            <small class="d-block">LIVE</small>
                                        </div>
                                    @else
                                        <span class="text-muted">- : -</span>
                                    @endif
                                </td>
                                <td>
                                    @if($match->status === 'scheduled')
                                        <span class="badge bg-secondary">Scheduled</span>
                                    @elseif($match->status === 'live')
                                        <span class="badge bg-danger">Live</span>
                                    @elseif($match->status === 'finished')
                                        <span class="badge bg-success">Finished</span>
                                    @elseif($match->status === 'postponed')
                                        <span class="badge bg-warning">Postponed</span>
                                    @elseif($match->status === 'cancelled')
                                        <span class="badge bg-dark">Cancelled</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('matches.show', $match->id) }}" 
                                           class="btn btn-sm btn-outline-info" 
                                           target="_blank" 
                                           title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.matches.edit', $match) }}" 
                                           class="btn btn-sm btn-outline-warning" 
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.matches.delete', $match) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to delete this match?')">
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
                    <i class="fas fa-futbol fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No matches found</h5>
                    <p class="text-muted">Start by creating your first match.</p>
                    <a href="{{ route('admin.matches.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>Create Match
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection