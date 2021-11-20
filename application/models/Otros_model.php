<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Otros_model extends CI_Model
{
    public function solicitudes($id_usuario)
    {
        $this->db->select('
        otros.id_otros,
        otros.descripcion,
        otros.fecha_solicitud,
        otros.estatus');
        $this->db->from('tb_otros as otros');
        $this->db->join('tb_usuario as usuario', 'usuario.id_usuario = otros.id_usuario');
        $this->db->where('otros.id_usuario', $id_usuario);
        $this->db->where('usuario.eliminado', 0);
        $this->db->where('usuario.estatus', 'Activo');
        $this->db->order_by('otros.id_otros', 'DESC');
        $query = $this->db->get();

        return ($query->num_rows() > 0) ? $query->result() : false;
    }

    public function comentario($otros)
    {
        $this->db->select('comentario.id_comentario_otros, comentario.comentario');
        $this->db->from('tb_otros as otros');
        $this->db->join('tb_comentario_otros as comentario', 'comentario.id_otros = otros.id_otros');
        $this->db->where('otros.id_otros', $otros);
        $query = $this->db->get();

        return ($query->num_rows() > 0) ? $query->result() : false;
    }

    public function agregar($data)
    {
        return $this->db->insert('tb_otros', $data);
    }

    public function editar($id_otros, $data)
    {
        $this->db->where('id_otros', $id_otros);
        $this->db->update('tb_otros', $data);

        return ($this->db->affected_rows() >= 0) ? true : false;
    }

    public function eliminar($id_otros)
    {
        $this->db->where('id_otros', $id_otros);
        $this->db->delete('tb_otros');

        return ($this->db->affected_rows() >= 0) ? true : false;
    }

    public function solicitudes_empleados()
    {
        $this->db->select("
        otros.id_otros,
        otros.descripcion,
        otros.fecha_solicitud,
        otros.estatus,
        otros.id_usuario,
        concat(usuario.nombre,' ',usuario.apaterno,' ',usuario.amaterno) as nombre,
        usuario.url_perfil");
        $this->db->from('tb_otros as otros');
        $this->db->join('tb_usuario as usuario', 'usuario.id_usuario = otros.id_usuario');
        $this->db->where('usuario.eliminado', 0);
        $this->db->where('usuario.estatus', 'Activo');
        $this->db->order_by('otros.id_otros', 'DESC');
        $query = $this->db->get();

        return ($query->num_rows() > 0) ? $query->result() : false;
    }

    public function responder_solicitud($data)
    {
        return $this->db->insert('tb_comentario_otros', $data);
    }

    public function actualizar_estatus($id_otro, $data)
    {
        $this->db->where('id_otros', $id_otro);
        $this->db->update('tb_otros', $data);

        return ($this->db->affected_rows() >= 0) ? true : false;
    }

    public function editar_mensaje($id_comentario, $data)
    {
        $this->db->where('id_comentario_otros', $id_comentario);
        $this->db->update('tb_comentario_otros', $data);

        return ($this->db->affected_rows() >= 0) ? true : false;
    }
}
