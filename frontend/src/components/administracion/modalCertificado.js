import React, { useRef, useState, useEffect } from "react";
import Modal from "react-bootstrap/Modal";
/* import { Mensaje, MensajeSiNo } from "../mensajes"; */
import { Loader, Dimmer, Label } from "semantic-ui-react";
import {
  validaSoloNumero,
  formatMoneda,
  validaMonto,
  formatoMonto,
  validaNumeroTelefono,
  validaEmail,
  validaSoloLetras,
} from "../../util/varios";

import axios from "axios";
import moment from "moment";
import { Mensaje } from "../mensajes";
import CatalogoClientes from "../../catalogos/catalogoClientes";
const dolarbcv = JSON.parse(localStorage.getItem("dolarbcv"));
export const ModalCertificadoMedico = (props) => {
  /*  variables de estados */

  let op = require("../../modulos/datos");
  let token = localStorage.getItem("jwtToken");
  const idCliente = useRef();
  const idMedico = useRef();
  const idCobertura = useRef();
  const txtEdad = useRef();
  const txtNombre = useRef();
  const txtTipoSangre = useRef();
  const txtCedula = useRef();
  const cmbLentes = useRef();
  const cmbPago = useRef();
  const cmbNacionalidad = useRef();

  const txtDatosPastor = useRef();
  const txtReferencia = useRef();
  const txtBs = useRef();
  const txtDolar = useRef();

  const txtFechaNaci = useRef();
  const txtApellido = useRef();
  const [edad, setEdad] = useState("");

  const [values, setValues] = useState({
    ced: "",
    nombre: "",
    apellido: "",
    fecha_nac: "",
    bas_agua: 1,

    status: 1,
    bas_espirit: 1,
    cod_iglesia: "",
    sexo: "M",
    fecha_baus: "",
    nacionalidad: "V",
    direccion: "",
    telefono: "",
    celular: "",
    estadocivil: 0,
    correo: "",
    tiposangre: "",
  });

  const btnCancela = useRef();
  const [mensaje, setMensaje] = useState({
    mostrar: false,
    titulo: "",
    texto: "",
    icono: "",
  });

  const btnAcepta = useRef();

  const [activate, setActivate] = useState(false);
  const [mostrar, setMostrar] = useState(false);

  const [operacion, setOperacion] = useState(0);

  /*********************************************** FUNCINES DE VALIDACION***********************************************************/

  const handleInputNumChange = (event) => {
    event.preventDefault();
    validaSoloNumero(event);
    var valido;
    if (event.which === 13 || typeof event.which === "undefined") {
      setValues({ ...values, [event.target.name]: event.target.value });
    } else if (event.which === 46) {
      return false;
    } else if (event.which >= 48 && event.which <= 57) {
      return true;
    } else if (event.which === 8 || event.which === 0 || event.which === 44) {
      return true;
    } else return false; //alert(e.which);
  };

  const salir = () => {
    props.onHideCancela();
    setValues({
      ced: "",
      nombre: "",
      apellido: "",
      fecha_nac: "",
      bas_agua: 1,

      status: 1,
      bas_espirit: 1,
      cod_iglesia: "",
      sexo: "M",
      fecha_baus: "",
      nacionalidad: "V",
      direccion: "",
      telefono: "",
      celular: "",
      estadocivil: 0,
      correo: "",
      tiposangre: "",
    });
  };
  const seleccionarRegistros = async (id) => {
    let endpoint = op.conexion + "/medico/ConsultarUno";
    console.log(endpoint);
    let bodyF = new FormData();
    bodyF.append("ID", id);
    await fetch(endpoint, {
      method: "POST",
      body: bodyF,
    })
      .then((res) => res.json())
      .then((response) => {
        setActivate(false);
        idCliente.current.value = response[0].cliente_id;
        idMedico.current.value = response[0].medico_id;
        idCobertura.current.value = response[0].nota_id;
        var cedula = response[0].cliente_cedula.split("-");
        cmbNacionalidad.current.value = cedula[0] + "-";
        txtCedula.current.value = cedula[1];
        txtNombre.current.value = response[0].cliente_nombre;
        txtApellido.current.value = response[0].cliente_apellido;
        txtFechaNaci.current.value = response[0].cliente_fechaNacimiento;
        txtEdad.current.value = response[0].medico_edad;
        txtTipoSangre.current.value = response[0].medico_tipoSangre;
        cmbLentes.current.value = response[0].medico_lente;
        cmbPago.current.value = response[0].nota_tipoPago;
        txtReferencia.current.value = response[0].nota_referencia;
      })
      .catch((error) =>
        setMensaje({
          mostrar: true,
          titulo: "Notificación",
          texto: error.res,
          icono: "informacion",
        })
      );
  };
  const actualizarCertificado = async () => {
    let endpoint;
    if (operacion === 3) {
      endpoint = op.conexion + "/medico/Editar";
    } else {
      endpoint = op.conexion + "/poliza/registrarCertificado";
    }
    console.log(endpoint);
    setActivate(true);
    //setLoading(false);
    let bodyF = new FormData();
    bodyF.append("idCliente", idCliente.current.value);
    bodyF.append("idMedico", idMedico.current.value);
    bodyF.append("idCobertura", idCobertura.current.value);
    bodyF.append("Nombre", txtNombre.current.value);
    bodyF.append("Apellido", txtApellido.current.value);
    bodyF.append(
      "Cedula",
      cmbNacionalidad.current.value + txtCedula.current.value
    );
    bodyF.append("fechaNacimiento", txtFechaNaci.current.value);
    bodyF.append("Edad", txtEdad.current.value);
    bodyF.append("tipoSangre", txtTipoSangre.current.value);
    bodyF.append("Lente", cmbLentes.current.value);
    bodyF.append("metodoPago", cmbPago.current.value);
    bodyF.append("Referencia", txtReferencia.current.value);
    bodyF.append("cantidadDolar", txtDolar.current.value);
    bodyF.append("Telefono", null);
    bodyF.append("Direccion", null);
    bodyF.append("precioDolar", dolarbcv.toFixed(2));
    bodyF.append("token", token);
    await fetch(endpoint, {
      method: "POST",
      body: bodyF,
    })
      .then((res) => res.json())
      .then((response) => {
        setActivate(false);
        console.log(response);
        if (response.code == 200) {
          setMensaje({
            mostrar: true,
            titulo: "Exito.",
            texto: response.res,
            icono: "exito",
          });
          if (operacion != 3) {
            window.open(
              `${op.conexion}/reporte/reporteMedico?ID=${response.id}`
            );
          }
        }
        if (response.code == 400) {
          setMensaje({
            mostrar: true,
            titulo: "Error.",
            texto: response.res,
            icono: "error",
          });
        }
      })
      .catch((error) =>
        setMensaje({
          mostrar: true,
          titulo: "Notificación",
          texto: error.res,
          icono: "informacion",
        })
      );
  };

  const onChangeValidar = () => {
    let sigue = true;
    let minimo = 0;
    let calculo = 0;

    /*  else if( && operacion === 1){
          setMensaje({
              mostrar: true,
              titulo: "Notificación",
              texto: "Ya existe un usuario con este n° de cedula",
              icono: "informacion",
            });
            sigue = false;
            txtCedula.current.focus()
  
      } */

    if (sigue) {
      actualizarCertificado();
    }
  };

  const blanquear = () => {
    setValues({
      ced: "",
      nombre: "",
      apellido: "",
      fecha_nac: "",
      bas_agua: 1,

      status: 1,
      bas_espirit: 1,
      cod_iglesia: "",
      sexo: "M",
      fecha_baus: "",
      nacionalidad: "V",
      direccion: "",
      telefono: "",
      celular: "",
      estadocivil: 0,
      correo: "",
      tiposangre: "",
    });
  };

  const check = (e) => {
    var textV = "which" in e ? e.which : e.keyCode,
      char = String.fromCharCode(textV),
      regex = /[a-z]/gi;
    if (!regex.test(char)) e.preventDefault();
    return false;
  };
  const seleccionarCliente = (nombre, apellido, cedula) => {
    console.log(nombre, apellido, cedula);
    txtCedula.current.value = cedula;
    txtApellido.current.value = apellido;
    txtNombre.current.value = nombre;
    let item = document.getElementById('ced');
    item.className -= ' form-text fw-bold visible ';
    item.className += ' form-text fw-bold hidden ';
    setMostrar(false);
  };

  const cerrarModal = () => {
    setMensaje({ mostrar: false, titulo: "", texto: "", icono: "" });
    props.onHideCancela();
  };

  function soloLetras(event) {
    if (
      (event.keyCode != 32 && event.keyCode < 65) ||
      (event.keyCode > 90 && event.keyCode < 97) ||
      event.keyCode > 122
    )
      event.returnValue = false;
  }
  function calcularEdad() {
    const fechaNacimiento = new Date(txtFechaNaci.current.value);
    fechaNacimiento.setHours(0, 0, 0, 0);

    const fechaActual = new Date();
    fechaActual.setHours(0, 0, 0, 0);

    const diferenciaDias = Math.floor(
      (fechaActual - fechaNacimiento) / (1000 * 60 * 60 * 24)
    );
    const edadCalculada = Math.floor(diferenciaDias / 365.25);

    // Actualizar el estado de la variable 'edad' con el valor calculado
    setEdad(edadCalculada);
  }
  const handleChange = (maxValue) => (e) => {
    const inputValue = e.target.value;
    // Verificar si la longitud del valor ingresado supera el valor máximo
    if (isNaN(inputValue)) {
      if (inputValue.length > maxValue && e.key !== "Backspace") {
        e.preventDefault(); // Evitar que se escriba el valor excedente
      }
    } else {
      if (
        inputValue.length >= maxValue &&
        e.key !== "Backspace" &&
        e.key !== " "
      ) {
        e.preventDefault(); // Evitar que se escriba el valor excedente
      }
    }
  };
  const handleInputMontoChange = (event) => {
    validaMonto(event);
    if (event.which === 13 || typeof event.which === "undefined") {
      if (event.target.name === "dolar") {
        let bs = parseFloat(dolarbcv);
        let total = parseFloat(event.target.value) * bs;
        txtBs.current.value = formatMoneda(
          total.toString().replace(",", "").replace(".", ","),
          ",",
          ".",
          2
        );
      }
      if (
        event.target.value === "" ||
        parseFloat(
          event.target.value.trim().replace(".", "").replace(",", ".")
        ) === 0.0
      ) {
        event.target.value = "0,00";
      }
      event.target.value = formatoMonto(event.target.value);
      let char1 = event.target.value.substring(0, 1);
      let char2 = event.target.value.substring(1, 2);
      if (char1 === "0" && char2 !== ",") {
        event.target.value = event.target.value.substring(
          1,
          event.target.value.legth
        );
      }
    } else if (event.which === 46) {
      return false;
    } else if (event.which >= 48 && event.which <= 57) {
      return true;
    } else if (event.which === 8 || event.which === 0 || event.which === 44) {
      return true;
    } else return false;
  };

  const validarInput = (e) => {
    console.log(e.target.name)
   let item = document.getElementById(e.target.name);
    if(!e.target.value || e.target.name === 'ced' && e.target.value.length < 8){
      console.log('1')
      item.className -= ' form-text fw-bold hidden ';
      item.className += ' form-text fw-bold visible ';
    } else {
      console.log('2')

      item.className -= ' form-text fw-bold visible ';
      item.className += ' form-text fw-bold hidden ';
    }
  }
  return (
    <Modal
      {...props}
      style={{ background: "rgb(28, 27, 23)" }}
      size="lg"
      aria-labelledby="contained-modal-title-vcenter"
      centered
      backdrop="static"
      keyboard={false}
      onShow={() => {
        setOperacion(props.operacion);
        if (props.operacion) {
          seleccionarRegistros(props.idLicencia);
        }
      }}
    >
      <Modal.Header className="bg-danger">
        <Modal.Title style={{ color: "#fff" }}>
          Registrar Certificado Medico
        </Modal.Title>
        <button
          ref={btnCancela}
          className="btn"
          style={{ border: 0, margin: 0, padding: 0, color: "#ffffff" }}
          onClick={salir}
        >
          <i className="far fa-window-close"></i>
        </button>
      </Modal.Header>
      <Modal.Body style={{ color: "rgb(106, 115, 123)" }}>
        <Dimmer active={activate} inverted>
          <Loader inverted>cargando...</Loader>
        </Dimmer>
        <CatalogoClientes
          show={mostrar}
          onHideCancela={() => {
            setMostrar(false);
          }}
          onHideCatalogo={seleccionarCliente}
        />

        <Mensaje
          mensaje={mensaje}
          onHide={() => {
            mensaje.titulo === "Exito."
              ? cerrarModal()
              : setMensaje({
                  mostrar: false,
                  titulo: "",
                  texto: "",
                  icono: "",
                });
          }}
        />

        <div className="col-md-12 row mx-auto">
          <div class="input-group input-group-sm mb-1 col-md-5">
            <span class="input-group-text" id="inputGroup-sizing-sm">
              Cedula:
            </span>
            <input type="hidden" ref={idCliente} />
            <input type="hidden" ref={idMedico} />
            <input type="hidden" ref={idCobertura} />
            <select
              class="form-select col-md-3"
              ref={cmbNacionalidad}
              aria-label="Default select example"
            >
              <option value="V-">V-</option>
              <option value="E-">E-</option>
              <option value="J-">J-</option>
              <option value="G-">G-</option>
            </select>
            <input
              onKeyDown={handleChange(9)}
              type="text"
              class="form-control"
              ref={txtCedula}
              aria-label="Sizing example input"
              aria-describedby="inputGroup-sizing-sm"
              name="ced"
              onBlur={validarInput}
              
            />
            <button
              type="button"
              class="btn btn-success"
              onClick={() => {
                setMostrar(true);
              }}
            >
              <i class="fa fa-search"></i>
            </button>
            <div id="ced" class="form-text hidden">Debe ingresar un cedula valida longitud(8-9).</div>

          </div>
          <div class=" col-md-7"></div>
          <div class=" col-md-6 mb-1">
          <div class="input-group input-group-sm  ">
            <span class="input-group-text" id="inputGroup-sizing-sm">
              Nombres:
            </span>
            <input
              type="text"
              onKeyDown={handleChange(25)}
              class="form-control"
              onChange={soloLetras}
              ref={txtNombre}
              aria-label="Sizing example input"
              aria-describedby="inputGroup-sizing-sm"
              name="nom"
              onBlur={validarInput}
            />
             </div>
            <div id="nom" class="form-text hidden">Debe ingresar nombre valido</div>

          </div>
          <div class=" col-md-6 mb-1">
          <div class="input-group input-group-sm ">
            <span class="input-group-text" id="inputGroup-sizing-sm">
              Apellidos:
            </span>
            <input
              onKeyDown={handleChange(25)}
              type="text"
              class="form-control"
              ref={txtApellido}
              aria-label="Sizing example input"
              aria-describedby="inputGroup-sizing-sm"
              name="ape"
              onBlur={validarInput}
            />
            </div>
          <div id="ape" class="form-text hidden">Debe ingresar apellido valido</div>
        
          </div>
          <div class="input-group input-group-sm mb-1 col-md-4">
            <span class="input-group-text" id="inputGroup-sizing-sm">
              Fecha de Nacimiento:
            </span>
            <input
              type="date"
              class="form-control"
              onChange={calcularEdad}
              ref={txtFechaNaci}
              aria-label="Sizing example input"
              aria-describedby="inputGroup-sizing-sm"
              name="fecha"
              onBlur={validarInput}
            />
          <div id="fecha" class="form-text hidden">Debe ingresar fecha valida</div>

          </div>
          <div class="mb-1 col-md-2">
          <div class="input-group input-group-sm">
            <span class="input-group-text" id="inputGroup-sizing-sm">
              Edad:
            </span>
            <input
              onKeyDown={handleChange(3)}
              type="text"
              value={edad}
              class="form-control text-right"
              ref={txtEdad}
              onChange={handleInputNumChange}
              aria-label="Sizing example input"
              aria-describedby="inputGroup-sizing-sm"
              disabled
            />
            </div>
            <div id="nom" class="form-text hidden">ho</div>

          </div>
          <div class=" col-md-3 mb-1">
          <div class="input-group input-group-sm">
            <span class="input-group-text" id="inputGroup-sizing-sm">
              Tipo de Sangre:
            </span>
            <input
              onKeyDown={handleChange(4)}
              type="text"
              class="form-control"
              ref={txtTipoSangre}
              aria-label="Sizing example input"
              aria-describedby="inputGroup-sizing-sm"
            />
         </div>
          <div  class="form-text hidden">h</div>
            
          </div>
          <div class=" col-md-3 mb-1">
          <div class="input-group input-group-sm">
            <span class="input-group-text" id="inputGroup-sizing-sm">
              Usa lentes:
            </span>
            <select
              class="form-select"
              ref={cmbLentes}
              aria-label="Default select example"
            >
              <option value="1">Si</option>
              <option value="0">No</option>
            </select>
          </div>
          <div  class="form-text hidden">h</div>
            
          </div>
          <div class=" mb-1 col-md-4">
          <div class="input-group input-group-sm">
            <span class="input-group-text" id="inputGroup-sizing-sm">
              Forma de Pago:
            </span>
            <select
              class="form-select"
              ref={cmbPago}
              aria-label="Default select example"
            >
              <option value="0">Pago Movil</option>
              <option value="1">Efectivo</option>
              <option value="2">Transferencia</option>
              <option value="3">Punto</option>
            </select>
          </div>
          <div  class="form-text hidden">h</div>
            
            </div>
          <div class="input-group input-group-sm mb-1 col-md-3">
            <span class="input-group-text" id="inputGroup-sizing-sm">
              Referencia:
            </span>
            <input
              onKeyDown={handleChange(4)}
              type="text"
              class="form-control"
              ref={txtReferencia}
              aria-label="Sizing example input"
              aria-describedby="inputGroup-sizing-sm"
              name="ref"
              onBlur={validarInput}
            />
          <div id="ref" class="form-text hidden">Debe ingresar referencia valido</div>

          </div>
          <div class=" col-md-5 mb-1">
          <div class="input-group input-group-sm">
            <span class="input-group-text" id="inputGroup-sizing-sm">
              Cantidad:
            </span>
            <input
              type="text"
              disabled
              class="form-control text-right"
              ref={txtDolar}
              aria-label="Sizing example input"
              aria-describedby="inputGroup-sizing-sm"
              placeholder="Dolares"
              onChange={handleInputMontoChange}
              name="dolar"
              value={10}
            />
            <input
              disabled
              type="text"
              class="form-control text-right"
              ref={txtBs}
              aria-label="Sizing example input"
              aria-describedby="inputGroup-sizing-sm"
              placeholder="Bolivares"
              onChange={handleInputMontoChange}
              value={(10 * dolarbcv).toFixed(2)}
            />
            </div>

           <div  class="form-text hidden">h</div>
            
            </div>
        </div>
      </Modal.Body>
      <Modal.Footer>
        <button
          className="btn btn-sm btn-success rounded-pill col-md-2"
          disabled={props.operacion === 4 ? true : false}
          onClick={onChangeValidar}
        >
          <i className="fas fa-check-circle"> Aceptar</i>
        </button>
        <button
          ref={btnCancela}
          onClick={salir}
          className="btn btn-sm btn-danger rounded-pill col-md-2"
        >
          <i className="fas fa-window-close"> Salir</i>
        </button>
      </Modal.Footer>
    </Modal>
  );
};
