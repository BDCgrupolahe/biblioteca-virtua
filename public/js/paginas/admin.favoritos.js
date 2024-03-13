$(function () {
    function createBookElement(bookData) {
        return `
                    <li>
                        <figure class='book'>        
                            <!-- Front -->        
                            <ul class='hardcover_front'>
                                <li>
                                    <img src="${
                                    bookData.image
                                    }" alt="" width="100%" height="100%">
                                </li>
                                <li></li>
                            </ul>        
                            <!-- Pages -->        
                            <ul class='page'>
                                <li></li>
                                <li>
                                    <a class="btn" href="${servidor}pdfjs-4.0.379-dist/web/viewer.html?file=${bookData.librover}">Leer</a>
                                    <a class="btn btn-delete-favo" data-id="${btoa(
                                    btoa(bookData.id_agregacion)
                                    )}" data-book-title="${bookData.title}">Eliminar</a>
                                </li>
                                <li></li>
                                <li></li>
                                <li></li>
                            </ul>        
                            <!-- Back -->        
                            <ul class='hardcover_back'>
                                <li></li>
                                <li></li>
                            </ul>
                            <ul class='book_spine'>
                                <li></li>
                                <li></li>
                            </ul>
                            <figcaption>
                                <h1>${bookData.title}</h1>
                                <span><h6>Autor:</h6>${bookData.author}</span>
                                <p><h6>Editorial:</h6>${bookData.description}</p>
                                <p><h6>Fecha de agregación:</h6>${
                                bookData.fechaagrega
                                }</p>
                                <br>
                            </figcaption>
                        </figure>
                    </li>`;
    }

    async function cardsEventos() {
        try {
        let peticion = await fetch(servidor + `admin/loslibrosfavoritos`);
        let response = await peticion.json();
        if (response.length == 0) {
            jQuery(
            `<h3 class="mt-4 text-center text-uppercase">Sin libros favoritos agregados</h3>`
            )
            .appendTo("#container-eventos")
            .addClass("text-danger");
            return false;
        }

        const bookContainer = $(".align");

        response.forEach((item, index) => {
            const bookData = {
            image: `${servidor}imagenes/${item.nombre_imagen}`,
            // image: "https://picsum.photos/300/200",
            title: item.titulo_libro, // Cambiar a la propiedad correcta de tu objeto item
            author: item.nombre_autor, // Cambiar según tus datos
            description: item.editorial_libro, // Cambiar a la propiedad correcta de tu objeto item
            fechaagrega: item.feha_agregacion,
            librover: item.nombre_archivo,
            id_agregacion: item.id_agregacion,
            };

            const bookElement = createBookElement(bookData);
            bookContainer.append(bookElement);
        });
        } catch (error) {
        if (error.name == "AbortError") {
        } else {
            throw error;
        }
        }
    }

    cardsEventos();

    $(document).on("click", ".btn-delete-favo", function () {
        const bookTitle = $(this).data("book-title");

        Swal.fire({
        title: `Desea Eliminar el libro \n"${bookTitle}" de tus favoritos?`,
        text: `Estás a punto de Eliminar este libro de tus favoritos.`,
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#82d616",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, Eliminar!",
        cancelButtonText: "No, cancelar",
        }).then((result) => {
        if (result.isConfirmed) {
            const id = $(this).data("id");
            eliminarLibro(id);
        }
        });
    });

    function eliminarLibro(id) {
        $.ajax({
        type: "POST",
        url: servidor + `usuarios/eliminarfavoritoLibro/${id}`,
        dataType: "json",
        contentType: false,
        cache: false,
        processData: false,
        beforeSend: function () {
            $("#loading").addClass("loading");
        },
        success: function (data) {
            // data = JSON.parse(data);
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
});
