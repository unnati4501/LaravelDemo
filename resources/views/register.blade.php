<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Register</h4>
                </div>
                <div class="card-body">
                    <div id="success-message" class="alert alert-success d-none"></div>
                    <div id="error-messages" class="alert alert-danger d-none"></div>

                    <form id="registerForm">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" placeholder="Enter your name">
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="Enter your email">
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Enter password">
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm password">
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Register</button>
                    </form>
                    <div class="mt-3 text-center">
                        Already have an account? <a href="{{ route('login.form') }}">Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
    $.ajaxSetup({ headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')} });

    $('#registerForm').on('submit', function(e){
        e.preventDefault();
        $('#error-messages').addClass('d-none').html('');
        $('#success-message').addClass('d-none').html('');

        $.ajax({
            url: "{{ route('register') }}",
            method: "POST",
            data: $(this).serialize(),
            success: function(response){
                $('#success-message').removeClass('d-none').html(response.success);
                $('#registerForm')[0].reset();
            },
            error: function(xhr){
                let errors = xhr.responseJSON.errors;
                let errorHtml = '<ul class="mb-0">';
                $.each(errors, function(key, value){
                    errorHtml += '<li>'+value[0]+'</li>';
                });
                errorHtml += '</ul>';
                $('#error-messages').removeClass('d-none').html(errorHtml);
            }
        });
    });
});
</script>
</body>
</html>
