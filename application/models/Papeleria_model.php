<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Papeleria_model extends CI_Model
{
    public function solicitudes($id_usuario)
    {
        $this->db->select('
        papeleria.id_papeleria,
        papeleria.titulo,
        papeleria.descripcion,
        papeleria.fecha_solicitud,
        papeleria.estatus');
        $this->db->from('tb_papeleria as papeleria');
        $this->db->join('tb_usuario as usuario', 'usuario.id_usuario = papeleria.id_usuario');
        $this->db->where('papeleria.id_usuario', $id_usuario);
        $this->db->where('usuario.eliminado', 0);
        $this->db->where('usuario.estatus', 'Activo');
        $this->db->order_by('papeleria.id_papeleria', 'DESC');
        $query = $this->db->get();

        return ($query->num_rows() > 0) ? $query->result() : false;
    }

    public function comentario($id_papeleria)
    {
        $this->db->select('comentario.id_comentario_papeleria, comentario.comentario');
        $this->db->from('tb_papeleria as papeleria');
        $this->db->join('tb_comentario_papeleria as comentario', 'comentario.id_papeleria = papeleria.id_papeleria');
        $this->db->where('papeleria.id_papeleria', $id_papeleria);
        $query = $this->db->get();

        return ($query->num_rows() > 0) ? $query->result() : false;
    }

    public function agregar($data)
    {
        return $this->db->insert('tb_papeleria', $data);
    }

    public function editar($id_papeleria, $data)
    {
        $this->db->where('id_papeleria', $id_papeleria);
        $this->db->update('tb_papeleria', $data);

        return ($this->db->affected_rows() >= 0) ? true : false;
    }

    public function eliminar($id_papeleria)
    {
        $this->db->where('id_papeleria', $id_papeleria);
        $this->db->delete('tb_papeleria');

        return ($this->db->affected_rows() >= 0) ? true : false;
    }

    public function solicitudes_empleados()
    {
        $this->db->select("
        papeleria.id_papeleria,
        papeleria.titulo,
        papeleria.descripcion,
        papeleria.fecha_solicitud,
        papeleria.estatus,
        papeleria.id_usuario,
        concat(usuario.nombre,' ',usuario.apaterno,' ',usuario.amaterno) as nombre,
        usuario.url_perfil");
        $this->db->from('tb_papeleria as papeleria');
        $this->db->join('tb_usuario as usuario', 'usuario.id_usuario = papeleria.id_usuario');
        $this->db->where('usuario.eliminado', 0);
        $this->db->where('usuario.estatus', 'Activo');
        $this->db->order_by('papeleria.id_papeleria', 'DESC');
        $query = $this->db->get();

        return ($query->num_rows() > 0) ? $query->result() : false;
    }

    public function responder_solicitud($data)
    {
        return $this->db->insert('tb_comentario_papeleria', $data);
    }

    public function actualizar_estatus($id_papeleria, $data)
    {
        $this->db->where('id_papeleria', $id_papeleria);
        $this->db->update('tb_papeleria', $data);

        return ($this->db->affected_rows() >= 0) ? true : false;
    }

    public function editar_mensaje($id_comentario, $data)
    {
        $this->db->where('id_comentario_papeleria', $id_comentario);
        $this->db->update('tb_comentario_papeleria', $data);

        return ($this->db->affected_rows() >= 0) ? true : false;
    }
}
