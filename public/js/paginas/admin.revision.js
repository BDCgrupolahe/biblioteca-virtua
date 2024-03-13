async function tablaLibros() {
  try {
    let peticion = await fetch(servidor + `admin/librorevision`);
    let response = await peticion.json();
    console.log("caja contenido:", response);
    $("#container-libros").empty();
    if (response.length == 0) {
      jQuery(`<h2>Sin libros por aprobar</h2>`)
        .appendTo("#container-libros")
        .addClass("text-danger text-center text-uppercase");
      return false;
    }
    jQuery(`<table class="table align-items-center mb-0 table table-striped table-bordered" style="width:100%" id="info-table-result">
                            <thead><tr>
                            <th class="text-uppercase">Usuario</th><th class="text-uppercase">Titulo Libro</th><th class="text-uppercase">Editorial</th><th class="text-uppercase">Genero</th><th class="text-uppercase">Autor</th><th class="text-uppercase">Año</th><th class="text-uppercase">Imagen</th><th class="text-uppercase">Acciones</th>
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
        { data: "nombre_usuario", className: "text-vertical text-center text-danger" },
        { data: "titulo_libro", className: "text-vertical text-center" },
        { data: "editorial_libro", className: "text-vertical text-center" },
        { data: "genero_libro", className: "text-vertical text-center" },
        { data: "autor_libro", className: "text-vertical text-center" },
        { data: "anio_libro", className: "text-vertical text-center" },
        {
          data: "nombre_imagen",
          render: function (data, type, row) {
            return `<img src="${servidor}imagenes/${data}" alt="Libro" style="width:50%; height:60%;">`;
          },
          className: "text-vertical text-center",
        },
        {
          data: null,
          render: function (data, item) {
            const condicion = data.estatus_permiso;
            let botones = "";

            if (condicion == 0) {
              botones = `<div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 justify-content align-items-center">
                                                    
                            <button data-nombre="${data.titulo_libro}" data-usuario="${data.nombre_usuario}" data-condicion="${data.estatus_permiso}" data-id="${btoa(btoa(data.id_libro))}" data-bs-toggle="tooltip" title="Aprobar libro" type="button" class="btn btn-warning btn-aprobacion-libro">
                            <i class="fa-solid"></i>Pendiente de Aprobación</button>

                            
                            <button data-nombrelibro="${data.titulo_libro}" data-usuariolibro="${data.nombre_usuario}" data-condicionlibro="${data.estatus_permiso}" data-idlibro="${btoa(btoa(data.id_libro))}" data-bs-toggle="tooltip" title="eliminar libro" type="button" class="btn btn-danger btn-eliminar-libro">
                            <i class="fa-solid fa fa-trash"></i></button>
                            
                          
                            
                            `;
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


$(document).on("click", ".btn-aprobacion-libro", function () {
  const condicion = $(this).data("condicion");
  if (condicion == 0) {
    Swal.fire({
      title: `Desea Aprobar el libro \n"${$(this).data("nombre")}" del usuario \n"${$(this).data("usuario")}"?`,
      text: `Estás a punto de aprobar este libro. Una vez que lo apruebes, desaparecerá de esta tabla.`,
      icon: "question",
      showCancelButton: true,
      confirmButtonColor: "#82d616",
      cancelButtonColor: "#d33",
      confirmButtonText: "Sí, Aprobar!",
      cancelButtonText: "No, cancelar",
    }).then((result) => {
      if (result.isConfirmed) {
        const id = $(this).data("id");
        const condicion = $(this).data("condicion");
        revisionlibro(id, condicion);
      }
    });
  }
});

function revisionlibro(id) {
  $.ajax({
    type: "POST",
    url: servidor + `admin/revisionlibro/${id}`,
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





$(document).on("click", ".btn-eliminar-libro", function () {
  const condicion = $(this).data("condicionlibro");
  if (condicion == 0) {
    Swal.fire({
      title: `Desea Eliminar el libro \n"${$(this).data("nombrelibro")}" del usuario \n"${$(this).data("usuariolibro")}"?`,
      text: `Estás a punto de Eliminar de forma permanente este libro. Una vez que lo elimines, desaparecerá el registro.`,
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#82d616",
      cancelButtonColor: "#d33",
      confirmButtonText: "Sí, Eliminar!",
      cancelButtonText: "No, cancelar",
    }).then((result) => {
      if (result.isConfirmed) {
        const id = $(this).data("idlibro");
        // const condicion = $(this).data("condicionlibro");
        eliminarlibrodesaprovado(id);
      }
    });
  }
});

function eliminarlibrodesaprovado(id) {
  $.ajax({
    type: "POST",
    url: servidor + `admin/eliminarlibrodesaprovado/${id}`,
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
