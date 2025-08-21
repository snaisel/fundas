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
  $("#formularioStock").on("change", "#marcas", function () {
    $("#marcas option:selected").each(function () {
      var marcasID = $(this).val();
      var yearID = $("#year").val();
      if (marcasID) {
        $.post(
          "ajaxData.php",
          { refMarca: marcasID, refYear: yearID },
          function (data) {
            $("#modelos").html(data);
          }
        );
      } else {
        $("#modelos").html(
          '<option value="">Selecciona una marca primero</option>'
        );
      }
    });
  });
  $("#formularioStock").on("change", "#year", function () {
    $("#year option:selected").each(function () {
      var yearID = $(this).val();
      var marcasID = $("#marcas").val();
      if (yearID) {
        $.post(
          "ajaxData.php",
          { refYear: yearID, refMarca: marcasID },
          function (data) {
            $("#modelos").html(data);
          }
        );
      } else {
        $("#modelos").html(
          '<option value="">Selecciona una marca primero</option>'
        );
      }
    });
  });
  $("#formularioStock").on("change", "#modelos", function () {
    $("#modelos option:selected").each(function () {
      var modelID = $(this).val();
      if (modelID) {
        $.post("ajaxData.php", { refModel: modelID }, function (data) {
          $("#year").html(data);
        });
      }
    });
  });

  $("#color").on("change", function () {
    $("#color option:selected").each(function () {
      var colorID = $(this).val();
      var modelID = $("#modelos").val();
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
  $("#colorref").on("keyup", function () {
    var colorID = $(this).val();
    if (colorID) {
      $.post("ajaxData.php", { refColoroption: colorID }, function (data) {
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
    console.log(id);
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
  let marcaref = null;
  let yearref = null;
  let removeFilter = false;
  $("#listadoMarcas").on("click", ".botonListadoMarcas", function () {
    marcaref = $(this).val();
    $.ajax({
      type: "POST",
      url: "ajaxData.php",
      data: "idMarcaListado=" + marcaref,
      success: function (data) {
        $("#listadoModelos").html(data);
      },
    });
    filterByMarcaYear();
  });
  $("#listadoYear").on("click", ".botonListadoYear", function () {
    yearref = $(this).val();
    $.ajax({
      type: "POST",
      url: "ajaxData.php",
      data: "refYearListado=" + yearref,
      success: function (data) {
        $("#listadoModelos").html(data);
      },
    });
    filterByMarcaYear();
  });
  $("#listadoModelos").on("click", ".modeloFilter", function () {
    removeFilter = true;
    if ($(this).attr("id") === "yearFilter") {
      yearref = null;
      console.log("yearref is null");
    } else if ($(this).attr("id") === "marcaFilter") {
      marcaref = null;
      console.log("marcaref is null");
    }
    filterByMarcaYear();
  });
  function filterByMarcaYear() {
    if (marcaref && yearref) {
      $.post(
        "ajaxData.php",
        { refMarcaFilter: marcaref, refYearFilter: yearref },
        function (data) {
          $("#listadoModelos").html(data);
        }
      );
    }
    else if (yearref && !marcaref && removeFilter) {
      removeFilter = false;
      $.post(
        "ajaxData.php",
        { refYearListado: yearref },
        function (data) {
          $("#listadoModelos").html(data);
        }
      );
    }
    else if (marcaref && !yearref && removeFilter) {
      removeFilter = false;
      $.post(
        "ajaxData.php",
        { idMarcaListado: marcaref },
        function (data) {
          $("#listadoModelos").html(data);
        }
      );
    }
    else if (!marcaref && !yearref && removeFilter) {
      removeFilter = false;
      $.post(
        "ajaxData.php",
        { noFilters: true},
        function (data) {
          $("#listadoModelos").html(data);
        }
      );
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
            '  <div class="spinner-border text-info" style="width:14px; height:14px" role="status"><span class="visually-hidden">Loading...</span></div>'
          );
        },
        success: function (data) {
          /*console.log(id + " " + ascdesc + " " + page + " " + model);*/
          $("#tablastock").html(data);
          $("#filterModel").after(
            "<form action='functions.php' method ='post'><input type ='hidden' name='model' value =" +
              model +
              "><button type='submit' class='btn btn-primary' name='exportarByModel'>Exportar este modelo<i class='bi bi-arrow-right-short'></i></button></form>"
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
          '<div class="spinner-border text-info" style="width:14px; height:14px" role="status"><span class="visually-hidden">Loading...</span></div>'
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
  const choices = new Choices(document.querySelector(".choices-single"));
});
function selectModel(val) {
  $("#search-box").val(val);
  $("#suggesstion-box").hide();
  $.post("ajaxData.php", { nombreModelo: val }, function (data) {
    var array = JSON.parse(data);
    $("#modeloref").attr("value", array.refModelo);
    $("#marcas").val(array.refMarca).change();
    $("#year").val(array.refYear).change();
  });
}
