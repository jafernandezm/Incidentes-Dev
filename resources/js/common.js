pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.worker.min.js';

let Menulist = document.querySelectorAll('.Menulist li');
let overlay_detail_tema = document.getElementById('overlay_detail_tema');
let overlay_detail_exito = document.getElementById('overlay_detail_exito');
let overlay_detail_delete = document.getElementById('overlay_detail_delete');
let overlay_detail_asigned_tema = document.getElementById('overlay_asigned_tema_student');
let overlay_detail_desasigned_tema = document.getElementById('overlay_desaigned_tema_student');
let overlay_detail_approve_profile_tema = document.getElementById('overlay_approve_profile_tema');
let overlay_detail_approve_proyect_tema = document.getElementById('overlay_approve_proyect_tema');

let arrayOverlays = [
    overlay_detail_tema,
    overlay_detail_exito,
    overlay_detail_delete,
    overlay_detail_asigned_tema,
    overlay_detail_desasigned_tema,
    overlay_detail_approve_profile_tema,
    overlay_detail_approve_proyect_tema
]

function showOverlay(indice) {
    arrayOverlays[indice].style.display = 'flex';
}

function hideOverlay(indice) {
    arrayOverlays[indice].style.display = 'none';
}

let close_buttons = document.querySelectorAll('.close')
close_buttons.forEach((item) => {
    item.addEventListener('click', (event) => {
        let overlayIndex = event.target.dataset.overlayIndex; // Obtener el valor de data-overlay-index
        hideOverlay(overlayIndex);
    })
})

function activeLink() {
    Menulist.forEach((item) =>
        item.classList.remove('active'));
    this.classList.add('active')
}
Menulist.forEach((item) =>
    item.addEventListener('click', activeLink));

function createElement(string) {
    let div = document.createElement('div')
    div.innerHTML = string;
    let element = div.firstChild
    return element
}

function formatDate(date, format) {
    date = date.split('-')
    const dia = parseInt(date[2])
    const mes = parseInt(date[1])
    const anio = parseInt(date[0])
    if (format == 'MM-AAAAA') {
        const meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre']
        const f_date = `${meses[mes - 1]} del ${anio}`
        return f_date
    } else if (format == 'DD-MM-AAAAA') {
        const meses = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre']
        const f_date = `${dia} de ${meses[mes - 1]} del ${anio}`
        return f_date
    }
}

function pxToVw(px) {
    const vw = (px / window.innerWidth) * 100;
    return vw;
}



function initializePopup() {
    console.log('Popup inicializado');
    let customInput = document.querySelector('.customInput');
    let selectedData = document.querySelector('.selectedData');
    let searchInput = document.querySelector('.searchInput input');
    let user = document.querySelector('#user');
    let ul = document.querySelector('.options ul');
    let customInputContainer = document.querySelector('.customInputContainer');

    // Control del enfoque
    window.addEventListener('click', (e) => {
        if (document.querySelector('.searchInput').contains(e.target)) {
            document.querySelector('.searchInput').classList.add('focus');
        } else {
            document.querySelector('.searchInput').classList.remove('focus');
        }
    });

    customInput.addEventListener('click', () => {
        customInputContainer.classList.toggle('show');
        if (customInputContainer.classList.contains('show')) {
            fetchIngenieros(customInputContainer.getAttribute('data-type'));
        }
    });

    function fetchIngenieros(dataType) {
        fetch(`/${dataType}`)
            .then(response => response.json())
            .then(data => {
                populateIngenieros(data);
            })
            .catch(error => console.error('Error al obtener los datos:', error));
    }

    function populateIngenieros(ingenieros) {
        ul.innerHTML = '';
        ingenieros.forEach(ingeniero => {
            const li = document.createElement('li');
            li.textContent = ingeniero.name;
            ul.appendChild(li);

            li.addEventListener('click', (e) => {
                let selectdItem = e.target.innerText;
                selectedData.innerText = selectdItem;
                user.value = selectdItem;

                ul.querySelectorAll('li.selected').forEach(li => li.classList.remove('selected'));
                e.target.classList.add('selected');
                customInputContainer.classList.toggle('show');
            });
        });
    }

    // Filtrar ingenieros en tiempo real
    searchInput.addEventListener('keyup', (e) => {
        if (searchInput.value.length <= 5 && customInputContainer.getAttribute('data-type') == 'tutores') {
            searchInput.value = "Ing. ";
        }
        let searchedVal = searchInput.value.toLowerCase();
        let dataType = customInputContainer.getAttribute('data-type');
        fetch(`/${dataType}`)
            .then(response => response.json())
            .then(ingenieros => {
                let searched_ing = ingenieros.filter(data => {
                    return data.name.toLowerCase().startsWith(searchedVal); // Ajusta según la estructura de tus datos
                }).map(data => {
                    return `<li>${data.name}</li>`; // Ajusta según la estructura de tus datos
                }).join('');
                ul.innerHTML = searched_ing ? searched_ing : "<p style='margin-top: 1rem;'>No se encontró ningún resultado. <p style='margin-top: .2rem; font-size: .9rem;'>Intenta buscar otro.</p></p>";

                // Reagregar eventos a los nuevos elementos
                ul.querySelectorAll('li').forEach(li => {
                    li.addEventListener('click', (e) => {
                        let selectdItem = e.target.innerText;
                        selectedData.innerText = selectdItem;
                        user.value = selectdItem;

                        ul.querySelectorAll('li.selected').forEach(li => li.classList.remove('selected'));
                        e.target.classList.add('selected');
                        customInputContainer.classList.toggle('show');
                    });
                });
            })
            .catch(error => console.error('Error al filtrar los datos:', error));
    });
}


