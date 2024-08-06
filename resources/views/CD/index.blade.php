<x-CD.head>
  <style>
    .table {
        display: flex;
        flex-direction: column;
        width: 200px; /* Ajusta el ancho según tus necesidades */
        border-collapse: collapse;
        margin-left:35px;
        padding-right: 35px;
    }
    .hour {
        display: flex;
    }
    .cell {
        flex: 1;
        border-bottom: 1px solid rgb(184, 184, 184);
        padding: 8px;
        text-align: left;
    }
    .cell:last-child .cell {
        border-bottom: none; /* Eliminar el borde inferior de la última fila */
    }
    .time{
      font-size: 12px;
      position: relative;
      top:18px;
      right:55px;
      text-align: right;
      width: 40px;
      display: flow;
    }
</style>
</x-CD.head>
<x-CD.body>


<div class="row">
  <!-- Columna Izquierda -->
  <div class="col-md-6">
      <div class="row">
          <div class="col-12">
              <div class="p-3 border bg-light">
              <div class="calendar">
                <div class="month-year">
                  <span class="month">{{__($calendar['month'])}}</span>
                  <span class="year">{{__($calendar['year'])}}</span>
                </div>
                <div class="days">
                  @foreach ($calendar['dayLabels'] as $weekday)
                  <span class="day-label">{{__($weekday)}}</span>
                  @endforeach
                  @foreach ($calendar['days'] as $day)
                  <span class="day {{$day['class']}}"><span class="content">{{$day['day']}}</span></span>
                  @endforeach
                </div>
              </div> <!-- calendar -->
            </div>
                      </div>
      </div>
      <div class="row">
          <div class="col-12">
              <div class="p-3 border bg-light">
                @if (Auth::check()) 
                {{ Auth::user()->name }}
                @else
                <a href="{{ route('reserva.redirect') }}" class="btn btn-primary"> Login with Google </a>
                @endif
              </div>
          </div>
      </div>
  </div>
  <!-- Columna Derecha -->
  <div class="col-md-6">
      <div class="p-3 border bg-light">
        <span>{{$calendar['dow']}}, {{$calendar['month']}} {{$calendar['day']}}</span>
        <div class="table">
          @foreach (['AM','PM'] as $m)
          @for ($h=1;$h<12;$h++)
          <div class="hour">
            <div class="cell" id="{{$h}}-{{$m}}"><span class="time">{{$h}} {{$m}}</span></div>
          </div>
          @endfor
            @if ($m=='AM')
           <div class="cell"><span class="time">12 M</span></div>
           @endif
          @endforeach
        </div>
      </div>
  </div>
</div>
</x-CD.body>
