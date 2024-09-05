<x-BK.head>
    <style>
        /* Estilo personalizado para centrar el formulario */
        .centered-form {
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>   
</x-BK.head>

<x-BK.body>

<div class="container centered-form">
    <div class="col-md-6">
        <h2 class="text-center">Checklist de Opciones</h2>
        <form action="{{ route('sincro') }}" method="post">
            @csrf

            @foreach ($employees as $id=>$name)
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="{{$id}}" id="opcion{{$loop->iteration}}" name="opciones[]">
                <label class="form-check-label" for="opcion1">
                    &nbsp;{{$name}}
                </label>
            </div>
            @endforeach
            <!-- Botón de envío -->
            <button type="submit" class="btn mt-3" id="submitBtn">Enviar</button>
        </form>
    </div>
</div>

</x-BK.body>