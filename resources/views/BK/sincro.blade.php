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
        }
    </style>   
</x-BK.head>

<x-BK.body>

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
                <div class="col-md-6">
                    <div class="text-center">
                        <a href="https://integrador.beltforge.com/bk/actives">
                            <img src="{{URL::asset('images/icon-monday.png')}}" class="img-fluid">
                        </a>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="text-center">
                        <a href="https://integrador.beltforge.com/bk/employees">
                            <img src="{{URL::asset('images/icon-buk.png')}}">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</x-BK.body>