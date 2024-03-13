// ###############################
// #            TABLA            #
// #           FUNCION           #
// ###############################

async function tablaLibros() {
  try {
    let peticion = await fetch(servidor + `usuarios/libroview`);
    let response = await peticion.json();
    console.log("caja contenido:", response);
    $("#container-libros").empty();
    if (response.length == 0) {
      jQuery(`<h2>Sin libros asignados</h2>`)
        .appendTo("#container-libros")
        .addClass("text-danger text-center text-uppercase");
      return false;
    }
    jQuery(`<table class="table align-items-center mb-0 table table-striped table-bordered" style="width:100%" id="info-table-result">
                            <thead><tr>
                            <th class="text-uppercase">Titulo Libro</th><th class="text-uppercase">Editorial</th><th class="text-uppercase">Genero</th><th class="text-uppercase">Autor</th><th class="text-uppercase">Año</th><th class="text-uppercase">Acciones</th>
                            </tr></thead>
                            </table>`)
      .appendTo("#container-libros")
      .removeClass("text-danger");

    $("#info-table-result").DataTable({
      drawCallback: function (settings) {
        $(".paginate_button").addClass("btn").removeClass("paginate_button");
        $(".dataTables_length").addClass("pull-left");
        $("#info-table-result_filter").addClass("pull-right");
        $("input").addClass("form-control");
        $("select").addClass("form-control");
        $(".previous.disabled").addClass(
          "btn-outline-info opacity-5 btn-rounded mx-2 mt-3"
        );
        $(".next.disabled").addClass(
          "btn-outline-info opacity-5 btn-rounded mx-2 mt-3"
        );
        $(".previous").addClass("btn-outline-info btn-rounded mx-2 mt-3");
        $(".next").addClass("btn-outline-info btn-rounded mx-2 mt-3");
      },
      language: {
        url: "https://cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json",
      },
      pageLength: 5,
      lengthMenu: [
        [5, 10, -1],
        [5, 10, "All"],
      ],
      data: response,
      columns: [
        { data: "titulo_libro", className: "text-vertical text-center" },
        { data: "editorial_libro", className: "text-vertical text-center" },
        { data: "genero_libro", className: "text-vertical text-center" },
        { data: "autor_libro", className: "text-vertical text-center" },
        { data: "anio_libro", className: "text-vertical text-center" },
        {
          data: null,
          render: function (data, item) {
            const condicion = data.estatus_permiso;
            let botones = "";

            if (condicion == 1) {
              botones = `<div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 justify-content align-items-center">
                          <button data-id="${btoa(
                            btoa(data.id_libro)
                          )}"  data-bs-toggle="tooltip" title="Editar Libro" type="button" class="btn btn-info btn-edit-libro">
                          <i class="fa-solid fa-edit"></i>Editar</button>


                          <button data-nombre="${data.titulo_libro}"data-condicion="${data.estatus_permiso}"data-id="${btoa(btoa(data.id_libro))}" 
                          data-bs-toggle="tooltip" title="Desactivar libro" type="button" class="btn btn-success btn-delete-libro" 
                          disabled><i class="fa-solid"></i>Autorizado</button>
                          
                          
                          <a href="${servidor}pdfjs-4.0.379-dist/web/viewer.html?file=${
                data.archivo_old
              }" target="_blank" data-bs-toggle="tooltip"
                          title="Ver libro" type="button" class="btn btn-secondary visualizar-cartas"><i class="fa-solid fa fa-file-word-o"></i></a>
                          </div>`;
            } else {
              botones = `<div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 justify-content align-items-center">
                          <button data-id="${btoa(
                            btoa(data.id_libro)
                          )}"  data-bs-toggle="tooltip" title="Editar libro" type="button" class="btn btn-info btn-edit-libro">
                          <i class="fa-solid fa-edit"></i>Editar</button>


                          <button data-nombre="${data.titulo_libro}"data-condicion="${data.estatus_permiso}"data-id="${btoa(btoa(data.id_libro))}" 
                          data-bs-toggle="tooltip" title="Desactivar libro" type="button" class="btn btn-danger btn-delete-libro" 
                          disabled><i class="fa-solid"></i>En Revisión</button>
                          
                          
                          <a href="${servidor}pdfjs-4.0.379-dist/web/viewer.html?file=${
                data.archivo_old
              }" target="_blank" data-bs-toggle="tooltip"
                          title="Ver libro" type="button" class="btn btn-secondary visualizar-cartas"><i class="fa-solid fa fa-file-word-o"></i></a>
                          </div>`;
            }

            return botones;
          },
          className: "text-vertical text-center",
        },
      ],
    });
  } catch (error) {
    console.log(error);
  }
}
tablaLibros();