function inicializeFunctionalidadPDF() {
    let drag_area = document.querySelector('.drag_area')
    let explore_button = document.getElementById('explore')
    let other_button = document.getElementById('other')
    let file_input = document.getElementById('file-input')
    let image_imput = document.getElementById('image-input')
    let uploaded_section = document.querySelector('.uploaded_section')
    let input_section = document.querySelector('.input_section')
    let message_section = document.querySelector('.message_section')
    let size = document.querySelector('.size')
    let file_name = document.querySelector('.file-name')

    

    explore_button.onclick = () => file_input.click();
    other_button.onclick = () => file_input.click();
    file_input.addEventListener('change', (e) => {
        showFile()
        createImage(e.target.files[0])
    })

    function showFile() {
        const file = file_input.files[0]
        console.log(file)
        if (file) {
            size.innerHTML = `${(file.size / (1024 * 1024)).toFixed(2)} MB`
            file_name.innerHTML = file.name
            input_section.style.display = "none"
            uploaded_section.style.display = "flex"
        }
    }

    function addEffect() {
        input_section.classList.add('section_effect')
        uploaded_section.classList.add('section_effect')
        message_section.classList.add('message_effect')
    }

    function removeEffect() {
        input_section.classList.remove('section_effect')
        uploaded_section.classList.remove('section_effect')
        message_section.classList.remove('message_effect')
    }

    drag_area.addEventListener('dragover', (e) => {
        e.preventDefault()
        addEffect()
    })

    drag_area.addEventListener('dragleave', (e) => {
        e.preventDefault()
        e.stopPropagation()
        removeEffect()
    })

    drag_area.addEventListener('drop', (e) => {
        e.preventDefault()
        removeEffect()
        const files = e.dataTransfer.files
        if (files.length > 0) {
            file_input.files = files
            showFile()
            createImage(e.target.files[0])
        }
    })

    function createImage(file) {
        let reader = new FileReader()
        reader.onload = () => {
            let url = reader.result
            let task = pdfjsLib.getDocument({ url: url });
            task.promise.then(async (pdf) => {
                let page = await pdf.getPage(1)
                const canvas = document.createElement('canvas')
                const context = canvas.getContext('2d')
                const viewport = page.getViewport({ scale: 1 })
                canvas.width = viewport.width
                canvas.height = viewport.height
                const render_context = {
                    canvasContext: context,
                    viewport: viewport
                }
                let render = page.render(render_context);
                render.promise.then(() => {
                    let image = canvas.toDataURL()
                    image_imput.value = image.split(',')[1]
                })
            })
        }
        reader.readAsDataURL(file);
    }

    function showFileEdit(file, nombreArchivoPDF) {
        if (file) {
            size.innerHTML = `${(file.size / (1024 * 1024)).toFixed(2)} MB`;
            file_name.innerHTML = nombreArchivoPDF;
            input_section.style.display = "none";
            uploaded_section.style.display = "flex";
        }
    }

    const nombreArchivoPDF = document.getElementById('nombre-archivo').value;

    if (nombreArchivoPDF) {
        fetch(`${base_url}/docs/${nombreArchivoPDF}`)
            .then(response => response.arrayBuffer())
            .then(buffer => {
                const archivo = new Blob([buffer], { type: 'application/pdf' });
                //console.log('Archivo PDF cargado:', archivo);

                // Mostrar el archivo PDF en el visor
                showFileEdit(archivo, nombreArchivoPDF);
                createImage(archivo);
            })
            .catch(error => {
                console.error('Error al cargar el archivo PDF:', error);
            });
    }

    const closeModal = document.querySelector('.eliminate-content');


    function clearUpload() {
        // Limpiar la sección de archivos cargados
        uploaded_section.style.display = 'none';
        size.innerHTML = '0 MB';
        file_name.innerHTML = '';
        input_section.style.display = 'flex';
        file_input.value = '';
        image_imput.value = '';
    }

    if (closeModal) {
        closeModal.addEventListener('click', clearUpload);
    }

}

export {
    showOverlay,
    createElement,
    formatDate,
    pxToVw,
    initializePopup,
    inicializeFunctionalidadPDF

}

