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
import CatalogoTipoContrato from "../../catalogos/catalagoTipoContrato";
import CatalogoAcesor from "../../catalogos/catalogoAcesor";
import CatalogoSucursal from "../../catalogos/catalagoSucursal";
import CatalogoTransporte from "../../catalogos/catalagoTransporte";
import CatalogoUso from "../../catalogos/catalogoUso";
import CatalogoClase from "../../catalogos/catalogoClase";
import CatalogoTipo from "../../catalogos/catalagoTipoVehiculo";
import CatalogoTitular from "../../catalogos/catalogoTitular";
import CatalogoVehiculo from "../../catalogos/catalogoVehiculo";

export const CargaFamiliar = (props) => {
    /*  variables de estados */

    let op = require("../../modulos/datos");
    let token = localStorage.getItem("jwtToken");
    const fechasistema = JSON.parse(localStorage.getItem("fechasistema"));
    const dolarbcv = JSON.parse(localStorage.getItem("dolarbcv"));
    const user = JSON.parse(localStorage.getItem("username"));
    const idUser = JSON.parse(localStorage.getItem("user_id"));
    const suc = JSON.parse(localStorage.getItem("sucursal"));
    const idsucursal = JSON.parse(localStorage.getItem("idsucursal"));
    const [value, setValue] = useState("");
    //ID
    const idPoliza = useRef();
    const idCliente = useRef();
    const idTitular = useRef();
    const idVehiculo = useRef();
    // const idColor = useRef();
    // const idMarca = useRef();
    // const idModelo = useRef();
    const idCobertura = useRef();
    //Contrato
    const TxtTipoContrato = useRef();
    const txtDesde = useRef();
    const txtHasta = useRef();
    const cmbEstado = useRef();
    const txtAcesor = useRef();
    const cmbSucursal = useRef();
    const txtLinea = useRef();
    //Contratante
    const cmbNacionalidad = useRef();
    const txtCedula = useRef();
    const txtNombre = useRef();
    const txtApellido = useRef();
    const txtFechaNaci = useRef();
    const cmbTelefono = useRef();
    const txtTelefono = useRef();
    const txtCorreo = useRef();
    const txtDirec = useRef();
    //Titular
    const cmbNacionalidadTitular = useRef();
    const txtCedulatTitular = useRef();
    const txtNombreTitular = useRef();
    const txtApellidoTitular = useRef();
    //Vehiculo
    const txtPlaca = useRef();
    const txtPuesto = useRef();
    const txtUso = useRef();
    const txtAño = useRef();
    const txtSerMotor = useRef();
    const txtClase = useRef();
    const txtColor = useRef();
    const txtSerCarroceria = useRef();
    const cmbTipo = useRef();
    const txtModelo = useRef();
    const txtMarca = useRef();
    const txtPeso = useRef();
    const txtCapTon = useRef();

    //Pago
    const cmbFormaPago = useRef();
    const txtReferencia = useRef();
    const txtBs = useRef();
    const txtDolar = useRef();

    //otros
    const txtNContrato = useRef();
    const txtTipoContrato = useRef();
    const txtFechaEmision = useRef();
    const txtFechaVencimiento = useRef();
    const txtDireccion = useRef();
    const txtPuestos = useRef();
    const txtAnio = useRef();
    const txtTipoVehiculo = useRef();
    const txtCapTone = useRef();
    const [vehiculo, setVehiculo] = useState();
    const [tipoContrato, setTipoContrato] = useState([]);
    const [estados, setEstados] = useState();
    const [acesor, setAcesor] = useState();
    const [sucursal, setSucursal] = useState();
    const [transporte, setTransporte] = useState();
    const [uso, setUso] = useState();
    const [clase, setClase] = useState();
    const [tipo, setTipo] = useState();

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


    const [idContrato, setIdContrato] = useState();
    const [operacion, setOperacion] = useState(0);

    /*********************************************** FUNCINES DE VALIDACION***********************************************************/

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



    const onChangeValidar = () => {
        let sigue = true;

        if (txtCedula.current.value === '') {
            setMensaje({
                mostrar: true,
                titulo: "Notificación",
                texto: 'Debe ingresar el numero de cedula',
                icono: "informacion",
            })
            sigue = false;

            txtCedula.current.focus()
        } else if (cmbTipo.current.value === '') {
            setMensaje({
                mostrar: true,
                titulo: "Notificación",
                texto: 'Debe seleccionarel tipo de carga',
                icono: "informacion",
            })
            sigue = false;

            cmbTipo.current.focus()
        } else if (txtFechaNaci.current.value === '') {
            setMensaje({
                mostrar: true,
                titulo: "Notificación",
                texto: 'Debe ingresar la fecha de nacimiento',
                icono: "informacion",
            })
            sigue = false;

            txtFechaNaci.current.focus()
        } else if (txtNombre.current.value === '') {
            setMensaje({
                mostrar: true,
                titulo: "Notificación",
                texto: 'Debe ingresar el nombre',
                icono: "informacion",
            })
            sigue = false;

            txtNombre.current.focus()
        } else if (txtApellido.current.value === '') {
            setMensaje({
                mostrar: true,
                titulo: "Notificación",
                texto: 'Debe ingresar el apellido',
                icono: "informacion",
            })
            sigue = false;

            txtApellido.current.focus()
        }

        if(sigue){
            let item = {
                ced:cmbNacionalidad.current.value + txtCedula.current.value,
                tipo:cmbTipo.current.value,
                fecha:txtFechaNaci.current.value,
                nom:txtNombre.current.value,
                ape:txtApellido.current.value
            }
            props.cargarFamiliar(item)
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
    // Para poder la fecha en rcv
    const fechaSistema = moment();
    const fechaHasta = fechaSistema.clone().add(1, "year");

    const check = (e) => {
        var textV = "which" in e ? e.which : e.keyCode,
            char = String.fromCharCode(textV),
            regex = /[a-z]/gi;
        if (!regex.test(char)) e.preventDefault();
        return false;
    };
    const seleccionarCliente = (
        nombre,
        apellido,
        cedula,
        nacionalidad,
        correo,
        codigo,
        telefono,
        dir
    ) => {
        console.log(nombre, apellido, cedula);
        cmbNacionalidad.current.value = nacionalidad + "-";
        txtCedula.current.value = cedula;
        txtApellido.current.value = apellido;
        txtNombre.current.value = nombre;

        let item = document.getElementById("ced");
        item.className -= " form-text fw-bold visible ";
        item.className += " form-text fw-bold hidden ";
        setMostrar(false);
    };



    const cerrarModal = () => {
        setMensaje({ mostrar: false, titulo: "", texto: "", icono: "" });
        props.onHideCancela2(idContrato);
    };



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


    const validarInput = (e) => {
        console.log(e.target.name)
        let item = document.getElementById(e.target.name);
        if (!e.target.value || e.target.name === 'ced' && e.target.value.length < 8) {
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

            }}
        >
            <Modal.Header className="bg-azul">
                <Modal.Title style={{ color: "#fff" }}>
                    <Modal.Title style={{ color: "#fff" }}>
                        Agregar Carga Familiar
                    </Modal.Title>{" "}
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



                <div class="col-md-12 row mx-auto">



                    <fieldset class="border rounded-3 p-3 row mx-auto">
                        <legend
                            class="float-none w-auto px-3 fw-bold"
                            style={{ fontSize: 15 }}
                        >
                            Datos del contratante
                        </legend>
                        <div class="input-group input-group-sm mb-1 col-md-4">
                            <span class="input-group-text" id="inputGroup-sizing-sm">
                                Cedula:
                            </span>
                            <select
                                disabled={operacion === 3}
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
                                type="text"
                                class="form-control"
                                disabled={operacion === 3}
                                ref={txtCedula}
                                onKeyDown={handleChange(9)}
                                aria-label="Sizing example input"
                                aria-describedby="inputGroup-sizing-sm"
                                maxLength={9}
                                name="ced1"
                                onChange={validaSoloNumero}
                                onFocus={validarInput}
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
                            <div id="ced1" class="form-text hidden">
                                Debe ingresar un dato valido .
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group input-group-sm mb-2">
                                <span class="input-group-text" id="inputGroup-sizing-sm">
                                    Tipo Familiar
                                </span>
                                <select class="form-select" ref={cmbTipo} aria-label="Default select example">
                                    <option value="1">Padre/Madre</option>
                                    <option value="2">Pareja</option>
                                    <option value="3">Hijo</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 mb-1">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text" id="inputGroup-sizing-sm">
                                    Fecha Nacimiento
                                </span>
                                <input
                                    disabled={operacion === 3}
                                    type="date"
                                    ref={txtFechaNaci}
                                    class="form-control"
                                    aria-label="Sizing example input"
                                    aria-describedby="inputGroup-sizing-sm"
                                    name="fecha1"
                                    onFocus={validarInput}
                                />
                            </div>
                            <div id="fecha1" class="form-text hidden">
                                Debe ingresar fecha valida
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="input-group input-group-sm mb-2">
                                <span class="input-group-text" id="inputGroup-sizing-sm">
                                    Nombre
                                </span>
                                <input
                                    type="text"
                                    disabled={operacion === 3}
                                    onKeyDown={handleChange(25)}
                                    ref={txtNombre}
                                    class="form-control "
                                    aria-label="Sizing example input"
                                    aria-describedby="inputGroup-sizing-sm"
                                    onChange={validaSoloLetras}
                                    name="nom1"
                                    onFocus={validarInput}

                                />
                            </div>
                            <div id="nom1" class="form-text hidden">
                                Debe ingresar el Nombre
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group input-group-sm mb-2">
                                <span class="input-group-text" id="inputGroup-sizing-sm">
                                    Apellido
                                </span>
                                <input
                                    disabled={operacion === 3}
                                    type="text"
                                    onKeyDown={handleChange(25)}
                                    ref={txtApellido}
                                    class="form-control"
                                    aria-label="Sizing example input"
                                    aria-describedby="inputGroup-sizing-sm"
                                    onChange={validaSoloLetras}
                                    name="ape1"
                                onFocus={validarInput}

                                />
                            </div>
                            <div id="ape1" class="form-text hidden">
                                Debe ingresar el Nombre
                            </div>
                        </div>





                    </fieldset>

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
