@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        <h5>Edit Department</h5>
    </div>
    <div class="card-body">
        <form id="deptForm">
            @csrf
            @method('PUT')
            <input type="hidden" name="department_id" value="{{ $department->id }}">

            <!-- Department Name -->
            <div class="mb-3">
                <label>Department Name</label>
                <input type="text" name="name" class="form-control" value="{{ $department->name }}">
                <span class="text-danger small error-name"></span>
            </div>

            <!-- Department Students -->
            <h6>Students</h6>
            <div id="studentsRows">
                @foreach($department->students ?? [] as $i => $student)
                <div class="row mb-2 student-row">
                    <div class="col-4">
                        <input type="text" name="students[{{ $i }}][name]" class="form-control" placeholder="Student Name" value="{{ $student->name }}">
                        <span class="text-danger small error-students-name-{{ $i }}"></span>
                    </div>
                    <div class="col-4">
                        <input type="text" name="students[{{ $i }}][degree]" class="form-control" placeholder="Degree" value="{{ $student->degree }}">
                        <span class="text-danger small error-students-degree-{{ $i }}"></span>
                    </div>
                    <div class="col-3">
                        <input type="date" name="students[{{ $i }}][birthdate]" class="form-control" value="{{ $student->birthdate }}">
                        <span class="text-danger small error-students-birthdate-{{ $i }}"></span>
                    </div>
                    <div class="col-1">
                        <button type="button" class="btn btn-danger remove-row" data-id="{{ $student->id }}">-</button>
                    </div>
                </div>
                @endforeach
                @if(count($department->students ?? [])==0)
                <div class="row mb-2 student-row">
                    <div class="col-4">
                        <input type="text" name="students[0][name]" class="form-control" placeholder="Student Name">
                        <span class="text-danger small error-student-name-0"></span>
                    </div>
                    <div class="col-4">
                        <input type="text" name="students[0][degree]" class="form-control" placeholder="Degree">
                        <span class="text-danger small error-student-degree-0"></span>
                    </div>
                    <div class="col-3">
                        <input type="date" name="students[0][birthdate]" class="form-control">
                        <span class="text-danger small error-student-birthdate-0"></span>
                    </div>
                    <div class="col-1">
                        <button type="button" class="btn1 btn-danger remove-row" data-id="{{ $student->id ?? 0 }}">-</button>
                    </div>
                </div>
                @endif
            </div>

            <button type="button" class="btn btn-primary btn-sm mt-2" id="addStudentRow">+ Add More</button>
            <button type="submit" class="btn btn-success w-100 mt-3">Update Department</button>
        </form>
    </div>
</div>

<script>
let studentIndex = {{ count($department->students ?? [0]) }};

// Add more student row
$('#addStudentRow').click(function(){
    let row = `
    <div class="row mb-2 student-row">
        <div class="col-4">
            <input type="text" name="students[${studentIndex}][name]" class="form-control" placeholder="Student Name">
            <span class="text-danger small error-students-${studentIndex}-name"></span>
        </div>
        <div class="col-4">
            <input type="text" name="students[${studentIndex}][degree]" class="form-control" placeholder="Degree">
            <span class="text-danger small error-students-${studentIndex}-degree"></span>
        </div>
        <div class="col-3">
            <input type="date" name="students[${studentIndex}][birthdate]" class="form-control">
            <span class="text-danger small error-students-${studentIndex}-birthdate"></span>
        </div>
        <div class="col-1">
            <button type="button" class="btn btn-danger remove-row">-</button>
        </div>
    </div>
    `;
    $('#studentsRows').append(row);
    studentIndex++;
});

// Remove row
// $(document).on('click', '.remove-row', function(){
//     $(this).closest('.student-row').remove();
// });

// Submit form via AJAX
$('#deptForm').on('submit', function(e){
    e.preventDefault();
    $('.text-danger').text('');
    $.ajax({
        url: "{{ route('departments.update', $department->id) }}",
        method: 'POST',
        data: new FormData(this),
        processData: false,
        contentType: false,
        success: function(res){
            alert('Department updated successfully');
            window.location.href = "{{ route('departments.index') }}";
        },
        error: function(err){
            if(err.status===422){
                let errors = err.responseJSON.errors;
                $.each(errors, function(key, val){
                    let selector = '.error-' + key.replace(/\./g,'-');
                    $(selector).text(val[0]);

                });
            }
        }
    });
});

$(document).on('click', '.remove-row', function(){
    let btn = $(this);
    let studentId = btn.data('id'); // Only for existing students

    if(studentId){
        if(!confirm('Are you sure you want to delete this student?')) return;

        $.ajax({
            url: '/department/student/' + studentId,
            method: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function(res){
                if(res.success){
                    btn.closest('.student-row').remove();
                }
            }
        });
    } else {
        // New row, just remove from form
        btn.closest('.student-row').remove();
    }
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

</script>
@endsection