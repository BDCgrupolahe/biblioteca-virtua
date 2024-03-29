$(function () {
    function createToplistElement(ranking, titulo_libro, genero, imagen) {
    return `
        <li data-rank="${ranking}">
            <div class="thumb">
                <img class="img" src="${servidor}imagenes/${imagen}" alt="${titulo_libro}"style="width: 30px; height: 50px";>
                // 
                <span class="name">${titulo_libro}</span>
                

                <label class="container">
                <input type="checkbox">
                <svg id="Glyph" version="1.1" viewBox="0 0 32 32" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><path d="M29.845,17.099l-2.489,8.725C26.989,27.105,25.804,28,24.473,28H11c-0.553,0-1-0.448-1-1V13  c0-0.215,0.069-0.425,0.198-0.597l5.392-7.24C16.188,4.414,17.05,4,17.974,4C19.643,4,21,5.357,21,7.026V12h5.002  c1.265,0,2.427,0.579,3.188,1.589C29.954,14.601,30.192,15.88,29.845,17.099z" id="XMLID_254_"></path><path d="M7,12H3c-0.553,0-1,0.448-1,1v14c0,0.552,0.447,1,1,1h4c0.553,0,1-0.448,1-1V13C8,12.448,7.553,12,7,12z   M5,25.5c-0.828,0-1.5-0.672-1.5-1.5c0-0.828,0.672-1.5,1.5-1.5c0.828,0,1.5,0.672,1.5,1.5C6.5,24.828,5.828,25.5,5,25.5z" id="XMLID_256_"></path></svg>
                </label>
                <span class="stat"><b>${genero}</b></span>


                    <style>
                    .container input {
                        position: absolute;
                        opacity: 0;
                        cursor: pointer;
                        height: 0;
                        width: 0;
                    }
                    
                    .container {
                        display: block;
                        position: relative;
                        cursor: pointer;
                        user-select: none;
                    }
                    
                    svg {
                        position: relative;
                        top: 0;
                        left: 0;
                        height: 50px;
                        width: 50px;
                        transition: all 0.3s;
                        fill: #666;
                    }
                    
                    svg:hover {
                        transform: scale(1.1) rotate(-10deg);
                    }
                    
                    .container input:checked ~ svg {
                        fill: #2196F3;
                    }
                    
                    </style>


                </div>
                <div class="more">
                    <!-- To be designed & implemented -->
                </div>
            </li>`;
    }

    async function createToplist() {
        try {
        let peticion = await fetch(servidor + `admin/librosfavoritostop`);
        let response = await peticion.json();

        if (response.length === 0) {
            $(
            '<h3 class="mt-4 text-center text-uppercase">Sin datos disponibles</h3>'
            )
            .appendTo(".toplist")
            .addClass("text-danger");
            return false;
        }

        const toplistContainer = $(".toplist");

        response.forEach((item, index) => {
            const toplistElement = createToplistElement(
            item.ranking,
            item.titulo_libro,
            item.genero,
            item.nombre_imagen
            );
            toplistContainer.append(toplistElement);
        });
        } catch (error) {
        if (error.name == "AbortError") {
        } else {
            throw error;
        }
        }
    }

    createToplist();
});
