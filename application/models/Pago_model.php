<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pago_model extends CI_Model
{
    public function listado_empleados()
    {
        $this->db->select("
        usuario.id_usuario,
        concat(usuario.nombre,' ',usuario.apaterno,' ',usuario.amaterno) as nombre_completo,
        usuario.url_perfil,
        tipo_usuario.nombre as tipo_usuario,
        usuario.rfc,
        usuario.curp,
        usuario.pago_semanal");
        $this->db->from('tb_usuario as usuario');
        $this->db->join('tb_tipo_usuario as tipo_usuario', 'tipo_usuario.id_tipo_usuario = usuario.id_tipo_usuario');
        $this->db->where('usuario.eliminado', 0);
        $this->db->where('usuario.estatus', 'Activo');
        $this->db->where('tipo_usuario.nombre', 'Empleado');
        $query = $this->db->get();

        return ($query->num_rows() > 0) ? $query->result() : false;
    }

    public function pagar($data)
    {
        return $this->db->insert('tb_talon_pago', $data);
    }
}
