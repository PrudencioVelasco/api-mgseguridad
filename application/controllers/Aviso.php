<?php
defined('BASEPATH') or exit('No direct script access allowed');

require(APPPATH . '/libraries/REST_Controller.php');

class Aviso extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('aviso_model', 'aviso');
    }

    public function listado_get()
    {
        $informacion = $this->aviso->listado();

        $response = array(
            "success" => true,
            "message" => '',
            "informacion" => array()
        );

        if ($informacion) {
            foreach ($informacion as $item) {
                $response['informacion'][] = array(
                    'id_aviso'         => $item->id_avisos,
                    'titulo'            => $item->titulo,
                    'mensaje'           => $item->mensaje,
                    'fecha_publicacion' => $item->fecha_publicacion,
                    'id_usuario'        => $item->id_usuario
                );
            }

            $this->response($response);
        } else {
            $data['success'] = false;
            $data['message'] = 'Sin informacion disponible, intente más tarde';

            $this->response($data);
        }
    }

    public function listado_por_usuario_post()
    {
        $id_usuario = $this->input->post('id_usuario');

        $informacion = $this->aviso->listado_por_usuario($id_usuario);

        $response = array(
            "success" => true,
            "message" => '',
            "informacion" => array()
        );

        if ($informacion) {
            foreach ($informacion as $item) {
                $response['informacion'][] = array(
                    'id_aviso'         => $item->id_avisos,
                    'titulo'            => $item->titulo,
                    'mensaje'           => $item->mensaje,
                    'fecha_publicacion' => $item->fecha_publicacion,
                    'id_usuario'        => $item->id_usuario
                );
            }

            $this->response($response);
        } else {
            $data['success'] = false;
            $data['message'] = 'Sin informacion disponible, intente más tarde';

            $this->response($data);
        }
    }

    public function agregar_post()
    {
        $response = array(
            "success" => true,
            "message" => ''
        );

        $data = array(
            'titulo'                => $this->input->post('titulo'),
            'mensaje'               => $this->input->post('mensaje'),
            'fecha_publicacion'     => $this->input->post('fecha_publicacion'),
            'id_usuario'            => $this->input->post('id_usuario')
        );

        $agregar = $this->aviso->agregar($data);

        if ($agregar) {
            $response['success'] =   true;
            $response['message'] =   'Aviso agregado exitosamente';

            $this->response($response);
        } else {
            $data['success'] = false;
            $data['message'] = 'Informacion no agregada, intente más tarde';

            $this->response($data);
        }
    }

    public function actualizar_post()
    {
        $id_aviso = $this->input->post('id_aviso');

        $response = array(
            "success" => true,
            "message" => ''
        );

        $data = array(
            'titulo'                => $this->input->post('titulo'),
            'mensaje'               => $this->input->post('mensaje'),
            'fecha_publicacion'     => $this->input->post('fecha_publicacion')
        );

        $actualizar = $this->aviso->actualizar($id_aviso, $data);

        if ($actualizar) {
            $response['success'] =   true;
            $response['message'] =   'Aviso actualizado exitosamente';

            $this->response($response);
        } else {
            $data['success'] = false;
            $data['message'] = 'Informacion no actualizada, intente mas tarde';

            $this->response($data);
        }
    }

    public function eliminar_post()
    {
        $id_aviso = $this->input->post('id_aviso');

        $response = array(
            "success" => true,
            "message" => ''
        );

        $eliminar = $this->aviso->eliminar($id_aviso);

        if ($eliminar) {
            $response['success'] =  true;
            $response['message'] =  'Aviso eliminado exitosamente';

            $this->response($response);
        } else {
            $data['success'] = false;
            $data['message'] = 'Informacion no eliminada, intente más tarde';

            $this->response($data);
        }
    }

    public function aviso_reciente_get()
    {
        $informacion = $this->aviso->aviso_reciente();

        $response = array(
            "success" => true,
            "message" => '',
            "informacion" => array()
        );

        if ($informacion) {

            foreach ($informacion as $item) {
                $response['informacion'][] = array(
                    'id_aviso'         => $item->id_avisos,
                    'titulo'            => $item->titulo,
                    'mensaje'           => $item->mensaje,
                    'fecha_publicacion' => $item->fecha_publicacion
                );
            }

            $this->response($response);
        } else {
            $data['success'] = false;
            $data['message'] = 'Sin informacion disponible, intente más tarde';

            $this->response($data);
        }
    }
}
