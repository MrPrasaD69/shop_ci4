<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<style>
    body{
        background: #ffffff;
    }

    .main-card{
        border: none;
        border-radius: 15px;
    }

    .main-card .card-body{
        padding: 2rem;
    }

    .form-control{
        border-radius: 10px;
    }
</style>
<div class="container">
    <div class="row text-center">
        <h1>Welcome to Shop App</h1>
    </div>
    <div class="row justify-content-center align-items-center">
        <div class="col-md-4">

            <div class="card shadow main-card">
                <div class="card-body">

                </div>
            </div>

        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        var loginBtn = $("#loginBtn");

        $('#loginForm').on('submit',function(e){
            e.preventDefault();

            let formData = $(this).serialize();

            $.ajax({
                url: $(this).attr('action'),
                type: "POST",
                data: formData,
                beforeSend:function(){
                    loginBtn.prop('disabled',true).html('Logging in...');
                },
                success: function(response){
                    loginBtn.prop('disabled',false).html('Login');

                    if(response.status){
                        window.location.href = response.redirect;
                    }
                    else{
                        $('#loginError').text(response.message);
                    }

                },
                error: function(xhr){
                    loginBtn.prop('disabled',false).html('Login');

                    if(xhr.responseJSON && xhr.responseJSON.errors){
                        let errors = xhr.responseJSON.errors;
                        let firstError = Object.values(errors)[0][0];
                        $('#loginError').text(firstError);
                    }
                    else{
                        $('#loginError').text("Something went wrong.");
                    }

                }
            });

        });
    });
</script>
<?= $this->endSection() ?>