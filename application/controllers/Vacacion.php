<?php
defined('BASEPATH') or exit('No direct script access allowed');

require(APPPATH . '/libraries/REST_Controller.php');

class Vacacion extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('vacacion_model', 'vacacion');
    }

    /*
     * Metodo para los Empleados
     *  
    */

    public function solicitudes_post()
    {
        $id_usuario = $this->input->post('id_usuario');

        $informacion = $this->vacacion->solicitudes($id_usuario);

        $response = array(
            "success" => true,
            "message" => '',
            "informacion" => array()
        );

        if ($informacion) {

            $info_comentario =  $this->vacacion->comentario($informacion[0]->id_vacaciones);

            $comentario = '';

            if ($info_comentario) {
                $comentario = $info_comentario[0]->comentario;
            }

            foreach ($informacion as $item) {
                $response['informacion'][] = array(
                    'id_vacacion'           => $item->id_vacaciones,
                    'titulo'                => $item->titulo,
                    'fecha_inicio'          => $item->fecha_inicio,
                    'fecha_fin'             => $item->fecha_fin,
                    'motivo'                => $item->motivo,
                    'estatus'               => $item->estatus,
                    'fecha_solicitud'       => $item->fecha_solicitud,
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
            'fecha_inicio'      => $this->input->post('fecha_inicio'),
            'fecha_fin'         => $this->input->post('fecha_fin'),
            'comentario'        => $this->input->post('comentario'),
            'estatus'           => $this->input->post('estatus'),
            'fecha_solicitud'   => $this->input->post('fecha_solicitud'),
            'id_usuario'        => $this->input->post('id_usuario')
        );

        $guardar = $this->vacacion->agregar($data);

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
        $id_vacacion = $this->input->post('id_vacacion');

        $data = array(
            'titulo'            => $this->input->post('titulo'),
            'fecha_inicio'      => $this->input->post('fecha_inicio'),
            'fecha_fin'         => $this->input->post('fecha_fin'),
            'comentario'        => $this->input->post('comentario'),
            'estatus'           => $this->input->post('estatus'),
            'fecha_solicitud'   => $this->input->post('fecha_solicitud'),
            'id_usuario'        => $this->input->post('id_usuario')
        );

        $editar = $this->vacacion->editar($id_vacacion, $data);

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
        $id_vacacion = $this->input->post('id_vacacion');

        $eliminar = $this->vacacion->eliminar($id_vacacion);

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
        $informacion = $this->vacacion->solicitudes_empleados();

        $response = array(
            "success" => true,
            "message" => '',
            "informacion" => array()
        );

        if ($informacion) {

            foreach ($informacion as $item) {

                $info_comentario =  $this->vacacion->comentario($item->id_vacaciones);

                $id_comentario = 0;
                $comentario = '';

                if ($info_comentario) {
                    foreach ($info_comentario as $comentario) {
                        $id_comentario = $comentario->id_comentario_vacaciones;
                        $comentario = $comentario->comentario;
                    }
                }

                $response['informacion'][] = array(
                    'id_vacacion'       => $item->id_vacaciones,
                    'titulo'            => $item->titulo,
                    'fecha_inicio'      => $item->fecha_inicio,
                    'fecha_fin'         => $item->fecha_fin,
                    'motivo'            => $item->motivo,
                    'estatus'           => $item->estatus,
                    'fecha_solicitud'   => $item->fecha_solicitud,
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
        $id_vacacion = $this->input->post('id_vacacion');

        $dataComentario = array(
            'comentario'    => $this->input->post('comentario'),
            'id_vacaciones' => $id_vacacion
        );

        $dataSolicitud = array(
            'estatus'    => $this->input->post('estatus')
        );

        $responder = $this->vacacion->responder_solicitud($dataComentario);

        $response = array(
            "success" => true,
            "message" => '',
        );

        if ($responder) {

            $actualizar = $this->vacacion->actualizar_estatus($id_vacacion, $dataSolicitud);

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
        $id_vacacion = $this->input->post('id_vacacion');
        $id_comentario = $this->input->post('id_comentario');

        $dataComentario = array(
            'comentario'    => $this->input->post('comentario'),
            'id_vacaciones' => $id_vacacion
        );

        $dataSolicitud = array(
            'estatus'    => $this->input->post('estatus')
        );

        $editar = $this->vacacion->editar_mensaje($id_comentario, $dataComentario);

        $response = array(
            "success" => true,
            "message" => '',
        );

        if ($editar) {
            $actualizar = $this->vacacion->actualizar_estatus($id_vacacion, $dataSolicitud);

            $response['message'] = 'Respuesta actualizada exitosamente';

            $this->response($response);
        } else {
            $data['success'] = false;
            $data['message'] = 'Respuesta no actualizada, intente mas tarde.';

            $this->response($data);
        }
    }

    public function solicitud_reciente_post()
    {
        $id_usuario = $this->input->post('id_usuario');

        $informacion = $this->vacacion->solicitud_reciente($id_usuario);

        $response = array(
            "success" => true,
            "message" => '',
            "informacion" => array()
        );

        if ($informacion) {

            foreach ($informacion as $item) {
                $response['informacion'][] = array(
                    'id_vacacion'       => $item->id_vacaciones,
                    'titulo'            => $item->titulo,
                    'fecha_inicio'      => $item->fecha_inicio,
                    'fecha_fin'         => $item->fecha_fin,
                    'motivo'            => $item->motivo,
                    'estatus'           => $item->estatus,
                    'fecha_solicitud'   => $item->fecha_solicitud
                );
            }

            $this->response($response);
        } else {
            $data['success'] = false;
            $data['message'] = 'Informacion no encontrada, intente mas tarde';

            $this->response($data);
        }
    }
}
