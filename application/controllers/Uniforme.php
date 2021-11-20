<?php
defined('BASEPATH') or exit('No direct script access allowed');

require(APPPATH . '/libraries/REST_Controller.php');

class Uniforme extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('uniforme_model', 'uniforme');
    }

    /*
     * Metodo para los Empleados
     * 
    */

    public function solicitudes_post()
    {
        $id_usuario = $this->input->post('id_usuario');

        $informacion = $this->uniforme->solicitudes($id_usuario);

        $response = array(
            "success" => true,
            "message" => '',
            "informacion" => array()
        );

        if ($informacion) {
            $info_comentario =  $this->uniforme->comentario($informacion[0]->id_uniforme);

            $comentario = '';

            if ($info_comentario) {
                $comentario    = $info_comentario[0]->comentario;
            }

            foreach ($informacion as $item) {
                $response['informacion'][] = array(
                    'id_uniforme'           => $item->id_uniforme,
                    'titulo'                => $item->titulo,
                    'talla'                 => $item->talla,
                    'color'                 => $item->color,
                    'motivo'                => $item->motivo,
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
            'talla'             => $this->input->post('talla'),
            'color'             => $this->input->post('color'),
            'motivo'            => $this->input->post('motivo'),
            'fecha_solicitud'   => $this->input->post('fecha_solicitud'),
            'estatus'           => $this->input->post('estatus'),
            'id_usuario'        => $this->input->post('id_usuario')
        );

        $guardar = $this->uniforme->agregar($data);

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
        $id_uniforme = $this->input->post('id_uniforme');

        $data = array(
            'titulo'            => $this->input->post('titulo'),
            'talla'             => $this->input->post('talla'),
            'color'             => $this->input->post('color'),
            'motivo'            => $this->input->post('motivo'),
            'fecha_solicitud'   => $this->input->post('fecha_solicitud'),
            'estatus'           => $this->input->post('estatus'),
            'id_usuario'        => $this->input->post('id_usuario')
        );

        $editar = $this->uniforme->editar($id_uniforme, $data);

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
        $id_uniforme = $this->input->post('id_uniforme');

        $eliminar = $this->uniforme->eliminar($id_uniforme);

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

                $info_comentario =  $this->uniforme->comentario($item->id_uniforme);

                $id_comentario = 0;
                $comentario = '';

                if ($info_comentario) {
                    foreach ($info_comentario as $comentario) {
                        $id_comentario = $comentario->id_comentario_uniforme;
                        $comentario    = $comentario->comentario;
                    }
                }

                $response['informacion'][] = array(
                    'id_uniforme'       => $item->id_uniforme,
                    'titulo'            => $item->titulo,
                    'talla'             => $item->talla,
                    'color'             => $item->color,
                    'motivo'            => $item->motivo,
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
        $id_uniforme = $this->input->post('id_uniforme');

        $dataComentario = array(
            'comentario'    => $this->input->post('comentario'),
            'id_uniforme'   => $id_uniforme
        );

        $dataSolicitud = array(
            'estatus'    => $this->input->post('estatus')
        );

        $responder = $this->uniforme->responder_solicitud($dataComentario);

        $response = array(
            "success" => true,
            "message" => '',
        );

        if ($responder) {

            $actualizar = $this->uniforme->actualizar_estatus($id_uniforme, $dataSolicitud);

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
        $id_uniforme = $this->input->post('id_uniforme');
        $id_comentario = $this->input->post('id_comentario');

        $dataComentario = array(
            'comentario'    => $this->input->post('comentario'),
            'id_uniforme'   => $id_uniforme
        );

        $dataSolicitud = array(
            'estatus'    => $this->input->post('estatus')
        );

        $editar = $this->uniforme->editar_mensaje($id_comentario, $dataComentario);

        $response = array(
            "success" => true,
            "message" => '',
        );

        if ($editar) {

            $actualizar = $this->uniforme->actualizar_estatus($id_uniforme, $dataSolicitud);

            $response['message'] = 'Respuesta actualizada exitosamente';

            $this->response($response);
        } else {
            $data['success'] = false;
            $data['message'] = 'Respuesta no actualizada, intente mas tarde.';

            $this->response($data);
        }
    }
}