// ###############################
// #   INSERTAR Y ACTUALIZAR     #
// #         FUNCION             #
// ###############################

$(function () {
  $(".btn-save-libros").on("click", function () {
    let form = $("#" + $(this).data("formulario"));
    let tipo_form = $(this).data("tipo");
    let url = tipo_form == "nuevo" ? "guardarLibro" : "actualizarLibro";

    if (form[0].checkValidity() === false) {
      event.preventDefault();
      event.stopPropagation();
    } else {
      if (tipo_form == "nuevo") {
        let inputFile = form.find('input[type="file"]');
        let uploadedFile = inputFile.prop("files")[0];

        if (uploadedFile) {
          let fileExtension = uploadedFile.name.split(".").pop().toLowerCase();
          if (fileExtension !== "pdf") {
            Swal.fire({
              icon: "error",
              title: "Error",
              text: "Por favor, seleccione un archivo PDF.",
              timer: 2000,
              showConfirmButton: false,
              timer: 2000,
            });
            return;
          }
        }
      }

      $.ajax({
        type: "POST",
        url: servidor + "usuarios/" + url,
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
          Swal.fire({
            position: "center",
            icon: data.estatus,
            title: data.titulo,
            html: data.verificacion,
            text: data.respuesta,
            showClass: {
              popup: `
                animate__animated
                animate__fadeInUp
                animate__faster
              `,
            },
            hideClass: {
              popup: `
                animate__animated
                animate__fadeOutDown
                animate__faster
              `,
            },
            didClose: () => {
              // Este código se ejecutará cuando se cierre la alerta
              location.reload();
            },
          });
        },

        error: function (data) {
          console.log("Error ajax");
          console.log(data);
        },
        complete: function () {
          $("#loading").removeClass("loading");
        },
      });
    }
    form.addClass("was-validated");
  });

  // ##########################################
  // #    ESTADOS ACTIVADO Y DESACTIVADO      #
  // #               FUNCION                  #
  // ##########################################
  $(document).on("click", ".btn-delete-libro", function () {
    const condicion = $(this).data("condicion");
    if (condicion == 1) {
      Swal.fire({
        title: `Desea Desactivar el libro \n"${$(this).data("nombre")}"?`,
        text: `Estás a punto de desactivar este libro.`,
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#82d616",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, desactivar!",
        cancelButtonText: "No, cancelar",
      }).then((result) => {
        if (result.isConfirmed) {
          const id = $(this).data("id");
          const condicion = $(this).data("condicion");
          eliminarLibro(id, condicion);
        }
      });
    } else {
      Swal.fire({
        title: `Desea Activar el Libro \n"${$(this).data("nombre")}"?`,
        text: `Estás a punto de Activar este libro.`,
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#82d616",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, activar!",
        cancelButtonText: "No, cancelar",
      }).then((result) => {
        if (result.isConfirmed) {
          const id = $(this).data("id");
          const condicion = $(this).data("condicion");
          eliminarLibro(id, condicion);
        }
      });
    }
  });

  function eliminarLibro(id) {
    $.ajax({
      type: "POST",
      url: servidor + `usuarios/eliminarLibro/${id}`,
      dataType: "json",
      contentType: false,
      cache: false,
      processData: false,
      beforeSend: function () {
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
      },
      complete: function () {
        $("#loading").removeClass("loading");
      },
    });
  }

  // ###############################
  // #            EDITAR           #
  // #           FUNCION           #
  // ###############################

  $(document).on("click", ".btn-cancelar-libros", function () {
    $("#form-libros")[0].reset();
  });

  $(document).on("click", ".btn-buscar-libro", function () {
    buscarLibroPorNombre($("#titulo_libro").val());
  });

  $(document).on("click", ".btn-edit-libro", function () {
    $("#modalNuevoLibroLabel").text("Editar libro");
    $("#form-libros")[0].reset();
    $("#tipo").val("editar");
    editarLibro($(this).data("id"));
  });

  async function editarLibro(id) {
    let peticion = await fetch(servidor + `usuarios/buscarusuariosLibro/${id}`);
    let response = await peticion.json();
    $("#id_libro").val(response["id_libro"]);
    $("#modalNuevoLibro").modal("show");
    $("#titulo_libro").val(response["titulo_libro"]);
    $("#editorial_libro").val(response["editorial_libro"]);
    $("#anio_libro").val(response["anio_libro"]);
    $("#genero").val(response["fk_genero_libro"]);
    $("#autor").val(response["fk_id_autor"]);
    $("#archivo_old").val(response["archivo_old"]);
    $("#ruta").val(response["ruta_archivo"]);
    $("#nombreArchivo").val(response["nombre_archivo"]);
    $("#nombreImagen").val(response["nombre_imagen"]);
    $("#token").val(response["token"]);
  }

  // ###############################
  // #          CAT_GENERO         #
  // ###############################

  async function cat_genero(identificador) {
    try {
      $(identificador).empty();
      let peticion = await fetch(servidor + `usuarios/cat_genero`);
      let response = await peticion.json();
      console.log(response);
      let option_select = document.createElement("option");
      option_select.value = "";
      option_select.text = "Seleccionar genero...";
      let option_select2 = document.createElement("option");
      option_select2.value = "agregar_genero";
      option_select2.text = "Crear nuevo genero";
      $(identificador).append(option_select);
      $(identificador).append(option_select2);
      for (let item of response) {
        let option = document.createElement("option");
        option.value = item.id_genero;
        option.text = item.genero;
        $(identificador).append(option);
      }
      console.log("cargando generos ...");
    } catch (err) {
      if (err.name == "AbortError") {
      } else {
        throw err;
      }
    }
  }
  cat_genero("#genero");

  $("#genero").change(function () {
    if ($(this).val() == "agregar_genero") {
      $("#modalNuevoGenero").modal("show");
    } else {
      $("#modalNuevoGenero").modal("hide");
    }
  });

  $(".btn-save-generos").on("click", function () {
    let form = $("#" + $(this).data("formulario"));
    let tipo_form = $(this).data("tipo");
    let url = tipo_form == "nuevo" ? "guardarGenero" : "actualizarDocumento";
    if (form[0].checkValidity() === false) {
      event.preventDefault();
      event.stopPropagation();
    } else {
      $.ajax({
        type: "POST",
        url: servidor + "usuarios/" + url,
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
          cat_genero("#genero");
          $("#modalNuevoGenero").modal("hide");
          form[0].reset();
        },
        error: function (data) {
          console.log("Error ajax");
          console.log(data);
          /* console.log(data.log); */
        },
        complete: function () {
          $("#loading").removeClass("loading");
        },
      });
    }
    form.addClass("was-validated");
  });

  // ###############################
  // #        CAT_AUTORES          #
  // ###############################

  async function cat_autores(identificador) {
    try {
      $(identificador).empty();
      let peticion = await fetch(servidor + `usuarios/cat_autores`);
      let response = await peticion.json();
      console.log(response);
      let option_select = document.createElement("option");
      option_select.value = "";
      option_select.text = "Seleccionar autor...";
      let option_select2 = document.createElement("option");
      option_select2.value = "agregar_autor";
      option_select2.text = "Crear nuevo autor";
      $(identificador).append(option_select);
      $(identificador).append(option_select2);
      for (let item of response) {
        let option = document.createElement("option");
        option.value = item.id_autor;
        option.text = item.nombre_autor;
        $(identificador).append(option);
      }
      console.log("cargando autores ...");
    } catch (err) {
      if (err.name == "AbortError") {
      } else {
        throw err;
      }
    }
  }
  cat_autores("#autor");

  $("#autor").change(function () {
    if ($(this).val() == "agregar_autor") {
      $("#modalNuevoAutor").modal("show");
    } else {
      $("#modalNuevoAutor").modal("hide");
    }
  });

  $(".btn-save-autores").on("click", function () {
    let form = $("#" + $(this).data("formulario"));
    let tipo_form = $(this).data("tipo");
    let url = tipo_form == "nuevo" ? "guardarGenero" : "actualizarDocumento";
    if (form[0].checkValidity() === false) {
      event.preventDefault();
      event.stopPropagation();
    } else {
      $.ajax({
        type: "POST",
        url: servidor + "usuarios/" + url,
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
          cat_autores("#autor");
          $("#modalNuevoAutor").modal("hide");
          form[0].reset();
        },
        error: function (data) {
          console.log("Error ajax");
          console.log(data);
          /* console.log(data.log); */
        },
        complete: function () {
          $("#loading").removeClass("loading");
        },
      });
    }
    form.addClass("was-validated");
  });
});
