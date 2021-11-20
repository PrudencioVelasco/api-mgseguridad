<?php
defined('BASEPATH') or exit('No direct script access allowed');

require(APPPATH . '/libraries/REST_Controller.php');

class Usuario extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('usuario_model', 'usuario');
    }

    public function listado_get()
    {
        $informacion = $this->usuario->listado();

        $response = array(
            "success" => true,
            "message" => '',
            "informacion" => array()
        );

        if ($informacion) {
            foreach ($informacion as $item) {
                $response['informacion'][] = array(
                    'id_usuario'            => $item->id_usuario,
                    'nombre_completo'       => $item->nombre_completo,
                    'sexo'                  => $item->sexo,
                    'telefono'              => $item->no_telefonico,
                    'correo'                => $item->correo_electronico,
                    'ciudad'                => $item->ciudad,
                    'estatus_empleado'      => $item->estatus_empleado,
                    'estatus'               => $item->estatus,
                    'url_perfil'            => $item->url_perfil
                );
            }

            $this->response($response);
        } else {
            $data['success'] = false;
            $data['message'] = 'Informacion no disponible, intente más tarde';

            $this->response($data);
        }
    }

    public function informacion_basica_post()
    {
        $id_usuario = $this->input->post('id_usuario');

        $informacion = $this->usuario->informacion_basica($id_usuario);

        $response = array(
            "success" => true,
            "message" => '',
            "informacion" => array()
        );

        if ($informacion) {

            $area_trabajo = $this->usuario->area_trabajo($informacion[0]->id_usuario);

            if ($area_trabajo) {
                foreach ($informacion as $item) {
                    foreach ($area_trabajo as $area) {
                        $response['informacion'][] = array(
                            'id_usuario'            => $item->id_usuario,
                            'nombre_completo'       => $item->nombre_completo,
                            'url_perfil'            => $item->url_perfil,
                            'tipo_usuario'          => $item->tipo_usuario,
                            'sexo'                  => $item->sexo,
                            'no_telefonico'         => $item->no_telefonico,
                            'ciudad'                => $item->ciudad,
                            'rfc'                   => $item->rfc,
                            'correo_electronico'    => $item->correo_electronico,
                            'direccion'             => $item->direccion,
                            'area_trabajo'          => array(
                                'id_area_trabajo'       => $area->id_area_trabajo,
                                'puesto'                => $area->puesto,
                                'servicio_asignado'     => $area->servicio_asignado,
                                'lugar_servicio'        => $area->lugar_servicio,
                                'turno'                 => $area->turno
                            )
                        );
                    }
                }

                $this->response($response);
            } else {
                $data['success'] = false;
                $data['message'] = 'Usuario no registrado.';

                $this->response($data);
            }
        }
    }

    public function registrar_post()
    {
        $response = array(
            "success" => true,
            "message" => ''
        );

        $data = array(
            'nombre'             => $this->input->post('nombre'),
            'apaterno'           => $this->input->post('apaterno'),
            'amaterno'           => $this->input->post('amaterno'),
            'fecha_nacimiento'   => $this->input->post('fecha_nacimiento'),
            'sexo'               => $this->input->post('sexo'),
            'estado_civil'       => $this->input->post('estado_civil'),
            'curp'               => $this->input->post('curp'),
            'no_telefonico'      => $this->input->post('no_telefonico'),
            'nivel_estudio'      => $this->input->post('nivel_estudio'),
            'ciudad'             => $this->input->post('ciudad'),
            'codigo_postal'      => $this->input->post('codigo_postal'),
            'correo_electronico' => $this->input->post('correo_electronico'),
            'fecha_ingreso'      => $this->input->post('fecha_ingreso'),
            'pago_semanal'       => $this->input->post('pago_semanal'),
            'direccion'          => $this->input->post('direccion'),
            'no_servicio_social' => $this->input->post('no_servicio_social'),
            'rfc'                => $this->input->post('rfc'),
            'estatus_empleado'   => $this->input->post('estatus_empleado'),
            'dia_descanso'       => $this->input->post('dia_descanso'),
            'auxiliar_nombre'    => $this->input->post('auxiliar_nombre'),
            'auxiliar_telefono'  => $this->input->post('auxiliar_telefono'),
            'usuario'            => $this->input->post('usuario'),
            'password'           => password_hash(trim($this->input->post('password')), PASSWORD_BCRYPT),
            'estatus'            => $this->input->post('estatus'),
            'url_perfil'         => $this->input->post('url_perfil'),
            'eliminado'          => 0,
            'id_tipo_usuario'    => $this->input->post('id_tipo_usuario')
        );

        $id_usuario = $this->usuario->registrar_usuario($data);

        if (!empty($id_usuario)) {

            $data = array(
                'puesto'            => $this->input->post('puesto'),
                'servicio_asignado' => $this->input->post('servicio_asignado'),
                'lugar_servicio'    => $this->input->post('lugar_servicio'),
                'turno'             => $this->input->post('turno'),
                'id_usuario'        => $id_usuario
            );

            $area_trabajo = $this->usuario->registrar_area_trabajo($data);

            if ($area_trabajo) {
                $response['success'] =   true;
                $response['message'] =   'Usuario registrado exitosamente';

                $this->response($response);
            } else {
                $data['success'] = false;
                $data['message'] = 'No se ha podido registrar, intente más tarde';

                $this->response($data);
            }
        }
    }

    public function actualizar_post()
    {
        $id_usuario = $this->input->post('id_usuario');

        $response = array(
            "success" => true,
            "message" => ''
        );

        $data = array(
            'nombre'             => $this->input->post('nombre'),
            'apaterno'           => $this->input->post('apaterno'),
            'amaterno'           => $this->input->post('amaterno'),
            'fecha_nacimiento'   => $this->input->post('fecha_nacimiento'),
            'sexo'               => $this->input->post('sexo'),
            'estado_civil'       => $this->input->post('estado_civil'),
            'curp'               => $this->input->post('curp'),
            'no_telefonico'      => $this->input->post('no_telefonico'),
            'nivel_estudio'      => $this->input->post('nivel_estudio'),
            'ciudad'             => $this->input->post('ciudad'),
            'codigo_postal'      => $this->input->post('codigo_postal'),
            'correo_electronico' => $this->input->post('correo_electronico'),
            'fecha_ingreso'      => $this->input->post('fecha_ingreso'),
            'direccion'          => $this->input->post('direccion'),
            'no_servicio_social' => $this->input->post('no_servicio_social'),
            'rfc'                => $this->input->post('rfc'),
            'auxiliar_nombre'    => $this->input->post('auxiliar_nombre'),
            'auxiliar_telefono'  => $this->input->post('auxiliar_telefono'),
            'usuario'            => $this->input->post('usuario'),
            'url_perfil'         => $this->input->post('url_perfil')
        );

        $actualizar = $this->usuario->actualizar_usuario($data, $id_usuario);

        if ($actualizar) {
            $response['success'] =   true;
            $response['message'] =   'Informacion actualizada exitosamente';

            $this->response($response);
        } else {
            $data['success'] = false;
            $data['message'] = 'No se ha podido actualizar, intente más tarde';

            $this->response($data);
        }
    }

    public function actualizar_usuario_post()
    {
        $id_usuario = $this->input->post('id_usuario');
        $id_area_trabajo = $this->input->post('id_area_trabajo');

        $response = array(
            "success" => true,
            "message" => ''
        );

        $dataUsuario = array(
            'nombre'             => $this->input->post('nombre'),
            'apaterno'           => $this->input->post('apaterno'),
            'amaterno'           => $this->input->post('amaterno'),
            'fecha_nacimiento'   => $this->input->post('fecha_nacimiento'),
            'sexo'               => $this->input->post('sexo'),
            'estado_civil'       => $this->input->post('estado_civil'),
            'curp'               => $this->input->post('curp'),
            'no_telefonico'      => $this->input->post('no_telefonico'),
            'nivel_estudio'      => $this->input->post('nivel_estudio'),
            'ciudad'             => $this->input->post('ciudad'),
            'codigo_postal'      => $this->input->post('codigo_postal'),
            'correo_electronico' => $this->input->post('correo_electronico'),
            'fecha_ingreso'      => $this->input->post('fecha_ingreso'),
            'pago_semanal'       => $this->input->post('pago_semanal'),
            'direccion'          => $this->input->post('direccion'),
            'no_servicio_social' => $this->input->post('no_servicio_social'),
            'rfc'                => $this->input->post('rfc'),
            'estatus_empleado'   => $this->input->post('estatus_empleado'),
            'dia_descanso'       => $this->input->post('dia_descanso'),
            'auxiliar_nombre'    => $this->input->post('auxiliar_nombre'),
            'auxiliar_telefono'  => $this->input->post('auxiliar_telefono'),
            'usuario'            => $this->input->post('usuario'),
            'password'           => (!empty($this->input->post('password_nueva'))) ? password_hash(trim($this->input->post('password_nueva')), PASSWORD_BCRYPT) : $this->input->post('password_anterior'),
            'estatus'            => $this->input->post('estatus'),
            'url_perfil'         => $this->input->post('url_perfil'),
            'id_tipo_usuario'    => $this->input->post('id_tipo_usuario')
        );

        $dataTrabajo = array(
            'puesto'            => $this->input->post('puesto'),
            'servicio_asignado' => $this->input->post('servicio_asignado'),
            'lugar_servicio'    => $this->input->post('lugar_servicio'),
            'turno'             => $this->input->post('turno'),
            'id_usuario'        => $id_usuario
        );

        $actualizar = $this->usuario->actualizar_usuario($dataUsuario, $id_usuario);

        if ($actualizar) {

            $area_trabajo = $this->usuario->actualizar_area_trabajo($dataTrabajo, $id_area_trabajo);

            $response['success'] =   true;
            $response['message'] =   'Informacion actualizada exitosamente';

            $this->response($response);
        } else {
            $data['success'] = false;
            $data['message'] = 'No se ha podido actualizar, intente más tarde';

            $this->response($data);
        }
    }

    public function informacion_completa_post()
    {
        $id_usuario = $this->input->post('id_usuario');

        $informacion = $this->usuario->informacion_completa($id_usuario);

        $response = array(
            "success" => true,
            "message" => '',
            "informacion" => array()
        );

        if ($informacion) {
            $area_trabajo = $this->usuario->area_trabajo($informacion[0]->id_usuario);

            if ($area_trabajo) {
                foreach ($informacion as $item) {
                    foreach ($area_trabajo as $area) {
                        $response['informacion'][] = array(
                            'id_usuario'            => $item->id_usuario,
                            'nombre'                => $item->nombre,
                            'apaterno'              => $item->apaterno,
                            'amaterno'              => $item->amaterno,
                            'fecha_nacimiento'      => $item->fecha_nacimiento,
                            'sexo'                  => $item->sexo,
                            'estado_civil'          => $item->estado_civil,
                            'curp'                  => $item->curp,
                            'no_telefonico'         => $item->no_telefonico,
                            'nivel_estudio'         => $item->nivel_estudio,
                            'ciudad'                => $item->ciudad,
                            'codigo_postal'         => $item->codigo_postal,
                            'correo_electronico'    => $item->correo_electronico,
                            'fecha_ingreso'         => $item->fecha_ingreso,
                            'pago_semanal'          => $item->pago_semanal,
                            'direccion'             => $item->direccion,
                            'no_servicio_social'    => $item->no_servicio_social,
                            'rfc'                   => $item->rfc,
                            'estatus_empleado'      => $item->estatus_empleado,
                            'dia_descanso'          => $item->dia_descanso,
                            'auxiliar_nombre'       => $item->auxiliar_nombre,
                            'auxiliar_telefono'     => $item->auxiliar_telefono,
                            'usuario'               => $item->usuario,
                            'password'              => $item->password,
                            'estatus'               => $item->estatus,
                            'url_perfil'            => $item->url_perfil,
                            'tipo_usuario'          => $item->tipo_usuario,
                            'area_trabajo'          => array(
                                'id_area_trabajo'       => $area->id_area_trabajo,
                                'puesto'                => $area->puesto,
                                'servicio_asignado'     => $area->servicio_asignado,
                                'lugar_servicio'        => $area->lugar_servicio,
                                'turno'                 => $area->turno
                            )
                        );
                    }
                }

                $this->response($response);
            } else {
                $data['success'] = false;
                $data['message'] = 'Informacion no encontrada, intente más tarde';

                $this->response($data);
            }
        }
    }

    public function actualizar_password_post()
    {
        $id_usuario = $this->input->post('id_usuario');
        $password = $this->input->post('password');

        $response = array(
            "success" => true,
            "message" => ''
        );

        $data = array(
            'password'  => password_hash(trim($this->input->post('nueva_password')), PASSWORD_BCRYPT)
        );

        $info = $this->usuario->seleccionar_password($id_usuario);

        if (password_verify($password, $info[0]->password)) {
            $actualizar = $this->usuario->actualizar_password($data, $id_usuario);

            if ($actualizar) {
                $response['success'] =   true;
                $response['message'] =   'Contraseña actualizada exitosamente';

                $this->response($response);
            } else {
                $data['success'] = false;
                $data['message'] = 'No se ha podido actualizar, intente más tarde';

                $this->response($data);
            }
        } else {
            $data_pass['success'] = false;
            $data_pass['message'] = 'La contraseña actual es incorrecta';

            $this->response($data_pass);
        }
    }

    public function eliminar_post()
    {
        $id_usuario = $this->input->post('id_usuario');

        $response = array(
            "success" => true,
            "message" => ''
        );

        $data = array(
            'eliminado'    => 1
        );

        $eliminar = $this->usuario->eliminar($data, $id_usuario);

        if ($eliminar) {
            $response['success'] =   true;
            $response['message'] =   'Usuario eliminado exitosamente';

            $this->response($response);
        } else {
            $data['success'] = false;
            $data['message'] = 'Usuario no eliminado, intente más tarde';

            $this->response($data);
        }
    }

    public function listado_prestamo_materiales_post()
    {
        $id_usuario = $this->input->post('id_usuario');

        $info_bota = $this->usuario->prestamo_material_botas($id_usuario);
        $info_uniforme = $this->usuario->prestamo_material_uniforme($id_usuario);
        $info_papeleria = $this->usuario->prestamo_material_papeleria($id_usuario);
        $info_otros = $this->usuario->prestamo_material_otros($id_usuario);

        $response = array(
            "success" => true,
            "message" => '',
            "informacion" => array()
        );

        if ($info_bota) {
            $response['informacion'][] = array(
                'titulo'            => $info_bota[0]->titulo,
                'motivo'            => $info_bota[0]->motivo,
                'fecha_solicitud'   => $info_bota[0]->fecha_solicitud,
                'categoria'         => 'Botas',
                'estatus'           => $info_bota[0]->estatus
            );
        }

        if ($info_uniforme) {
            $response['informacion'][] = array(
                'titulo'            => $info_uniforme[0]->titulo,
                'motivo'            => $info_uniforme[0]->motivo,
                'fecha_solicitud'   => $info_uniforme[0]->fecha_solicitud,
                'categoria'         => 'Uniforme',
                'estatus'           => $info_uniforme[0]->estatus
            );
        }

        if ($info_papeleria) {
            $response['informacion'][] = array(
                'titulo'            => $info_papeleria[0]->titulo,
                'motivo'            => $info_papeleria[0]->descripcion,
                'fecha_solicitud'   => $info_papeleria[0]->fecha_solicitud,
                'categoria'         => 'Papeleria',
                'estatus'           => $info_papeleria[0]->estatus
            );
        }

        if ($info_otros) {
            $response['informacion'][] = array(
                'titulo'            => '',
                'motivo'            => $info_otros[0]->descripcion,
                'fecha_solicitud'   => $info_otros[0]->fecha_solicitud,
                'categoria'         => 'Otros',
                'estatus'           => $info_otros[0]->estatus
            );
        }

        $this->response($response);
    }

    public function generar_post(){
        $password = password_hash(trim($this->input->post('password')), PASSWORD_BCRYPT);

        var_dump($password);
    }
}
