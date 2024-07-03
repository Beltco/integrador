<?php // Init values
  $i=1; 
  $dealers=array('texto40','texto2','text','texto2__1','dup__of_proveedor__','texto9__1','text6','texto1__1','text3','texto7__1','text2','texto0__1');
  $fields=array('texto8','texto0','n_meros7','texto4','n_meros0','estado'); 
?>
<x-MT.head>
    <style>
        h1{
            font-size: 45px !important;
            text-align: center;
            font-weight: bolder !important;
        }
        .espacio{
            padding-bottom:15px;
        }
        .data{
            padding-top: 25px;
            font-size: 25px;
            width: 80%;
            margin: 0 auto !important;
        }
        @media only screen and (max-width: 500px) {
    .data {
        width: 100%;
    }
  }
    </style>
</x-MT.head>  
<x-MT.body>
    <header style="padding:15px">
        <img class="mx-auto" src="{{asset('/images/banner-materioteca.png')}}">
    </header>

    <div class="row">
      <div class="column" style="width:100%">
        <div class="carousel">
            <div class="carousel-inner">
                @foreach ($data['archivo9']['value'] as $img)
                <div class="carousel-item active">
                    <img src="{{$img}}" alt="Image {{$i++}}">
                </div>
                @endforeach
            </div>
            <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
            <a class="next" onclick="plusSlides(1)">&#10095;</a>
        </div>
        <h1>{{$data['name']['value']}}</h1>
        <script src="{{asset('/js/app.js')}}"></script>
      </div>
    </div>
    <div class="row data">
        <div class="col-sm">
            <p><b>Consecutivo:</b> {{$data['n_meros__1']['value']}}</p>
            <p class="espacio"><b>Yéminus:</b> {{$data['texto__1']['value']}}</p>
            <p><b>Proveedores</b></p>
            @for ($i = 0; $i < count($dealers); $i+=2)
                <p style="padding-top: 5px;"><a href="{{$data[$dealers[$i+1]]['value']}}"><img src="{{asset('/images/webpage.png')}}" style="width:30px;float: inline-start;">&nbsp;{{$data[$dealers[$i]]['value']}}</a></p>
            @endfor
            <p>&nbsp;</p>
        </div>
        <div class="col-sm">
            @foreach ($fields as $field)
                <p class="espacio"><b>{{$data[$field]['title']}}:</b><br />{{$data[$field]['value'].(strcmp($field,'n_meros7')==0?" ".$data['texto0']['value']:"")}}</p>
            @endforeach
        </div>
    </div>
    <div class="row data">
        <div class="col-sm">
            <p class="espacio"><b>{{$data['pdf_files']['title']}}</b></p>
            @foreach ($data['pdf_files']['value'] as $url)
            <a href="{{$url}}" target="_blank"><img src="{{asset('images/pdf.png')}}" style="width: 100px" /></a>
            @endforeach
        </div>
    </div>

    <div style="display: none">  {{print_r($data)}} </div>        
</x-MT.body>

