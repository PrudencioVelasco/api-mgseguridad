<?php
defined('BASEPATH') or exit('No direct script access allowed');

require(APPPATH . '/libraries/REST_Controller.php');

class Prestamo extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('prestamo_model', 'prestamo');
    }

    /*
     * Metodo para los Empleados
     * 
    */

    public function solicitudes_post()
    {
        $id_usuario = $this->input->post('id_usuario');

        $informacion = $this->prestamo->solicitudes($id_usuario);

        $response = array(
            "success" => true,
            "message" => '',
            "informacion" => array()
        );

        if ($informacion) {

            $info_comentario =  $this->prestamo->comentario($informacion[0]->id_prestamo);

            $comentario = '';

            if ($info_comentario) {
                $comentario = $info_comentario[0]->comentario;
            }

            foreach ($informacion as $item) {
                $response['informacion'][] = array(
                    'id_prestamo'       => $item->id_prestamo,
                    'titulo'            => $item->titulo,
                    'fecha_solicitud'   => $item->fecha_solicitud,
                    'motivo'            => $item->motivo,
                    'dias_trabajados'   => $item->dias_trabajados,
                    'dias_faltas'       => $item->dias_faltas,
                    'forma_pago'        => $item->forma_pago,
                    'cuenta_bancaria'   => $item->cuenta_bancaria,
                    'monto'             => $item->monto,
                    'estatus'           => $item->estatus,
                    'fecha_ingreso'     => $item->fecha_ingreso,
                    'nombre'            => $item->nombre,
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

    public function info_solicitudes_prestamo_post()
    {
        $id_usuario = $this->input->post('id_usuario');

        $info_solicitud_prestamo = $this->prestamo->info_solicitud_prestamo($id_usuario);

        $response = array(
            'success' => true,
            'message' => '',
            'informacion' => array()
        );

        if ($info_solicitud_prestamo) {
            $response['informacion'][] = array(
                'nombre' => $info_solicitud_prestamo[0]->nombre,
                'fecha_ingreso' => $info_solicitud_prestamo[0]->fecha_ingreso,
                'pago_semanal' => $info_solicitud_prestamo[0]->pago_semanal,
                'suma_total' => $info_solicitud_prestamo[0]->total_solicitado
            );

            $this->response($response);
        } else {
            $data['success'] = false;
            $data['message'] = 'Sin información disponible, intente más tarde';

            $this->response($data);
        }
    }

    public function agregar_post()
    {
        $data = array(
            'titulo'            => $this->input->post('titulo'),
            'fecha_solicitud'   => $this->input->post('fecha_solicitud'),
            'motivo'            => $this->input->post('motivo'),
            'dias_trabajados'   => $this->input->post('dias_trabajados'),
            'dias_faltas'       => $this->input->post('dias_faltas'),
            'forma_pago'        => $this->input->post('forma_pago'),
            'cuenta_bancaria'   => $this->input->post('cuenta_bancaria'),
            'monto'             => $this->input->post('monto'),
            'estatus'           => $this->input->post('estatus'),
            'id_usuario'        => $this->input->post('id_usuario')
        );

        $guardar = $this->prestamo->agregar($data);

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
        $id_prestamo = $this->input->post('id_prestamo');

        $data = array(
            'titulo'            => $this->input->post('titulo'),
            'fecha_solicitud'   => $this->input->post('fecha_solicitud'),
            'motivo'            => $this->input->post('motivo'),
            'dias_trabajados'   => $this->input->post('dias_trabajados'),
            'dias_faltas'       => $this->input->post('dias_faltas'),
            'forma_pago'        => $this->input->post('forma_pago'),
            'cuenta_bancaria'   => $this->input->post('cuenta_bancaria'),
            'monto'             => $this->input->post('monto'),
            'estatus'           => $this->input->post('estatus'),
            'id_usuario'        => $this->input->post('id_usuario')
        );

        $editar = $this->prestamo->editar($id_prestamo, $data);

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
        $id_prestamo = $this->input->post('id_prestamo');

        $eliminar = $this->prestamo->eliminar($id_prestamo);

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
        $informacion = $this->prestamo->solicitudes_empleados();

        $response = array(
            "success" => true,
            "message" => '',
            "informacion" => array()
        );

        if ($informacion) {

            foreach ($informacion as $item) {

                $info_comentario =  $this->prestamo->comentario($item->id_prestamo);

                $id_comentario = 0;
                $comentario = '';

                if ($info_comentario) {
                    foreach ($info_comentario as $comentario) {
                        $id_comentario = $comentario->id_comentario_prestamo;
                        $comentario    = $comentario->comentario;
                    }
                }

                $response['informacion'][] = array(
                    'id_prestamo'       => $item->id_prestamo,
                    'titulo'            => $item->titulo,
                    'fecha_solicitud'   => $item->fecha_solicitud,
                    'motivo'            => $item->motivo,
                    'dias_trabajados'   => $item->dias_trabajados,
                    'dias_faltas'       => $item->dias_faltas,
                    'forma_pago'        => $item->forma_pago,
                    'cuenta_bancaria'   => $item->cuenta_bancaria,
                    'monto'             => $item->monto,
                    'estatus'           => $item->estatus,
                    'id_usuario'        => $item->id_usuario,
                    'nombre'            => $item->nombre,
                    'fecha_ingreso'     => $item->fecha_ingreso,
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
        $id_prestamo = $this->input->post('id_prestamo');

        $dataComentario = array(
            'comentario'    => $this->input->post('comentario'),
            'id_prestamo'   => $id_prestamo
        );

        $dataSolicitud = array(
            'estatus'    => $this->input->post('estatus')
        );

        $responder = $this->prestamo->responder_solicitud($dataComentario);

        $response = array(
            "success" => true,
            "message" => '',
        );

        if ($responder) {

            $actualizar = $this->prestamo->actualizar_estatus($id_prestamo, $dataSolicitud);

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
        $id_prestamo = $this->input->post('id_prestamo');
        $id_comentario = $this->input->post('id_comentario');

        $dataComentario = array(
            'comentario'    => $this->input->post('comentario'),
            'id_prestamo'   => $id_prestamo
        );

        $dataSolicitud = array(
            'estatus'    => $this->input->post('estatus')
        );

        $editar = $this->prestamo->editar_mensaje($id_comentario, $dataComentario);

        $response = array(
            "success" => true,
            "message" => '',
        );

        if ($editar) {

            $actualizar = $this->vacacion->actualizar_estatus($id_prestamo, $dataSolicitud);

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

        $informacion = $this->prestamo->solicitud_reciente($id_usuario);

        $response = array(
            "success" => true,
            "message" => '',
            "informacion" => array()
        );

        if ($informacion) {
            foreach ($informacion as $item) {
                $response['informacion'][] = array(
                    'id_prestamo'       => $item->id_prestamo,
                    'titulo'            => $item->titulo,
                    'fecha_solicitud'   => $item->fecha_solicitud,
                    'motivo'            => $item->motivo,
                    'dias_trabajados'   => $item->dias_trabajados,
                    'dias_faltas'       => $item->dias_faltas,
                    'forma_pago'        => $item->forma_pago,
                    'cuenta_bancaria'   => $item->cuenta_bancaria,
                    'monto'             => $item->monto,
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
