<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Botas_model extends CI_Model
{
    public function solicitudes($id_usuario)
    {
        $this->db->select('
        bota.id_botas,
        bota.titulo,
        bota.numero,
        bota.motivo,
        bota.fecha_solicitud,
        bota.estatus');
        $this->db->from('tb_botas as bota');
        $this->db->join('tb_usuario as usuario', 'usuario.id_usuario = bota.id_usuario');
        $this->db->where('bota.id_usuario', $id_usuario);
        $this->db->where('usuario.eliminado', 0);
        $this->db->where('usuario.estatus', 'Activo');
        $this->db->order_by('bota.id_botas', 'DESC');
        $query = $this->db->get();

        return ($query->num_rows() > 0) ? $query->result() : false;
    }

    public function comentario($id_botas)
    {
        $this->db->select('comentario.id_comentario_botas, comentario.comentario');
        $this->db->from('tb_botas as bota');
        $this->db->join('tb_comentario_botas as comentario', 'comentario.id_botas = bota.id_botas');
        $this->db->where('bota.id_botas', $id_botas);
        $query = $this->db->get();

        return ($query->num_rows() > 0) ? $query->result() : false;
    }

    public function agregar($data)
    {
        return $this->db->insert('tb_botas', $data);
    }

    public function editar($data, $id_bota)
    {
        $this->db->where('id_botas', $id_bota);
        $this->db->update('tb_botas', $data);

        return ($this->db->affected_rows() >= 0) ? true : false;
    }

    public function eliminar($id_botas)
    {
        $this->db->where('id_botas', $id_botas);
        $this->db->delete('tb_botas');

        return ($this->db->affected_rows() >= 0) ? true : false;
    }

    public function solicitudes_empleados()
    {
        $this->db->select("
        bota.id_botas,
        bota.titulo,
        bota.numero,
        bota.motivo,
        bota.fecha_solicitud,
        bota.estatus,
        bota.id_usuario,
        concat(usuario.nombre,' ',usuario.apaterno,' ',usuario.amaterno) as nombre,
        usuario.url_perfil");
        $this->db->from('tb_botas as bota');
        $this->db->join('tb_usuario as usuario', 'usuario.id_usuario = bota.id_usuario');
        $this->db->where('usuario.eliminado', 0);
        $this->db->where('usuario.estatus', 'Activo');
        $this->db->order_by('bota.id_botas', 'DESC');
        $query = $this->db->get();

        return ($query->num_rows() > 0) ? $query->result() : false;
    }

    public function responder_solicitud($data)
    {
        return $this->db->insert('tb_comentario_botas', $data);
    }

    public function actualizar_estatus($id_botas, $data)
    {
        $this->db->where('id_botas', $id_botas);
        $this->db->update('tb_botas', $data);

        return ($this->db->affected_rows() >= 0) ? true : false;
    }

    public function editar_mensaje($id_comentario, $data)
    {
        $this->db->where('id_comentario_botas', $id_comentario);
        $this->db->update('tb_comentario_botas', $data);

        return ($this->db->affected_rows() >= 0) ? true : false;
    }
}
