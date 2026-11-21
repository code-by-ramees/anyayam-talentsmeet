@extends('layouts.admin')

@section('title', 'Participating Groups')

@section('content')
    <div class="container-fluid px-lg-4">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
            <div>
                <h2 class="h4 fw-bold mb-1">Participating Groups</h2>
                <p class="text-muted small mb-0">Create, manage, and configure login accounts for all festival groups.</p>
            </div>
            <a href="{{ route('admin.groups.create') }}" class="btn btn-teal text-white shadow-sm"
                style="background-color: #9b111e;">
                <i class="bi bi-person-plus-fill me-1"></i> Add New Group
            </a>
        </div>

        <div class="card admin-card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Group / House</th>
                                <th>Portal Login</th>
                                <th class="text-center">Total Members</th>
                                <th class="text-center">Registration</th>
                                <th class="text-center">Status</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($groups as $grp)
                                <tr>
                                    <td class="ps-4 py-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="rounded-3 shadow-sm d-flex align-items-center justify-content-center text-white fw-bold"
                                                style="width: 44px; height: 44px; background-color: {{ $grp->color ?: '#9b111e' }};">
                                                {{ substr($grp->code, 0, 3) }}
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark fs-6">{{ $grp->name }}</div>
                                                <span class="badge bg-light text-secondary border">Code: {{ $grp->code }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($grp->user)
                                            <div class="font-monospace small text-dark fw-bold">
                                                <i class="bi bi-person-fill text-muted me-1"></i>{{ $grp->user->username }}
                                            </div>
                                        @else
                                            <span class="text-muted small">No login created</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.members.index', ['group_id' => $grp->id]) }}"
                                            class="badge bg-secondary-subtle text-secondary border px-3 py-2 text-decoration-none">
                                            <i class="bi bi-people me-1"></i> {{ $grp->members_count }} Members
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        @if($grp->allow_member_registration)
                                            <span
                                                class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1">Allowed</span>
                                        @else
                                            <span
                                                class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-2 py-1">Locked</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <form action="{{ route('admin.groups.toggle-status', $grp) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm p-0 border-0"
                                                title="Click to toggle status">
                                                @if($grp->status === 'active')
                                                    <span
                                                        class="badge bg-success rounded-pill px-3 py-1 cursor-pointer">Active</span>
                                                @else
                                                    <span
                                                        class="badge bg-danger rounded-pill px-3 py-1 cursor-pointer">Inactive</span>
                                                @endif
                                            </button>
                                        </form>
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="d-flex justify-content-end gap-2">
                                            <button type="button" class="btn btn-sm btn-outline-warning rounded-pill px-2"
                                                data-bs-toggle="modal" data-bs-target="#resetPassModal{{ $grp->id }}"
                                                title="Change Group Password">
                                                <i class="bi bi-key-fill"></i>
                                            </button>
                                            <a href="{{ route('admin.groups.edit', $grp) }}"
                                                class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                                <i class="bi bi-pencil me-1"></i> Edit
                                            </a>
                                            <form action="{{ route('admin.groups.destroy', $grp) }}" method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete {{ $grp->name }}? This will also remove the group login.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>

                                        <!-- Reset Password Modal -->
                                        <div class="modal fade text-start" id="resetPassModal{{ $grp->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <form action="{{ route('admin.groups.reset-password', $grp) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-content border-0 shadow">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title fw-bold">
                                                                <i class="bi bi-key-fill text-warning me-2"></i>Change Password for {{ $grp->name }}
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body p-4">
                                                            <div class="mb-3 p-3 bg-light rounded-3 small">
                                                                <div><strong>Group Code:</strong> {{ $grp->code }}</div>
                                                                <div><strong>Username:</strong> {{ $grp->user->username ?? 'No User' }}</div>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label fw-semibold">New Password <span class="text-danger">*</span></label>
                                                                <input type="text" name="password" class="form-control" placeholder="Enter new password (min. 6 characters)" required minlength="6">
                                                                <small class="text-muted">Directly resets the portal login password for this group.</small>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-teal text-white px-4" style="background-color: #9b111e;">
                                                                <i class="bi bi-check-lg me-1"></i> Save Password
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="bi bi-people display-4 mb-3 d-block text-secondary"></i>
                                        <h5>No Groups Found</h5>
                                        <p class="small">Click "Add New Group" above to register participating groups.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection