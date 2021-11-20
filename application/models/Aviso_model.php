<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Aviso_model extends CI_Model
{
    public function listado()
    {
        $this->db->select('*');
        $this->db->from('tb_avisos');
        $this->db->order_by('id_avisos', 'DESC');
        $query = $this->db->get();

        return ($query->num_rows() > 0) ? $query->result() : false;
    }

    public function listado_por_usuario($id_usuario)
    {
        $this->db->select('aviso.*');
        $this->db->from('tb_avisos as aviso');
        $this->db->join('tb_usuario as usuario', 'usuario.id_usuario = aviso.id_usuario');
        $this->db->where('aviso.id_usuario', $id_usuario);
        $this->db->where('usuario.eliminado', 0);
        $this->db->where('usuario.estatus', 'Activo');
        $this->db->order_by('id_avisos', 'DESC');
        $query = $this->db->get();

        return ($query->num_rows() > 0) ? $query->result() : false;
    }

    public function agregar($data)
    {
        return $this->db->insert('tb_avisos', $data);
    }

    public function actualizar($id_aviso, $data)
    {
        $this->db->where('id_avisos', $id_aviso);
        $this->db->update('tb_avisos', $data);

        return ($this->db->affected_rows() >= 0) ? true : false;
    }

    public function eliminar($id_aviso)
    {
        $this->db->where('id_avisos', $id_aviso);
        $this->db->delete('tb_avisos');

        return ($this->db->affected_rows() >= 0) ? true : false;
    }

    public function aviso_reciente()
    {
        $this->db->select('*');
        $this->db->from('tb_avisos');
        $this->db->order_by('id_avisos', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();

        return ($query->num_rows() > 0) ? $query->result() : false;
    }
}
