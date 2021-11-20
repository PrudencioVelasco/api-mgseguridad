<?php
defined('BASEPATH') or exit('No direct script access allowed');

require(APPPATH . '/libraries/REST_Controller.php');

class Autenticacion extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('autenticacion_model', 'autenticacion');
    }

    public function iniciar_sesion_post()
    {
        $usuario = $this->post('usuario');
        $password = $this->post('password');

        $datos  = $this->autenticacion->iniciar_sesion($usuario);

        if ($datos) {
            foreach ($datos as $item) {
                if (password_verify($password, $item->password)) {

                    if ($item->estatus === "Activo") {
                        $response['success'] = true;
                        $response['message'] = 'Usuario autenticado exitosamente';
                        $response['id_usuario'] = $item->id_usuario;
                        $response['nombre'] = $item->nombre;
                        $response['nombre_completo'] = $item->nombre_completo;
                        $response['url_perfil'] = $item->url_perfil;
                        $response['tipo_usuario'] = $item->tipo_usuario;
                        $response['estatus'] = $item->estatus;

                        $this->response($response);
                    } else {
                        $dataInactive['success'] = false;
                        $dataInactive['message'] = 'Usuario inactivo';

                        $this->response($dataInactive);
                    }
                } else {
                    $dataUserPass['success'] = false;
                    $dataUserPass['message'] = 'Usuario o contraseña incorrecta';

                    $this->response($dataUserPass);
                }
            }
        } else {
            $dataUserNotFound['success'] = false;
            $dataUserNotFound['message'] = 'Usuario no registrado';

            $this->response($dataUserNotFound);
        }
    }
}
