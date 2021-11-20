<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Uniforme_model extends CI_Model
{
    public function solicitudes($id_usuario)
    {
        $this->db->select('
        uniforme.id_uniforme,
        uniforme.titulo,
        uniforme.talla,
        uniforme.color,
        uniforme.motivo,
        uniforme.fecha_solicitud,
        uniforme.estatus');
        $this->db->from('tb_uniformes as uniforme');
        $this->db->join('tb_usuario as usuario', 'usuario.id_usuario = uniforme.id_usuario');
        $this->db->where('uniforme.id_usuario', $id_usuario);
        $this->db->where('usuario.eliminado', 0);
        $this->db->where('usuario.estatus', 'Activo');
        $this->db->order_by('uniforme.id_uniforme', 'DESC');
        $query = $this->db->get();

        return ($query->num_rows() > 0) ? $query->result() : false;
    }

    public function comentario($id_uniforme)
    {
        $this->db->select('comentario.id_comentario_uniforme, comentario.comentario');
        $this->db->from('tb_uniformes as uniforme');
        $this->db->join('tb_comentario_uniforme as comentario', 'comentario.id_uniforme = uniforme.id_uniforme');
        $this->db->where('uniforme.id_uniforme', $id_uniforme);
        $query = $this->db->get();

        return ($query->num_rows() > 0) ? $query->result() : false;
    }

    public function agregar($data)
    {
        return $this->db->insert('tb_uniformes', $data);
    }

    public function editar($id_uniforme, $data)
    {
        $this->db->where('id_uniforme', $id_uniforme);
        $this->db->update('tb_uniformes', $data);

        return ($this->db->affected_rows() >= 0) ? true : false;
    }

    public function eliminar($id_uniforme)
    {
        $this->db->where('id_uniforme', $id_uniforme);
        $this->db->delete('tb_uniformes');

        return ($this->db->affected_rows() >= 0) ? true : false;
    }

    public function solicitudes_empleados()
    {
        $this->db->select("
        uniforme.id_uniforme,
        uniforme.titulo,
        uniforme.talla,
        uniforme.color,
        uniforme.motivo,
        uniforme.fecha_solicitud,
        uniforme.estatus,
        uniforme.id_usuario,
        concat(usuario.nombre,' ',usuario.apaterno,' ',usuario.amaterno) as nombre,
        usuario.url_perfil,");
        $this->db->from('tb_uniformes as uniforme');
        $this->db->join('tb_usuario as usuario', 'usuario.id_usuario = uniforme.id_usuario');
        $this->db->where('usuario.eliminado', 0);
        $this->db->where('usuario.estatus', 'Activo');
        $this->db->order_by('uniforme.id_uniforme', 'DESC');
        $query = $this->db->get();

        return ($query->num_rows() > 0) ? $query->result() : false;
    }

    public function responder_solicitud($data)
    {
        return $this->db->insert('tb_comentario_uniforme', $data);
    }

    public function actualizar_estatus($id_uniforme, $data)
    {
        $this->db->where('id_uniforme', $id_uniforme);
        $this->db->update('tb_uniformes', $data);

        return ($this->db->affected_rows() >= 0) ? true : false;
    }

    public function editar_mensaje($id_comentario, $data)
    {
        $this->db->where('id_comentario_uniforme', $id_comentario);
        $this->db->update('tb_comentario_uniforme', $data);

        return ($this->db->affected_rows() >= 0) ? true : false;
    }
}
