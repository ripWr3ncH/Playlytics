@extends('admin.layout')

@section('title', 'SQL Query Logs')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-database me-2"></i>
        SQL Query Logs
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="window.print()">
                <i class="fas fa-print me-1"></i>Print
            </button>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>
            <strong>Academic Purpose:</strong> This section demonstrates various SQL operations used in the Playlytics database system.
            These queries show JOIN operations, aggregations, subqueries, and data manipulation statements.
        </div>
    </div>
</div>

@foreach($sqlQueries as $index => $query)
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <span class="badge bg-primary me-2">{{ $index + 1 }}</span>
                    {{ $query['type'] }}
                </h5>
                <small class="text-muted">{{ $query['description'] }}</small>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-8">
                        <h6 class="text-primary">
                            <i class="fas fa-code me-1"></i>
                            SQL Query:
                        </h6>
                        <div class="bg-dark text-light p-3 rounded" style="font-family: 'Courier New', monospace;">
                            <pre class="mb-0 text-light"><code>{{ $query['query'] }}</code></pre>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <h6 class="text-success">
                            <i class="fas fa-lightbulb me-1"></i>
                            Educational Purpose:
                        </h6>
                        <p class="small text-muted mb-0">{{ $query['purpose'] }}</p>
                        
                        @switch($query['type'])
                            @case('SELECT with JOIN')
                                <div class="mt-3">
                                    <h6 class="small text-warning">Key Concepts:</h6>
                                    <ul class="small">
                                        <li>INNER JOIN between multiple tables</li>
                                        <li>Table aliases (m, ht, at, l)</li>
                                        <li>Foreign key relationships</li>
                                        <li>ORDER BY clause</li>
                                    </ul>
                                </div>
                                @break
                                
                            @case('SELECT with COUNT and GROUP BY')
                                <div class="mt-3">
                                    <h6 class="small text-warning">Key Concepts:</h6>
                                    <ul class="small">
                                        <li>LEFT JOIN (includes teams with 0 players)</li>
                                        <li>COUNT() aggregate function</li>
                                        <li>GROUP BY clause</li>
                                        <li>Data aggregation</li>
                                    </ul>
                                </div>
                                @break
                                
                            @case('SELECT with Subquery')
                                <div class="mt-3">
                                    <h6 class="small text-warning">Key Concepts:</h6>
                                    <ul class="small">
                                        <li>Correlated subqueries</li>
                                        <li>Subquery in SELECT clause</li>
                                        <li>Subquery in WHERE clause</li>
                                        <li>Performance considerations</li>
                                    </ul>
                                </div>
                                @break
                                
                            @case('INSERT with Foreign Key')
                                <div class="mt-3">
                                    <h6 class="small text-warning">Key Concepts:</h6>
                                    <ul class="small">
                                        <li>INSERT statement</li>
                                        <li>Foreign key constraints</li>
                                        <li>Prepared statements (?)</li>
                                        <li>Data integrity</li>
                                    </ul>
                                </div>
                                @break
                                
                            @case('UPDATE with JOIN')
                                <div class="mt-3">
                                    <h6 class="small text-warning">Key Concepts:</h6>
                                    <ul class="small">
                                        <li>UPDATE statement</li>
                                        <li>Conditional updates with WHERE</li>
                                        <li>Subquery in WHERE clause</li>
                                        <li>Data modification</li>
                                    </ul>
                                </div>
                                @break
                                
                            @case('DELETE with CASCADE consideration')
                                <div class="mt-3">
                                    <h6 class="small text-warning">Key Concepts:</h6>
                                    <ul class="small">
                                        <li>DELETE statement</li>
                                        <li>Foreign key constraints</li>
                                        <li>CASCADE operations</li>
                                        <li>Data consistency</li>
                                    </ul>
                                </div>
                                @break
                                
                            @case('Complex Analytics Query')
                                <div class="mt-3">
                                    <h6 class="small text-warning">Key Concepts:</h6>
                                    <ul class="small">
                                        <li>Multiple LEFT JOINs</li>
                                        <li>Multiple aggregate functions</li>
                                        <li>CASE statements</li>
                                        <li>ROUND() function</li>
                                        <li>DISTINCT keyword</li>
                                    </ul>
                                </div>
                                @break
                        @endswitch
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-graduation-cap me-2"></i>
                    Database Design Summary
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-primary">Entity Relationships:</h6>
                        <ul class="small">
                            <li><strong>Leagues → Teams:</strong> One-to-Many (1:N)</li>
                            <li><strong>Teams → Players:</strong> One-to-Many (1:N)</li>
                            <li><strong>Leagues → Matches:</strong> One-to-Many (1:N)</li>
                            <li><strong>Teams → Matches:</strong> Many-to-Many (M:N) as home/away teams</li>
                            <li><strong>Players → Player Stats:</strong> One-to-Many (1:N)</li>
                            <li><strong>Matches → Player Stats:</strong> One-to-Many (1:N)</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-primary">SQL Concepts Demonstrated:</h6>
                        <ul class="small">
                            <li>✅ INNER JOIN and LEFT JOIN</li>
                            <li>✅ Aggregate Functions (COUNT, AVG)</li>
                            <li>✅ GROUP BY and ORDER BY</li>
                            <li>✅ Subqueries (correlated and non-correlated)</li>
                            <li>✅ CASE statements</li>
                            <li>✅ Foreign Key Constraints</li>
                            <li>✅ CRUD Operations (Create, Read, Update, Delete)</li>
                            <li>✅ Data Validation and Integrity</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('styles')
<style>
.stats-card {
    transition: transform 0.2s;
}

.stats-card:hover {
    transform: translateY(-2px);
}

.border-left-primary {
    border-left: 4px solid #007bff;
}

.border-left-success {
    border-left: 4px solid #28a745;
}

.border-left-info {
    border-left: 4px solid #17a2b8;
}

.border-left-warning {
    border-left: 4px solid #ffc107;
}

pre code {
    font-size: 0.9rem;
    line-height: 1.4;
}

@media print {
    .btn-toolbar {
        display: none !important;
    }
}
</style>
@endsection