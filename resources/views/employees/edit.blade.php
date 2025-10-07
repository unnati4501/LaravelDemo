@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Edit Employee</h5>
    </div>
    <div class="card-body">
        <form id="employeeEditForm" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label>Name</label>
                <input type="text" name="name" class="form-control" value="{{ $employee->name }}">
                <span class="text-danger small error-name"></span>
            </div>

            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="{{ $employee->email }}">
                <span class="text-danger small error-email"></span>
            </div>

            <div class="mb-3">
                <label>Phone</label>
                <input type="text" name="phone" class="form-control" value="{{ $employee->phone }}">
                <span class="text-danger small error-phone"></span>
            </div>

            <div class="mb-3">
                <label>Department</label>
                <select name="department_id" class="form-select">
                    <option value="">Select Department</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" 
                            {{ $employee->department_id == $dept->id ? 'selected' : '' }}>
                            {{ $dept->name }}
                        </option>
                    @endforeach
                </select>
                <span class="text-danger small error-department_id"></span>
            </div>

            <div class="mb-3">
                <label>Photo</label>
                <input type="file" name="photo" class="form-control">
                @if($employee->photo)
                    <img src="{{ asset('storage/'.$employee->photo) }}" width="80" class="mt-2 rounded">
                @endif
                <span class="text-danger small error-photo"></span>
            </div>

            <button class="btn btn-success w-100">Update Employee</button>
        </form>
    </div>
</div>

<script>
$('#employeeEditForm').on('submit', function(e){
    e.preventDefault();
    $('.text-danger').text('');
    
    let formData = new FormData(this);
    
    $.ajax({
        url: "{{ route('employees.update', $employee->id) }}",
        method: 'POST', // we include _method=PUT in form
        data: formData,
        processData: false,
        contentType: false,
        success: function(res){
            alert(res.message);
            window.location.href = "{{ route('employees.index') }}"; // redirect to list
        },
        error: function(err){
            if(err.status === 422){
                let errors = err.responseJSON.errors;
                $.each(errors, function(key, val){
                    $('.error-' + key).text(val[0]);
                });
            }
        }
    });
});
</script>
@endsection
