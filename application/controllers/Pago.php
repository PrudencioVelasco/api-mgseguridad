<?php
defined('BASEPATH') or exit('No direct script access allowed');

require(APPPATH . '/libraries/REST_Controller.php');

class Pago extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('pago_model', 'pago');
    }

    public function listado_empleados_get()
    {
        $empleados = $this->pago->listado_empleados();

        $response = array(
            "success" => true,
            "message" => '',
            "informacion" => array()
        );

        if ($empleados) {
            foreach ($empleados as $item) {
                $response['informacion'][] = array(
                    'id_usuario'        => $item->id_usuario,
                    'nombre_completo'   => $item->nombre_completo,
                    'url_perfil'        => $item->url_perfil,
                    'tipo_usuario'      => $item->tipo_usuario,
                    'rfc'               => $item->rfc,
                    'curp'              => $item->curp,
                    'pago_semanal'      => $item->pago_semanal
                );
            }

            $this->response($response);
        } else {
            $data['success'] = false;
            $data['message'] = 'Informacion no encontrada, intente mas tarde';

            $this->response($data);
        }
    }

    public function pagar_post()
    {
        $data = array(
            'fecha_pago'        => date('Y-m-d'),
            'dias_trabajados'   => $this->input->post('dias_trabajados'),
            'salario_bruto'     => $this->input->post('salario_bruto'),
            'salario_neto'      => $this->input->post('salario_neto'),
            'impuestos_imss'    => $this->input->post('impuestos_imss'),
            'id_usuario'        => $this->input->post('id_usuario')
        );

        $informacion = $this->pago->pagar($data);

        $response = array(
            "success" => true,
            "message" => ''
        );

        if ($informacion) {
            $response['success'] = true;
            $response['message'] = 'Pago realizado exitosamente';

            $this->response($response);
        } else {
            $data['success'] = false;
            $data['message'] = 'Pago no realizado, intente mas tarde';

            $this->response($data);
        }
    }
}
