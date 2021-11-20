<?php
defined('BASEPATH') or exit('No direct script access allowed');

require(APPPATH . '/libraries/REST_Controller.php');

class Botas extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('botas_model', 'botas');
    }

    /*
     * Metodo para los Empleados
     * 
    */

    public function solicitudes_post()
    {
        $id_usuario = $this->input->post('id_usuario');

        $informacion = $this->botas->solicitudes($id_usuario);

        $response = array(
            "success" => true,
            "message" => '',
            "informacion" => array()
        );

        if ($informacion) {
            $info_comentario =  $this->botas->comentario($informacion[0]->id_botas);

            $comentario = '';

            if ($info_comentario) {
                $comentario = $info_comentario[0]->comentario;
            }

            foreach ($informacion as $item) {
                $response['informacion'][] = array(
                    'id_bota'               => $item->id_botas,
                    'titulo'                => $item->titulo,
                    'numero'                => $item->numero,
                    'motivo'                => $item->motivo,
                    'fecha_solicitud'       => $item->fecha_solicitud,
                    'estatus'               => $item->estatus,
                    'respuesta_solicitud'   => $comentario
                );
            }

            $this->response($response);
        } else {
            $data['success'] = false;
            $data['message'] = 'Informacion no disponible, intente mas tarde';

            $this->response($data);
        }
    }

    public function agregar_post()
    {
        $data = array(
            'titulo'            => $this->input->post('titulo'),
            'numero'            => $this->input->post('numero'),
            'motivo'            => $this->input->post('motivo'),
            'fecha_solicitud'   => $this->input->post('fecha_solicitud'),
            'estatus'           => $this->input->post('estatus'),
            'id_usuario'        => $this->input->post('id_usuario')
        );

        $guardar = $this->botas->agregar($data);

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
        $id_bota = $this->input->post('id_bota');

        $response = array(
            'success' => true,
            'message' => ''
        );

        $data = array(
            'titulo'            => $this->input->post('titulo'),
            'numero'            => $this->input->post('numero'),
            'motivo'            => $this->input->post('motivo'),
            'fecha_solicitud'   => $this->input->post('fecha_solicitud'),
            'estatus'           => $this->input->post('estatus'),
            'id_usuario'        => $this->input->post('id_usuario')
        );

        $actualizar = $this->botas->editar($data, $id_bota);

        if ($actualizar) {
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
        $id_botas = $this->input->post('id_bota');

        $eliminar = $this->botas->eliminar($id_botas);

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
        $informacion = $this->botas->solicitudes_empleados();

        $response = array(
            "success" => true,
            "message" => '',
            "informacion" => array()
        );

        if ($informacion) {

            foreach ($informacion as $item) {

                $info_comentario =  $this->botas->comentario($item->id_botas);

                $id_comentario = 0;
                $comentario = '';

                if ($info_comentario) {
                    foreach ($info_comentario as $comentario) {
                        $id_comentario = $comentario->id_comentario_botas;
                        $comentario    = $comentario->comentario;
                    }
                }

                $response['informacion'][] = array(
                    'id_bota'          => $item->id_botas,
                    'titulo'            => $item->titulo,
                    'numero'            => $item->numero,
                    'motivo'            => $item->motivo,
                    'fecha_solicitud'   => $item->fecha_solicitud,
                    'estatus'           => $item->estatus,
                    'id_usuario'        => $item->id_usuario,
                    'nombre'            => $item->nombre,
                    'url_perfil'        => $item->url_perfil,
                    'id_comentario'     => $id_comentario,
                    'comentario'        => $comentario,
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
        $id_bota = $this->input->post('id_bota');

        $dataComentario = array(
            'comentario' => $this->input->post('comentario'),
            'id_botas'   => $id_bota
        );

        $dataSolicitud = array(
            'estatus'    => $this->input->post('estatus')
        );

        $responder = $this->botas->responder_solicitud($dataComentario);

        $response = array(
            "success" => true,
            "message" => '',
        );

        if ($responder) {

            $actualizar = $this->botas->actualizar_estatus($id_bota, $dataSolicitud);

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
        $id_bota = $this->input->post('id_bota');
        $id_comentario = $this->input->post('id_comentario');

        $dataComentario = array(
            'comentario' => $this->input->post('comentario'),
            'id_botas'   => $id_bota
        );

        $dataSolicitud = array(
            'estatus'    => $this->input->post('estatus')
        );

        $editar = $this->botas->editar_mensaje($id_comentario, $dataComentario);

        $response = array(
            "success" => true,
            "message" => '',
        );

        if ($editar) {

            $actualizar = $this->botas->actualizar_estatus($id_bota, $dataSolicitud);

            $response['message'] = 'Respuesta actualizada exitosamente';

            $this->response($response);
        } else {
            $data['success'] = false;
            $data['message'] = 'Respuesta no actualizada, intente mas tarde.';

            $this->response($data);
        }
    }
}
