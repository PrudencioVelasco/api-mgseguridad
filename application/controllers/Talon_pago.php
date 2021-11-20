<?php
defined('BASEPATH') or exit('No direct script access allowed');

require(APPPATH . '/libraries/REST_Controller.php');

class Talon_pago extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('talon_model', 'talon');
    }

    /*
     * Metodo para los Empleados
     *  
    */

    public function historial_post()
    {
        $id_usuario = $this->input->post('id_usuario');

        $informacion = $this->talon->historial($id_usuario);

        $response = array(
            "success" => true,
            "message" => '',
            "informacion" => array()
        );

        if ($informacion) {
            foreach ($informacion as $item) {
                $response['informacion'][] = array(
                    'id_talon_pago'     => $item->id_talon_pago,
                    'rfc'               => $item->rfc,
                    'fecha_pago'        => $item->fecha_pago,
                    'dias_trabajados'   => $item->dias_trabajados,
                    'salario_bruto'     => $item->salario_bruto,
                    'salario_neto'      => $item->salario_neto,
                    'impuestos_imss'    => $item->impuestos_imss
                );
            }

            $this->response($response);
        } else {
            $data['success'] = false;
            $data['message'] = 'No existe informacion disponible, intente mas tarde';

            $this->response($data);
        }
    }

    /*
     * Metodo para el Administrador
     * 
    */

    public function historial_empleados_get()
    {
        $informacion = $this->talon->historial_empleados();

        $response = array(
            "success" => true,
            "message" => '',
            "informacion" => array()
        );

        if ($informacion) {
            foreach ($informacion as $item) {
                $response['informacion'][] = array(
                    'id_usuario'        => $item->id_usuario,
                    'nombre'            => $item->nombre,
                    'url_perfil'        => $item->url_perfil,
                    'rfc'               => $item->rfc,
                    'id_talon_pago'     => $item->id_talon_pago,
                    'fecha_pago'        => $item->fecha_pago,
                    'dias_trabajados'   => $item->dias_trabajados,
                    'salario_bruto'     => $item->salario_bruto,
                    'salario_neto'      => $item->salario_neto,
                    'impuestos_imss'    => $item->impuestos_imss
                );
            }

            $this->response($response);
        } else {
            $data['success'] = false;
            $data['message'] = 'No existe informacion disponible, intente mas tarde';

            $this->response($data);
        }
    }

    public function talon_pago_reciente_post()
    {
        $id_usuario = $this->input->post('id_usuario');

        $informacion = $this->talon->talon_pago_reciente($id_usuario);

        $response = array(
            "success" => true,
            "message" => '',
            "informacion" => array()
        );

        if ($informacion) {
            foreach ($informacion as $item) {
                $response['informacion'][] = array(
                    'id_talon_pago'     => $item->id_talon_pago,
                    'rfc'               => $item->rfc,
                    'fecha_pago'        => $item->fecha_pago,
                    'dias_trabajados'   => $item->dias_trabajados,
                    'salario_bruto'     => $item->salario_bruto,
                    'salario_neto'      => $item->salario_neto,
                    'impuestos_imss'    => $item->impuestos_imss
                );
            }

            $this->response($response);
        } else {
            $data['success'] = false;
            $data['message'] = 'No existe informacion disponible, intente mas tarde';

            $this->response($data);
        }
    }
}
