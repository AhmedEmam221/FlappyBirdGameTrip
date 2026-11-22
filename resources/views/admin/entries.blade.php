@extends('admin._layout')

@section('title', 'Submissions Management')

@section('content')
<div class="w-full">
    
    <!-- Header -->
    <header class="mb-6">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold text-primary">Submissions Management</h1>
            <a href="#" class="btn btn-primary text-sm">Refresh</a>
        </div>
        
        <!-- Filters -->
        <div class="card p-4 mb-6">
            <form method="GET" class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm mb-2 text-muted">Status</label>
                    <select name="status" class="form-input w-full">
                        <option value="all">All Statuses</option>
                        <option value="pending_verification" {{ request('status') == 'pending_verification' ? 'selected' : '' }}>Pending Verification</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm mb-2 text-muted">Min Score</label>
                    <input type="number" name="min_score" class="form-input w-full" placeholder="Min score" value="{{ request('min_score') }}">
                </div>
                <div>
                    <label class="block text-sm mb-2 text-muted">Max Score</label>
                    <input type="number" name="max_score" class="form-input w-full" placeholder="Max score" value="{{ request('max_score') }}">
                </div>
                <div>
                    <label class="block text-sm mb-2 text-muted">Date From</label>
                    <input type="date" name="start_date" class="form-input w-full" value="{{ request('start_date') }}">
                </div>
                <div>
                    <label class="block text-sm mb-2 text-muted">Date To</label>
                    <input type="date" name="end_date" class="form-input w-full" value="{{ request('end_date') }}">
                </div>
                <div>
                    <label class="block text-sm mb-2 text-muted">Search Username</label>
                    <input type="text" name="search" class="form-input w-full" placeholder="Search Instagram username" value="{{ request('search') }}">
                </div>
                <button type="submit" class="btn btn-primary mt-4 w-full">Apply Filters</button>
            </form>
        </div>
    </header>
    
    <!-- Submissions Table -->
    <div class="card p-6">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr>
                        <th class="py-3 px-4 text-left text-xs font-semibold text-muted">#</th>
                        <th class="py-3 px-4 text-left text-xs font-semibold text-muted">Instagram</th>
                        <th class="py-3 px-4 text-left text-xs font-semibold text-muted">Bus No.</th>
                        <th class="py-3 px-4 text-left text-xs font-semibold text-muted">Submitted Score</th>
                        <th class="py-3 px-4 text-left text-xs font-semibold text-muted">Best Score</th>
                        <th class="py-3 px-4 text-left text-xs font-semibold text-muted">Submitted</th>
                        <th class="py-3 px-4 text-left text-xs font-semibold text-muted">Status</th>
                        <th class="py-3 px-4 text-left text-xs font-semibold text-muted">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($submissions as $index => $submission)
                    <tr class="border-b transition-colors hover:bg-gray-50">
                        <td class="py-3 px-4">{{ $index + 1 }}</td>
                        <td class="py-3 px-4"><kbd class="font-mono text-sm">{{ $submission->player->instagram_username }}</kbd></td>
                        <td class="py-3 px-4"><span class="badge badge-warning">{{ $submission->bus_number ?? '—' }}</span></td>
                        <td class="py-3 px-4"><span class="font-semibold">{{ $submission->submitted_score }}</span></td>
                        <td class="py-3 px-4">{{ $submission->best_score ?? '-' }}</td>
                        <td class="py-3 px-4"><span class="text-sm text-muted">{{ $submission->submitted_at->format('M d, Y') }}</span></td>
                        <td class="py-3 px-4">
                            <span class="badge badge-{{ $submission->verification_status }}">
                                {{ ucfirst($submission->verification_status) }}
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            <form method="POST" action="{{ route('admin.entries.approve', $submission->id) }}" style="display: inline;">
                                @csrf
                                @method('POST')
                                <button type="submit" class="text-primary text-sm hover:underline">Approve</button>
                            </form>
                            <form method="POST" action="{{ route('admin.entries.reject', $submission->id) }}" style="display: inline; margin-left: 8px;">
                                @csrf
                                @method('POST')
                                <button type="submit" class="text-danger text-sm hover:underline">Reject</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        {{ $submissions->links() }}
    </div>
</div>