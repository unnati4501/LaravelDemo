<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    
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
                    <h4 class="mb-0">Login</h4>
                </div>
                <div class="card-body">
                    <div id="success-message" class="alert alert-success d-none"></div>
                    <div id="error-messages" class="alert alert-danger d-none"></div>

                    <form id="loginForm">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="Enter your email">
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Enter password">
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Login</button>
                    </form>
                    <div class="mt-3 text-center">
                        Don't have an account? <a href="{{ route('register.form') }}">Register</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
    
    $.ajaxSetup({ headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')} });

    $('#loginForm').on('submit', function(e){
        e.preventDefault();
        $('#error-messages').addClass('d-none').html('');
        $('#success-message').addClass('d-none').html('');

        $.ajax({
            url: "{{ route('login') }}",
            method: "POST",
            data: $(this).serialize(),
            success: function(response){
                window.location.href = "{{ route('dashboard') }}";
            },
            error: function(xhr){
                let errors = xhr.responseJSON.errors;
                if(errors){
                    let errorHtml = '<ul class="mb-0">';
                    $.each(errors, function(key, value){
                        errorHtml += '<li>'+value[0]+'</li>';
                    });
                    errorHtml += '</ul>';
                    $('#error-messages').removeClass('d-none').html(errorHtml);
                } else {
                    $('#error-messages').removeClass('d-none').html('<p>Invalid credentials</p>');
                }
            }
        });
    });
});
</script>
</body>
</html>
