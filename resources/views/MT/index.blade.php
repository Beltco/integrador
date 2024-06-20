<x-MT.head>
  <style>
  .box{
    text-align:center;
    min-width: 320px;
    max-width: 350px;
    border:1px solid black; 
    height:430px;
    float:right;
    padding: 23% 0;
  }
  img{
    float:left;
  }
  .error{
    margin-top: 5px;
    font-weight: bold;
    background-color: red;
    color: white;
  }
  @media only screen and (max-width: 500px) {
    .box, img {
        float:unset ;
        height: unset;
    }
    .box{
      padding:20px 0;
    }
  }
  </style>
  <script type="text/javascript">
    function callURL()
    {
      var code = document.getElementById("code").value;

      if (code.trim() === ""){
        alert( '<?= __("You must enter a valid item code") ?>' )
        return false;
      }
      else
        window.location.href='<?= URL::to('/') ?>'+'/materioteca/'+code;
    }
  </script>
</x-MT.head>

<x-MT.body>
  <div class="mx-auto" style="max-width: 750px;padding:15px" >
    <img src="{{URL::asset('images/materioteca.png')}}" style="margin-bottom:10px" alt="Materioteca Belt">
    <div class="box">
        {{ __('Enter the Item Code') }}<br>
        <input type="number" id="code" style="margin-bottom:10px"/><br>
        <x-primary-button id="send" type="button" onclick="callURL()">{{__('Search')}}</x-primary-button><br>
        @if (isset($error))
          @if ($error)
            <p class="error">{{__('Error! Code not found')}}</p>
          @endif
        @endif
    </div>
  </div>
</x-MT.body>
