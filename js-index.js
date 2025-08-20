//para editar modal de marcas
document.addEventListener("DOMContentLoaded", function () {
  document.querySelectorAll(".botonEditarMarcas").forEach(function (button) {
    button.addEventListener("click", function () {
      var idMarca = this.value;
      fetch("ajaxData.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: "idMarcaEditar=" + encodeURIComponent(idMarca),
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.error) {
            alert(data.error);
          } else {
            document.getElementById("idMarca").value = data.idMarca;
            document.getElementById("nombreMarca").value = data.nombreMarca;
            var myModal = new bootstrap.Modal(
              document.getElementById("editarMarcaModal")
            );
            myModal.show();
          }
        });
    });
  });
  document.querySelectorAll(".botonEditarYear").forEach(function (button) {
    button.addEventListener("click", function () {
      var idYear = this.value;
      fetch("ajaxData.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: "idYearEditar=" + encodeURIComponent(idYear),
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.error) {
            alert(data.error);
          } else {
            document.getElementById("idYear").value = data.idYear;
            document.getElementById("year").value = data.year;
            var myModal = new bootstrap.Modal(
              document.getElementById("editarYearModal")
            );
            myModal.show();
          }
        });
    });
  });
  document.querySelectorAll(".botonEditarTipos").forEach(function (button) {
    button.addEventListener("click", function () {
      var idTipo = this.value;
      fetch("ajaxData.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: "idTipoEditar=" + encodeURIComponent(idTipo),
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.error) {
            alert(data.error);
          } else {
            document.getElementById("idTipo").value = data.idTipo;
            document.getElementById("pvp").value = data.pvp;
            document.getElementById("nombreTipo").value = data.nombreTipo;
            // Eliminar backdrop existente para prevenir duplicados
            document
              .querySelectorAll(".modal-backdrop")
              .forEach((el) => el.remove());

            // Cerrar otros modales abiertos
            document
              .querySelectorAll(".modal.show")
              .forEach(function (openModal) {
                bootstrap.Modal.getInstance(openModal).hide();
              });
            var myModal = new bootstrap.Modal(
              document.getElementById("editarTipoModal")
            );
            myModal.show();
          }
        });
    });
  });
  document.querySelectorAll(".botonEditarColores").forEach(function (button) {
    button.addEventListener("click", function () {
      var idTipo = this.value;
      fetch("ajaxData.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: "idColorEditar=" + encodeURIComponent(idTipo),
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.error) {
            alert(data.error);
          } else {
            document.getElementById("idColor").value = data.idColor;
            document.getElementById("nombreColor").value = data.nombreColor;
            document.getElementById("thumb").value = data.thumb;
            // Eliminar backdrop existente para prevenir duplicados
            document
              .querySelectorAll(".modal-backdrop")
              .forEach((el) => el.remove());

            // Cerrar otros modales abiertos
            document
              .querySelectorAll(".modal.show")
              .forEach(function (openModal) {
                bootstrap.Modal.getInstance(openModal).hide();
              });
            var myModal = new bootstrap.Modal(
              document.getElementById("editarColorModal")
            );
            myModal.show();
          }
        });
    });
  });
  document
    .getElementById("guardarCambiosMarca")
    .addEventListener("click", function () {
      var formData = new URLSearchParams(
        new FormData(document.getElementById("formEditarMarca"))
      ).toString();
      console.log("Datos enviados:", formData); // Verifica los datos que se envían

      fetch("guardarCambios.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: formData,
      })
        .then((response) => response.text())
        .then((response) => {
          console.log("Respuesta del servidor:", response); // Verifica la respuesta del servidor
          if (response.includes("exitosamente")) {
            alert("Marca actualizada exitosamente.");
            var myModalEl = document.getElementById("editarMarcaModal");
            var modal = bootstrap.Modal.getInstance(myModalEl);
            modal.hide();
            location.reload(); // Recargar la página para reflejar los cambios
          } else {
            alert("Error al guardar los cambios: " + response);
          }
        })
        .catch((error) => {
          alert("Ocurrió un error al guardar los cambios.");
          console.error("Error:", error);
        });
    });
    document
    .getElementById("guardarCambiosYear")
    .addEventListener("click", function () {
      var formData = new URLSearchParams(
        new FormData(document.getElementById("formEditarYear"))
      ).toString();
      console.log("Datos enviados:", formData); // Verifica los datos que se envían

      fetch("guardarCambios.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: formData,
      })
        .then((response) => response.text())
        .then((response) => {
          console.log("Respuesta del servidor:", response); // Verifica la respuesta del servidor
          if (response.includes("exitosamente")) {
            alert("Año actualizado exitosamente.");
            var myModalEl = document.getElementById("editarYearModal");
            var modal = bootstrap.Modal.getInstance(myModalEl);
            modal.hide();
            location.reload(); // Recargar la página para reflejar los cambios
          } else {
            alert("Error al guardar los cambios: " + response);
          }
        })
        .catch((error) => {
          alert("Ocurrió un error al guardar los cambios.");
          console.error("Error:", error);
        });
    });
  document
    .getElementById("guardarCambiosModelo")
    .addEventListener("click", function () {
      var formData = new URLSearchParams(
        new FormData(document.getElementById("formEditarModelo"))
      ).toString();
      console.log("Datos enviados:", formData); // Verifica los datos que se envían

      fetch("guardarCambios.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: formData,
      })
        .then((response) => response.text())
        .then((response) => {
          console.log("Respuesta del servidor:", response); // Verifica la respuesta del servidor
          if (response.includes("exitosamente")) {
            alert("Modelo actualizada exitosamente.");
            var myModalEl = document.getElementById("editarModeloModal");
            var modal = bootstrap.Modal.getInstance(myModalEl);
            modal.hide();
            location.reload(); // Recargar la página para reflejar los cambios
          } else {
            alert("Error al guardar los cambios: " + response);
          }
        })
        .catch((error) => {
          alert("Ocurrió un error al guardar los cambios.");
          console.error("Error:", error);
        });
    });
  document
    .getElementById("guardarCambiosTipo")
    .addEventListener("click", function () {
      var formData = new URLSearchParams(
        new FormData(document.getElementById("formEditarTipo"))
      ).toString();
      console.log("Datos enviados:", formData); // Verifica los datos que se envían

      fetch("guardarCambios.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: formData,
      })
        .then((response) => response.text())
        .then((response) => {
          console.log("Respuesta del servidor:", response); // Verifica la respuesta del servidor
          if (response.includes("exitosamente")) {
            alert("Tipo actualizada exitosamente.");
            var myModalEl = document.getElementById("editarTipoModal");
            var modal = bootstrap.Modal.getInstance(myModalEl);
            modal.hide();
            location.reload(); // Recargar la página para reflejar los cambios
          } else {
            alert("Error al guardar los cambios: " + response);
          }
        })
        .catch((error) => {
          alert("Ocurrió un error al guardar los cambios.");
          console.error("Error:", error);
        });
    });
  document
    .getElementById("guardarCambiosColor")
    .addEventListener("click", function () {
      var formData = new URLSearchParams(
        new FormData(document.getElementById("formEditarColor"))
      ).toString();
      console.log("Datos enviados:", formData); // Verifica los datos que se envían

      fetch("guardarCambios.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: formData,
      })
        .then((response) => response.text())
        .then((response) => {
          console.log("Respuesta del servidor:", response); // Verifica la respuesta del servidor
          if (response.includes("exitosamente")) {
            alert("Color actualizada exitosamente.");
            var myModalEl = document.getElementById("editarColorModal");
            var modal = bootstrap.Modal.getInstance(myModalEl);
            modal.hide();
            location.reload(); // Recargar la página para reflejar los cambios
          } else {
            alert("Error al guardar los cambios: " + response);
          }
        })
        .catch((error) => {
          alert("Ocurrió un error al guardar los cambios.");
          console.error("Error:", error);
        });
    });
});
