@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
  <div class="card-header bg-primary text-white d-flex justify-content-between">
    <h5>Employee Management</h5>
    <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">+ Add Employee</button>
  </div>
  <div class="card-body">
    <form class="row g-3 mb-3" method="GET">
      <div class="col-md-4">
        <input type="text" name="search" value="{{ $search }}" placeholder="Search employee..." class="form-control">
      </div>
      <div class="col-md-4">
        <select name="department_id" class="form-select">
          <option value="">All Departments</option>
          @foreach ($departments as $dept)
            <option value="{{ $dept->id }}" {{ $dept->id == $departmentId ? 'selected' : '' }}>{{ $dept->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-2">
        <button class="btn btn-secondary w-100">Filter</button>
      </div>
    </form>

    <table class="table table-bordered table-hover align-middle">
      <thead class="table-light">
        <tr><th>#</th><th>Photo</th><th>Name</th><th>Email</th><th>Department</th><th>Action</th></tr>
      </thead>
      <tbody>
        @foreach ($employees as $emp)
          <tr>
            <td>{{ $loop->iteration }}</td>
            <td><img src="{{ asset('storage/'.$emp->photo) }}" width="50" height="50" class="rounded-circle"></td>
            <td>{{ $emp->name }}</td>
            <td>{{ $emp->email }}</td>
            <td>{{ $emp->department->name }}</td>
            <td>
                <!-- Edit button links to edit page -->
                <a href="{{ route('employees.edit', $emp->id) }}" class="btn btn-sm btn-primary">
                    <i class="fa fa-edit"></i> Edit
                </a>

                <!-- Delete button remains AJAX -->
                <button class="btn btn-sm btn-danger delete-btn" data-id="{{ $emp->id }}">
                    <i class="fa fa-trash"></i>
                </button>
            </td>

          </tr>
        @endforeach
      </tbody>
    </table>
    {{ $employees->links() }}
  </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addEmployeeModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="addEmployeeForm" enctype="multipart/form-data">
        @csrf
        <div class="modal-header">
          <h5>Add Employee</h5>
          <button class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="text" name="name" placeholder="Name" class="form-control mb-2">
          <input type="email" name="email" placeholder="Email" class="form-control mb-2">
          <input type="text" name="phone" placeholder="Phone" class="form-control mb-2">
          <select name="department_id" class="form-select mb-2">
            <option value="">Select Department</option>
            @foreach ($departments as $dept)
              <option value="{{ $dept->id }}">{{ $dept->name }}</option>
            @endforeach
          </select>
          <input type="file" name="photo" class="form-control mb-2">
        </div>
        <div class="modal-footer">
          <button class="btn btn-primary w-100">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
$('#addEmployeeForm').on('submit', function(e){
  e.preventDefault();
  let formData = new FormData(this);
  $.ajax({
    url: "{{ route('employees.store') }}",
    method: "POST",
    data: formData,
    processData: false,
    contentType: false,
    success: function(res){
      if(res.success){
        location.reload();
      }
    }
  });
});

$('.delete-btn').on('click', function(){
  if(!confirm('Delete this employee?')) return;
  let id = $(this).data('id');
  $.ajax({
    url: "/employees/" + id,
    method: "DELETE",
    data: {_token: "{{ csrf_token() }}"},
    success: function(res){
      if(res.success){
        location.reload();
      }
    }
  });
});
</script>
@endsection
