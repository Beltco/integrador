<body>
    <header>
        <x-BK.header/>
    </header>
    <div class="container"> 
    {{$slot}}
    </div>
  
    <x-MT.footer />
  
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>

    <!-- JavaScript para controlar el estado del botón -->
    <script>
      // Selecciona todos los checkboxes y el botón de envío
      const checkboxes = document.querySelectorAll('input[type="checkbox"]');
      const submitBtn = document.getElementById('submitBtn');
      submitBtn.disabled = true;

      // Función para verificar si algún checkbox está seleccionado
      function toggleSubmitButton() {
          const isChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);
          submitBtn.disabled = !isChecked; // Desactiva si ninguno está seleccionado
      }

      // Agrega un evento a cada checkbox para verificar el estado cuando se cambie
      checkboxes.forEach(checkbox => {
          checkbox.addEventListener('change', toggleSubmitButton);
      });
  </script>    
  </body>
  </html>