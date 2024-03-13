$(function () {
  // ###################################
  // #  SECCION DE BUSQUEDA Y CARDS    #
  // #   QUE COINCIDEN CON NOMBRE      #
  // ###################################

  $(document).ready(function () {
    var libros;

    $("#card-result").hide();
    $("#resultSelect").hide();
    $("#result").text("");

    // Limpiar el campo de búsqueda
    $("#searchInput").val("");

    // Limpiar la lista de resultados
    $("#card-result").empty();

    $("#searchButton").on("click", function () {
      try {
        var searchTerm = $("#searchInput").val();

        $.ajax({
          url: servidor + "usuarios/buscarLibro",
          method: "POST",
          data: { searchTerm: searchTerm },
          success: function (response) {
            libros = JSON.parse(response);

            $("#card-result").empty();

            libros.forEach(function (libro, index) {
              var listItem = $('<li class="card"></li>');
              var imagenSrc = "imagenes/" + libro.nombre_imagen;
              listItem.append(
                '<img src="' +
                  imagenSrc +
                  '" style="max-width: 150px; max-height: 300px;  display: block; margin: 0 auto;" class="card-img-top" alt="' +
                  libro.titulo_libro +
                  '" class="img-fluid mb-3">' +
                  // '<img src="https://picsum.photos/300/200" class="card-img-top"alt=${libro.titulo_libro}" class="img-fluid mb-3">' +
                  '<div><h3 class="card-title">' +
                  libro.titulo_libro +
                  '</h3><div class="card-content"><p>' +
                  libro.editorial_libro +
                  "</p></div></div>"
              );
              listItem.append(
                '<button class="btn btn-outline-success resultSelect" data-index="' +
                  index +
                  '">Ver mas infromacion</button>'
              );

              // Agregamos el elemento de lista a la lista
              $("#card-result").append(listItem);
            });

            // Mostramos la lista
            $("#card-result").show();
            if (libros.length > 0) {
              $("#card-result").show();
              $("#resultSelect").show();
              $("#result").text("Libros encontrados");
              $("#result").removeClass("text-danger");
              $("#result").addClass("text-success text-center text-uppercase");
            } else {
              $("#card-result").show();
              $("#resultSelect").hide();
              $("#result").text(
                "Lo sentimos, su búsqueda no coincidió con ningún documento."
              );
              $("#result").addClass("text-danger text-center text-uppercase");
            }

            $("#cleanButton").on("click", function () {
              try {
                // Ocultar resultados y mensajes
                $("#card-result").hide();
                $("#resultSelect").hide();
                $("#result").text("");

                // Limpiar el campo de búsqueda
                $("#searchInput").val("");

                // Limpiar la lista de resultados
                $("#card-result").empty();
              } catch (error) {
                console.error("Error en cleanButton:", error);
              }
            });
          },
          error: function (error) {
            console.error("Error al buscar libros:", error);
          },
        });
      } catch (error) {
        console.error("Error en searchButton:", error);
      }
    });

    var libroSeleccionadoId;

    $("#card-result").on("click", ".resultSelect", function () {
      try {
        var selectedLibroIndex = $(this).data("index");
        var selectedLibro = libros[selectedLibroIndex];

        libroSeleccionadoId = selectedLibro;

        var modalContent = `
            <img src="imagenes/${
              selectedLibro.nombre_imagen
            }" style="max-width: 150px; max-height: 300px;  display: block; margin: 0 auto;" class="card-img-top" class="rounded">
            <p><strong>Título:</strong> ${selectedLibro.titulo_libro}</p>
            <p><strong>Editorial:</strong> ${selectedLibro.editorial_libro}</p>
            <p><strong>Año:</strong> ${selectedLibro.anio_libro}</p>
            <p><strong>Autor:</strong> ${selectedLibro.autor_libro}</p>
            <p><strong>Genero:</strong> ${selectedLibro.genero_libro}</p>
            
            
  
            <div class="modal-footer">
            <button id="verPDF" class="btn btn-outline-info">Ver PDF</button>
            <button type="button" class="btn btn-outline-danger ms-auto" data-bs-dismiss="modal">Cerrar</button>
  
            <input type="checkbox" class="editor-active-checkbox" id="star"  data-product-id="${
              selectedLibro.id_libro
            }" name="star" ${
          selectedLibro.fav_id && selectedLibro.fav_user == IdGlobalUsuario
            ? "checked"
            : ""
        }>
            <label for="star" title="Agregar a favoritos">
              <svg viewBox="0 0 24 24">
                <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"></path>
              </svg>
            </label>
  
  
          
            </div>
        `;
        $("#libroModalBody").html(modalContent);
        $("#libroModal").modal("show");
        
        $("#verPDF").on("click", function () {
          var ruta_archivo = `${servidor}pdfjs-4.0.379-dist/web/viewer.html?file=${selectedLibro.nombre_archivo}`;
          mostrarPDF(ruta_archivo);
          console.log("esta es la ruta" + ruta_archivo);
          $("#libroModal").modal("show");
          $("#tamaño").addClass("modal-fullscreen");
          $("#tamaño").removeClass("modal-dialog modal-dialog-centered");
          var closeButton =
            '<button type="button" class="btn btn-outline-danger ms-auto" data-bs-dismiss="modal">Cerrar</button>';
          $("#libroModalLabel").html(closeButton);
          $("#libroModal").on("hidden.bs.modal", function () {
            // Aquí realizas la recarga de la página
            location.reload();
          }); 
        });

        // estes es otro para el evento
        $(document)
          .off("change", ".editor-active-checkbox")
          .on("change", ".editor-active-checkbox", async function () {
            var isChecked = this.checked;
            var libroId = obtenerLibroId();

            if (isChecked) {
              insertarFavorito(libroId);
              $("#libroModal").on("hidden.bs.modal", function () {
                // Aquí realizas la recarga de la página
                location.reload();
              }); 
            } else {
              actualizarEstado(libroId);
              $("#libroModal").on("hidden.bs.modal", function () {
                // Aquí realizas la recarga de la página
                location.reload();
              }); 
            }
          });

        function obtenerLibroId() {
          try {
            return libroSeleccionadoId.id_libro;
          } catch (error) {
            console.error("Error en obtenerLibroId:", error);
          }
        }
      } catch (error) {
        console.error("Error en resultSelect:", error);
      }
    });

    function insertarFavorito(libroId) {
      $.ajax({
        url: servidor + "usuarios/guardarFavorito",
        method: "POST",
        data: {
          tipo: "nuevo",
          libro_id: libroId,
        },
        success: function (response) {
          console.log(response);
        },
        error: function (error) {
          console.error(error);
        },
      });
    }

    function actualizarEstado(libroId) {
      $.ajax({
        url: servidor + "usuarios/guardarFavorito",
        method: "POST",
        data: {
          tipo: "actualizar",
          libro_id: libroId,
        },
        success: function (response) {
          console.log(response);
        },
        error: function (error) {
          console.error(error);
        },
      });
    }

    function mostrarPDF(url) {
      var iframe = `<iframe src="${url}" style="width:100%;height:100%;"></iframe>`;
      $("#libroModalBody").html(iframe);
    }
  });

  // ###############################
  // #    SECCION DE CARRUSEL      #
  // #      PARTE DE ABAJO         #
  // ###############################

  async function cardsLibros() {
    try {
      let peticion = await fetch(servidor + `usuarios/eventos`);
      let response = await peticion.json();

      if (response.length == 0) {
        jQuery(
          `<h3 class="mt-4 text-center text-uppercase">Sin libros disponibles</h3>`
        )
          .appendTo("#container-libros")
          .addClass("text-danger");
        return false;
      }

      let carrusel = jQuery(`
                    <div id="librosCarrusel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner"></div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#librosCarrusel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#librosCarrusel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                `);

      response.forEach((libro, index) => {
        if (index % 3 === 0) {
          let activeClass = index === 0 ? "active" : "";
          let cardSet = jQuery(
            '<div class="carousel-item ' +
              activeClass +
              '"><div class="row"></div></div>'
          );
          carrusel.find(".carousel-inner").append(cardSet);
        }

        // ###############################################################################
        // #                                 CARRUSEL                                    #
        // #                 MOSTRAR INFORMACION DANDO CLICK AL CARRUSEL                 #
        // ###############################################################################
        let card = jQuery(`
              <div class="col-md-4 mb-3">
                  <div class="card" style="background-color: #f1ebeb; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); height: 100%;">
                      <img src="imagenes/${libro.nombre_imagen}" style="max-width: 150px; max-height: 300px; display: block; margin: 0 auto;" class="card-img-top" alt="Imagen de libro">
                      <div class="card-body">
                          <h5 class="card-title">${libro.titulo_libro}</h5>
                          <p class="card-text">${libro.editorial_libro}</p>
                      </div>
                  </div>
              </div>
          `);
        //     let card = jQuery(`
        //     <div class="col-md-4 mb-3">
        //     <div class="card" style="background-color: #607d8b; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
        //         <img src="imagenes/${libro.nombre_imagen}"  style="max-width: 150px; max-height: 300px;  display: block; margin: 0 auto;" class="card-img-top" alt="Imagen de libro">
        //         <div class="card-body">
        //             <h5 class="card-title">${libro.titulo_libro}</h5>
        //             <p class="card-text">${libro.editorial_libro}</p>
        //         </div>
        //     </div>
        // </div>
        //     `);

        // ###############################
        // #        MODAL CONTENIDO      #
        // #           FUNCION           #
        // ###############################
        card.on("click", function () {
          mostrarInformacionLibro(libro);
        });

        function mostrarInformacionLibro(libro) {
          var modalContent = `
              <img src="imagenes/${
                libro.nombre_imagen
              }"   style="max-width: 150px; max-height: 300px;  display: block; margin: 0 auto;" class="card-img-top"alt="${
            libro.titulo_libro
          }" class="img-fluid mb-3">
              <p><strong>Título:</strong> ${libro.titulo_libro}</p>
              <p><strong>Editorial:</strong> ${libro.editorial_libro}</p>
              <p><strong>Año:</strong> ${libro.anio_libro}</p>
              <p><strong>Autor:</strong> ${libro.autor_libro}</p>
              <p><strong>Genero:</strong> ${libro.genero_libro}</p>
  
              <div class="modal-footer">
              <button id="verPDF" class="btn btn-outline-info">Ver PDF</button>  
              <button type="button" class="btn btn-outline-danger ms-auto" data-bs-dismiss="modal">Cerrar</button>
  
  
              <input type="checkbox" class="nueva-clase-checkbox" id="nuevo-id-checkbox"  data-product-id="${
                libro.id_libro
              }" name="nuevo-nombre-checkbox" ${
            libro.fav_id && libro.fav_user == IdGlobalUsuario ? "checked" : ""
          }>
              <label for="nuevo-id-checkbox" title="Agregar a favoritos" class="nueva-clase-label">
                <svg viewBox="0 0 24 24">
                  <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"></path>
                </svg>
              </label>
              </div>
              
              `;
          $("#libroModalBody").html(modalContent);
          $("#libroModal").modal("show");
          $("#verPDF").on("click", function () {
            var ruta_archivo = `${servidor}pdfjs-4.0.379-dist/web/viewer.html?file=${libro.nombre_archivo}`;
            mostrarPDF(ruta_archivo);
            $("#libroModal").modal("show");
            $("#libroModalLabel").text("Lectura de pdf.");
            $("#tamaño").addClass("modal-fullscreen");
            $("#tamaño").removeClass("modal-dialog modal-dialog-centered");
            var closeButton =
              '<button type="button" class="btn btn-outline-danger ms-auto" data-bs-dismiss="modal">Cerrar</button>';
            $("#libroModalLabel").html(closeButton);
            $("#libroModal").on("hidden.bs.modal", function () {
              // Aquí realizas la recarga de la página
              location.reload();
            }); 
          });

          $(document)
            .off("change", ".nueva-clase-checkbox")
            .on("change", ".nueva-clase-checkbox", async function () {
              // $(document).on("change", ".editor-active-checkbox", async function () {
              var isChecked = this.checked;
              var idlibro = obteneridlibro();

              if (isChecked) {
                insertarlibro(idlibro);
                $("#libroModal").on("hidden.bs.modal", function () {
                  // Aquí realizas la recarga de la página
                  location.reload();
                }); 
              } else {
                actualizarlibro(idlibro);
                $("#libroModal").on("hidden.bs.modal", function () {
                  // Aquí realizas la recarga de la página
                  location.reload();
                }); 
              }
            });

          function obteneridlibro() {
            try {
              return libro.id_libro;
            } catch (error) {
              console.error("Error en obteneridlibro:", error);
            }
          }

          function insertarlibro(idlibro) {
            $.ajax({
              url: servidor + "usuarios/guardarFavorito",
              method: "POST",
              data: {
                tipo: "nuevo",
                libro_id: idlibro,
              },
              success: function (response) {
                console.log(response);
              },
              error: function (error) {
                console.error(error);
              },
            });
          }

          function actualizarlibro(idlibro) {
            $.ajax({
              url: servidor + "usuarios/guardarFavorito",
              method: "POST",
              data: {
                tipo: "actualizar",
                libro_id: idlibro,
              },
              success: function (response) {
                console.log(response);
              },
              error: function (error) {
                console.error(error);
              },
            });
          }
        }
        card.appendTo(carrusel.find(".carousel-item:last .row"));
      });
      carrusel.appendTo("#container-libros");
      function mostrarPDF(url) {
        var iframe = `<iframe src="${url}" style="width:100%;height:100%;"></iframe>`;
        $("#libroModalBody").html(iframe);
      }
    } catch (error) {
      if (error.name == "AbortError") {
      } else {
        throw error;
      }
    }
  }
  cardsLibros();

  $(function () {
    function createBookElement(bookData) {
      return `
          <div class="swiper-slide swiper-slide--one" style="background: linear-gradient(to bottom, #2c536400, #203a4303, #0f2027cc), url('${servidor}imagenes/${bookData.image}') no-repeat 50% 50%/cover;">
                  <span>Favoritos <h3>#${bookData.ranking}</h3></span>
                  <div class="slide-content">
                      <h3>${bookData.title}</h3>
                      ${bookData.genre ? `<p>${bookData.genre}</p>` : ""}
                  </div>
              </div>`;
    }

    function generateBookElements(data) {
      const elements = [];
      for (let i = 0; i < data.length; i++) {
        const bookData = {
          ranking: data[i].ranking,
          title: data[i].titulo_libro,
          genre: data[i].anio_libro,
          image: data[i].nombre_imagen,
        };
        const bookElement = createBookElement(bookData);
        elements.push(bookElement);
      }
      return elements;
    }

    async function loadBooks() {
      try {
        let peticion = await fetch(servidor + `usuarios/librosfavoritos`);
        let response = await peticion.json();

        if (response.length === 0) {
          console.log("Sin libros favoritos agregados");
          return;
        }

        const swiperWrapper = $("#bookSwiperWrapper");
        const bookElements = generateBookElements(response);

        swiperWrapper.html(bookElements.join(""));

        var swiper = new Swiper(".swiper", {
          effect: "coverflow",
          grabCursor: true,
          spaceBetween: 30,
          centeredSlides: false,
          coverflowEffect: {
            rotate: 0,
            stretch: 0,
            depth: 0,
            modifier: 1,
            slideShadows: false,
          },
          loop: true,
          keyboard: {
            enabled: true,
          },
          mousewheel: {
            thresholdDelta: 70,
          },
          breakpoints: {
            460: {
              slidesPerView: 3,
            },
            768: {
              slidesPerView: 3,
            },
            1024: {
              slidesPerView: 3,
            },
            1600: {
              slidesPerView: 3.6,
            },
          },
        });
      } catch (error) {
        console.error(error);
      }
    }

    loadBooks();
  });
});
