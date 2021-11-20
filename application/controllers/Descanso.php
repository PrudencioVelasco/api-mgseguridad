<?php
defined('BASEPATH') or exit('No direct script access allowed');

require(APPPATH . '/libraries/REST_Controller.php');

class Descanso extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('descanso_model', 'descanso');
    }

    /*
     * Metodo para los Empleados
     *  
    */

    public function solicitudes_post()
    {
        $id_usuario = $this->input->post('id_usuario');

        $informacion = $this->descanso->solicitudes($id_usuario);

        $response = array(
            "success" => true,
            "message" => '',
            "informacion" => array()
        );

        if ($informacion) {
            $info_comentario =  $this->descanso->comentario($informacion[0]->id_descanso);

            $comentario = '';

            if ($info_comentario) {
                $comentario    = $info_comentario[0]->comentario;
            }

            foreach ($informacion as $item) {
                $response['informacion'][] = array(
                    'id_descanso'           => $item->id_descanso,
                    'titulo'                => $item->titulo,
                    'fecha_solicitud'       => $item->fecha_solicitud,
                    'dia_solicitado'        => $item->dia_solicitado,
                    'dia_descanso'          => $item->dia_descanso,
                    'mensaje'               => $item->mensaje,
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
            'fecha_solicitud'   => $this->input->post('fecha_solicitud'),
            'dia_solicitado'    => $this->input->post('dia_solicitado'),
            'mensaje'           => $this->input->post('mensaje'),
            'estatus'           => $this->input->post('estatus'),
            'id_usuario'        => $this->input->post('id_usuario')
        );

        $guardar = $this->descanso->agregar($data);

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
        $id_descanso = $this->input->post('id_descanso');

        $data = array(
            'titulo'            => $this->input->post('titulo'),
            'fecha_solicitud'   => $this->input->post('fecha_solicitud'),
            'dia_solicitado'    => $this->input->post('dia_solicitado'),
            'mensaje'           => $this->input->post('mensaje'),
            'estatus'           => $this->input->post('estatus'),
            'id_usuario'        => $this->input->post('id_usuario')
        );

        $editar = $this->descanso->editar($id_descanso, $data);

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
        $id_descanso = $this->input->post('id_descanso');

        $eliminar = $this->descanso->eliminar($id_descanso);

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
        $informacion = $this->descanso->solicitudes_empleados();

        $response = array(
            "success" => true,
            "message" => '',
            "informacion" => array()
        );

        if ($informacion) {

            foreach ($informacion as $item) {

                $info_comentario =  $this->descanso->comentario($item->id_descanso);

                $id_comentario = 0;
                $comentario = '';

                if ($info_comentario) {
                    foreach ($info_comentario as $comentario) {
                        $id_comentario = $comentario->id_comentario_descanso;
                        $comentario    = $comentario->comentario;
                    }
                }

                $response['informacion'][] = array(
                    'id_descanso'       => $item->id_descanso,
                    'titulo'            => $item->titulo,
                    'fecha_solicitud'   => $item->fecha_solicitud,
                    'dia_solicitado'    => $item->dia_solicitado,
                    'dia_asignado'      => $item->dia_descanso,
                    'mensaje'           => $item->mensaje,
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
        $id_descanso = $this->input->post('id_descanso');

        $dataComentario = array(
            'comentario'    => $this->input->post('comentario'),
            'id_descanso'   => $id_descanso
        );

        $dataSolicitud = array(
            'estatus'    => $this->input->post('estatus')
        );

        $responder = $this->descanso->responder_solicitud($dataComentario);

        $response = array(
            "success" => true,
            "message" => '',
        );

        if ($responder) {

            $actualizar = $this->descanso->actualizar_estatus($id_descanso, $dataSolicitud);

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
        $id_descanso = $this->input->post('id_descanso');
        $id_comentario = $this->input->post('id_comentario');

        $dataComentario = array(
            'comentario'    => $this->input->post('comentario'),
            'id_descanso'   => $id_descanso
        );

        $dataSolicitud = array(
            'estatus'    => $this->input->post('estatus')
        );

        $editar = $this->descanso->editar_mensaje($id_comentario, $dataComentario);

        $response = array(
            "success" => true,
            "message" => '',
        );

        if ($editar) {

            $actualizar = $this->descanso->actualizar_estatus($id_descanso, $dataSolicitud);

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

        $informacion = $this->descanso->solicitud_reciente($id_usuario);

        $response = array(
            "success" => true,
            "message" => '',
            "informacion" => array()
        );

        if ($informacion) {
            foreach ($informacion as $item) {
                $response['informacion'][] = array(
                    'id_descanso'       => $item->id_descanso,
                    'titulo'            => $item->titulo,
                    'fecha_solicitud'   => $item->fecha_solicitud,
                    'dia_solicitado'    => $item->dia_solicitado,
                    'mensaje'           => $item->mensaje,
                    'estatus'           => $item->estatus
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
