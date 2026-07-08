<!DOCTYPE html>
    <html lang="id">
        <head>
        <meta charset="UTF-8">

        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title><?= $title ?></title>

        <link rel="stylesheet"
        href="<?= base_url('assets/adminlte/dist/css/adminlte.css') ?>">

        <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

        </head>

<body class="login-page bg-body-secondary">

<div class="login-box">
    <div class="card card-outline card-primary">
        <div class="card-header text-center">
            <h3>Smart Absensi</h3>
        </div>
    <div class="card-body">
    <form id="formLogin">
        <div class="mb-3">
            <input type="text" name="login" class="form-control"  placeholder="Username atau Email">
        </div>

    <div class="mb-3">
        <input type="password" name="password" class="form-control" placeholder="Password">
    </div>

            <button class="btn btn-primary w-100"> Masuk </button>

    </form>

        </div>

    </div>

</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$('#formLogin').submit(function(e){
e.preventDefault();
    $.ajax({
        url:"<?= site_url('login')?>",
        method:"POST",
        data:$(this).serialize(),
        dataType:"json",

    success:function(res){

    if(res.status){
        Swal.fire({
        icon:'success',
        title:'Berhasil',
        text:res.message,
        timer:1200,
        showConfirmButton:false
    });

setTimeout(function(){

        window.location="<?= site_url('dashboard')?>";

    },1200);

    }else{

    Swal.fire({
        icon:'error',
        title:'Login Gagal',
        text:res.message
    });

    }

    }

});

});
</script>

</body>

</html>