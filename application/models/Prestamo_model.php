<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Prestamo_model extends CI_Model
{
    public function solicitudes($id_usuario)
    {
        $this->db->select("
        prestamo.id_prestamo,
        prestamo.titulo,
        prestamo.fecha_solicitud,
        prestamo.motivo,
        prestamo.dias_trabajados,
        prestamo.dias_faltas,
        prestamo.forma_pago,
        prestamo.cuenta_bancaria,
        prestamo.monto,
        prestamo.estatus,
        usuario.fecha_ingreso,
        concat(usuario.nombre,' ',usuario.apaterno,' ',usuario.amaterno) as nombre");
        $this->db->from('tb_prestamo as prestamo');
        $this->db->join('tb_usuario as usuario', 'usuario.id_usuario = prestamo.id_usuario');
        $this->db->where('prestamo.id_usuario', $id_usuario);
        $this->db->where('usuario.eliminado', 0);
        $this->db->where('usuario.estatus', 'Activo');
        $this->db->order_by('prestamo.id_prestamo', 'DESC');
        $query = $this->db->get();

        return ($query->num_rows() > 0) ? $query->result() : false;
    }

    public function comentario($id_prestamo)
    {
        $this->db->select('comentario.id_comentario_prestamo, comentario.comentario');
        $this->db->from('tb_prestamo as prestamo');
        $this->db->join('tb_comentario_prestamo as comentario', 'comentario.id_prestamo = prestamo.id_prestamo');
        $this->db->where('prestamo.id_prestamo', $id_prestamo);
        $query = $this->db->get();

        return ($query->num_rows() > 0) ? $query->result() : false;
    }

    public function agregar($data)
    {
        return $this->db->insert('tb_prestamo', $data);
    }

    public function editar($id_prestamo, $data)
    {
        $this->db->where('id_prestamo', $id_prestamo);
        $this->db->update('tb_prestamo', $data);

        return ($this->db->affected_rows() >= 0) ? true : false;
    }

    public function eliminar($id_prestamo)
    {
        $this->db->where('id_prestamo', $id_prestamo);
        $this->db->delete('tb_prestamo');

        return ($this->db->affected_rows() >= 0) ? true : false;
    }

    public function solicitudes_empleados()
    {
        $this->db->select("
        prestamo.id_prestamo,
        prestamo.titulo,
        prestamo.fecha_solicitud,
        prestamo.motivo,
        prestamo.dias_trabajados,
        prestamo.dias_faltas,
        prestamo.forma_pago,
        prestamo.cuenta_bancaria,
        prestamo.monto,
        prestamo.estatus,
        prestamo.id_usuario,
        concat(usuario.nombre,' ',usuario.apaterno,' ',usuario.amaterno) as nombre,
        usuario.fecha_ingreso,
        usuario.url_perfil");
        $this->db->from('tb_prestamo as prestamo');
        $this->db->join('tb_usuario as usuario', 'usuario.id_usuario = prestamo.id_usuario');
        $this->db->where('usuario.eliminado', 0);
        $this->db->where('usuario.estatus', 'Activo');
        $this->db->order_by('prestamo.id_prestamo', 'DESC');
        $query = $this->db->get();

        return ($query->num_rows() > 0) ? $query->result() : false;
    }

    public function responder_solicitud($data)
    {
        return $this->db->insert('tb_comentario_prestamo', $data);
    }

    public function actualizar_estatus($id_prestamo, $data)
    {
        $this->db->where('id_prestamo', $id_prestamo);
        $this->db->update('tb_prestamo', $data);

        return ($this->db->affected_rows() >= 0) ? true : false;
    }

    public function editar_mensaje($id_comentario, $data)
    {
        $this->db->where('id_comentario_prestamo', $id_comentario);
        $this->db->update('tb_comentario_prestamo', $data);

        return ($this->db->affected_rows() >= 0) ? true : false;
    }

    public function solicitud_reciente($id_usuario)
    {
        $this->db->select('
        prestamo.id_prestamo,
        prestamo.titulo,
        prestamo.fecha_solicitud,
        prestamo.motivo,
        prestamo.dias_trabajados,
        prestamo.dias_faltas,
        prestamo.forma_pago,
        prestamo.cuenta_bancaria,
        prestamo.monto,
        prestamo.estatus');
        $this->db->from('tb_prestamo as prestamo');
        $this->db->join('tb_usuario as usuario', 'usuario.id_usuario = prestamo.id_usuario');
        $this->db->where('prestamo.id_usuario', $id_usuario);
        $this->db->where('usuario.eliminado', 0);
        $this->db->where('usuario.estatus', 'Activo');
        $this->db->order_by('prestamo.id_prestamo', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();

        return ($query->num_rows() > 0) ? $query->result() : false;
    }

    public function info_solicitud_prestamo($id_usuario)
    {
        $this->db->select("
        concat(usuario.nombre,' ',usuario.apaterno,' ',usuario.amaterno) as nombre,
        usuario.fecha_ingreso,
        usuario.pago_semanal,
        SUM(monto) AS total_solicitado");
        $this->db->from('tb_prestamo as prestamo');
        $this->db->join('tb_usuario as usuario', 'usuario.id_usuario = prestamo.id_usuario');
        $this->db->where('prestamo.id_usuario', $id_usuario);
        $this->db->where('usuario.eliminado', 0);
        $this->db->where('usuario.estatus', 'Activo');
        $this->db->order_by('prestamo.id_prestamo', 'DESC');
        $query = $this->db->get();

        return ($query->num_rows() > 0) ? $query->result() : false;
    }
}
