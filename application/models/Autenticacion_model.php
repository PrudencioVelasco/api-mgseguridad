<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Autenticacion_model extends CI_Model
{

    public function iniciar_sesion($usuario)
    {
        $this->db->select("
        usuario.id_usuario,
        usuario.nombre,
        concat(usuario.nombre,' ',usuario.apaterno,' ',usuario.amaterno) as nombre_completo,
        usuario.url_perfil,
        tipo_usuario.nombre as tipo_usuario,
        usuario.usuario,
        usuario.password,
        usuario.estatus");
        $this->db->from('tb_usuario as usuario');
        $this->db->join('tb_tipo_usuario as tipo_usuario', 'tipo_usuario.id_tipo_usuario = usuario.id_tipo_usuario');
        $this->db->where('usuario.usuario', $usuario);
        $this->db->where('usuario.eliminado', 0);
        $this->db->limit(1);
        $query = $this->db->get();

        return ($query->num_rows() > 0) ? $query->result() : false;
    }
}
