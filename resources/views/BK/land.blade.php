<x-BK.head>
    <style>
        h2{
            padding: 2rem 0 3rem 0;
            font-size: 1.5rem !important;
        }
        h3{
            font-weight: bold !important;
        }
        #submitBtn{
            cursor: pointer;
            margin-bottom: 1rem;
            background-color: #007bff !important;
        }
        #submitBtn:hover {
            background-color: #1d2b3a !important; 
        }
    </style>   
</x-BK.head>

<x-BK.body>
<div class="container centered-form">
    <div class="col-md-6">
        <h2 class="text-center">Crear nuevos de BUK en Monday>Actives</h2>
        <h3>{{$msg}}</h3>
<br />
        <p><a class="btn" id="submitBtn" href="{{$url}}">Regresar</a></p>

    </div>
</div>

</x-BK.body>