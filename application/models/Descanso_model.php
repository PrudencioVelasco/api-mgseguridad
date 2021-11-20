<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Descanso_model extends CI_Model
{
    public function solicitudes($id_usuario)
    {
        $this->db->select('
        descanso.id_descanso,
        descanso.titulo,
        descanso.fecha_solicitud,
        descanso.dia_solicitado,
        usuario.dia_descanso,
        descanso.mensaje,
        descanso.estatus');
        $this->db->from('tb_descanso as descanso');
        $this->db->join('tb_usuario as usuario', 'usuario.id_usuario = descanso.id_usuario');
        $this->db->where('descanso.id_usuario', $id_usuario);
        $this->db->where('usuario.eliminado', 0);
        $this->db->where('usuario.estatus', 'Activo');
        $this->db->order_by('descanso.id_descanso', 'DESC');
        $query = $this->db->get();

        return ($query->num_rows() > 0) ? $query->result() : false;
    }

    public function comentario($id_descanso)
    {
        $this->db->select('comentario.id_comentario_descanso, comentario.comentario');
        $this->db->from('tb_descanso as descanso');
        $this->db->join('tb_comentario_descanso as comentario', 'comentario.id_descanso = descanso.id_descanso');
        $this->db->where('descanso.id_descanso', $id_descanso);
        $query = $this->db->get();

        return ($query->num_rows() > 0) ? $query->result() : false;
    }

    public function agregar($data)
    {
        return $this->db->insert('tb_descanso', $data);
    }

    public function editar($id_descanso, $data)
    {
        $this->db->where('id_descanso', $id_descanso);
        $this->db->update('tb_descanso', $data);

        return ($this->db->affected_rows() >= 0) ? true : false;
    }

    public function eliminar($id_descanso)
    {
        $this->db->where('id_descanso', $id_descanso);
        $this->db->delete('tb_descanso');

        return ($this->db->affected_rows() >= 0) ? true : false;
    }

    public function solicitudes_empleados()
    {
        $this->db->select("
        descanso.id_descanso,
        descanso.titulo,
        descanso.fecha_solicitud,
        descanso.dia_solicitado,
        usuario.dia_descanso,
        descanso.mensaje,
        descanso.estatus,
        descanso.id_usuario,
        concat(usuario.nombre,' ',usuario.apaterno,' ',usuario.amaterno) as nombre,
        usuario.url_perfil");
        $this->db->from('tb_descanso as descanso');
        $this->db->join('tb_usuario as usuario', 'usuario.id_usuario = descanso.id_usuario');
        $this->db->where('usuario.eliminado', 0);
        $this->db->where('usuario.estatus', 'Activo');
        $this->db->order_by('descanso.id_descanso', 'DESC');
        $query = $this->db->get();

        return ($query->num_rows() > 0) ? $query->result() : false;
    }

    public function responder_solicitud($data)
    {
        return $this->db->insert('tb_comentario_descanso', $data);
    }

    public function actualizar_estatus($id_descanso, $data)
    {
        $this->db->where('id_descanso', $id_descanso);
        $this->db->update('tb_descanso', $data);

        return ($this->db->affected_rows() >= 0) ? true : false;
    }

    public function editar_mensaje($id_comentario, $data)
    {
        $this->db->where('id_comentario_descanso', $id_comentario);
        $this->db->update('tb_comentario_descanso', $data);

        return ($this->db->affected_rows() >= 0) ? true : false;
    }

    public function solicitud_reciente($id_usuario)
    {
        $this->db->select('
        descanso.id_descanso,
        descanso.titulo,
        descanso.fecha_solicitud,
        descanso.dia_solicitado,
        descanso.mensaje,
        descanso.estatus');
        $this->db->from('tb_descanso as descanso');
        $this->db->join('tb_usuario as usuario', 'usuario.id_usuario = descanso.id_usuario');
        $this->db->where('descanso.id_usuario', $id_usuario);
        $this->db->where('usuario.eliminado', 0);
        $this->db->where('usuario.estatus', 'Activo');
        $this->db->order_by('descanso.id_descanso', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();

        return ($query->num_rows() > 0) ? $query->result() : false;
    }
}
