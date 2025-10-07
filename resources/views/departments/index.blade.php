@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-success text-white d-flex justify-content-between">
        <h5>Departments</h5>
        <button class="btn btn-light btn-sm open-multi-dept">
            <i class="fa fa-plus"></i> Add Departments
        </button>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Department Name</th>
                    <th>Employees Count</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($departments as $dept)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $dept->name }}</td>
                    <td>{{ $dept->employees_count ?? 0 }}</td>
                    <td>
                        <a href="{{ route('departments.edit', $dept->id) }}" class="btn btn-sm btn-primary">
                            Edit
                        </a>
                        <button class="btn btn-sm btn-danger delete-dept" data-id="{{ $dept->id }}">
                            Delete
                        </button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center">No departments</td></tr>
                @endforelse
            </tbody>
        </table>
        {{ $departments->links() }}
    </div>
</div>

<!-- Multiple Add Modal -->
<div class="modal fade" id="multiDeptModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="multiDeptForm">
                @csrf
                <div class="modal-header">
                    <h5>Add Multiple Departments</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="departmentRows">
                        <div class="row mb-2 dept-row">
                            <div class="col-10">
                                <input type="text" name="name[]" class="form-control" placeholder="Department Name">
                                <span class="text-danger small error-name-0"></span>
                            </div>
                            <div class="col-2">
                                <button type="button" class="btn btn-danger remove-row">-</button>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-primary btn-sm mt-2" id="addRow">+ Add More</button>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success w-100">Save All</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$('.open-multi-dept').click(function(){
    $('#multiDeptModal').modal('show');
    $('#departmentRows').html(`
        <div class="row mb-2 dept-row">
            <div class="col-10">
                <input type="text" name="name[]" class="form-control" placeholder="Department Name">
                <span class="text-danger small error-name-0"></span>
            </div>
            <div class="col-2">
                <button type="button" class="btn btn-danger remove-row">-</button>
            </div>
        </div>
    `);
});

// Add more row
let rowIndex = 1;
$('#addRow').click(function(){
    $('#departmentRows').append(`
        <div class="row mb-2 dept-row">
            <div class="col-10">
                <input type="text" name="name[]" class="form-control" placeholder="Department Name">
                <span class="text-danger small error-name-${rowIndex}"></span>
            </div>
            <div class="col-2">
                <button type="button" class="btn btn-danger remove-row">-</button>
            </div>
        </div>
    `);
    rowIndex++;
});

// Remove row
$(document).on('click', '.remove-row', function(){
    $(this).closest('.dept-row').remove();
});

$(document).on('click', '.delete-dept', function(){
    let deptId = $(this).data('id');

    if(!confirm('Are you sure you want to delete this department?')) return;

    $.ajax({
        url: '/departments/' + deptId,
        method: 'DELETE',
        data: { _token: '{{ csrf_token() }}' },
        success: function(res){
            if(res.success){
                alert('Department deleted successfully.');
                location.reload();
            }
        },
        error: function(){
            alert('Error deleting department.');
        }
    });
});


// Submit multiple departments via AJAX
$('#multiDeptForm').on('submit', function(e){
    e.preventDefault();
    $('.text-danger').text('');
    $.ajax({
        url: "{{ route('departments.store') }}",
        method: "POST",
        data: $(this).serialize(),
        success: function(res){
            if(res.success){ location.reload(); }
        },
        error: function(err){
            if(err.status === 422){
                let errors = err.responseJSON.errors;
                // Loop over errors and display per row
                Object.keys(errors).forEach(function(k){
                    let matches = k.match(/name\.(\d+)/);
                    if(matches){
                        $('.error-name-' + matches[1]).text(errors[k][0]);
                    }
                });
            }
        }
    });
});
</script>
@endsection
