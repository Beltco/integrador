<x-BK.head>
    <style>
        /* Estilo personalizado para centrar el formulario */
        .centered-form {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        h2{
            padding: 2rem 0 3rem 0;
            font-size: 1.5rem !important;
        }
        #submitBtn{
            cursor: pointer;
            margin-bottom: 1rem;
            background-color: #007bff !important;
        }
        #submitBtn:hover {
            background-color: #1d2b3a !important; 
        }
        #overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: none;
            z-index: 9999;
        }
        #overlay-content {
            color: white;
            font-size: 2rem;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center !important;
        }
        .icons{
            width: 50px;
        }
    </style>   
</x-BK.head>

<x-BK.body>
    <div id="overlay">
        <div id="overlay-content">
            Procesando<img style="margin: 0 auto;" src="{{URL::asset('images/favicon_90.gif')}}">Por favor espere... 
        </div>
    </div>
<div class="container centered-form">
    <div class="col-md-6">
        <h2 class="text-center">Crear nuevos de BUK en Monday>Actives</h2>
        <form action="{{ route('createMD') }}" method="post">
            @csrf

            @foreach ($employees as $id=>$name)
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="{{$id}}" id="opcion{{$loop->iteration}}" name="opciones[]">
                <label class="form-check-label" for="opcion{{$loop->iteration}}">
                    &nbsp;{{$name}}
                </label>
            </div>
            @endforeach
            <!-- Botón de envío -->
            <button type="submit" class="btn btn-primary mt-3" id="submitBtn">Crear en Monday</button>
        </form>
        <div class="container mt-4">
            <div class="row">
                <div class="col-md-2">
                    <div class="text-center">
                        <a href="{{route('employees')}}" onclick="document.getElementById('overlay').style.display='block'">
                            <img class="icons" src="{{URL::asset('images/icon-buk.png')}}">
                        </a>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="text-center">
                        <a href="{{route('actives')}}">
                            <img class="icons" src="{{URL::asset('images/icon-monday.png')}}" onclick="document.getElementById('overlay').style.display='block'">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Obtener el botón y la div de overlay
    const submitBtn = document.getElementById('submitBtn');
    const overlay = document.getElementById('overlay');

    // Añadir evento de clic al botón
    submitBtn.addEventListener('click', function() {
        // Mostrar la div de overlay cuando se haga clic
        overlay.style.display = 'block';
        
        // Aquí puedes añadir lógica para procesar tu formulario si es necesario.
    });
</script>

</x-BK.body>