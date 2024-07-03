<?php // Init values
  $dealers=array('texto40','texto2','text','texto2__1','dup__of_proveedor__','texto9__1','text6','texto1__1','text3','texto7__1','text2','texto0__1');
  $order=array('texto0','n_meros7','n_meros0','estado__1','estado','texto4'); 
  $pdfs=count($data['pdf_files']['value']);
?>
<x-MT.head>
    <style>
        h1{
            font-size: 20px !important;
            text-align: center;
            font-weight: bolder !important;
            margin: 20px 0 0 0 !important; 
        }
        .espacio{
            padding:8px 0;
        }
        .data{
            padding-top: 25px;
            font-size: 16px;
            width: 80%;
            margin: 0 auto !important;
        }
        header{
            font-weight: bold;
            text-align: center;
            margin:40px 0 -105px 0;
            padding-top: 5px;
            height: 210px;
            background-color:  #efefef; 
        }
        @media only screen and (max-width: 500px) {
            .data {
                width: 100%;
            }
        }
    </style>
</x-MT.head>  
<x-MT.body>
    <div class="row">
      <div class="column" style="width:100%">
        <div class="carousel">
            <div class="carousel-inner">
                @foreach ($data['archivo9']['value'] as $i=>$img)
                <div class="carousel-item active">
                    <img src="{{$img}}" alt="Image {{$i+1}}">
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
            @foreach ($order as $i=>$field)
            <div class="row espacio" {{($i>(count($order)-3)?'style=background-color:#efefef':'')}}>
              <div class="col-8">{{$data[$field]['title']}}:</div>
              <div class="col-4">{{$data[$field]['value']}}</div>
            </div>
            @endforeach
        </div>
        <div class="col-sm">
            <div class="row" style="background-color: #efefef; margin-top:15px">
                <div class="col"><p style="padding:15px 0;"><b>PROVEEDORES</b></p></div>
            </div>
            <div class="row espacio">    
                <div class="col">
                @for ($i = 0; $i < count($dealers); $i+=2)
                <a href="{{$data[$dealers[$i+1]]['value']}}" class="row espacio">
                    <div class="col-8">
                        <li>{{$data[$dealers[$i]]['value']}} </li>
                    </div>
                    <div class="col-4">
                        <img src="{{asset('/images/webpage.png')}}" style="width:30px;">
                    </div>
                </a>
                @endfor
                 </div>
            </div>
        </div>
    </div>
    <div class="row" style="background-color: #efefef; margin-top:15px">
        <div class="col"><p style="padding:15px 0;"><b>{{$data['pdf_files']['title']}}</b></p></div>
    </div>
    <div class="row espacio">
        @if ($pdfs==0)
        <div class="col-12">
            <img src="{{asset('images/nopdf.png')}}" alt="Sin ficha técnica" title="No hay ficha técnica" style="width: 100px" />
        </div>
        @else
        @foreach ($data['pdf_files']['value'] as $url)
        <div class="col-{{floor(12/$pdfs)}}">
            <a href="{{$url}}" target="_blank"><img src="{{asset('images/pdf.png')}}" title="Ficha técnica" style="width: {{($pdfs>3?floor(400/$pdfs):100)}}px" /></a>
        </div>
        @endforeach
        @endif
    </div>

    <div class="row" style="background-color: #efefef; margin-top:15px">
        <div class="col"><p style="padding:10px 0; text-align:right;"><b>CODIGO DE BARRAS<br>GÉMINUS</b></p></div>
    </div>

    <div class="row">
        <div class="col" style="text-align: right"><p class="espacio"><b></b> {{$data['texto__1']['value']}}</p></div>
    </div>    

    <p style="display:none"><b>Consecutivo:</b> {{$data['n_meros__1']['value']}}</p>
    <div style="display: none">  {{print_r($data)}} </div>        
</x-MT.body>

