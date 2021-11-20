<?php
defined('BASEPATH') or exit('No direct script access allowed');

require(APPPATH . '/libraries/REST_Controller.php');

class Papeleria extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('papeleria_model', 'papeleria');
    }

    /*
     * Metodo para los Empleados
     * 
    */

    public function solicitudes_post()
    {
        $id_usuario = $this->input->post('id_usuario');

        $informacion = $this->papeleria->solicitudes($id_usuario);

        $response = array(
            "success" => true,
            "message" => '',
            "informacion" => array()
        );

        if ($informacion) {
            $info_comentario =  $this->papeleria->comentario($informacion[0]->id_papeleria);

            $comentario = '';

            if ($info_comentario) {
                $comentario = $info_comentario[0]->comentario;
            }

            foreach ($informacion as $item) {
                $response['informacion'][] = array(
                    'id_papeleria'          => $item->id_papeleria,
                    'titulo'                => $item->titulo,
                    'descripcion'           => $item->descripcion,
                    'fecha_solicitud'       => $item->fecha_solicitud,
                    'estatus'               => $item->estatus,
                    'respuesta_solicitud'   => $comentario
                );
            }

            $this->response($response);
        } else {
            $data['success'] = false;
            $data['message'] = 'Informacion no encontrada, intente mas tarde';

            $this->response($data);
        }
    }

    public function agregar_post()
    {
        $data = array(
            'titulo'            => $this->input->post('titulo'),
            'descripcion'       => $this->input->post('descripcion'),
            'fecha_solicitud'   => $this->input->post('fecha_solicitud'),
            'estatus'           => $this->input->post('estatus'),
            'id_usuario'        => $this->input->post('id_usuario')
        );

        $guardar = $this->papeleria->agregar($data);

        $response = array(
            "success" => true,
            "message" => ''
        );

        if ($guardar) {
            $response['message'] = 'Solicitud enviada exitosamente';

            $this->response($response);
        } else {
            $data['success'] = false;
            $data['message'] = 'Informacion no guardada, intente mas tarde';

            $this->response($data);
        }
    }

    public function editar_post()
    {
        $id_papeleria = $this->input->post('id_papeleria');

        $data = array(
            'titulo'            => $this->input->post('titulo'),
            'descripcion'       => $this->input->post('descripcion'),
            'fecha_solicitud'   => $this->input->post('fecha_solicitud'),
            'estatus'           => $this->input->post('estatus'),
            'id_usuario'        => $this->input->post('id_usuario')
        );

        $editar = $this->papeleria->editar($id_papeleria, $data);

        $response = array(
            "success" => true,
            "message" => ''
        );

        if ($editar) {
            $response['message'] = 'Solicitud actualizada exitosamente';

            $this->response($response);
        } else {
            $data['success'] = false;
            $data['message'] = 'Informacion no actualizada, intente mas tarde';

            $this->response($data);
        }
    }

    public function eliminar_post()
    {
        $id_papeleria = $this->input->post('id_papeleria');

        $eliminar = $this->papeleria->eliminar($id_papeleria);

        $response = array(
            "success" => true,
            "message" => ''
        );

        if ($eliminar) {
            $response['message'] = 'Solicitud eliminada exitosamente';

            $this->response($response);
        } else {
            $data['success'] = false;
            $data['message'] = 'Informacion no eliminada, intente mas tarde';

            $this->response($data);
        }
    }

    /*
     * Metodo para el Administrador
     *  
    */

    public function solicitudes_empleados_get()
    {
        $informacion = $this->uniforme->solicitudes_empleados();

        $response = array(
            "success" => true,
            "message" => '',
            "informacion" => array()
        );

        if ($informacion) {

            foreach ($informacion as $item) {

                $info_comentario =  $this->papeleria->comentario($item->id_papeleria);

                $id_comentario = 0;
                $comentario = '';

                if ($info_comentario) {
                    foreach ($info_comentario as $comentario) {
                        $id_comentario = $comentario->id_comentario_papeleria;
                        $comentario    = $comentario->comentario;
                    }
                }

                $response['informacion'][] = array(
                    'id_papeleria'      => $item->id_papeleria,
                    'titulo'            => $item->titulo,
                    'descripcion'       => $item->descripcion,
                    'fecha_solicitud'   => $item->fecha_solicitud,
                    'estatus'           => $item->estatus,
                    'id_usuario'        => $item->id_usuario,
                    'nombre'            => $item->nombre,
                    'url_perfil'        => $item->url_perfil,
                    'id_comentario'     => $id_comentario,
                    'comentario'        => $comentario
                );
            }

            $this->response($response);
        } else {
            $data['success'] = false;
            $data['message'] = 'Informacion no encontrada, intente mas tarde';

            $this->response($data);
        }
    }

    public function responder_solicitud_post()
    {
        $id_papeleria = $this->input->post('id_papeleria');

        $dataComentario = array(
            'comentario'    => $this->input->post('comentario'),
            'id_papeleria'  => $id_papeleria
        );

        $dataSolicitud = array(
            'estatus'    => $this->input->post('estatus')
        );

        $responder = $this->papeleria->responder_solicitud($dataComentario);

        $response = array(
            "success" => true,
            "message" => '',
        );

        if ($responder) {

            $actualizar = $this->papeleria->actualizar_estatus($id_papeleria, $dataSolicitud);

            $response['message'] = 'Respuesta enviada exitosamente';

            $this->response($response);
        } else {
            $data['success'] = false;
            $data['message'] = 'Respuesta no enviada, intente mas tarde.';

            $this->response($data);
        }
    }

    public function editar_mensaje_post()
    {
        $id_papeleria = $this->input->post('id_papeleria');
        $id_comentario = $this->input->post('id_comentario');

        $dataComentario = array(
            'comentario'    => $this->input->post('comentario'),
            'id_papeleria'  => $id_papeleria
        );

        $dataSolicitud = array(
            'estatus'    => $this->input->post('estatus')
        );

        $editar = $this->papeleria->editar_mensaje($id_comentario, $dataComentario);

        $response = array(
            "success" => true,
            "message" => '',
        );

        if ($editar) {
            $actualizar = $this->papeleria->actualizar_estatus($id_papeleria, $dataSolicitud);

            $response['message'] = 'Respuesta actualizada exitosamente';

            $this->response($response);
        } else {
            $data['success'] = false;
            $data['message'] = 'Respuesta no actualizada, intente mas tarde.';

            $this->response($data);
        }
    }
}
