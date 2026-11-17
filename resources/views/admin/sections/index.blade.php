@extends('layouts.admin')

@section('title', 'Festival Sections')

@section('content')
<div class="container-fluid px-lg-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h2 class="h4 fw-bold mb-1">Festival Sections</h2>
            <p class="text-muted small mb-0">Manage major festival competition sections (e.g., Bakalooriyya & Sanaviyya Ulya).</p>
        </div>
        <button type="button" class="btn btn-teal text-white shadow-sm" style="background-color: #0d9488;" data-bs-toggle="modal" data-bs-target="#createSectionModal">
            <i class="bi bi-plus-circle me-1"></i> Add New Section
        </button>
    </div>

    <div class="row g-4">
        @forelse($sections as $sec)
            <div class="col-md-6 col-xl-4">
                <div class="card admin-card h-100 shadow-sm border-0">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <span class="badge px-3 py-2 fs-6 rounded-pill text-white" style="background-color: #0d9488;">
                                <i class="bi bi-layers-fill me-1"></i> {{ $sec->code }}
                            </span>
                            @if($sec->status)
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1">Active</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary border rounded-pill px-3 py-1">Inactive</span>
                            @endif
                        </div>

                        <h4 class="fw-bold mb-2 text-dark">{{ $sec->name }}</h4>
                        <p class="text-muted small mb-4">
                            {{ $sec->description ?: 'No detailed description provided for this section.' }}
                        </p>

                        <div class="d-flex gap-4 p-3 bg-light rounded-3 mb-4">
                            <div class="d-flex align-items-center gap-2">
                                <div class="bg-primary text-white rounded p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                    <i class="bi bi-people-fill"></i>
                                </div>
                                <div>
                                    <div class="fs-5 fw-bold text-dark">{{ $sec->groups_count }}</div>
                                    <div class="text-muted small">Groups</div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <div class="bg-warning text-dark rounded p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                    <i class="bi bi-calendar-event-fill"></i>
                                </div>
                                <div>
                                    <div class="fs-5 fw-bold text-dark">{{ $sec->programs_count }}</div>
                                    <div class="text-muted small">Programs</div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#editSectionModal{{ $sec->id }}">
                                <i class="bi bi-pencil me-1"></i> Edit Details
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Modal -->
            <div class="modal fade" id="editSectionModal{{ $sec->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <form action="{{ route('admin.sections.update', $sec) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-content border-0 shadow">
                            <div class="modal-header">
                                <h5 class="modal-title fw-bold">Edit Section: {{ $sec->name }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Section Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" value="{{ $sec->name }}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Section Code <span class="text-danger">*</span></label>
                                    <input type="text" name="code" class="form-control" value="{{ $sec->code }}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Description</label>
                                    <textarea name="description" class="form-control" rows="3">{{ $sec->description }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Status</label>
                                    <select name="status" class="form-select">
                                        <option value="1" {{ $sec->status ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ ! $sec->status ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary px-4">Save Changes</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card admin-card p-5 text-center text-muted">
                    <i class="bi bi-layers display-4 mb-3 text-secondary"></i>
                    <h5>No Sections Found</h5>
                    <p class="small">Click "Add New Section" above to create competition sections.</p>
                </div>
            </div>
        @endforelse
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createSectionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('admin.sections.store') }}" method="POST">
            @csrf
            <div class="modal-content border-0 shadow">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Create New Festival Section</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Section Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Bakalooriyya" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Section Code <span class="text-danger">*</span></label>
                        <input type="text" name="code" class="form-control" placeholder="e.g. BAK" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Brief note about participants or eligibility..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-teal text-white px-4" style="background-color: #0d9488;">Create Section</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
