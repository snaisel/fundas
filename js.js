/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function () {
  var current = location.pathname.split("/").pop();
  $(".nav-link").each(function () {
    if ($(this).attr("href") === current) {
      $(this).addClass("active");
    }
  });
  $("#textoSumar").focus();
  $("#textoRestar").focus();
  $("#resetAll").click(function () {
    $("#marcas").toggle();
  });

  $("#seleccionarRels").on("change", function () {
    var str = "";
    $("option.modeloRel:selected").each(function () {
      str += "<span class='badge bg-secondary'>" + $(this).text() + "</span> ";
    });
    $("#resultadosRel").html(str);
  });
  $("#formularioStock").on("change", "#selectMarcas", function () {
    $("#selectMarcas option:selected").each(function () {
      var marcasID = $(this).val();
      var yearID = $("#selectYear").val();
      if (marcasID) {
        $.post(
          "ajaxData.php",
          { idMarca: marcasID, idYear: yearID, changeModelos: true },
          function (data) {
            $("#selectModelos").html(data);
          }
        );
      } else {
        $("#selectModelos").html(
          '<option value="">Selecciona una marca primero</option>'
        );
      }
    });
  });
  $("#formularioStock").on("change", "#selectYear", function () {
    $("#selectYear option:selected").each(function () {
      var yearID = $(this).val();
      var marcasID = $("#selectMarcas").val();
      if (yearID) {
        $.post(
          "ajaxData.php",
          { idYear: yearID, idMarca: marcasID, changeModelos: true },
          function (data) {
            $("#selectModelos").html(data);
          }
        );
      } else {
        $("#selectModelos").html(
          '<option value="">Selecciona una marca primero</option>'
        );
      }
    });
  });
  $("#formularioStock").on("change", "#selectModelos", function () {
    $("#selectModelos option:selected").each(function () {
      var modelID = $(this).val();
      var colorID = $("#color").val();
      var tipoID = $("#tipo").val();
      if (colorID && tipoID) {
        $.post(
          "ajaxData.php",
          { refModelo: modelID, refTipo: tipoID, refColor: colorID },
          function (data) {
            $("#stock").html(data);
          }
        );
      }
      if (modelID) {
        $.post(
          "ajaxData.php",
          { idModelo: modelID, changeModelos: true},
          function (data) {
            $("#selectYear").html(data);
          }
        );
      }
    });
  });
  $("#formularioStock").on("click", "#resetFormulario", function () {
    console.log("resetFormulario clicked");
    $.post("ajaxData.php", { resetFormulario: true }, function (data) {
      $("#formularioStock").html(data);
    });
  });
  $("#color").on("change", function () {
    $("#color option:selected").each(function () {
      var colorID = $(this).val();
      var modelID = $("#selectModelos").val();
      var tipoID = $("#tipo").val();
      if (colorID) {
        $.post(
          "ajaxData.php",
          { refModelo: modelID, refTipo: tipoID, refColor: colorID },
          function (data) {
            $("#stock").html(data);
          }
        );
      } else {
        $("#stock").html('<input type="number" name="stock" required>');
      }
    });
  });
  $("#tipo").on("change", function () {
    $("#tipo option:selected").each(function () {
      var tipoID = $(this).val();
      var modelID = $("#selectModelos").val();
      var colorID = $("#color").val();
      if (tipoID) {
        $.post(
          "ajaxData.php",
          { refModelo: modelID, refTipo: tipoID, refColor: colorID },
          function (data) {
            $("#stock").html(data);
          }
        );
      } else {
        $("#stock").html('<input type="number" name="stock" required>');
      }
    });
  });
  $("#colorref").on("keyup", function () {
    var colorRef = $(this).val();
    if (colorRef) {
      $.post("ajaxData.php", { refColoroption: colorRef }, function (data) {
        $("#colorreftext").html(data);
      });
    } else {
      $("#colorreftext").html("No hay resultados");
    }
  });

  $("#search-box").on("keyup", function () {
    $.ajax({
      type: "POST",
      url: "ajaxData.php",
      data: "keyword=" + $(this).val(),
      beforeSend: function () {
        $("#search-box").css(
          "background",
          "#FFF url(LoaderIcon.gif) no-repeat 165px"
        );
      },
      success: function (data) {
        $("#suggesstion-box").show();
        $("#suggesstion-box").html(data);
        $("#search-box").css("background", "#FFF");
      },
    });
  });
  $("#tablastock").on("click", ".botoneliminar", function () {
    var eliminar = confirm("¿Estas Seguro de eliminar esta funda?");
    if (eliminar) {
      var id = $(this).val();
      $.ajax({
        type: "POST",
        url: "ajaxData.php",
        data: "idStock=" + $(this).val(),
        success: function (data) {
          $(id).html(data);
          $("#fila" + id).remove();
        },
      });
    }
  });
  $("#tablastock").on("click", ".botoneditar", function () {
    var id = $(this).val();
    $.ajax({
      type: "POST",
      url: "ajaxData.php",
      data: "idStockEditar=" + $(this).val(),
      success: function (data) {
        $("html, body").animate({ scrollTop: 0 }, 300);
        $("#formularioStock").html(data);
      },
    });
  });
  $("#comprobarCodigo").on("click", "#enviarCodigo", function () {
    var id = $("#codigoFunda").val();
    $.ajax({
      type: "POST",
      url: "ajaxData.php",
      data: "idStockEditar=" + id,
      success: function (data) {
        $("html, body").animate({ scrollTop: 0 }, 300);
        $("#formularioStock").html(data);
      },
    });
  });
  $("#codigoFunda").on("keypress", function (e) {
    if (e.which === 13) {
      var id = $("#codigoFunda").val();
      console.log(id);
      $.ajax({
        type: "POST",
        url: "ajaxData.php",
        data: "idStockEditar=" + id,
        success: function (data) {
          $("html, body").animate({ scrollTop: 0 }, 300);
          $("#formularioStock").html(data);
        },
      });
    }
  });
  $("#GrupoRelacionados").on(
    "click",
    ".eliminarRelaciones .eliminarRelacion",
    function () {
      var eliminar = confirm("¿Estas Seguro de eliminar esta relación?");
      if (eliminar) {
        var id = $(this).val();
        $.ajax({
          type: "POST",
          url: "ajaxData.php",
          data: "idRelEditar=" + $(this).val(),
          success: function () {
            $("#relacionados" + id).remove();
          },
        });
      }
    }
  );
  let marcaid = null;
  let yearid = null;
  let removeFilter = false;
  $("#listadoMarcas").on("click", ".botonListadoMarcas", function () {
    marcaid = $(this).val();
    var $btn = $(this);
    $.ajax({
      type: "POST",
      url: "ajaxData.php",
      data: "idMarcaListado=" + marcaid,
      beforeSend: function () {
        $btn.after(
          '  <div class="spinner-border text-info spinner-temp" style="width:14px; height:14px" role="status"><span class="visually-hidden">Loading...</span></div>'
        );
      },
      success: function (data) {
        $("#listadoModelos").html(data);
      },
      complete: function () {
        $(".spinner-temp").remove();
      },
    });
    filterByMarcaYear();
  });
  $("#listadoYear").on("click", ".botonListadoYear", function () {
    yearid = $(this).val();
    var $btn = $(this);
    $.ajax({
      type: "POST",
      url: "ajaxData.php",
      data: "idYearListado=" + yearid,
      beforeSend: function () {
        $btn.after(
          '  <div class="spinner-border text-info spinner-temp" style="width:14px; height:14px" role="status"><span class="visually-hidden">Loading...</span></div>'
        );
      },
      success: function (data) {
        $("#listadoModelos").html(data);
      },
      complete: function () {
        $(".spinner-temp").remove();
      },
    });
    filterByMarcaYear();
  });
  $("#listadoModelos").on("click", ".modeloFilter", function () {
    removeFilter = true;
    if ($(this).attr("id") === "yearFilter") {
      yearid = null;
      console.log("yearid is null");
    } else if ($(this).attr("id") === "marcaFilter") {
      marcaid = null;
      console.log("marcaid is null");
    }
    $(this).after(
      '  <div class="spinner-border text-info spinner-temp" style="width:14px; height:14px" role="status"><span class="visually-hidden">Loading...</span></div>'
    );
    filterByMarcaYear();
  });
  function filterByMarcaYear() {
    if (marcaid && yearid) {
      $.post(
        "ajaxData.php",
        { idMarcaFilter: marcaid, idYearFilter: yearid },
        function (data) {
          $("#listadoModelos").html(data);
        }
      );
    } else if (yearid && !marcaid && removeFilter) {
      removeFilter = false;
      $.post("ajaxData.php", { idYearListado: yearid }, function (data) {
        $("#listadoModelos").html(data);
      });
    } else if (marcaid && !yearid && removeFilter) {
      removeFilter = false;
      $.post("ajaxData.php", { idMarcaListado: marcaid }, function (data) {
        $("#listadoModelos").html(data);
      });
    } else if (!marcaid && !yearid && removeFilter) {
      removeFilter = false;
      $.post("ajaxData.php", { noFilters: true }, function (data) {
        $("#listadoModelos").html(data);
      });
    }
  }
  $("#listadoMarcas").on("click", ".botonEliminarMarcas", function () {
    // Muestra el cuadro de confirmación
    if (confirm("¿Estás seguro de que deseas eliminar esta marca?")) {
      // Si el usuario confirma, realiza la solicitud AJAX
      $.ajax({
        type: "POST",
        url: "ajaxData.php",
        data: "idMarcaEliminar=" + $(this).val(),
        success: function (data) {
          // Recarga la página tras el éxito de la solicitud
          location.reload();
        },
        error: function (xhr, status, error) {
          // Muestra un mensaje de error si la solicitud falla
          alert("Ocurrió un error al intentar eliminar la marca: " + error);
        },
      });
    } else {
      return false;
    }
  });
  $("#listadoYear").on("click", ".botonEliminarYear", function () {
    // Muestra el cuadro de confirmación
    if (confirm("¿Estás seguro de que deseas eliminar este año?")) {
      // Si el usuario confirma, realiza la solicitud AJAX
      $.ajax({
        type: "POST",
        url: "ajaxData.php",
        data: "idYearEliminar=" + $(this).val(),
        success: function (data) {
          // Recarga la página tras el éxito de la solicitud
          location.reload();
        },
        error: function (xhr, status, error) {
          // Muestra un mensaje de error si la solicitud falla
          alert("Ocurrió un error al intentar eliminar el año: " + error);
        },
      });
    } else {
      return false;
    }
  });
  $("#listadoModelos").on("click", ".botonEliminarModelos", function () {
    // Muestra el cuadro de confirmación
    if (confirm("¿Estás seguro de que deseas eliminar este modelo?")) {
      // Si el usuario confirma, realiza la solicitud AJAX
      $.ajax({
        type: "POST",
        url: "ajaxData.php",
        data: "idModeloEliminar=" + $(this).val(),
        success: function (data) {
          // Recarga la página tras el éxito de la solicitud
          location.reload();
        },
        error: function (xhr, status, error) {
          // Muestra un mensaje de error si la solicitud falla
          alert("Ocurrió un error al intentar eliminar el modelo: " + error);
        },
      });
    } else {
      return false;
    }
  });
  $("#listadoModelos").on("click", ".botonEditarModelos", function () {
    var idModelo = this.value;
    fetch("ajaxData.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: "idModeloEditar=" + encodeURIComponent(idModelo),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.error) {
          alert(data.error);
        } else {
          document.getElementById("idModelo").value = data.idModelo;
          document.getElementById("nombreModelo").value = data.nombreModelo;
          document.getElementById("refModelo").value = data.refModelo;
          document.getElementById("selectYear").value = data.idYear;
          document.getElementById("selectMarcas").value = data.idMarca;
          var myModal = new bootstrap.Modal(
            document.getElementById("editarModeloModal")
          );
          myModal.show();
        }
      });
  });
  $("#listadoTipos").on("click", ".botonEliminarTipos", function () {
    // Muestra el cuadro de confirmación
    if (confirm("¿Estás seguro de que deseas eliminar este tipo?")) {
      // Si el usuario confirma, realiza la solicitud AJAX
      $.ajax({
        type: "POST",
        url: "ajaxData.php",
        data: "idTipoEliminar=" + $(this).val(),
        success: function (data) {
          // Recarga la página tras el éxito de la solicitud
          location.reload();
        },
        error: function (xhr, status, error) {
          // Muestra un mensaje de error si la solicitud falla
          alert("Ocurrió un error al intentar eliminar el tipo: " + error);
        },
      });
    } else {
      return false;
    }
  });
  $("#listadoColores").on("click", ".botonEliminarColores", function () {
    // Muestra el cuadro de confirmación
    if (confirm("¿Estás seguro de que deseas eliminar este color?")) {
      // Si el usuario confirma, realiza la solicitud AJAX
      $.ajax({
        type: "POST",
        url: "ajaxData.php",
        data: "idColorEliminar=" + $(this).val(),
        success: function (data) {
          // Recarga la página tras el éxito de la solicitud
          location.reload();
        },
        error: function (xhr, status, error) {
          // Muestra un mensaje de error si la solicitud falla
          alert("Ocurrió un error al intentar eliminar el color: " + error);
        },
      });
    } else {
      return false;
    }
  });

  $("#tablastock").on("click", ".ordenar", function () {
    var ascdesc = $("#cuerpostock").attr("class");
    if (ascdesc == "ASC") {
      ascdesc = "DESC";
    } else {
      ascdesc = "ASC";
    }
    var id = $(this).attr("id");
    var model = $("#filtermodel option").filter(":selected").val();
    $.ajax({
      type: "POST",
      url: "ajaxData.php",
      data: { parametro: $(this).attr("id"), ascdesc: ascdesc, model: model },
      beforeSend: function () {
        $("#" + id + ".ordenar").after(
          '  <div class="spinner-border text-info" style="width:14px; height:14px" role="status"><span class="visually-hidden">Loading...</span></div>'
        );
      },
      success: function (data) {
        console.log(id + " " + ascdesc + " " + model);
        $("#tablastock").html(data);
        if (id != "idStock") {
          $("#idStock.ordenar").removeClass("active");
        }
        $("#" + id + ".ordenar").addClass("active");
        if (ascdesc == "ASC") {
          $("#" + id + ".ordenar").append(
            '<i class="bi bi-arrow-down-circle"></i>'
          );
        } else if (ascdesc == "DESC") {
          $("#" + id + ".ordenar").append(
            '<i class="bi bi-arrow-up-circle"></i>'
          );
        }
      },
    });
  });
  $("#tablastock").on("click", ".page-item .page-link", function () {
    var id = $(".ordenar.active").attr("id");
    var ascdesc = $("#cuerpostock").attr("class");
    var model = $("#filtermodel option").filter(":selected").val();
    var page = $(this).attr("id");
    $.ajax({
      type: "POST",
      url: "ajaxData.php",
      data: { parametro: id, ascdesc: ascdesc, page: page, model: model },
      success: function (data) {
        $("#tablastock").html(data);
        if (id != "idStock") {
          $("#idStock.ordenar").removeClass("active");
        }
        $("#" + id + ".ordenar").addClass("active");
        if (ascdesc == "ASC") {
          $("#" + id + ".ordenar").append(
            '<i class="bi bi-arrow-down-circle"></i>'
          );
        } else if (ascdesc == "DESC") {
          $("#" + id + ".ordenar").append(
            '<i class="bi bi-arrow-up-circle"></i>'
          );
        }
      },
    });
  });
  $("#tablastock").on("change", "#filterModel", function () {
    $("#filterModel option:selected").each(function () {
      var id = $(".ordenar.active").attr("id");
      var ascdesc = $("#cuerpostock").attr("class");
      var page = $(".page-link.active").attr("id");
      var model = $(this).val();
      $.ajax({
        type: "POST",
        url: "ajaxData.php",
        data: { parametro: id, ascdesc: ascdesc, page: page, model: model },
        beforeSend: function () {
          $("#filterModel").after(
            '<span class="spinner-border text-info" style="width:14px; height:14px" role="status"><span class="visually-hidden">Loading...</span></span>'
          );
        },
        success: function (data) {
          $("#tablastock").html(data);
          $("#filterModel").after(
            "<form action='functions.php' method ='post'><input type ='hidden' name='model' value =" +
              model +
              "><button type='button' class='btn btn-primary' name='exportarByModel' id='exportarByModel'>Exportar este modelo<i class='bi bi-arrow-right-short'></i>" +
              "<span id='exportarModelSpinner' class='spinner-border spinner-border-sm ms-2 d-none' role='status' aria-hidden='true'></span>" +
              "</button><div class='form-group'><input type='checkbox' name='stock0modelo' id='stock0modelo'><label for='stock0modelo'>Incluir Fundas con Stock 0</label></div></form>"
          );
          // Re-initialize Choices.js on #filterModel
          if (window.choicesInstance) {
            window.choicesInstance.destroy();
          }
          window.choicesInstance = new Choices(
            document.getElementById("filterModel")
          );
          $("#" + id + ".ordenar").addClass("active");
        },
      });
    });
  });
  //revisar a partir de aquí
  $("#modelos-page").on("click", ".order", function () {
    var ascdesc = $("#orderby").attr("class");
    console.log(ascdesc + " ");
    if (ascdesc == "ASC") {
      ascdesc = "DESC";
    } else {
      ascdesc = "ASC";
    }
    var id = $(this).attr("id");
    //var model = $('#filtermodel option').filter(':selected').val();
    $.ajax({
      type: "POST",
      url: "ajaxData_1.php",
      data: { parametro: $(this).attr("id"), orderby: ascdesc },
      beforeSend: function () {
        $("#" + id + ".order").after(
          '<span class="spinner-border text-info" style="width:14px; height:14px" role="status"><span class="visually-hidden">Loading...</span></span>'
        );
      },
      success: function (data) {
        console.log(id + " " + ascdesc + " ");
        $("#tablamodelos").html(data);
        if (id != "idModelo") {
          $("#idModelo.order").removeClass("active");
        }
        $("#" + id + ".order").addClass("active");
        if (ascdesc == "ASC") {
          $("#" + id + ".order").append(
            '<i class="bi bi-arrow-down-circle"></i>'
          );
        } else if (ascdesc == "DESC") {
          $("#" + id + ".order").append(
            '<i class="bi bi-arrow-up-circle"></i>'
          );
        }
      },
    });
  });
  $("#modelos-page").on("click", ".page-item .page-link", function () {
    var id = $("#headModelos .order.active").attr("id");
    var orderby = $("#orderby").attr("class");
    //var model = $('#filtermodel option').filter(':selected').val();
    var page = $(this).attr("id");
    console.log(id + " " + page);
    $.ajax({
      type: "POST",
      url: "ajaxData_1.php",
      data: { parametro: id, orderby: orderby, page: page },
      success: function (data) {
        $("#tablamodelos").html(data);
        if (id != "idModelo") {
          $("#idModelo.order").removeClass("active");
        }
        $("#" + id + ".order").addClass("active");
        if (ascdesc == "ASC") {
          $("#" + id + ".order").append(
            '<i class="bi bi-arrow-down-circle"></i>'
          );
        } else if (ascdesc == "DESC") {
          $("#" + id + ".order").append(
            '<i class="bi bi-arrow-up-circle"></i>'
          );
        }
      },
    });
  });

  $("#datepickerDiv").on("focus", ".datepicker", function () {
    $("#datepicker").datepicker();
  });
  $("#exportarBtn").on("click", function (e) {
    e.preventDefault();
    $("#exportarSpinner").removeClass("d-none").show();
    $("#exportarBtn").prop("disabled", true);
    var form = $(this).closest("form");
    $.ajax({
      url: form.attr("action"),
      type: "POST",
      data: form.serialize() + "&exportar=1",
      xhrFields: {
        responseType: "blob",
      },
      success: function (blob, status, xhr) {
        var filename = "";
        var disposition = xhr.getResponseHeader("Content-Disposition");
        if (disposition && disposition.indexOf("filename=") !== -1) {
          filename = disposition.split("filename=")[1].replace(/['\"]/g, "");
        } else {
          filename = "data_export.csv";
        }
        var link = document.createElement("a");
        var url = window.URL.createObjectURL(blob);
        link.href = url;
        link.download = filename;
        document.body.appendChild(link);
        link.click();
        setTimeout(function () {
          window.URL.revokeObjectURL(url);
          document.body.removeChild(link);
        }, 100);
        $("#exportarSpinner").addClass("d-none").hide();
        $("#exportarBtn").prop("disabled", false);
      },
      error: function () {
        alert("Error al exportar.");
        $("#exportarSpinner").addClass("d-none").hide();
        $("#exportarBtn").prop("disabled", false);
      },
    });
  });
  $("#datepickerSubmit").on("click", function (e) {
    e.preventDefault();
    $("#exportarFechaSpinner").removeClass("d-none").show();
    $("#datepickerSubmit").prop("disabled", true);
    var form = $(this).closest("form");
    $.ajax({
      url: form.attr("action"),
      type: "POST",
      data: form.serialize() + "&datepickerSubmit=1",
      xhrFields: {
        responseType: "blob",
      },
      success: function (blob, status, xhr) {
        var filename = "";
        var disposition = xhr.getResponseHeader("Content-Disposition");
        if (disposition && disposition.indexOf("filename=") !== -1) {
          filename = disposition.split("filename=")[1].replace(/['\"]/g, "");
        } else {
          filename = "data_export.csv";
        }
        var link = document.createElement("a");
        var url = window.URL.createObjectURL(blob);
        link.href = url;
        link.download = filename;
        document.body.appendChild(link);
        link.click();
        setTimeout(function () {
          window.URL.revokeObjectURL(url);
          document.body.removeChild(link);
        }, 100);
        $("#exportarFechaSpinner").addClass("d-none").hide();
        $("#datepickerSubmit").prop("disabled", false);
      },
      error: function () {
        alert("Error al exportar.");
        $("#exportarFechaSpinner").addClass("d-none").hide();
        $("#datepickerSubmit").prop("disabled", false);
      },
    });
  });
  $("#tablastock").on("click", "#exportarByModel", function (e) {
    e.preventDefault();
    $("#exportarModelSpinner").removeClass("d-none").show();
    $("#exportarByModel").prop("disabled", true);
    var form = $(this).closest("form");
    $.ajax({
      url: form.attr("action"),
      type: "POST",
      data: form.serialize() + "&exportarByModel=1",
      xhrFields: {
        responseType: "blob",
      },
      success: function (blob, status, xhr) {
        var filename = "";
        var disposition = xhr.getResponseHeader("Content-Disposition");
        if (disposition && disposition.indexOf("filename=") !== -1) {
          filename = disposition.split("filename=")[1].replace(/['\"]/g, "");
        } else {
          filename = "data_export.csv";
        }
        var link = document.createElement("a");
        try {
          var url = window.URL.createObjectURL(blob);
        } catch (error) {
          console.error("Error creating object URL:", error);
          alert("Error al exportar. Posiblemente no existan referencias");
          $("#exportarModelSpinner").addClass("d-none").hide();
          $("#exportarByModel").prop("disabled", false);
          location.reload();
          return;
        }
        link.href = url;
        link.download = filename;
        document.body.appendChild(link);
        link.click();
        setTimeout(function () {
          window.URL.revokeObjectURL(url);
          document.body.removeChild(link);
        }, 100);
        $("#exportarModelSpinner").addClass("d-none").hide();
        $("#exportarByModel").prop("disabled", false);
      },
      error: function () {
        alert("Error al exportar.");
        $("#exportarModelSpinner").addClass("d-none").hide();
        $("#exportarByModel").prop("disabled", false);
      },
    });
  });
  if($('#tablastock').length>0){
    const choices = new Choices(document.getElementById("filterModel"));
  }
});
function selectModel(val) {
  $("#search-box").val(val);
  $("#suggesstion-box").hide();
  $.post("ajaxData.php", { nombreModelo: val }, function (data) {
    var array = JSON.parse(data);
    $("#modeloref").attr("value", array.refModelo);
    $("#marcas").val(array.idMarca).change();
    $("#year").val(array.idYear).change();
  });
}
