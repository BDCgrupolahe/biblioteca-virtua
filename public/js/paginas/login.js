$(function () {
  $("#btn-login").click(function () {
    let form = $("#form-login");
    if (form[0].checkValidity() === false) {
      event.preventDefault();
      event.stopPropagation();
    } else {
      $.ajax({
        type: "POST",
        url: servidor + "login/acceso",
        dataType: "json",
        data: form.serialize(),
        beforeSend: function () {
          // setting a timeout
          $("#loading").addClass("loading");
        },
        success: function (data) {
          Swal.fire({
            icon: data.estatus,
            title: data.titulo,
            text: data.respuesta,
            showConfirmButton: false,
            timer: 2000,
          });
          if (data.estatus == "success") {
            setTimeout(() => {
              location.reload();
            }, 2000);
          }
        },
        error: function (data) {
          console.log(data);
        },
        complete: function () {
          $("#loading").removeClass("loading");
        },
      });
    }
    form.addClass("was-validated");
  });

  $(function () {
    $(".btn-save-usuarios").on("click", function () {
      let form = $("#" + $(this).data("formulario"));
      let tipo_form = $(this).data("tipo");
      let url =
        tipo_form == "nuevo" ? "guardarRegistro" : "actualizarDocumento";

      if (form[0].checkValidity() === false) {
        event.preventDefault();
        event.stopPropagation();
      } else {
        $.ajax({
          type: "POST",
          url: servidor + "login/" + url,
          dataType: "json",
          data: new FormData(form.get(0)),
          contentType: false,
          cache: false,
          processData: false,
          beforeSend: function () {
            // setting a timeout
            $("#loading").addClass("loading");
          },
          success: function (data) {
            console.log(data);
            Swal.fire({
              position: "top-end",
              icon: data.estatus,
              title: data.titulo,
              text: data.respuesta,
              showConfirmButton: false,
              timer: 2000,
            });
            setTimeout(() => {
              location.reload();
            }, 2000);
          },
          error: function (data) {
            console.log("Error ajax");
            console.log(data);
            // console.log(data.log);
          },
          complete: function () {
            $("#loading").removeClass("loading");
          },
        });
      }
      form.addClass("was-validated");
    });
  });
});
