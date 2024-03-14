<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>{{ $title }}</title>
</head>
<style>

    .container div {
        margin-bottom: 3%;
        position: relative;
    }
    .center {
        text-align: center;
        font-size: 12px;

    }
    .center-item {
        margin-left: 7.5%;
        float: left;
    }

    .center-text {
        margin: 2% auto;
        text-align: center;
        font-size: 12px;

    }

    .center p {
        margin: 0px 5px;
    }


    .infos {
        margin: 15% 0;
        border: 1px solid black;
        padding: 5px 12px;
        font-size: 15px;
    }

    .small {
        font-size: 12px;
    }

    .automatic {
        position: fixed;
        bottom: 0px;
    }

    .span-emphasis {
        font-weight: bolder;
        font-style: italic;
    }

    .big-table {
        font-size: 15px;
        position: relative;
        width: 100%;
    }


    th {
        border-top:  1px solid black;
        border-left:  1px solid black;
        border-bottom: 1px solid black;
        border-right:  1px solid black;
        padding: 5px;
        background-color: #ccc;
    }


    td {
        border-left:  1px solid black;
        border-bottom: 1px solid black;
        border-right:  1px solid black;
        padding: 5px;
    }

    .resume {
        font-size: 13px;
        padding: 20px 80px;

    }

    .color {
        font-weight: bolder;
    }

    .result {
        padding: 1px 3px;
        border-radius: 3px;
        border: 1px solid black;
        background-color: none;
        font-weight: bolder
    }

    .caption {
        font-weight: bolder;
        border: 1px solid;
        border-radius: 15px;
        padding: 0px 2px;

    }
    .box-clean {
        border: 1px solid;
        padding:6%;
    }
    .normal-table {
        width: 100%;

        border-collapse: collapse;

    }

</style>
<body>
<div class="container">
    <div class="center">
        <img src="img/logo-adpar.jpg" class="center-item" width="70">
        <h3 class="center-item" >Igreja Evangélica Assembleia de Deus em Parnamirim/RN <br> <span style="font-size: 12px; font-weight: lighter">Departamento de Escola Bíblica Dominical </span></h3>
        <img class="center-item" src="img/logo_ebd.jpg" width="70">
    </div>
    <h3>{{ $title }}</h3>
    @yield('content')

    <p class="small">Documento gerado automaticamente em <span class="span-emphasis">{{date('d/m/Y')}}</span> às <span class="span-emphasis">{{date('H:i:s')}}</span>, pelo sistema de administração <span class="span-emphasis">EBDControl</span></p>
</div>
</body>
</html>
