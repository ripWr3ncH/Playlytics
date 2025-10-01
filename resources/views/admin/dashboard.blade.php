@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-tachometer-alt me-2"></i>
        Dashboard
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <button type="button" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-download me-1"></i>Export
            </button>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card stats-card border-left-primary h-100">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Leagues
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['leagues'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-trophy fa-2x text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card stats-card border-left-success h-100">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Total Teams
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['teams'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card stats-card border-left-info h-100">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Total Players
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['players'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-alt fa-2x text-info"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card stats-card border-left-warning h-100">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Total Matches
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['matches'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-futbol fa-2x text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-plus-circle me-2"></i>
                    Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <a href="{{ route('admin.leagues.create') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-trophy me-2 text-primary"></i>
                        Add New League
                    </a>
                    <a href="{{ route('admin.teams.create') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-users me-2 text-success"></i>
                        Add New Team
                    </a>
                    <a href="{{ route('admin.players.create') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-user-alt me-2 text-info"></i>
                        Add New Player
                    </a>
                    <a href="{{ route('admin.matches.create') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-futbol me-2 text-warning"></i>
                        Schedule Match
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-database me-2"></i>
                    Database Overview
                </h5>
            </div>
            <div class="card-body">
                <p class="card-text">
                    Welcome to the Playlytics Admin Panel! This system allows you to manage your football database with full CRUD operations.
                </p>
                <ul class="list-unstyled">
                    <li><i class="fas fa-check text-success me-2"></i>MySQL Database Integration</li>
                    <li><i class="fas fa-check text-success me-2"></i>SQL Query Logging</li>
                    <li><i class="fas fa-check text-success me-2"></i>Relationship Management</li>
                    <li><i class="fas fa-check text-success me-2"></i>Data Validation & Security</li>
                </ul>
                <a href="{{ route('home') }}" class="btn btn-outline-info" target="_blank">
                    <i class="fas fa-external-link-alt me-1"></i>
                    View Frontend
                </a>
            </div>
        </div>
    </div>
</div>

@endsection