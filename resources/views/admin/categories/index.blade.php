@extends('layouts.admin')

@section('title', 'Competition Categories & Position Marks')

@section('content')
<div class="container-fluid px-lg-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h2 class="h4 fw-bold mb-1">Program Categories & Position Marks</h2>
            <p class="text-muted small mb-0">Configure point allocation rules for 1st, 2nd, and 3rd prizes per category.</p>
        </div>
        <button type="button" class="btn btn-teal text-white shadow-sm" style="background-color: #0d9488;" data-bs-toggle="modal" data-bs-target="#createCategoryModal">
            <i class="bi bi-plus-circle me-1"></i> Add New Category
        </button>
    </div>

    <!-- Alert / Explanation Box -->
    <div class="alert alert-info border-0 shadow-sm rounded-3 d-flex align-items-center gap-3 mb-4">
        <i class="bi bi-calculator-fill fs-3 text-info"></i>
        <div class="small">
            <strong>Automated Mark Calculation:</strong> When entering competition results, the system automatically awards points based on the program's category (e.g. Category A = 10 / 7 / 5 pts, Category B = 15 / 10 / 5 pts). You can customize these points per category at any time.
        </div>
    </div>

    <div class="row g-4">
        @forelse($categories as $cat)
            <div class="col-md-6 col-xl-3">
                <div class="card admin-card h-100 border-0 shadow-sm">
                    <div class="card-body p-4 d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="badge bg-teal text-white px-3 py-1 fs-6 rounded-pill" style="background-color: #0d9488;">
                                {{ $cat->code }}
                            </span>
                            <span class="badge bg-light text-secondary border rounded-pill">
                                {{ $cat->programs_count }} Programs
                            </span>
                        </div>

                        <h5 class="fw-bold text-dark mt-2 mb-1">{{ $cat->name }}</h5>
                        <p class="text-muted small mb-4 flex-grow-1">
                            {{ $cat->description ?: 'Configured point weightings for this category.' }}
                        </p>

                        <!-- Marks Breakdown -->
                        <div class="bg-light p-3 rounded-3 mb-3">
                            <div class="d-flex justify-content-between align-items-center py-1 border-bottom">
                                <span class="small fw-semibold text-warning-emphasis">
                                    <i class="bi bi-trophy-fill text-warning me-1"></i> 1st Prize:
                                </span>
                                <span class="badge bg-warning text-dark fs-6">{{ $cat->first_marks }} pts</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center py-1 border-bottom">
                                <span class="small fw-semibold text-secondary">
                                    <i class="bi bi-award-fill text-secondary me-1"></i> 2nd Prize:
                                </span>
                                <span class="badge bg-secondary fs-6">{{ $cat->second_marks }} pts</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center py-1">
                                <span class="small fw-semibold text-bronze" style="color: #cd7f32;">
                                    <i class="bi bi-award text-bronze me-1"></i> 3rd Prize:
                                </span>
                                <span class="badge fs-6 text-white" style="background-color: #cd7f32;">{{ $cat->third_marks }} pts</span>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-auto">
                            <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#editCategoryModal{{ $cat->id }}">
                                <i class="bi bi-pencil me-1"></i> Edit Marks
                            </button>
                            @if($cat->programs_count === 0)
                                <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST" onsubmit="return confirm('Delete category {{ $cat->name }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-2">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Category Modal -->
            <div class="modal fade" id="editCategoryModal{{ $cat->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <form action="{{ route('admin.categories.update', $cat) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-content border-0 shadow">
                            <div class="modal-header">
                                <h5 class="modal-title fw-bold">Edit Category: {{ $cat->name }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Category Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" value="{{ $cat->name }}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Category Code <span class="text-danger">*</span></label>
                                    <input type="text" name="code" class="form-control" value="{{ $cat->code }}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Description</label>
                                    <textarea name="description" class="form-control" rows="2">{{ $cat->description }}</textarea>
                                </div>

                                <h6 class="fw-bold mt-4 mb-2 text-dark">Position Marks Configuration</h6>
                                <div class="row g-2">
                                    <div class="col-4">
                                        <label class="form-label small fw-semibold text-warning-emphasis">1st Prize Marks</label>
                                        <input type="number" name="first_marks" class="form-control" value="{{ $cat->first_marks }}" min="1" required>
                                    </div>
                                    <div class="col-4">
                                        <label class="form-label small fw-semibold text-secondary">2nd Prize Marks</label>
                                        <input type="number" name="second_marks" class="form-control" value="{{ $cat->second_marks }}" min="0" required>
                                    </div>
                                    <div class="col-4">
                                        <label class="form-label small fw-semibold text-bronze" style="color: #cd7f32;">3rd Prize Marks</label>
                                        <input type="number" name="third_marks" class="form-control" value="{{ $cat->third_marks }}" min="0" required>
                                    </div>
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
                    <i class="bi bi-tags display-4 mb-3 text-secondary"></i>
                    <h5>No Categories Found</h5>
                    <p class="small">Click "Add New Category" above to configure competition categories.</p>
                </div>
            </div>
        @endforelse
    </div>
</div>

<!-- Create Category Modal -->
<div class="modal fade" id="createCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf
            <div class="modal-content border-0 shadow">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Create New Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Category Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Category A" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Category Code <span class="text-danger">*</span></label>
                        <input type="text" name="code" class="form-control text-uppercase" placeholder="e.g. CAT-A" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea name="description" class="form-control" rows="2" placeholder="Brief note about the tier or level of programs..."></textarea>
                    </div>

                    <h6 class="fw-bold mt-4 mb-2 text-dark">Position Marks Configuration</h6>
                    <div class="row g-2">
                        <div class="col-4">
                            <label class="form-label small fw-semibold text-warning-emphasis">1st Prize</label>
                            <input type="number" name="first_marks" class="form-control" placeholder="e.g. 10" min="1" required>
                        </div>
                        <div class="col-4">
                            <label class="form-label small fw-semibold text-secondary">2nd Prize</label>
                            <input type="number" name="second_marks" class="form-control" placeholder="e.g. 7" min="0" required>
                        </div>
                        <div class="col-4">
                            <label class="form-label small fw-semibold text-bronze" style="color: #cd7f32;">3rd Prize</label>
                            <input type="number" name="third_marks" class="form-control" placeholder="e.g. 5" min="0" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-teal text-white px-4" style="background-color: #0d9488;">Create Category</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
