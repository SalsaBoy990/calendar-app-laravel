# clean-dropzone.js

Modified by `András Gulácsi`.

Used in my clean.css library.


bs-dropzone.js
==============

Proyecto mantenido por `Wilfredo Nina Choquetarqui`.

Documentación y ejemplos [aquí](https://wilnicho.github.io/bs-dropzone.js).

¿Qué es clean-dropzone.js?
-----------------------

Es una extensión para `jQuery` y `Bootstrap` que permite transformar visualmente un elemento de entrada para archivos `<input type="file">` en un elemento que permite los gestos de arrastrar y soltar dentro de una zona, es totalmente personalizable y permite hacer una llamada de función cuando el evento change es ejecutado.

¿Qué necesito?
--------------
Un navegador moderno y las dependencias de `jQuery` y `Bootstrap` en sus versiones correspondientes.

¿Cómo se usa?
-------------
Incluya dentro de su proyecto los archivos `bs-dropzone.css` y `clean-dropzone.js` que debe descargar previamente.

```html static
<link rel="stylesheet" href="bs-dropzone.css">
```
```html static
<script type="text/javascript" src="clean-dropzone.js"></script>
```

#### Aplicación por defecto.

```html static
<input type="file" name="file">
```
```js static
// aplica arrastrar y soltar en el elemento :file
$('#elemento').bs_dropzone();
```

#### Visualización de imágenes.

```js static
// aplica arrastrar y soltar en el elemento :file
$('#elemento').bs_dropzone({
    preview: true
});
```

#### Asignación de clases personalizadas sobre la zona de arrastrar y soltar.

```js static
// aplica arrastrar y soltar en el elemento :file
$('#elemento').bs_dropzone({
    boxClass: 'alert p-5 text-center font-weight-bold',
    noneColorClass: 'alert-info border-info',
    dragColorClass: 'alert-warning border-warning',
    doneColorClass: 'alert-success border-success',
    failColorClass: 'alert-danger border-danger'
});
```

#### Asignación de textos personalizados.

```js static
// aplica arrastrar y soltar en el elemento :file
$('#elemento').bs_dropzone({
    language: {
        emptyText: '[arrastrar aquí]',
        dragText: '[soltar aquí]',
        dropText: '[_t_ por guardar]'
    }
});
```

#### Asignación de clases personalizadas sobre la zona de arrastrar y soltar en selección multiple de archivos.

```html static
<input type="file" name="files" multiple>
```
```js static
// aplica arrastrar y soltar en el elemento :file
$('#elemento').bs_dropzone({
    preview: true,
    accepted: ['jpg', 'jpeg', 'png'],
    dropzoneTemplate: '<div class="bs-dropzone"><div class="bs-dropzone-area"></div><div class="bs-dropzone-message"></div><div class="bs-dropzone-preview mt-0"></div></div>',
    parentTemplate: '<div class="row ml-0 justify-content-center align-items-center"></div>',
    childTemplate: '<div class="col-4 col-md-3 pl-0"></div>',
    imageClass: 'img-fluid mt-3 rounded'
});
```

#### Ejecución de una función luego de la selección.

```js static
// aplica arrastrar y soltar en el elemento :file
$('#elemento').bs_dropzone({
    change: function (element, files) {
        alert(files.length + ' archivos cargados');
    }
});
```

Como habrá visto la librería es muy fácil de implementar y personalizar ya que permite incluir clases propias, además con el evento `change` podemos extender aún más las funcionalidades, pero eso ya depende de las necesidades específicas de su aplicación.

Licencia
--------
Copyright © 2020 Wilfredo Nina Choquetarqui; Publicado bajo la licencia MIT.
