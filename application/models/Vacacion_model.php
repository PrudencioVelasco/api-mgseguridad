<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Vacacion_model extends CI_Model
{

    public function solicitudes($id_usuario)
    {
        $this->db->select('
        vacacion.id_vacaciones,
        vacacion.titulo,
        vacacion.fecha_inicio,
        vacacion.fecha_fin,
        vacacion.comentario as motivo,
        vacacion.estatus,
        vacacion.fecha_solicitud');
        $this->db->from('tb_vacaciones as vacacion');
        $this->db->join('tb_usuario as usuario', 'usuario.id_usuario = vacacion.id_usuario');
        $this->db->where('vacacion.id_usuario', $id_usuario);
        $this->db->where('usuario.eliminado', 0);
        $this->db->where('usuario.estatus', 'Activo');
        $this->db->order_by('vacacion.id_vacaciones', 'DESC');
        $query = $this->db->get();

        return ($query->num_rows() > 0) ? $query->result() : false;
    }

    public function comentario($id_vacacion)
    {
        $this->db->select('comentario.id_comentario_vacaciones,comentario.comentario');
        $this->db->from('tb_vacaciones as vacacion');
        $this->db->join('tb_comentario_vacaciones as comentario', 'comentario.id_vacaciones = vacacion.id_vacaciones');
        $this->db->where('vacacion.id_vacaciones', $id_vacacion);
        $query = $this->db->get();

        return ($query->num_rows() > 0) ? $query->result() : false;
    }

    public function agregar($data)
    {
        return $this->db->insert('tb_vacaciones', $data);
    }

    public function editar($id_vacacion, $data)
    {
        $this->db->where('id_vacaciones', $id_vacacion);
        $this->db->update('tb_vacaciones', $data);

        return ($this->db->affected_rows() >= 0) ? true : false;
    }

    public function eliminar($id_vacacion)
    {
        $this->db->where('id_vacaciones', $id_vacacion);
        $this->db->delete('tb_vacaciones');

        return ($this->db->affected_rows() > 0) ? true : false;
    }

    public function solicitudes_empleados()
    {
        $this->db->select("
        vacacion.id_vacaciones,
        vacacion.titulo,
        vacacion.fecha_inicio,
        vacacion.fecha_fin,
        vacacion.comentario as motivo,
        vacacion.estatus,
        vacacion.fecha_solicitud,
        vacacion.id_usuario,
        concat(usuario.nombre,' ',usuario.apaterno,' ',usuario.amaterno) as nombre,
        usuario.url_perfil");
        $this->db->from('tb_vacaciones as vacacion');
        $this->db->join('tb_usuario as usuario', 'usuario.id_usuario = vacacion.id_usuario');
        $this->db->where('usuario.eliminado', 0);
        $this->db->where('usuario.estatus', 'Activo');
        $this->db->order_by('vacacion.id_vacaciones', 'DESC');
        $query = $this->db->get();

        return ($query->num_rows() > 0) ? $query->result() : false;
    }

    public function responder_solicitud($data)
    {
        return $this->db->insert('tb_comentario_vacaciones', $data);
    }

    public function actualizar_estatus($id_vacacion, $data)
    {
        $this->db->where('id_vacaciones', $id_vacacion);
        $this->db->update('tb_vacaciones', $data);

        return ($this->db->affected_rows() >= 0) ? true : false;
    }

    public function editar_mensaje($id_comentario, $data)
    {
        $this->db->where('id_comentario_vacaciones', $id_comentario);
        $this->db->update('tb_comentario_vacaciones', $data);

        return ($this->db->affected_rows() >= 0) ? true : false;
    }

    public function solicitud_reciente($id_usuario)
    {
        $this->db->select('
        vacacion.id_vacaciones,
        vacacion.titulo,
        vacacion.fecha_inicio,
        vacacion.fecha_fin,
        vacacion.comentario as motivo,
        vacacion.estatus,
        vacacion.fecha_solicitud');
        $this->db->from('tb_vacaciones as vacacion');
        $this->db->join('tb_usuario as usuario', 'usuario.id_usuario = vacacion.id_usuario');
        $this->db->where('vacacion.id_usuario', $id_usuario);
        $this->db->where('usuario.eliminado', 0);
        $this->db->where('usuario.estatus', 'Activo');
        $this->db->order_by('vacacion.id_vacaciones', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();

        return ($query->num_rows() > 0) ? $query->result() : false;
    }
}
